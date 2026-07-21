<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckEmployer
{
    public function handle(Request $request, Closure $next)
    {
        $customer = auth('customer')->user();

        if (!$customer || !$customer->is_employer) {
            return redirect()->route('employer.register')
                ->with('info', 'Bạn cần đăng ký tài khoản Employer để sử dụng tính năng này.');
        }

        if ($customer->employer_status === 'suspended') {
            abort(403, 'Tài khoản employer của bạn đã bị tạm dừng.');
        }

        return $next($request);
    }
}
