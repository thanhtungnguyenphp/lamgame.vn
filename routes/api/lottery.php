<?php

use App\Http\Controllers\Api\Lottery\LotteryCheckController;
use App\Http\Controllers\Api\Lottery\LotteryHealthController;
use App\Http\Controllers\Api\Lottery\LotteryLatestController;
use App\Http\Controllers\Api\Lottery\LotteryScheduleController;
use App\Http\Controllers\Api\Lottery\LotteryStatisticsController;
use App\Http\Controllers\Api\Lottery\TraditionalLotteryController;
use App\Http\Controllers\Api\Lottery\UserSyncController;
use App\Http\Controllers\Api\Lottery\UserTicketController;
use App\Http\Controllers\Api\Lottery\VietlotController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/lottery')->name('api.lottery.')->middleware('throttle:60,1')->group(function () {
    // Existing endpoints
    Route::get('/health',      [LotteryHealthController::class, 'index'])->name('health');
    Route::get('/latest',      [LotteryLatestController::class, 'index'])->name('latest');
    Route::get('/traditional', [TraditionalLotteryController::class, 'index'])->name('traditional');
    Route::get('/vietlot',     [VietlotController::class, 'index'])->name('vietlot');
    Route::get('/schedule',    [LotteryScheduleController::class, 'index'])->name('schedule');
    Route::get('/statistics',  [LotteryStatisticsController::class, 'index'])->name('statistics');

    // Sprint 1: Dò số
    Route::post('/check', [LotteryCheckController::class, 'check'])
        ->middleware('throttle:30,1')
        ->name('check');
});

// Sprint 1: User Tickets
Route::prefix('v1/user/tickets')->name('api.user.tickets.')->middleware('throttle:30,1')->group(function () {
    Route::post('/',              [UserTicketController::class, 'store'])->name('store');
    Route::get('/',               [UserTicketController::class, 'index'])->name('index');
    Route::get('/{ticketId}',     [UserTicketController::class, 'show'])->name('show');
    Route::delete('/{ticketId}',  [UserTicketController::class, 'destroy'])->name('destroy');
});

// User Sync (Firebase Auth required)
Route::prefix('v1/user')->name('api.user.')->middleware(['throttle:30,1', 'firebase.auth'])->group(function () {
    Route::put('/sync', [UserSyncController::class, 'sync'])->name('sync');
});
