<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PlantController;
use App\Http\Controllers\PlantActivityController;
use App\Http\Controllers\WorkerController;

/*
|--------------------------------------------------------------------------
| Public Route
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Authenticated & Verified Routes (Sistem SiBuCaRa dengan 3 Role)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {

    // Generic dashboard route for fallback
    Route::get('/dashboard', [PlantController::class, 'dashboard'])->name('dashboard');

    // Role-specific dashboard routes
    Route::prefix('owner')->middleware([\App\Http\Middleware\OwnerMiddleware::class])->name('owner.')->group(function () {
        Route::get('dashboard', [PlantController::class, 'dashboard'])->name('dashboard');
    });

    Route::prefix('worker')->middleware([\App\Http\Middleware\WorkerMiddleware::class])->name('worker.')->group(function () {
        Route::get('dashboard', [PlantController::class, 'dashboard'])->name('dashboard');
    });

    Route::prefix('penyuluh')->middleware([\App\Http\Middleware\PenyuluhMiddleware::class])->name('penyuluh.')->group(function () {
        Route::get('dashboard', [PlantController::class, 'dashboard'])->name('dashboard');
    });

    // PLANTS - Owner only
    Route::middleware([\App\Http\Middleware\OwnerMiddleware::class])->group(function () {
        Route::get('jadwal', [\App\Http\Controllers\PlantController::class, 'schedules'])->name('schedules.index');
        Route::get('jadwal/cetak', [\App\Http\Controllers\PlantController::class, 'printSchedules'])->name('schedules.print');
        Route::patch('activities/{activity}/assign-worker', [PlantActivityController::class, 'assignWorker'])->name('activities.assignWorker');
        Route::resource('plants', PlantController::class)->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy']);
        Route::resource('workers', WorkerController::class)->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy']);
        Route::patch('/workers/{worker}/reset-password', [WorkerController::class, 'resetPassword'])->name('workers.resetPassword');
    });

    // PLANT ACTIVITIES - Owner & Worker
    Route::prefix('activities')->group(function () {
        Route::get('{activity}', [PlantActivityController::class, 'show'])->name('activities.show');
        Route::patch('{activity}/status', [PlantActivityController::class, 'updateStatus'])->name('activities.updateStatus');
        Route::patch('{activity}/complete', [PlantActivityController::class, 'markComplete'])->name('activities.complete');
        Route::patch('{activity}/not-done', [PlantActivityController::class, 'markNotDone'])->name('activities.notDone');
    });

    // Navigation & Reports
    Route::get('/kalender', [PlantController::class, 'calendarView'])->name('plants.calendar');
    Route::get('/laporan', [PlantController::class, 'report'])->name('plants.report');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
