<?php

use Illuminate\Support\Facades\Route;
use LamGame\Banner\Http\Controllers\Api\BannerController;

/*
|--------------------------------------------------------------------------
| Banner API Routes
|--------------------------------------------------------------------------
|
| These routes are for the Banner API and are prefixed with 'api/banners'
| All routes use the 'api' middleware group with throttling.
|
*/

Route::middleware(['api', 'throttle:60,1'])->prefix('api/banners')->group(function () {
    
    // Get banners with filtering
    Route::get('/', [BannerController::class, 'index'])
        ->name('api.banners.index');
    
    // Get banners by specific position
    Route::get('/position/{position}', [BannerController::class, 'getByPosition'])
        ->name('api.banners.position')
        ->where('position', '[a-zA-Z0-9_-]+');
    
    // Track banner click
    Route::post('/{id}/click', [BannerController::class, 'trackClick'])
        ->name('api.banners.click')
        ->where('id', '[0-9]+');
    
    // Get available positions
    Route::get('/positions', [BannerController::class, 'positions'])
        ->name('api.banners.positions');
    
    // Get available device types
    Route::get('/device-types', [BannerController::class, 'deviceTypes'])
        ->name('api.banners.device-types');
});