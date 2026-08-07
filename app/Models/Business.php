<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Business extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'api_key',
        'subscription_status',
        'trial_ends_at',
        'subscription_ends_at',
    ];

    protected $casts = [
        'trial_ends_at' => 'datetime',
        'subscription_ends_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (Business $business) {
            $business->slug ??= Str::slug($business->name) . '-' . Str::random(6);
            $business->api_key ??= Str::random(48);
            $business->trial_ends_at ??= now()->addDays(14);
        });
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
    public function plans()
    {
        return $this->hasMany(Plan::class);
    }
    public function vouchers()
    {
        return $this->hasMany(Voucher::class);
    }

    public function voucherSessions()
    {
        return $this->hasMany(VoucherSession::class);
    }
    public function isSubscriptionActive(): bool
    {
        if ($this->subscription_status === 'trial') {
            return $this->trial_ends_at?->isFuture() ?? false;
        }

        if ($this->subscription_status === 'active') {
            return $this->subscription_ends_at?->isFuture() ?? true;
        }

        return false;
    }
}
