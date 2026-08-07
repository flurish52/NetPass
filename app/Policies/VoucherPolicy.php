<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Voucher;

class VoucherPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('manage vouchers');
    }

    public function view(User $user, Voucher $voucher): bool
    {
        return $user->business_id === $voucher->business_id && $user->can('manage vouchers');
    }

    public function print(User $user, Voucher $voucher): bool
    {
        return $user->business_id === $voucher->business_id && $user->can('manage vouchers');
    }
}
