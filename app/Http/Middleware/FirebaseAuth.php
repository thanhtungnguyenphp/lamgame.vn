<?php

namespace App\Http\Middleware;

use App\Services\FirebaseAuthService;
use Closure;
use Illuminate\Http\Request;

class FirebaseAuth
{
    public function __construct(private FirebaseAuthService $firebase) {}

    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json([
                'status' => 'error',
                'error'  => ['code' => 'UNAUTHORIZED', 'message' => 'Missing authorization token.'],
            ], 401);
        }

        try {
            $uid = $this->firebase->verifyIdToken($token);
            $request->merge(['firebase_uid' => $uid]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'error'  => ['code' => 'UNAUTHORIZED', 'message' => 'Invalid token.'],
            ], 401);
        }

        return $next($request);
    }
}
