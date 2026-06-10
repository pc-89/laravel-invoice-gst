<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, \Closure $next)
    {
        // Adjust the 'admin' text string if your admin role column uses an integer (like 1)
        if (auth()->check() && auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized action. Admin access required.');
        }

        return $next($request);
    }
}
