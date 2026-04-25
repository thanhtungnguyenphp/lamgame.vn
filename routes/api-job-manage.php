<?php

use App\Http\Controllers\Api\JobManageController;
use App\Http\Controllers\Api\CandidateManageController;
use App\Http\Controllers\Api\CompanyManageController;

/*
|--------------------------------------------------------------------------
| Job Management API Routes (api.key auth — tương tự Blog Publish API)
|--------------------------------------------------------------------------
|
| Header: X-Api-Key: {admin_api_token}
| Prefix: /api/manage
|
*/
Route::prefix('manage')->name('api.manage.')->middleware(['api.key', 'throttle:60,1'])->group(function () {

    // === Job Management ===
    Route::prefix('jobs')->name('jobs.')->group(function () {
        Route::get('/', [JobManageController::class, 'list'])->name('list');
        Route::get('/statistics', [JobManageController::class, 'statistics'])->name('statistics');
        Route::get('/{slug}', [JobManageController::class, 'detail'])->name('detail');
        Route::post('/', [JobManageController::class, 'publish'])->name('publish')->middleware('throttle:10,1');
        Route::put('/{slug}', [JobManageController::class, 'update'])->name('update')->middleware('throttle:10,1');
        Route::delete('/{slug}', [JobManageController::class, 'destroy'])->name('destroy')->middleware('throttle:10,1');
        Route::post('/{slug}/status', [JobManageController::class, 'changeStatus'])->name('change-status')->middleware('throttle:10,1');
    });

    // === Candidate / Application Management ===
    Route::prefix('candidates')->name('candidates.')->group(function () {
        Route::get('/', [CandidateManageController::class, 'list'])->name('list');
        Route::get('/statistics', [CandidateManageController::class, 'statistics'])->name('statistics');
        Route::get('/{id}', [CandidateManageController::class, 'detail'])->name('detail')->where('id', '[0-9]+');
        Route::patch('/{id}/status', [CandidateManageController::class, 'updateStatus'])->name('update-status')->where('id', '[0-9]+')->middleware('throttle:10,1');
        Route::delete('/{id}', [CandidateManageController::class, 'destroy'])->name('destroy')->where('id', '[0-9]+')->middleware('throttle:10,1');
    });

    // === Company / Employer Management ===
    Route::prefix('companies')->name('companies.')->group(function () {
        Route::get('/', [CompanyManageController::class, 'list'])->name('list');
        Route::get('/{id}', [CompanyManageController::class, 'detail'])->name('detail')->where('id', '[0-9]+');
        Route::post('/', [CompanyManageController::class, 'store'])->name('store')->middleware('throttle:10,1');
        Route::post('/{id}', [CompanyManageController::class, 'update'])->name('update')->where('id', '[0-9]+')->middleware('throttle:10,1');
        Route::delete('/{id}', [CompanyManageController::class, 'destroy'])->name('destroy')->where('id', '[0-9]+')->middleware('throttle:10,1');
    });
});
