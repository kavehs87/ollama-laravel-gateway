<?php

use App\Http\Controllers\LLMController;
use App\Http\Controllers\UsageController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'track.token'])->group(function () {
    Route::post('/generate', [LLMController::class, 'generate'])->name('generate');
    Route::post('/chat', [LLMController::class, 'chat'])->name('chat');
    Route::post('/embedding', [LLMController::class, 'embedding'])->name('embedding');

    Route::get('/usage', [UsageController::class, 'index']);
    Route::get('/usage/history', [UsageController::class, 'history']);

    Route::post('/models/pull', [LLMController::class, 'pullModel']);
    Route::delete('/models/{model}', [LLMController::class, 'deleteModel']);
});
