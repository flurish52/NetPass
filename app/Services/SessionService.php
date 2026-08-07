<?php

namespace App\Services;

use App\Models\Business;
use App\Models\Voucher;
use App\Models\VoucherSession;

class SessionService
{
    /**
     * Redeem a voucher and start a session for a device.
     * Returns the session, or null if the voucher can't be redeemed.
     */
    public function redeem(Business $business, string $code, string $deviceMac, ?string $ipAddress = null): ?VoucherSession
    {
        $voucher = Voucher::where('business_id', $business->id)
            ->where('code', $code)
            ->first();

        if (! $voucher) {
            return null;
        }

        if ($voucher->locked_until && $voucher->locked_until->isFuture()) {
            return null;
        }

        if (! $voucher->isRedeemable()) {
            $this->registerFailedAttempt($voucher);

            return null;
        }

        // Reset attempts on success so a previously-fumbled code isn't left
        // sitting at a nonzero failure count once it's legitimately redeemed
        if ($voucher->failed_attempts > 0) {
            $voucher->update(['failed_attempts' => 0, 'locked_until' => null]);
        }

        $voucher->activate($deviceMac);

        return VoucherSession::create([
            'business_id' => $business->id,
            'voucher_id' => $voucher->id,
            'device_mac' => $deviceMac,
            'ip_address' => $ipAddress,
            'status' => 'active',
            'started_at' => now(),
            'expires_at' => $voucher->expires_at,
        ]);
    }

    private function registerFailedAttempt(Voucher $voucher): void
    {
        $voucher->increment('failed_attempts');

        if ($voucher->failed_attempts >= 5) {
            $voucher->update(['locked_until' => now()->addMinutes(15)]);
        }
    }

    /**
     * Check whether a device currently has valid, unexpired access.
     */
    public function checkAccess(Business $business, string $deviceMac): ?VoucherSession
    {
        $session = VoucherSession::where('business_id', $business->id)
            ->where('device_mac', $deviceMac)
            ->where('status', 'active')
            ->latest('started_at')
            ->first();

        if (! $session) {
            return null;
        }

        if ($session->isExpired()) {
            $session->markExpired();
            $session->voucher->update(['status' => 'expired']);

            return null;
        }

        return $session;
    }

    /**
     * Sweep all businesses for expired sessions. Used by the scheduled command.
     */
    public function expireOverdueSessions(): int
    {
        $expired = VoucherSession::where('status', 'active')
            ->where('expires_at', '<=', now())
            ->get();

        foreach ($expired as $session) {
            $session->markExpired();
            $session->voucher->update(['status' => 'expired']);
        }

        return $expired->count();
    }
}
