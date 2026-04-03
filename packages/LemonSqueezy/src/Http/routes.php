<?php

use Illuminate\Support\Facades\Route;
use LemonSqueezy\Http\Controllers\LemonSqueezyController;

Route::group(['middleware' => ['web']], function () {
    Route::post('lemonsqueezy/checkout', [LemonSqueezyController::class, 'createCheckout'])
        ->name('lemonsqueezy.checkout.create');

    Route::get('lemonsqueezy/success', [LemonSqueezyController::class, 'success'])
        ->name('lemonsqueezy.checkout.success');
});

// Webhook — no CSRF
Route::post('api/webhooks/lemonsqueezy', [LemonSqueezyController::class, 'handleWebhook'])
    ->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class)
    ->name('lemonsqueezy.webhook');
