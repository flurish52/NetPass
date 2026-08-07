<?php

namespace App\Http\Controllers\Api\Agent;

use App\Http\Controllers\Controller;
use App\Http\Requests\Agent\DeviceConnectedRequest;
use App\Services\SessionService;
use Illuminate\Http\JsonResponse;

class DeviceController extends Controller
{
    public function connected(DeviceConnectedRequest $request, SessionService $sessionService): JsonResponse
    {
        $business = $request->attributes->get('business');

        $session = $sessionService->checkAccess($business, $request->mac);

        if (! $session) {
            return response()->json(['allowed' => false]);
        }

        return response()->json([
            'allowed' => true,
            'expires_at' => $session->expires_at->toIso8601String(),
        ]);
    }
}
