<?php

namespace App\Providers;

use App\Services\S3AuthenticationService;
use Aws\S3\S3Client;
use Illuminate\Support\ServiceProvider;

class S3AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(S3Client::class, function ($app) {
            $authService = new S3AuthenticationService();
            return $authService->signIn(); // Returns an S3Client instance
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
