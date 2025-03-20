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
        URL::forceScheme('http');

        if (config('app.env') === 'production' && !env('RUNNING_IN_DOCKER')) {
            URL::forceScheme('https');
        }
        if (! Schema::hasTable('sessions')) {
            config(['session.driver' => 'file']);
        }
        RateLimiter::for('login', function (Request $request) {
            return [
                Limit::perMinute(9)->response(function () {
                    return redirect()->route('rejected', ['message' => 'Too many requests too fast! Wait at least a minute before trying again']);
                }),
            ];
        });

        RateLimiter::for('shared', function (Request $request) {
            return Limit::perMinute(20)
                ->response(function (Request $request, array $headers) {
                    return response('Too Many requests..', 429, $headers);
                });
        });
    }
}
