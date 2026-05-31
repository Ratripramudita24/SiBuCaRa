<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PenyuluhMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (! $request->user() || $request->user()->role !== 'penyuluh') {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}
