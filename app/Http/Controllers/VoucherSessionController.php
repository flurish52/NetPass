<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

class VoucherSessionController extends Controller
{
    public function index(): Response
    {
        $business = auth()->user()->business;

        $sessions = $business->voucherSessions()
            ->with('voucher:id,code,plan_id', 'voucher.plan:id,name')
            ->latest('started_at')
            ->paginate(50);

        return Inertia::render('VoucherSession/Index', [
            'sessions' => $sessions,
        ]);
    }
}
