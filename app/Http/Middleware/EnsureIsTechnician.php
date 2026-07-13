<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureIsTechnician
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->user()->role !== 'technician') {
            abort(403, 'Only IT technicians can access this page.');
        }

        return $next($request);
    }
}