<?php

use App\Http\Controllers\Api\AiJobDescriptionController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->prefix('ai')->group(function () {
    Route::post('job-description/optimize', [AiJobDescriptionController::class, 'optimize']);
    Route::post('job-description/suggestions', [AiJobDescriptionController::class, 'generateSuggestions']);
});
