<?php

namespace App\Http\Controllers\Api\Lottery;

use App\Http\Controllers\Controller;
use App\Services\Lottery\LotteryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VietlotController extends Controller
{
    public function index(Request $request, LotteryService $service): JsonResponse
    {
        $game = $request->input('game');
        if (!$game || !in_array($game, ['mega645', 'power655', 'max3d', 'max3d_pro', 'keno'])) {
            return response()->json([
                'status' => 'error',
                'error'  => ['code' => 'INVALID_GAME', 'message' => 'Game required: mega645, power655, max3d, max3d_pro, keno'],
            ], 400);
        }

        $date   = $request->input('date');
        $period = $request->input('period');

        // Keno theo ngày → trả nhiều kỳ
        if ($game === 'keno' && $date && !$period) {
            $data = $service->getKenoPeriods($date);
        } else {
            $data = $service->getVietlot($game, $date, $period);
        }

        // Fallback: lấy draw mới nhất nếu không có data hôm nay
        if (!$data && !$date) {
            $data = $service->getVietlotLatest($game);
        }

        if (!$data) {
            return response()->json([
                'status' => 'error',
                'error'  => ['code' => 'NO_RESULTS', 'message' => 'Không có kết quả.'],
            ], 404);
        }

        return response()->json([
            'status' => 'ok',
            'data'   => $data,
            'meta'   => ['cached' => true, 'fetched_at' => now()->toIso8601String()],
        ]);
    }
}
