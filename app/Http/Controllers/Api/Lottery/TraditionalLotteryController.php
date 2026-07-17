<?php

namespace App\Http\Controllers\Api\Lottery;

use App\Http\Controllers\Controller;
use App\Services\Lottery\LotteryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TraditionalLotteryController extends Controller
{
    public function index(Request $request, LotteryService $service): JsonResponse
    {
        $region = $request->input('region');
        if (!$region || !in_array($region, ['mien-nam', 'mien-trung', 'mien-bac'])) {
            return response()->json([
                'status' => 'error',
                'error'  => ['code' => 'INVALID_REGION', 'message' => 'Region required: mien-nam, mien-trung, mien-bac'],
            ], 400);
        }

        $date = $request->input('date');
        $provinceCode = $request->input('province');

        $data = $service->getTraditional($region, $date, $provinceCode);

        // Fallback: nếu không có data cho ngày requested (hoặc hôm nay), lấy ngày gần nhất
        if (!$data && !$date) {
            $data = $service->getTraditionalLatest($region, $provinceCode);
        }

        if (!$data) {
            return response()->json([
                'status' => 'error',
                'error'  => ['code' => 'NO_RESULTS', 'message' => 'Không có kết quả cho ngày này.'],
            ], 404);
        }

        return response()->json([
            'status' => 'ok',
            'data'   => $data,
            'meta'   => ['cached' => true, 'fetched_at' => now()->toIso8601String()],
        ]);
    }
}
