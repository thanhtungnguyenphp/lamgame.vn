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
        $request->validate([
            'game'   => 'required|in:mega645,power655,max3d,max3d_pro,keno',
            'date'   => 'nullable|date_format:Y-m-d',
            'period' => 'nullable|string|max:10',
        ]);

        $game   = $request->input('game');
        $date   = $request->input('date');
        $period = $request->input('period');

        // Keno theo ngày → trả nhiều kỳ
        if ($game === 'keno' && $date && !$period) {
            $data = $service->getKenoPeriods($date);
        } else {
            $data = $service->getVietlot($game, $date, $period);
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
