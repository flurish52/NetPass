<?php

namespace App\Http\Controllers\Api\Agent;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LicenseController extends Controller
{
    public function check(Request $request): JsonResponse
    {
        $business = $request->attributes->get('business');

        return response()->json([
            'licensed' => $business->isSubscriptionActive(),
            'status' => $business->subscription_status,
            'expires_at' => $business->subscription_status === 'trial'
                ? $business->trial_ends_at?->toIso8601String()
                : $business->subscription_ends_at?->toIso8601String(),
        ]);
    }
}
