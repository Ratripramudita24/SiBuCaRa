<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPenyuluh
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->role === 'penyuluh') {
            return $next($request);
        }

        abort(403, 'Unauthorized. Penyuluh access only.');
    }
}
