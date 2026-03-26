<?php

namespace App\Http\Controllers\Api\Lottery;

use App\Http\Controllers\Controller;
use App\Services\Lottery\LotteryStatisticsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LotteryStatisticsController extends Controller
{
    public function index(Request $request, LotteryStatisticsService $service): JsonResponse
    {
        $request->validate([
            'region'        => 'required|in:mien-nam,mien-trung,mien-bac',
            'province_code' => 'nullable|string|max:10',
            'days'          => 'nullable|integer|min:1|max:90',
            'type'          => 'nullable|in:all,frequency,streak,head_tail',
        ]);

        $data = $service->getStatistics(
            $request->input('region'),
            $request->input('province_code'),
            (int) $request->input('days', 30),
            $request->input('type', 'all'),
        );

        return response()->json([
            'status' => 'ok',
            'data'   => $data,
            'meta'   => ['cached' => true, 'fetched_at' => now()->toIso8601String()],
        ]);
    }
}
