<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoucherSession extends Model
{
    use HasFactory;

    protected $table = 'voucher_sessions';

    protected $fillable = [
        'business_id',
        'voucher_id',
        'device_mac',
        'ip_address',
        'status',
        'started_at',
        'expires_at',
        'ended_at',
    ];

    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'expires_at' => 'datetime',
            'ended_at' => 'datetime',
        ];
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    public function markExpired(): void
    {
        $this->update([
            'status' => 'expired',
            'ended_at' => now(),
        ]);
    }
}
