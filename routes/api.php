<?php

use App\Http\Controllers\ApiScanController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ManualScanController;

Route::get('/scan-manual', [ManualScanController::class, 'scanApi']);
Route::get('/ssid-list', function() {
    return \App\Models\Scan::distinct()->pluck('ssid');
});
Route::post('/scan', [ApiScanController::class, 'store']);