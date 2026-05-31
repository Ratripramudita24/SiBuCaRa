<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PlantController;
use App\Http\Controllers\ActivityController;

/*
|--------------------------------------------------------------------------
| Public Route
|--------------------------------------------------------------------------
*/
// 1. Ini sudah diperbaiki kembali ke bawaan asli Laravel Breeze
Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Authenticated & Verified Routes (Sistem SiBuCaRa)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {

    // 1. Beranda (Dashboard Utama - Menampilkan tugas hari ini)
    Route::get('/dashboard', [PlantController::class, 'index'])->name('dashboard');

    // 2. CRUD Tanaman (Menggunakan Resource)
    Route::resource('plants', PlantController::class)->only(['index', 'store', 'destroy']);

    // 3. Navigasi Tambahan (Non-CRUD Tanaman)
    Route::get('/kalender', [PlantController::class, 'calendarView'])->name('plants.calendar');
    Route::get('/laporan', [PlantController::class, 'report'])->name('plants.report');

    // 4. Fitur Ceklis & Batal Ceklis Aktivitas
    Route::patch('/activity/{id}/done', [ActivityController::class, 'done'])->name('activity.done');
    Route::patch('/activity/{id}/undo', [ActivityController::class, 'undo'])->name('activity.undo');

    /*
    |--------------------------------------------------------------------------
    | Profile Bawaan Laravel Breeze
    |--------------------------------------------------------------------------
    */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
