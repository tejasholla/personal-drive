<?php

namespace App\Providers;

use App\Services\UUIDService;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
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
        if (env('APP_ENV') !== 'local') {
            URL::forceScheme('https');
        }
        Vite::prefetch(concurrency: 3);
        if (! Schema::hasTable('sessions')) {
            config(['session.driver' => 'file']);
        }
        RateLimiter::for('login', function (Request $request) {
            return [
                Limit::perMinute(5)->response(function () {
                    return redirect()->route('rejected', ['message' => 'Too many requests.']);
                }),
            ];
        });

        RateLimiter::for('shared', function (Request $request) {
            return Limit::perMinute(10)
                ->response(function (Request $request, array $headers) {
                    return response('Too Many requests..', 429, $headers);
                });
        });
    }
}
