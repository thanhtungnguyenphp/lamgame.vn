<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Webkul\User\Models\Admin;

class ApiKeyAuth
{
    public function handle(Request $request, Closure $next)
    {
        $admin = Admin::where('api_token', hash('sha256', $request->header('X-Api-Key', '')))->first();

        if (! $admin) {
            return response()->json(['status' => 'error', 'message' => 'Invalid API key'], 401);
        }

        $request->merge(['auth_admin' => $admin]);

        return $next($request);
    }
}
