<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PlantController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\RecommendationController;
use App\Http\Controllers\WorkerController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware(['auth', 'owner'])->prefix('owner')->group(function () {
    Route::get('/dashboard', [PlantController::class, 'ownerDashboard'])->name('owner.dashboard');
    Route::post('/plants', [PlantController::class, 'store'])->name('plants.store');
    Route::get('/plants/{plant}', [PlantController::class, 'show'])->name('plants.show');
    Route::delete('/plants/{plant}', [PlantController::class, 'destroy'])->name('plants.destroy');

    Route::post('/workers', [WorkerController::class, 'storeWorker'])->name('workers.store');
    Route::delete('/workers/{worker}', [WorkerController::class, 'destroy'])->name('workers.destroy');
});

Route::middleware(['auth', 'worker'])->prefix('worker')->group(function () {
    Route::get('/dashboard', [ScheduleController::class, 'workerDashboard'])->name('worker.dashboard');
    Route::patch('/schedules/{schedule}', [ScheduleController::class, 'updateStatus'])->name('schedules.update');
});

Route::middleware(['auth', 'penyuluh'])->prefix('penyuluh')->group(function () {
    Route::get('/dashboard', [RecommendationController::class, 'penyuluhDashboard'])->name('penyuluh.dashboard');
    Route::post('/recommendations/{plant}', [RecommendationController::class, 'store'])->name('recommendations.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

