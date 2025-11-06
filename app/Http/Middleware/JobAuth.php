<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class JobAuth
{
    public function handle(Request $request, Closure $next)
    {
        return response()->redirectTo('/admin/login');
    }
}
