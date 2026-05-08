<?php

use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DistrictController;
use App\Http\Controllers\OfficerController;
use App\Http\Controllers\PollingStationController;
use App\Http\Controllers\VillageController;
use App\Http\Controllers\VoteResultController;
use Illuminate\Support\Facades\Route;

// Auth
Route::prefix('auth')->controller(AuthController::class)->group(function () {
    Route::post('/register', 'register');
    Route::post('/login', 'login');

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', 'logout');
        Route::get('/profile', 'profile');
        Route::put('/profile/edit', 'editprofile');
        Route::post('/profile/changepassword', 'changepassword');
    });
});

Route::middleware('auth:sanctum')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Polling Stations — extra routes must be registered before apiResource
    Route::prefix('polling-stations')->name('polling-stations.')->group(function () {
        Route::post('/import', [PollingStationController::class, 'import'])->name('import');
        Route::get('/map-data', [PollingStationController::class, 'mapData'])->name('map-data');
        Route::get('/export', [PollingStationController::class, 'export'])->name('export');
    });
    Route::apiResource('polling-stations', PollingStationController::class);

    // Districts
    Route::apiResource('districts', DistrictController::class)->only(['index', 'show']);

    // Villages
    Route::apiResource('villages', VillageController::class)->only(['index', 'show']);

    // Officers
    Route::apiResource('officers', OfficerController::class);

    // Assignments
    Route::patch('/assignments/{assignment}/confirm', [AssignmentController::class, 'confirm'])->name('assignments.confirm');
    Route::apiResource('assignments', AssignmentController::class);

    // Vote Results
    Route::patch('/vote-results/{voteResult}/verify', [VoteResultController::class, 'verify'])->name('vote-results.verify');
    Route::apiResource('vote-results', VoteResultController::class)->only(['index', 'store', 'show', 'update']);
});
