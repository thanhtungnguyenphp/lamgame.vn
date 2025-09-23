<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Simple admin check using session or IP-based authentication
        // In production, this should be replaced with proper authentication
        $adminSession = $request->session()->get('admin_authenticated');
        $adminPassword = $request->get('admin_password');
        
        // Check if admin password is provided in request
        if ($adminPassword === 'lamgame2025!') {
            $request->session()->put('admin_authenticated', true);
            return $next($request);
        }
        
        // Check if already authenticated
        if ($adminSession) {
            return $next($request);
        }
        
        // Show admin login if not authenticated
        if ($request->wantsJson()) {
            return response()->json(['error' => 'Admin authentication required'], 401);
        }
        
        return response()->view('admin.login', [
            'login_url' => $request->url(),
        ]);
    }
}
