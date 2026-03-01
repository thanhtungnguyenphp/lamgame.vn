<?php

namespace App\Http\Controllers\Api\Lottery;

use App\Http\Controllers\Controller;
use App\Services\Lottery\LotteryService;
use Illuminate\Http\JsonResponse;

class LotteryHealthController extends Controller
{
    public function index(LotteryService $service): JsonResponse
    {
        return response()->json($service->getHealth());
    }
}
