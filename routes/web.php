<?php

use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Resource route otomatis meng-cover: index, create, store, show, edit, update, destroy
Route::resource('laporan', LaporanController::class);

// Halaman "Sampah" (soft delete) laporan warga.
Route::get('laporan-sampah', [LaporanController::class, 'trashed'])->name('laporan.trashed');
Route::patch('laporan/{id}/restore', [LaporanController::class, 'restore'])->name('laporan.restore');
Route::delete('laporan/{id}/force-delete', [LaporanController::class, 'forceDelete'])->name('laporan.forceDelete');

// Arahkan halaman utama ke daftar laporan
Route::get('/', [LaporanController::class, 'index'])->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
