<?php

use App\Http\Controllers\LaporanController;
use Illuminate\Support\Facades\Route;

// Resource route otomatis meng-cover: index, create, store, show, edit, update, destroy
Route::resource('laporan', LaporanController::class);

// Contoh: arahkan halaman utama ke daftar laporan
Route::get('/', [LaporanController::class, 'index'])->name('home');


Route::get('/laporan/{id}', [LaporanController::class, 'show'])
    ->name('laporan.show');

Route::get('laporan-sampah', [LaporanController::class, 'trashed'])->name('laporan.trashed');
Route::patch('laporan/{id}/restore', [LaporanController::class, 'restore'])->name('laporan.restore');
Route::delete('laporan/{id}/force-delete', [LaporanController::class, 'forceDelete'])->name('laporan.forceDelete');
