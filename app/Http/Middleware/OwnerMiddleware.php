<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class OwnerMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (! $request->user() || $request->user()->role !== 'owner') {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}
