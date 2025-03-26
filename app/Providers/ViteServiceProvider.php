<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ViteServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if (!file_exists(public_path('build/manifest.json'))) {
            return redirect()->route('rejected', ['message' => 'Frontend view resources not found. make sure node, npm are installed. Re-run setup or manually do run "npm install && npm run build" ']);
        }
    }
}
