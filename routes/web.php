<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ManualScanController;
use App\Http\Controllers\HistoryController;

Route::get('/scan-manual', [ManualScanController::class, 'scan'])->name('scan.manual');
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/history', [HistoryController::class, 'index'])->name('history');
Route::get('/export-excel', [HistoryController::class, 'export'])->name('export.excel');