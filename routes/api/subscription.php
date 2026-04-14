<?php

use App\Http\Controllers\Api\SubscriptionController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/subscription')->name('api.subscription.')->group(function () {
    // Public
    Route::get('/plans', [SubscriptionController::class, 'plans'])->name('plans');

    // PayPal callbacks (không cần auth)
    Route::get('/paypal/return', [SubscriptionController::class, 'paypalReturn'])->name('paypal.return');
    Route::post('/webhook', [SubscriptionController::class, 'webhook'])->name('webhook');

    // Protected (cần auth)
    Route::middleware('auth:sanctum-customer,customer')->group(function () {
        Route::post('/subscribe', [SubscriptionController::class, 'subscribe'])->name('subscribe');
        Route::get('/status', [SubscriptionController::class, 'status'])->name('status');
        Route::post('/cancel', [SubscriptionController::class, 'cancel'])->name('cancel');
        Route::get('/usage', [SubscriptionController::class, 'usage'])->name('usage');
        Route::post('/check-quota', [SubscriptionController::class, 'checkQuota'])->name('check-quota');
        Route::post('/use-quota', [SubscriptionController::class, 'useQuota'])->name('use-quota');
    });
});
