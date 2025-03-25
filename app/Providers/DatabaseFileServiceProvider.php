<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\File;

class DatabaseFileServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        // Only run this logic if we're using SQLite
        if (config('database.default') === 'sqlite') {
            $databasePath = config('database.connections.sqlite.database');

            // Create database directory if it doesn't exist
            $databaseDir = dirname($databasePath);

            if (!File::exists($databaseDir)) {
                File::makeDirectory($databaseDir, 0777, true);
            }
            // Create empty database file if it doesn't exist
            if (!File::exists($databasePath)) {
                File::put($databasePath, '');
                // Optionally run migrations automatically
                // $this->artisan('migrate');
            }
        }
    }
}