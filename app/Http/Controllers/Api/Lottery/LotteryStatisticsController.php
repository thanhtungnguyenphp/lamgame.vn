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
        $region = $request->input('region');

        // Nếu không truyền region, default mien-bac
        if (!$region || !in_array($region, ['mien-nam', 'mien-trung', 'mien-bac'])) {
            $region = 'mien-bac';
        }

        $days = max(1, min(90, (int) $request->input('days', 30)));
        $provinceCode = $request->input('province_code');
        $type = $request->input('type', 'all');

        if (!in_array($type, ['all', 'frequency', 'streak', 'head_tail', 'gap', 'prediction', 'pattern', 'special', 'summary'])) {
            $type = 'all';
        }

        $data = $service->getStatistics($region, $provinceCode, $days, $type);

        return response()->json([
            'status' => 'ok',
            'data'   => $data,
            'meta'   => ['cached' => true, 'fetched_at' => now()->toIso8601String()],
        ]);
    }
}
