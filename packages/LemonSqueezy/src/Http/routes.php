<?php

use Illuminate\Support\Facades\Route;
use LemonSqueezy\Http\Controllers\LemonSqueezyController;

Route::group(['middleware' => ['web']], function () {
    Route::post('lemonsqueezy/checkout', [LemonSqueezyController::class, 'createCheckout'])
        ->middleware('auth:customer')
        ->name('lemonsqueezy.checkout.create');

    Route::get('lemonsqueezy/success', [LemonSqueezyController::class, 'success'])
        ->name('lemonsqueezy.checkout.success');

    Route::get('lemonsqueezy/check', [LemonSqueezyController::class, 'checkStatus'])
        ->name('lemonsqueezy.checkout.check');
});

// Webhook — no CSRF
Route::post('api/webhooks/lemonsqueezy', [LemonSqueezyController::class, 'handleWebhook'])
    ->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class)
    ->name('lemonsqueezy.webhook');
