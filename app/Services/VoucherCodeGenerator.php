<?php

namespace App\Services;

use App\Models\Voucher;
use Illuminate\Support\Str;

class VoucherCodeGenerator
{
    public function generate(): string
    {
        do {
            $code = strtoupper(Str::random(4)) . '-' . strtoupper(Str::random(4));
        } while (Voucher::where('code', $code)->exists());

        return $code;
    }
}
