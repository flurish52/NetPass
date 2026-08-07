<?php


use App\Http\Controllers\Api\Agent\DeviceController;
use App\Http\Controllers\Api\Agent\LicenseController;
use App\Http\Controllers\Api\Agent\VoucherRedemptionController;
use Illuminate\Support\Facades\Route;

Route::prefix('agent')->middleware('agent.auth')->group(function () {
    Route::get('license-check', [LicenseController::class, 'check']);
    Route::post('device-connected', [DeviceController::class, 'connected']);

    Route::post('voucher/redeem', [VoucherRedemptionController::class, 'redeem'])
        ->middleware('throttle:agent-vouchers');
});
