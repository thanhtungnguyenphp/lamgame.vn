<?php

use App\Http\Controllers\Api\NotificationController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/notifications')->name('api.notifications.')->group(function () {
    Route::middleware(['auth:sanctum-customer', 'web'])->group(function () {
        Route::post('/fcm-token', [NotificationController::class, 'registerFcmToken'])->name('fcm-token.register');
        Route::delete('/fcm-token', [NotificationController::class, 'deleteFcmToken'])->name('fcm-token.delete');
    });
});
