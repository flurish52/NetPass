<?php

use App\Http\Controllers\Admin\BusinessController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\VoucherPrintController;
use App\Http\Controllers\VoucherSessionController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('plans', PlanController::class)->except(['create', 'edit', 'show']);
    Route::get('vouchers', [VoucherController::class, 'index'])->name('vouchers.index');
    Route::post('vouchers/generate', [VoucherController::class, 'generate'])->name('vouchers.generate');
    Route::get('vouchers/batch/{batchId}/print', [VoucherPrintController::class, 'batch'])->name('vouchers.print');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('sessions', [VoucherSessionController::class, 'index'])->name('sessions.index');
    Route::get('billing', [BillingController::class, 'index'])->name('billing.index');

    Route::resource('plans', PlanController::class)->except(['create', 'edit', 'show']);

    Route::get('staff', [StaffController::class, 'index'])->name('staff.index');
    Route::post('staff', [StaffController::class, 'store'])->name('staff.store');
    Route::delete('staff/{staff}', [StaffController::class, 'destroy'])->name('staff.destroy');
});


Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('businesses', [BusinessController::class, 'index'])->name('businesses.index');
    Route::get('businesses/create', [BusinessController::class, 'create'])->name('businesses.create');
    Route::post('businesses', [BusinessController::class, 'store'])->name('businesses.store');
});


require __DIR__.'/auth.php';
