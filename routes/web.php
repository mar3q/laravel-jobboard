<?php

use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [JobController::class, 'index'])->name('home');
Route::get('/jobs', [JobController::class, 'index'])->name('jobs.index');
Route::get('/jobs/{job:slug}', [JobController::class, 'show'])->name('jobs.show');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/jobs/{job:slug}/apply', [ApplicationController::class, 'create'])->name('applications.create');
    Route::post('/jobs/{job:slug}/apply', [ApplicationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('applications.store');

    Route::get('/applications', [ApplicationController::class, 'index'])->name('applications.index');
    Route::patch('/applications/{application}/withdraw', [ApplicationController::class, 'withdraw'])
        ->name('applications.withdraw');

    Route::get('/cvs/{cv}/download', [ApplicationController::class, 'downloadCv'])
        ->middleware('signed')
        ->name('cvs.download');
});

Route::middleware(['auth', 'verified', 'role:employer|admin'])
    ->prefix('employer')
    ->name('employer.')
    ->group(function () {
        Route::get('/jobs', [\App\Http\Controllers\Employer\JobController::class, 'index'])->name('jobs.index');
        Route::get('/jobs/create', [\App\Http\Controllers\Employer\JobController::class, 'create'])->name('jobs.create');
        Route::post('/jobs', [\App\Http\Controllers\Employer\JobController::class, 'store'])->name('jobs.store');
        Route::get('/jobs/{job}/edit', [\App\Http\Controllers\Employer\JobController::class, 'edit'])->name('jobs.edit');
        Route::put('/jobs/{job}', [\App\Http\Controllers\Employer\JobController::class, 'update'])->name('jobs.update');
        Route::delete('/jobs/{job}', [\App\Http\Controllers\Employer\JobController::class, 'destroy'])->name('jobs.destroy');

        Route::get('/jobs/{job}/applications', [\App\Http\Controllers\Employer\ApplicationController::class, 'index'])->name('jobs.applications');
        Route::get('/applications/{application}', [\App\Http\Controllers\Employer\ApplicationController::class, 'show'])->name('applications.show');
        Route::patch('/applications/{application}/status', [\App\Http\Controllers\Employer\ApplicationController::class, 'changeStatus'])->name('applications.status');
    });

Route::middleware(['auth', 'verified', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', \App\Http\Controllers\Admin\DashboardController::class)->name('dashboard');
        Route::get('/moderation', [\App\Http\Controllers\Admin\JobModerationController::class, 'index'])->name('moderation.index');
        Route::post('/moderation/{job}/approve', [\App\Http\Controllers\Admin\JobModerationController::class, 'approve'])->name('moderation.approve');
        Route::post('/moderation/{job}/reject', [\App\Http\Controllers\Admin\JobModerationController::class, 'reject'])->name('moderation.reject');
    });

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
