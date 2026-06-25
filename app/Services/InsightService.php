<?php

namespace App\Services;

use App\Models\Scan;
use Illuminate\Support\Facades\DB;

class InsightService
{
    /** Minimal jumlah sampel supaya insight dianggap valid (hindari noise) */
    private const MIN_SAMPLE = 3;

    /**
     * Generate semua insight otomatis. Return array of:
     * ['type' => 'warning'|'info'|'success', 'icon' => '...', 'text' => '...']
     */
    public static function generate(): array
    {
        $insights = [];

        self::addJamRawan($insights);
        self::addPerbandinganInterface($insights);
        self::addTrenMingguan($insights);
        self::addKoneksiTerburuk($insights);

        return $insights;
    }

    /** Insight 1: jam dalam sehari dengan rata-rata skor terendah */
    private static function addJamRawan(array &$insights): void
    {
        $rows = Scan::selectRaw("substr(jam, 1, 2) as hour, ROUND(AVG(score)) as avg_score, COUNT(*) as cnt")
            ->groupBy('hour')
            ->having('cnt', '>=', self::MIN_SAMPLE)
            ->orderBy('avg_score', 'asc')
            ->first();

        if ($rows) {
            $insights[] = [
                'type' => $rows->avg_score < 60 ? 'warning' : 'info',
                'icon' => 'bi-clock-history',
                'text' => "Jam <strong>{$rows->hour}:00–" . (str_pad(((int)$rows->hour + 1) % 24, 2, '0', STR_PAD_LEFT) . ":00") . "</strong> punya rata-rata skor terendah (<strong>{$rows->avg_score}</strong>) dari {$rows->cnt} scan. Kemungkinan jam sibuk/banyak pengguna di jaringan.",
            ];
        }
    }

    /** Insight 2: perbandingan performa WLAN vs LAN */
    private static function addPerbandinganInterface(array &$insights): void
    {
        $stats = Scan::selectRaw("interface, ROUND(AVG(score)) as avg_score, COUNT(*) as cnt")
            ->whereNotNull('interface')
            ->groupBy('interface')
            ->having('cnt', '>=', self::MIN_SAMPLE)
            ->get()
            ->keyBy('interface');

        if ($stats->has('WLAN') && $stats->has('LAN')) {
            $wlan = $stats['WLAN'];
            $lan  = $stats['LAN'];
            $diff = abs($wlan->avg_score - $lan->avg_score);

            if ($diff >= 10) {
                $better = $wlan->avg_score > $lan->avg_score ? 'WLAN' : 'LAN';
                $insights[] = [
                    'type' => 'info',
                    'icon' => 'bi-arrow-left-right',
                    'text' => "Koneksi <strong>{$better}</strong> tercatat lebih stabil — WLAN rata-rata {$wlan->avg_score}, LAN rata-rata {$lan->avg_score} (selisih {$diff} poin).",
                ];
            }
        }
    }

    /** Insight 3: tren skor minggu ini vs minggu lalu */
    private static function addTrenMingguan(array &$insights): void
    {
        $now = now();

        $minggIni = Scan::whereBetween('tanggal', [
                $now->copy()->subDays(7)->format('Y-m-d'),
                $now->format('Y-m-d'),
            ])->avg('score');

        $mingguLalu = Scan::whereBetween('tanggal', [
                $now->copy()->subDays(14)->format('Y-m-d'),
                $now->copy()->subDays(8)->format('Y-m-d'),
            ])->avg('score');

        if ($minggIni !== null && $mingguLalu !== null && $mingguLalu > 0) {
            $delta = round((($minggIni - $mingguLalu) / $mingguLalu) * 100);

            if (abs($delta) >= 5) {
                $arah = $delta > 0 ? 'naik' : 'turun';
                $type = $delta > 0 ? 'success' : 'warning';
                $icon = $delta > 0 ? 'bi-graph-up-arrow' : 'bi-graph-down-arrow';
                $insights[] = [
                    'type' => $type,
                    'icon' => $icon,
                    'text' => "Rata-rata skor minggu ini <strong>{$arah} " . abs($delta) . "%</strong> dibanding minggu lalu (" . round($mingguLalu) . " → " . round($minggIni) . ").",
                ];
            }
        }
    }

    /** Insight 4: SSID/koneksi dengan persentase kategori "Buruk" tertinggi */
    private static function addKoneksiTerburuk(array &$insights): void
    {
        $rows = Scan::selectRaw("ssid, COUNT(*) as total, SUM(CASE WHEN kategori = 'Buruk' THEN 1 ELSE 0 END) as buruk_count")
            ->whereNotNull('ssid')
            ->groupBy('ssid')
            ->having('total', '>=', self::MIN_SAMPLE)
            ->get()
            ->map(function ($r) {
                $r->persen_buruk = round(($r->buruk_count / $r->total) * 100);
                return $r;
            })
            ->sortByDesc('persen_buruk')
            ->first();

        if ($rows && $rows->persen_buruk >= 40) {
            $insights[] = [
                'type' => 'warning',
                'icon' => 'bi-exclamation-triangle',
                'text' => "Jaringan <strong>{$rows->ssid}</strong> berada di kategori \"Buruk\" pada <strong>{$rows->persen_buruk}%</strong> dari {$rows->total} scan. Pertimbangkan cek perangkat/posisi router.",
            ];
        }
    }
}
