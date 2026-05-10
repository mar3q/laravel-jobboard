<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\JobController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::post('/tokens', [AuthController::class, 'token'])
        ->middleware('throttle:5,1')
        ->name('api.v1.tokens.create');

    Route::get('/jobs', [JobController::class, 'index'])->name('api.v1.jobs.index');
    Route::get('/jobs/{job:slug}', [JobController::class, 'show'])->name('api.v1.jobs.show');

    Route::middleware(['auth:sanctum', 'throttle:60,1'])->group(function () {
        Route::get('/me', fn (Request $request) => $request->user());
        Route::post('/jobs', [JobController::class, 'store'])
            ->middleware('ability:jobs:write')
            ->name('api.v1.jobs.store');
    });
});
