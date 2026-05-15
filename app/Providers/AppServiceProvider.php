<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;
use Illuminate\Cache\RateLimiting\Limit;

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
        RateLimiter::for('api', function (Request $request) {
            $user = $request->user();

            if (!$user) {
                return Limit::perMinute(30)->by($request->ip());
            }

            return match ($user->role) {
                'admin'     => Limit::perMinute(300)->by($user->id),
                'organiser' => Limit::perMinute(120)->by($user->id),
                'customer'  => Limit::perMinute(60)->by($user->id),
                default     => Limit::perMinute(60)->by($user->id),
            };
        });

        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip())->response(function (Request $request, array $headers) {
                return response()->json([
                    'success' => false,
                    'message' => 'Too many login attempts. Please try again in 1 minute.',
                ], 429, $headers);
            });
        });
    }
}
