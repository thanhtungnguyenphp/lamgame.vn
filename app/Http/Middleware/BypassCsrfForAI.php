<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class BypassCsrfForAI
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // For AI thumbnail routes, we'll bypass CSRF by not requiring it
        // This middleware doesn't actually do anything special,
        // it just exists to mark these routes as CSRF-free
        
        return $next($request);
    }
}
