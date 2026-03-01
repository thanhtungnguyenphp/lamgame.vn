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
        $request->validate([
            'region'   => 'required|in:mien-nam,mien-trung,mien-bac',
            'date'     => 'nullable|date_format:Y-m-d',
            'province' => 'nullable|string|max:10',
        ]);

        $data = $service->getTraditional(
            $request->input('region'),
            $request->input('date'),
            $request->input('province'),
        );

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
