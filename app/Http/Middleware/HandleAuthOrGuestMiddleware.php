<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HandleAuthOrGuestMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $authMiddleware = app(Authenticate::class);
        try {
            return $authMiddleware->handle($request, $next);
        } catch (\Illuminate\Auth\AuthenticationException $e) {
            // If not authenticated, delegate to the HandleGuestShareRequests middleware
            return app(HandleGuestShareMiddleware::class)->handle($request, $next);
        }    }
}
