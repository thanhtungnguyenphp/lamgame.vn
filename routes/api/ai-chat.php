<?php

use App\Http\Controllers\Api\AiChatController;

Route::prefix('v1/ai-chat')->name('api.ai-chat.')->group(function () {
    Route::middleware(['auth:sanctum-customer', 'throttle:ai'])->group(function () {
        Route::post('/message', [AiChatController::class, 'message'])->name('message');
        Route::post('/stream', [AiChatController::class, 'stream'])->name('stream');
        Route::get('/sessions', [AiChatController::class, 'sessions'])->name('sessions');
        Route::get('/config', [AiChatController::class, 'config'])->name('config');
    });
});
