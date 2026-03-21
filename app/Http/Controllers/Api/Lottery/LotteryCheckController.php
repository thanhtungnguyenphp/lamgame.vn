<?php

namespace App\Http\Controllers\Api\Lottery;

use App\Http\Controllers\Controller;
use App\Services\Lottery\LotteryCheckService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LotteryCheckController extends Controller
{
    public function check(Request $request, LotteryCheckService $service): JsonResponse
    {
        $request->validate([
            'numbers'       => 'required|array|min:1|max:20',
            'numbers.*'     => 'required|string|regex:/^\d{2,6}$/',
            'region'        => 'required|in:mien-nam,mien-trung,mien-bac',
            'date'          => 'required|date_format:Y-m-d',
            'province_code' => 'nullable|string|max:10',
        ]);

        $result = $service->check(
            $request->input('numbers'),
            $request->input('region'),
            $request->input('date'),
            $request->input('province_code'),
        );

        return response()->json([
            'status' => 'ok',
            'data'   => [
                'date'          => $request->input('date'),
                'region'        => $request->input('region'),
                'matches'       => $result['matches'],
                'total_matches' => $result['total_matches'],
            ],
        ]);
    }
}
