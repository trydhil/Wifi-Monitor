<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ManualScanController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\SettingsController;

Route::get('/scan-manual', [ManualScanController::class, 'scan'])->name('scan.manual');
Route::get('/scan-manual/standar', [ManualScanController::class, 'setStandar'])->name('scan.manual.standar');
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/history', [HistoryController::class, 'index'])->name('history');
Route::get('/export-excel', [HistoryController::class, 'export'])->name('export.excel');
Route::get('/pengaturan', [SettingsController::class, 'index'])->name('settings');
Route::post('/pengaturan', [SettingsController::class, 'update'])->name('settings.update');
