<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ManualScanController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

// ── Auth ──────────────────────────────────────────────────
Route::get('/login',  [LoginController::class, 'create'])->middleware('guest')->name('login');
Route::post('/login', [LoginController::class, 'store'])->middleware('guest');
Route::post('/logout',[LoginController::class, 'destroy'])->middleware('auth')->name('logout');

// ── Protected ─────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/',              [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/scan-manual',   [ManualScanController::class, 'scan'])->name('scan.manual');
    Route::post('/scan-manual/run', [ManualScanController::class, 'runScan'])->name('scan.manual.run');
    Route::get('/scan-manual/standar', [ManualScanController::class, 'setStandar'])->name('scan.manual.standar');
    Route::get('/history',       [HistoryController::class, 'index'])->name('history');
    Route::delete('/history/{id}', [HistoryController::class, 'destroy'])->name('history.destroy');
    Route::get('/export-excel',  [HistoryController::class, 'export'])->name('export.excel');
    Route::get('/pengaturan',    [SettingsController::class, 'index'])->name('settings');
    Route::post('/pengaturan',   [SettingsController::class, 'update'])->name('settings.update');
});