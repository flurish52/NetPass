<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'name',
        'price',
        'duration_minutes',
        'speed_limit_mbps',
        'data_limit_mb',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function vouchers()
    {
        return $this->hasMany(Voucher::class);
    }

    public function priceInNaira(): float
    {
        return $this->price / 100;
    }
}
