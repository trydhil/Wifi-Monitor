<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ManualScanController;

Route::get('/scan-manual', [ManualScanController::class, 'scan'])->name('scan.manual');
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
