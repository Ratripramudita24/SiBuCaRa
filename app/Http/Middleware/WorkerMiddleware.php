<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class WorkerMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (! $request->user() || $request->user()->role !== 'worker') {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}
