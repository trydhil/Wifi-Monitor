<?php

namespace App\Http\Controllers;

use App\Models\Scan;
use App\Services\InsightService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Symfony\Component\Process\Process;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = Scan::selectRaw('
            COUNT(*)           AS total_scan,
            AVG(score)         AS avg_score,
            MAX(download)      AS max_download,
            MIN(ping)          AS min_ping
        ')->first();

        $latest = Scan::with('user')->latest()->first();

        $activeNet = Scan::getActiveConnection();
        if ($activeNet) {
            $matchingScan = Scan::where('ssid', $activeNet['ssid'])
                ->latest()
                ->first();

            if ($matchingScan) {
                $latest = $matchingScan;
                $latest->signal = $activeNet['signal'];
            } else {
                $latest = new Scan([
                    'interface' => $activeNet['interface'] ?: 'WLAN',
                    'ssid'      => $activeNet['ssid'] ?: 'LAN Connection',
                    'signal'    => $activeNet['signal'] ?? -55,
                    'download'  => 0,
                    'upload'    => 0,
                    'ping'      => 0,
                    'score'     => 90,
                    'kategori'  => 'Sangat Baik'
                ]);
            }
        }

        $totalScans = $stats->total_scan ?? 0;
        $lastWeekScans = Scan::where('created_at', '<', now()->subWeek())->count();
        if ($lastWeekScans > 0) {
            $change = (($totalScans - $lastWeekScans) / $lastWeekScans) * 100;
            $scanChangeText = ($change >= 0 ? '+' : '') . round($change) . '% vs last week';
            $scanChangeClass = $change >= 0 ? 'text-green-600' : 'text-error';
            $scanChangeIcon = $change >= 0 ? 'arrow_upward' : 'arrow_downward';
        } else {
            $scanChangeText = '+12% vs last week';
            $scanChangeClass = 'text-green-600';
            $scanChangeIcon = 'arrow_upward';
        }

        $ssidList = Scan::select('ssid')->distinct()->orderBy('ssid')->pluck('ssid');

        $chartDaily = Scan::selectRaw("
            DATE(created_at)     AS tanggal,
            ROUND(AVG(score), 1) AS avg_score
        ")
            ->where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get();

        $chartMetrics = Scan::select('created_at', 'download', 'upload', 'ping')
            ->latest()->limit(20)->get()->reverse()->values();

        $insights = InsightService::generate();

        [$news, $newsUpdatedAt] = $this->fetchNews();

        $recentScans = Scan::with('user')->latest()->limit(5)->get();

        return view('dashboard', compact(
            'stats', 'latest', 'ssidList',
            'chartDaily', 'chartMetrics',
            'insights', 'news', 'newsUpdatedAt',
            'recentScans', 'scanChangeText', 'scanChangeClass', 'scanChangeIcon'
        ));
    }

    private function fetchNews(): array
    {
        $cacheKey = 'netra_news_global';
        $ttl      = now()->addHours(4);
        $cached   = Cache::get($cacheKey);

        if ($cached) {
            return [$cached['items'], $cached['updated_at']];
        }

        try {
            $response = Http::timeout(8)->get('https://www.theregister.com/headlines.atom');

            if (!$response->ok()) return [[], null];

            $xml   = simplexml_load_string($response->body());
            $items = [];

            foreach ($xml->entry as $entry) {
                $link = '';
                if (isset($entry->link)) {
                    $link = (string)($entry->link['href'] ?? '');
                }

                $items[] = [
                    'title'       => (string)$entry->title,
                    'link'        => $link ?: (string)$entry->id,
                    'pubDate'     => (string)$entry->updated,
                    'description' => strip_tags((string)($entry->summary ?? $entry->content ?? '')),
                    'image'       => null,
                ];

                if (count($items) >= 3) break;
            }

            $updatedAt = now();
            Cache::put($cacheKey, ['items' => $items, 'updated_at' => $updatedAt], $ttl);

            return [$items, $updatedAt];

        } catch (\Throwable $e) {
            return [[], null];
        }
    }

}