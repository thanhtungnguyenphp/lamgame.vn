<?php

use App\Http\Controllers\Api\AiToolsController;

Route::prefix('v1/ai-tools')->name('api.ai-tools.')->group(function () {
    Route::middleware(['auth:sanctum-customer', 'throttle:ai'])->group(function () {
        Route::get('/dashboard', [AiToolsController::class, 'dashboard'])->name('dashboard');

        // AI Tool endpoints
        Route::post('/concept', [AiToolsController::class, 'concept'])->name('concept');
        Route::post('/codegen', [AiToolsController::class, 'codegen'])->name('codegen');
        Route::post('/debug', [AiToolsController::class, 'debug'])->name('debug');
        Route::post('/test', [AiToolsController::class, 'test'])->name('test');
        Route::post('/review', [AiToolsController::class, 'review'])->name('review');
        Route::post('/generate-asset', [AiToolsController::class, 'generateAsset'])->name('generate-asset');
        Route::post('/gdd', [AiToolsController::class, 'generateGDD'])->name('gdd');

        // History
        Route::get('/history', [AiToolsController::class, 'history'])->name('history');
        Route::get('/history/{id}', [AiToolsController::class, 'historyDetail'])->name('history.detail');
    });
});
