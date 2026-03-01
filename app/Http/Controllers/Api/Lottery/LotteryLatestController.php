<?php

namespace App\Http\Controllers\Api\Lottery;

use App\Http\Controllers\Controller;
use App\Services\Lottery\LotteryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LotteryLatestController extends Controller
{
    public function index(Request $request, LotteryService $service): JsonResponse
    {
        $request->validate([
            'include' => 'nullable|in:all,traditional,vietlot',
        ]);

        $data = $service->getLatest($request->input('include', 'all'));

        return response()->json([
            'status' => 'ok',
            'data'   => $data,
            'meta'   => ['cached' => true, 'fetched_at' => now()->toIso8601String()],
        ]);
    }
}
