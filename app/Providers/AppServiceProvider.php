<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
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
        $this->configureRateLimiting();
    }

    private function configureRateLimiting(): void
    {
        // General API limit — 60 requests per minute per user/IP
       RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        // authentication routes — 10 requests per minute per IP
        RateLimiter::for('auth', function (Request $request) {
            return Limit::perMinute(10)
            ->by($request->ip())
            ->response(function () {
                return response()->json([
                    'status' => false,
                    'message' => 'Too many login attempts. Please try again later.',
                    'error'   => null
                ], 429);
            });
        });

        // Location updates — driver sends every 5 seconds
        RateLimiter::for('location', function (Request $request) {
            return Limit::perMinute(30)->by($request->user()?->id ?: $request->ip());
        });
        
    }
}
