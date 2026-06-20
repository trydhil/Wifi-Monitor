<?php

namespace App\Http\Controllers;

use App\Models\Scan;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil data statistik dari database (meski masih kosong)
        $totalScans = Scan::count();
        $activeSSID = Scan::whereNotNull('ssid')->orderBy('created_at', 'desc')->value('ssid') ?? 'Belum ada scan';
        $lastScore = Scan::orderBy('created_at', 'desc')->value('score') ?? 0;
        $lastCategory = Scan::orderBy('created_at', 'desc')->value('kategori') ?? '-';

        return view('dashboard', compact('totalScans', 'activeSSID', 'lastScore', 'lastCategory'));
    }
}