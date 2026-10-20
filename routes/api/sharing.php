<?php

use App\Http\Controllers\Api\Sharing\SharingController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/sharing/v1')->name('api.sharing.')->group(function () {
    // A signed URL is deliberately headerless and does not expose the private disk.
    Route::get('/images/{shareId}', [SharingController::class, 'image'])->name('image');

    Route::middleware(['sharing.firebase.auth', 'sharing.rate:30', 'throttle:30,1'])->group(function () {
        Route::get('/me', [SharingController::class, 'me']);
        Route::post('/recipients/resolve', [SharingController::class, 'resolve'])->middleware('throttle:10,1');
        Route::post('/shares', [SharingController::class, 'store'])->middleware('throttle:10,1');
        Route::get('/shares', [SharingController::class, 'index']);
        Route::get('/shares/{shareId}', [SharingController::class, 'show']);
        Route::post('/shares/{shareId}/accept', [SharingController::class, 'action'])->defaults('action', 'accept');
        Route::post('/shares/{shareId}/decline', [SharingController::class, 'action'])->defaults('action', 'decline');
        Route::post('/shares/{shareId}/cancel', [SharingController::class, 'action'])->defaults('action', 'cancel');
        Route::put('/devices/{installationId}', [SharingController::class, 'putDevice']);
        Route::delete('/devices/{installationId}', [SharingController::class, 'deleteDevice']);
        Route::put('/blocks/{publicId}', [SharingController::class, 'block']);
        Route::post('/reports', [SharingController::class, 'report'])->middleware('throttle:10,1');
    });
});
