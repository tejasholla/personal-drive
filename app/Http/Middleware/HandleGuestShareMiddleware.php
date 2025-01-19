<?php

namespace App\Http\Middleware;

use App\Models\Share;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class HandleGuestShareMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $slug = $request->route('slug') ?? $request->input('slug');
        $share = Share::whereBySlug($slug)->first();
        if (!$share || !$share->enabled) {
            return redirect()->route('rejected');
        }
        if ($share->password && !Session::get("shared_{$slug}_authenticated")) {
            return redirect()->route('shared.password.check', ['slug' => $slug]);
        }

        return $next($request);
    }
}
