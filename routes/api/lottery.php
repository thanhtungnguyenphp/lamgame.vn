<?php

use App\Http\Controllers\Api\Lottery\LotteryHealthController;
use App\Http\Controllers\Api\Lottery\LotteryLatestController;
use App\Http\Controllers\Api\Lottery\LotteryScheduleController;
use App\Http\Controllers\Api\Lottery\TraditionalLotteryController;
use App\Http\Controllers\Api\Lottery\VietlotController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/lottery')->name('api.lottery.')->middleware('throttle:60,1')->group(function () {
    Route::get('/health',      [LotteryHealthController::class, 'index'])->name('health');
    Route::get('/latest',      [LotteryLatestController::class, 'index'])->name('latest');
    Route::get('/traditional', [TraditionalLotteryController::class, 'index'])->name('traditional');
    Route::get('/vietlot',     [VietlotController::class, 'index'])->name('vietlot');
    Route::get('/schedule',    [LotteryScheduleController::class, 'index'])->name('schedule');
});
