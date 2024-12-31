<?php

namespace App\Providers;

use App\Services\S3Service;
use Aws\S3\S3Client;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        $this->app->bind(S3Service::class, function ($app) {
            return new S3Service();
        });
    }


    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);
    }
}
