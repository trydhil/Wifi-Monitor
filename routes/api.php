<?php

use App\Http\Controllers\ApiScanController;
use Illuminate\Support\Facades\Route;

Route::post('/scan', [ApiScanController::class, 'store']);