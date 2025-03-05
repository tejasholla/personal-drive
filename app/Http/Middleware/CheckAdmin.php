<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::user()->is_admin) {
            return $next($request);
        }
        return redirect()->route('rejected', ['message' => 'You do not have admin access']);
    }
}