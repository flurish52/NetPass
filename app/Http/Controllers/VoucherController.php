<?php

namespace App\Http\Controllers;

use App\Http\Requests\GenerateVoucherBatchRequest;
use App\Models\Plan;
use App\Models\Voucher;
use App\Services\VoucherBatchService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class VoucherController extends Controller
{
    public function index(): Response
    {
        $this->authorize('viewAny', Voucher::class);

        $business = auth()->user()->business;

        $vouchers = $business->vouchers()
            ->with('plan:id,name')
            ->latest()
            ->paginate(50);

        return Inertia::render('Vouchers/Index', [
            'vouchers' => $vouchers,
            'plans' => $business->plans()->where('is_active', true)->get(['id', 'name']),
        ]);
    }

    public function generate(GenerateVoucherBatchRequest $request, VoucherBatchService $batchService): RedirectResponse
    {
        $business = auth()->user()->business;
        $plan = Plan::where('business_id', $business->id)->findOrFail($request->plan_id);

        $vouchers = $batchService->generateBatch($business, $plan, $request->quantity);

        return back()->with([
            'success' => "{$vouchers->count()} vouchers generated.",
            'batch_id' => $vouchers->first()->batch_id,
        ]);
    }
}
