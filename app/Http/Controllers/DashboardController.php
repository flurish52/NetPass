<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(Request $request): Response|RedirectResponse
    {
        $user = Auth::user();

        if ($user->hasRole('admin')) {
            return redirect()->route('admin.businesses.index');
        }

        $business = $user->business;

        $revenueToday = $business->vouchers()
            ->whereDate('created_at', today())
            ->whereNotNull('activated_at')
            ->with('plan:id,price')
            ->get()
            ->sum(fn ($voucher) => $voucher->plan->price);

        return Inertia::render('Dashboard', [
            'stats' => [
                'activeSessions' => $business->voucherSessions()
                    ->where('status', 'active')
                    ->where('expires_at', '>', now())
                    ->count(),
                'vouchersToday' => $business->vouchers()
                    ->whereDate('created_at', today())
                    ->count(),
                'redeemedToday' => $business->voucherSessions()
                    ->whereDate('started_at', today())
                    ->count(),
                'revenueToday' => $revenueToday,
            ],
            'license' => [
                'status' => $business->subscription_status,
                'isActive' => $business->isSubscriptionActive(),
                'expiresAt' => $business->subscription_status === 'trial'
                    ? $business->trial_ends_at?->toIso8601String()
                    : $business->subscription_ends_at?->toIso8601String(),
            ],
            'recentSessions' => $business->voucherSessions()
                ->with('voucher:id,code,plan_id', 'voucher.plan:id,name')
                ->latest('started_at')
                ->limit(10)
                ->get()
                ->map(fn ($session) => [
                    'id' => $session->id,
                    'code' => $session->voucher->code,
                    'plan' => $session->voucher->plan->name,
                    'deviceMac' => $session->device_mac,
                    'status' => $session->status,
                    'startedAt' => $session->started_at->toIso8601String(),
                    'expiresAt' => $session->expires_at->toIso8601String(),
                ]),
        ]);
    }
}
