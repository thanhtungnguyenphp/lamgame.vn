<?php

namespace App\Http\Controllers\Api\Lottery;

use App\Http\Controllers\Controller;
use App\Services\Lottery\LotteryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LotteryScheduleController extends Controller
{
    public function index(Request $request, LotteryService $service): JsonResponse
    {
        $request->validate([
            'date' => 'nullable|date_format:Y-m-d',
            'type' => 'nullable|in:all,traditional,vietlot',
        ]);

        $data = $service->getSchedule(
            $request->input('date'),
            $request->input('type', 'all'),
        );

        return response()->json([
            'status' => 'ok',
            'data'   => $data,
            'meta'   => ['cached' => true, 'fetched_at' => now()->toIso8601String()],
        ]);
    }
}
