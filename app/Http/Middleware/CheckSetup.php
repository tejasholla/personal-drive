<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\Response;

class CheckSetup
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Schema::hasTable('migrations') && !$request->is('setup*')) {
            config(['session.driver' => 'array']); // Use a non-persistent session driver
            return redirect('/setup/account');
        }

        return $next($request);
    }
}
