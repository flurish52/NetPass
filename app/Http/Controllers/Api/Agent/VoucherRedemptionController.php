<?php

namespace App\Http\Controllers\Api\Agent;

use App\Http\Controllers\Controller;
use App\Http\Requests\Agent\RedeemVoucherRequest;
use App\Services\SessionService;
use Illuminate\Http\JsonResponse;

class VoucherRedemptionController extends Controller
{
    public function redeem(RedeemVoucherRequest $request, SessionService $sessionService): JsonResponse
    {
        $business = $request->attributes->get('business');

        $session = $sessionService->redeem(
            $business,
            strtoupper($request->code),
            $request->mac,
            $request->ip,
        );

        if (! $session) {
            return response()->json([
                'allowed' => false,
                'message' => 'Invalid or already-used voucher.',
            ], 422);
        }

        return response()->json([
            'allowed' => true,
            'expires_at' => $session->expires_at->toIso8601String(),
        ]);
    }
}
