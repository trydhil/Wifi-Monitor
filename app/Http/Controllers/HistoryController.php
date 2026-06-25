<?php

namespace App\Http\Controllers;

use App\Exports\ScansExport;
use App\Models\Scan;
use App\Services\ScoringService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class HistoryController extends Controller
{
    public function index(Request $request)
    {
        // Simpan pilihan standar ke session
        if ($request->filled('standar')) {
            $request->session()->put('scoring_standar', $request->standar);
        }

        $query = Scan::query();

        if ($request->filled('tanggal_awal') && $request->filled('tanggal_akhir')) {
            $query->whereBetween('tanggal', [$request->tanggal_awal, $request->tanggal_akhir]);
        }
        if ($request->filled('ssid')) {
            $query->where('ssid', $request->ssid);
        }
        if ($request->filled('interface')) {
            $query->where('interface', strtoupper($request->interface));
        }
        if ($request->filled('kategori')) {
            // Filter kategori berdasarkan skor raw — tidak bisa filter kolom DB
            // karena kategori dihitung ulang. Kita filter setelah recalculate.
            // Simpan untuk dipakai di bawah.
        }

        $scans = $query->orderBy('created_at', 'desc')->paginate(15);

        // Recalculate skor & kategori semua record pakai standar aktif
        $activeKey = ScoringService::activeKey();
        $scans->getCollection()->transform(function ($scan) use ($activeKey) {
            $scored = ScoringService::calculate(
                download:  $scan->download  ?? 0,
                upload:    $scan->upload    ?? 0,
                ping:      $scan->ping      ?? 0,
                signal:    $scan->signal    ?? null,
                interface: $scan->interface ?? 'WLAN',
                standarKey: $activeKey,
            );
            $scan->score    = $scored['score'];
            $scan->kategori = $scored['kategori'];
            return $scan;
        });

        // Filter kategori setelah recalculate (kalau ada)
        if ($request->filled('kategori')) {
            $filtered = $scans->getCollection()->filter(
                fn($s) => $s->kategori === $request->kategori
            );
            $scans->setCollection($filtered->values());
        }

        $ssidList  = Scan::distinct()->pluck('ssid');
        $standards = ScoringService::allStandards();
        $activeKey = ScoringService::activeKey();

        return view('history', compact('scans', 'ssidList', 'standards', 'activeKey'));
    }

    public function export(Request $request)
    {
        if ($request->filled('standar')) {
            $request->session()->put('scoring_standar', $request->standar);
        }

        $namaFile = 'riwayat-scan-' . now()->format('Y-m-d_His') . '.xlsx';
        return Excel::download(new ScansExport($request), $namaFile);
    }
}