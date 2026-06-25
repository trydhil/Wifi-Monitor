<?php

namespace App\Http\Controllers;

use App\Models\Scan;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Satu query untuk statistik agregat — tidak duplikat
        $stats = Scan::selectRaw('
            COUNT(*)           AS total_scan,
            AVG(score)         AS avg_score,
            MAX(download)      AS max_download,
            MIN(ping)          AS min_ping
        ')->first();

        // Scan terakhir — null-safe dipakai di view dengan ?->
        $latest = Scan::latest()->first();

        // SSID unik yang pernah tercatat
        $ssidList = Scan::select('ssid')
            ->distinct()
            ->orderBy('ssid')
            ->pluck('ssid');

        // --- Data grafik: Skor harian (7 hari terakhir) ---
        // Grouping per tanggal, ambil rata-rata skor
        $chartDaily = Scan::selectRaw("
            DATE(created_at)  AS tanggal,
            ROUND(AVG(score), 1) AS avg_score
        ")
            ->where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get();

        // --- Data grafik: Download / Upload / Ping (20 scan terakhir) ---
        $chartMetrics = Scan::select('created_at', 'download', 'upload', 'ping')
            ->latest()
            ->limit(20)
            ->get()
            ->reverse()  // urut dari lama ke baru supaya grafik runtut
            ->values();

        return view('dashboard', compact(
            'stats',
            'latest',
            'ssidList',
            'chartDaily',
            'chartMetrics'
        ));
    }
}