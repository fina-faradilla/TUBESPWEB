<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LaporanApiController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// REST API Laporan
Route::apiResource('laporan', LaporanApiController::class)
    ->names([
        'index' => 'api.laporan.index',
        'store' => 'api.laporan.store',
        'show' => 'api.laporan.show',
        'update' => 'api.laporan.update',
        'destroy' => 'api.laporan.destroy',
    ]);