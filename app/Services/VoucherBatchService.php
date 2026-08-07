<?php

namespace App\Services;

use App\Models\Business;
use App\Models\Plan;
use App\Models\Voucher;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class VoucherBatchService
{
    public function __construct(
        private readonly VoucherCodeGenerator $codeGenerator,
    ) {}

    public function generateBatch(Business $business, Plan $plan, int $quantity): Collection
    {
        $batchId = strtoupper(Str::random(8));

        $vouchers = collect(range(1, $quantity))->map(fn () => [
            'business_id' => $business->id,
            'plan_id' => $plan->id,
            'code' => $this->codeGenerator->generate(),
            'batch_id' => $batchId,
            'status' => 'unused',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Voucher::insert($vouchers->toArray());

        return Voucher::where('batch_id', $batchId)->get();
    }
}
