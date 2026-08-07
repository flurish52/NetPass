<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

class BillingController extends Controller
{
    public function index(): Response
    {
        $business = auth()->user()->business;

        return Inertia::render('Billing/Index', [
            'business' => [
                'subscriptionStatus' => $business->subscription_status,
                'isActive' => $business->isSubscriptionActive(),
                'trialEndsAt' => $business->trial_ends_at?->toIso8601String(),
                'subscriptionEndsAt' => $business->subscription_ends_at?->toIso8601String(),
            ],
        ]);
    }
}
