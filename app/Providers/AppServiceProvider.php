<?php

namespace App\Providers;

use App\Services\UUIDService;
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
    public function register()
    {
        $this->app->singleton(UUIDService::class, function () {
            return new UUIDService();
        });
    }


    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);

        RateLimiter::for('login', function (Request $request) {
            return [
                Limit::perMinute(5),
            ];
        });

        RateLimiter::for('shared', function (Request $request) {
            return Limit::perMinute(6)
                ->response(function (Request $request, array $headers) {
                    return response('Too Many requests..', 429, $headers);
                });
        });
    }
}
