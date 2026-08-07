<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);

        RateLimiter::for('agent-vouchers', function (Request $request) {
            $business = $request->attributes->get('business');

            // Fall back to IP if middleware hasn't resolved a business yet
            // (shouldn't happen since agent.auth runs first, but fail safe)
            $key = $business?->id ?? $request->ip();

            return Limit::perMinute(8)->by('voucher-redeem:' . $key);
        });
    }
}
