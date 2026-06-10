<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsApproved
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, \Closure $next)
    {
        // If the user is logged in, is a normal user, but hasn't been approved yet, block them
        if (auth()->check() && auth()->user()->role !== 'admin' && !auth()->user()->is_approved) {
            return redirect()->route('approval.notice');
        }

        return $next($request);
    }
}
