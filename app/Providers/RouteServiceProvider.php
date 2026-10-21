<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit ;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        RateLimiter::for('checkout', function ($request) {
            return Limit::perMinute(30)->by($request->user()?->id ?: $request->ip());
        });
        RateLimiter::for('cancel', function ($request) {
            return Limit::perMinute(15)->by($request->user()?->id ?: $request->ip());
        });
        RateLimiter::for('reports', function ($request) {
            return Limit::perMinute(5)->by($request->user()?->id ?: $request->ip());
        });
    }
}
