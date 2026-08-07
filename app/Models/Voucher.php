<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'plan_id',
        'code',
        'batch_id',
        'status',
        'device_mac',
        'activated_at',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'activated_at' => 'datetime',
            'expires_at' => 'datetime',
        ];
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function session()
    {
        return $this->hasOne(VoucherSession::class);
    }

    public function isRedeemable(): bool
    {
        return $this->status === 'unused';
    }

    public function activate(string $deviceMac): void
    {
        $this->update([
            'status' => 'active',
            'device_mac' => $deviceMac,
            'activated_at' => now(),
            'expires_at' => now()->addMinutes($this->plan->duration_minutes),
        ]);
    }
}
