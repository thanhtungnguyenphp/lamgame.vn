<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CustomerFcmToken;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function registerFcmToken(Request $request): JsonResponse
    {
        $request->validate([
            'token'    => 'required|string|max:500',
            'platform' => 'nullable|string|in:web,android,ios',
        ]);

        $customer = $request->user('customer') ?? $request->user();

        if (! $customer) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        CustomerFcmToken::updateOrCreate(
            ['token' => $request->token],
            ['customer_id' => $customer->id, 'platform' => $request->input('platform', 'web')]
        );

        return response()->json(['success' => true]);
    }

    public function deleteFcmToken(Request $request): JsonResponse
    {
        $request->validate(['token' => 'required|string']);

        $customer = $request->user('customer') ?? $request->user();

        CustomerFcmToken::where('customer_id', $customer->id)
            ->where('token', $request->token)
            ->delete();

        return response()->json(['success' => true]);
    }
}
