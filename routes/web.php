<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IoTController;

// Halaman utama - Real-time monitoring
Route::get('/', [IoTController::class, 'index'])->name('iot.index');

// Halaman dashboard - Rekap & Chart
Route::get('/dashboard', [IoTController::class, 'dashboard'])->name('iot.dashboard');

// API untuk update real-time
Route::get('/api/data', [IoTController::class, 'getData'])->name('iot.data');