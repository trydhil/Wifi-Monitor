<?php

namespace App\Http\Controllers;

use App\Exports\ScansExport;
use App\Models\Scan;
use App\Services\ScoringService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class HistoryController extends Controller
{
    public function index(Request $request)
    {
        if ($request->filled('standar')) {
            $request->session()->put('scoring_standar', $request->standar);
        }

        $query = Scan::with('user')->orderBy('created_at', 'desc');

        if ($request->filled('tanggal_awal') && $request->filled('tanggal_akhir')) {
            $query->whereBetween('tanggal', [$request->tanggal_awal, $request->tanggal_akhir]);
        }
        if ($request->filled('ssid')) {
            $query->where('ssid', $request->ssid);
        }
        if ($request->filled('interface')) {
            $query->where('interface', strtoupper($request->interface));
        }

        $scans     = $query->paginate(15);
        $activeKey = ScoringService::activeKey();
        $standards = ScoringService::allStandards();

        // Recalculate skor pakai standar aktif
        $scans->getCollection()->transform(function ($scan) use ($activeKey) {
            $scored = ScoringService::calculate(
                download:   $scan->download  ?? 0,
                upload:     $scan->upload    ?? 0,
                ping:       $scan->ping      ?? 0,
                signal:     $scan->signal    ?? null,
                interface:  $scan->interface ?? 'WLAN',
                standarKey: $activeKey,
            );
            $scan->score    = $scored['score'];
            $scan->kategori = $scored['kategori'];
            return $scan;
        });

        // Filter kategori setelah recalculate
        if ($request->filled('kategori')) {
            $filtered = $scans->getCollection()->filter(
                fn($s) => $s->kategori === $request->kategori
            );
            $scans->setCollection($filtered->values());
        }

        $ssidList = Scan::distinct()->orderBy('ssid')->pluck('ssid');

        // ── Analisis: Avg Score per SSID ─────────────────────────────
        $ssidComparison = Scan::select('ssid', DB::raw('ROUND(AVG(score),1) as avg_score'))
            ->whereNotNull('ssid')
            ->groupBy('ssid')
            ->orderByDesc('avg_score')
            ->limit(6)
            ->get();

        // ── Analisis: Hourly Performance ─────────────────────────────
        $hourlyComparison = Scan::select(
                DB::raw("CAST(strftime('%H', jam) AS INTEGER) as hour"),
                DB::raw('ROUND(AVG(score),1) as avg_score')
            )
            ->groupBy('hour')
            ->orderBy('hour')
            ->get();

        // Jam rawan = jam dengan avg_score terendah
        $peakHour = $hourlyComparison->sortBy('avg_score')->first()?->hour;

        // ── Analisis: Interface Proportion ───────────────────────────
        $wlanCount = Scan::where(function($q) {
            $q->where('interface', 'LIKE', 'wlan%')
              ->orWhere('interface', 'LIKE', 'wifi%')
              ->orWhere('interface', 'LIKE', 'wi-fi%')
              ->orWhere('interface', 'LIKE', 'WLAN%')
              ->orWhere('interface', 'LIKE', 'Wi-Fi%')
              ->orWhereNull('interface')
              ->orWhere('interface', '');
        })->count();

        $totalScans = Scan::count();
        $lanCount   = max(0, $totalScans - $wlanCount);

        $interfaceComparison = [
            'wlan'    => ['count' => $wlanCount],
            'lan'     => ['count' => $lanCount],
            'verdict' => $wlanCount > $lanCount
                ? 'Mayoritas monitoring via WLAN'
                : ($lanCount > $wlanCount ? 'Mayoritas monitoring via LAN' : null),
        ];

        // ── Analisis: Weekly Score Trend (4 minggu) ──────────────────
        $weeklyTrend = collect();
        for ($i = 3; $i >= 0; $i--) {
            $start = now()->subWeeks($i)->startOfWeek();
            $end   = now()->subWeeks($i)->endOfWeek();
            $avg   = Scan::whereBetween('created_at', [$start, $end])->avg('score');
            $weeklyTrend->push([
                'week_label' => 'Week ' . (4 - $i),
                'avg_score'  => round($avg ?? 0, 1),
            ]);
        }

        // Tren naik/turun
        $first  = $weeklyTrend->first()['avg_score'];
        $last   = $weeklyTrend->last()['avg_score'];
        $diff   = $last - $first;
        $weeklyTrendPct = ($first > 0)
            ? ($diff >= 0 ? '+' : '') . round($diff / $first * 100, 1) . '%'
            : null;

        return view('history', compact(
            'scans', 'ssidList', 'standards', 'activeKey',
            'ssidComparison', 'hourlyComparison', 'peakHour',
            'interfaceComparison', 'weeklyTrend', 'weeklyTrendPct'
        ));
    }

    public function destroy($id)
    {
        Scan::findOrFail($id)->delete();
        return back()->with('success', 'Data scan berhasil dihapus.');
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