<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HandleAuthOrGuestMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {

        // Use the 'auth' middleware to check if the user is authenticated
        $authMiddleware = app(Authenticate::class);
//        Log::info("in HandleAuthOrGuestMiddleware");
        // If authenticated, the 'auth' middleware will grant access
        try {
            return $authMiddleware->handle($request, $next);
        } catch (\Illuminate\Auth\AuthenticationException $e) {
            // If not authenticated, delegate to the HandleGuestShareRequests middleware
            return app(HandleGuestShareMiddleware::class)->handle($request, $next);
        }    }
}
