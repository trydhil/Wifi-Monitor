@extends('layouts.app')

@section('title', 'Dashboard')

@section('topbar-action')
<button onclick="window.location.reload();" class="bg-secondary text-on-secondary px-md py-xs rounded-full font-title-sm text-body-md hover:opacity-80 transition-opacity flex items-center gap-xs select-none">
    <span class="material-symbols-outlined text-[18px]">refresh</span>
    Refresh Data
</button>
@endsection

@section('content')
<div class="p-lg max-w-container-max mx-auto w-full space-y-lg">
    <!-- 1. Connection Banner -->
    <div class="w-full bg-surface-container-lowest rounded-xl p-md border border-outline-variant flex items-center justify-between shadow-sm">
        <div class="flex items-center gap-md">
            @if($latest)
                @php 
                    $isWlan = strtoupper($latest->interface ?? 'WLAN') === 'WLAN'; 
                    $signal = $latest->signal ?? -100;
                    if ($signal >= -60) {
                        $dotClass = 'bg-green-500';
                        $pulseClass = 'bg-green-400';
                        $badgeText = 'Koneksi Optimal';
                        $badgeClass = 'bg-green-500/10 text-green-600';
                    } elseif ($signal >= -75) {
                        $dotClass = 'bg-secondary';
                        $pulseClass = 'bg-secondary-container';
                        $badgeText = 'Koneksi Stabil';
                        $badgeClass = 'bg-secondary/10 text-secondary';
                    } elseif ($signal >= -85) {
                        $dotClass = 'bg-amber-500';
                        $pulseClass = 'bg-amber-400';
                        $badgeText = 'Koneksi Cukup';
                        $badgeClass = 'bg-amber-500/10 text-amber-600';
                    } else {
                        $dotClass = 'bg-error';
                        $pulseClass = 'bg-error-container';
                        $badgeText = 'Koneksi Lemah';
                        $badgeClass = 'bg-error-container/30 text-error';
                    }
                @endphp
                <div class="relative flex h-3 w-3">
                    <span class="status-dot-pulse absolute inline-flex h-full w-full rounded-full {{ $pulseClass }} opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 {{ $dotClass }}"></span>
                </div>
                <div class="flex flex-col">
                    <span class="font-title-sm text-title-sm text-primary">Terhubung via {{ $isWlan ? 'WLAN' : 'LAN' }}</span>
                    <span class="font-body-sm text-body-sm text-on-surface-variant">
                        @if($isWlan)
                            SSID: {{ $latest->ssid ?? 'wlan0' }} · Signal: {{ $latest->signal ?? '—' }} dBm
                        @else
                            Kabel terhubung · Signal: N/A
                        @endif
                    </span>
                </div>
            @else
                <div class="relative flex h-3 w-3">
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-outline-variant"></span>
                </div>
                <div class="flex flex-col">
                    <span class="font-title-sm text-title-sm text-primary">Belum ada data scan</span>
                    <span class="font-body-sm text-body-sm text-on-surface-variant">Jalankan scan manual pertama Anda</span>
                </div>
            @endif
        </div>
        
        @if($latest)
            <div class="flex items-center gap-xs {{ $badgeClass }} px-md py-xs rounded-full font-title-sm text-body-sm">
                <span class="material-symbols-outlined text-[18px]">{{ $isWlan ? 'signal_wifi_4_bar' : 'ethernet' }}</span>
                {{ $badgeText }}
            </div>
        @endif
    </div>

    <!-- 2. AI Auto-Insights Chips -->
    @if(count($insights) > 0)
        <div class="flex flex-wrap gap-md mb-lg">
            @foreach($insights as $insight)
                @php
                    $badgeColor = 'bg-surface-container-high border-outline-variant text-on-surface';
                    $iconColor = 'text-secondary';
                    if ($insight['type'] === 'warning') {
                        $badgeColor = 'bg-error-container/30 border-error/20 text-on-error-container';
                        $iconColor = 'text-error';
                    } elseif ($insight['type'] === 'success') {
                        $badgeColor = 'bg-green-100/50 border-green-200 text-green-800';
                        $iconColor = 'text-green-600';
                    }

                    $iconMap = [
                        'bi-clock-history' => 'history',
                        'bi-arrow-left-right' => 'compare_arrows',
                        'bi-graph-up-arrow' => 'trending_up',
                        'bi-graph-down-arrow' => 'trending_down',
                        'bi-exclamation-triangle' => 'warning',
                        'bi-info-circle' => 'info',
                    ];
                    $iconName = $iconMap[$insight['icon']] ?? $insight['icon'] ?? 'info';
                @endphp
                <div class="flex items-center gap-xs px-md py-base rounded-full border {{ $badgeColor }}">
                    <span class="material-symbols-outlined text-[16px] {{ $iconColor }}" style="font-variation-settings: 'FILL' 1;">{{ $iconName }}</span>
                    <span class="font-body-sm text-body-sm">{!! $insight['text'] !!}</span>
                </div>
            @endforeach
        </div>
    @else
        <!-- Fallback Mock Chips from design -->
        <div class="flex flex-wrap gap-md mb-lg">
            <div class="flex items-center gap-xs bg-surface-container-high px-md py-base rounded-full border border-outline-variant">
                <span class="material-symbols-outlined text-[16px] text-on-tertiary-container" style="font-variation-settings: 'FILL' 1;">bolt</span>
                <span class="font-body-sm text-body-sm text-on-surface">Peak Latency: 14:30</span>
            </div>
            <div class="flex items-center gap-xs bg-surface-container-high px-md py-base rounded-full border border-outline-variant">
                <span class="material-symbols-outlined text-[16px] text-secondary" style="font-variation-settings: 'FILL' 1;">verified</span>
                <span class="font-body-sm text-body-sm text-on-surface">Stability Index: 98.2%</span>
            </div>
            <div class="flex items-center gap-xs bg-surface-container-high px-md py-base rounded-full border border-outline-variant">
                <span class="material-symbols-outlined text-[16px] text-green-600" style="font-variation-settings: 'FILL' 1;">trending_up</span>
                <span class="font-body-sm text-body-sm text-on-surface">Performance: Upwards</span>
            </div>
            <div class="flex items-center gap-xs bg-error-container/30 px-md py-base rounded-full border border-error/20">
                <span class="material-symbols-outlined text-[16px] text-error" style="font-variation-settings: 'FILL' 1;">warning</span>
                <span class="font-body-sm text-body-sm text-on-error-container">Capacity Alert: 85% Load</span>
            </div>
        </div>
    @endif

    <!-- 3. Stat Cards Bento Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-lg mb-lg">
        <div class="bg-surface-container-lowest p-lg rounded-xl border border-outline-variant shadow-sm hover:shadow-md transition-shadow">
            <div class="flex justify-between items-start mb-sm">
                <span class="text-on-surface-variant font-title-sm text-body-md">Total Scan</span>
                <span class="material-symbols-outlined text-secondary">radar</span>
            </div>
            <div class="font-display-lg text-display-lg text-primary">{{ number_format($stats->total_scan ?? 0) }}</div>
            <div class="{{ $scanChangeClass }} font-body-sm text-body-sm mt-xs flex items-center gap-1">
                <span class="material-symbols-outlined text-[14px]">{{ $scanChangeIcon }}</span> {{ $scanChangeText }}
            </div>
        </div>
        
        <div class="bg-surface-container-lowest p-lg rounded-xl border border-outline-variant shadow-sm hover:shadow-md transition-shadow">
            <div class="flex justify-between items-start mb-sm">
                <span class="text-on-surface-variant font-title-sm text-body-md">Skor Terakhir</span>
                <span class="material-symbols-outlined text-green-500">check_circle</span>
            </div>
            <div class="font-display-lg text-display-lg text-primary">{{ $latest->score ?? '—' }}<span class="text-headline-md text-on-surface-variant font-normal">/100</span></div>
            <div class="text-on-surface-variant font-body-sm text-body-sm mt-xs">
                @if($latest)
                    {{ $latest->score >= 90 ? 'Optimal Condition' : ($latest->score >= 75 ? 'Good Condition' : ($latest->score >= 60 ? 'Fair Condition' : 'Poor Condition')) }}
                @else
                    Belum ada data scan
                @endif
            </div>
        </div>
        
        <div class="bg-surface-container-lowest p-lg rounded-xl border border-outline-variant shadow-sm hover:shadow-md transition-shadow">
            <div class="flex justify-between items-start mb-sm">
                <span class="text-on-surface-variant font-title-sm text-body-md">Download Maks</span>
                <span class="material-symbols-outlined text-tertiary-fixed-dim">download</span>
            </div>
            <div class="font-display-lg text-display-lg text-primary">{{ number_format($stats->max_download ?? 0, 1) }} <span class="text-title-sm font-medium">Mbps</span></div>
            <div class="text-on-surface-variant font-body-sm text-body-sm mt-xs">Peak bandwidth recorded</div>
        </div>
        
        <div class="bg-surface-container-lowest p-lg rounded-xl border border-outline-variant shadow-sm hover:shadow-md transition-shadow">
            <div class="flex justify-between items-start mb-sm">
                <span class="text-on-surface-variant font-title-sm text-body-md">Ping Terbaik</span>
                <span class="material-symbols-outlined text-error">speed</span>
            </div>
            <div class="font-display-lg text-display-lg text-primary">{{ number_format($stats->min_ping ?? 0, 1) }} <span class="text-title-sm font-medium">ms</span></div>
            <div class="{{ ($stats->min_ping ?? 100) <= 20 ? 'text-green-600' : 'text-on-surface-variant' }} font-body-sm text-body-sm mt-xs">
                {{ ($stats->min_ping ?? 100) <= 20 ? 'Ultra Low Latency' : 'Normal Latency' }}
            </div>
        </div>
    </div>

    <!-- 4. Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-lg mb-lg">
        <!-- Daily Score Trend -->
        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant shadow-sm p-lg">
            <div class="flex justify-between items-center mb-xl">
                <h3 class="font-title-sm text-title-sm text-primary">Daily Score Trend</h3>
                <select class="bg-transparent border-none text-body-sm text-on-surface-variant focus:ring-0 cursor-pointer">
                    <option>Last 7 Days</option>
                    <option>Last 30 Days</option>
                </select>
            </div>
            @if(!$chartDaily->isEmpty())
                <div class="h-64 flex items-end gap-2 px-2">
                    @foreach($chartDaily as $day)
                        @php
                            $score = round($day->avg_score);
                            $height = max(10, min(100, $score)); // Map 0-100 score directly to height percentage
                            $isLast = $loop->last;
                            $barBg = $isLast ? 'bg-secondary' : 'bg-secondary/10 hover:bg-secondary/30';
                            $textClass = $isLast ? 'bg-primary text-white text-[10px] px-2 py-1 rounded' : 'absolute -top-8 left-1/2 -translate-x-1/2 bg-primary text-white text-[10px] px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity';
                        @endphp
                        <div class="flex-1 {{ $barBg }} rounded-t transition-all relative group" style="height: {{ $height }}%">
                            <div class="{{ $textClass }}">{{ $score }}</div>
                        </div>
                    @endforeach
                </div>
                <div class="flex justify-between mt-md text-body-sm text-on-surface-variant px-2">
                    @foreach($chartDaily as $day)
                        <span>{{ \Carbon\Carbon::parse($day->tanggal)->translatedFormat('D') }}</span>
                    @endforeach
                </div>
            @else
                <div class="h-64 flex items-center justify-center text-on-surface-variant opacity-60">
                    Belum ada data skor harian
                </div>
            @endif
        </div>
        
        <!-- Metrics Throughput (Ping/DL/UL) -->
        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant shadow-sm p-lg">
            <div class="flex justify-between items-center mb-xl">
                <h3 class="font-title-sm text-title-sm text-primary">Metrics Throughput</h3>
                <div class="flex gap-md">
                    <div class="flex items-center gap-1 text-[10px] font-bold text-on-surface-variant">
                        <span class="w-2 h-2 rounded-full bg-secondary"></span> DL
                    </div>
                    <div class="flex items-center gap-1 text-[10px] font-bold text-on-surface-variant">
                        <span class="w-2 h-2 rounded-full bg-tertiary-fixed-dim"></span> UL
                    </div>
                    <div class="flex items-center gap-1 text-[10px] font-bold text-on-surface-variant">
                        <span class="w-2 h-2 rounded-full bg-error"></span> Ping
                    </div>
                </div>
            </div>
            <!-- Dynamic ChartJS wave line visualization -->
            <div class="h-64 w-full relative">
                @if($chartMetrics->isEmpty())
                    <div class="absolute inset-0 flex items-center justify-center text-muted">Belum ada data performa kecepatan</div>
                @else
                    <canvas id="chartMetrics" class="w-full h-full"></canvas>
                @endif
            </div>
        </div>
    </div>

    <!-- 5. Berita Teknologi Grid -->
    <div class="mb-lg">
        <h3 class="font-title-sm text-title-sm text-primary mb-md">Berita Teknologi Terbaru</h3>
        @if(count($news) === 0)
            <div class="grid grid-cols-1 md:grid-cols-3 gap-lg">
                <!-- Fallback News Card 1 -->
                <div class="bg-surface-container-lowest rounded-xl border border-outline-variant overflow-hidden shadow-sm group">
                    <div class="h-40 overflow-hidden relative">
                        <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCws4W7-mer8DzbPGHygbBS1yJBvzppHMpDe5XWsiUjvCI2U15_CS1hIAaGG9fOd38Wkf1-AFvl8P1NBa13xrO3s-Kl6ojhekehcmb-V13LG3BzIW0OmeooScUG7lJ__FL9de3fGKQv0RXJqf4_8R-XpsdhsC5zTlSsYO_te_tVT29BelbfCuVhDfN0DeQT0-So0tXB7sUAePzTXm9fiiTBCVEbuYGdbUabDCiK2BEYsbczs6nhMuF8H466rg3s4ai1xwVdmkVxlng">
                        <div class="absolute top-md left-md bg-secondary text-white text-[10px] px-2 py-1 font-bold rounded">DETIK INET</div>
                    </div>
                    <div class="p-md">
                        <h4 class="font-title-sm text-body-md text-primary line-clamp-2 mb-xs">Eksplorasi Teknologi 6G: Kecepatan Tanpa Batas Masa Depan</h4>
                        <p class="text-body-sm text-on-surface-variant line-clamp-2">Para peneliti mulai menguji coba frekuensi terahertz untuk implementasi jaringan 6G...</p>
                        <a class="inline-block mt-md text-secondary font-bold text-body-sm hover:underline" href="#">Baca Selengkapnya</a>
                    </div>
                </div>
                <!-- Fallback News Card 2 -->
                <div class="bg-surface-container-lowest rounded-xl border border-outline-variant overflow-hidden shadow-sm group">
                    <div class="h-40 overflow-hidden relative">
                        <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" src="https://lh3.googleusercontent.com/aida-public/AB6AXuD4_i2CUX-AR_a5VQCQ_21GnyUTD6TOgtsNr1KKI3ZLWPajOoCxhvDtHz1LG7JfC8FPPEHlV2tP53a_p7KE3AoBUB5Dy0WgnQWn-DbpC9l-Xj3IB8dRwhEei7IvkcEI82ll8-TarOJa0AOvLSpuKZkLyyNDsnc_8B48yojfha6XkkrpyADf2qTWDF-kdUnFip6oqKkAYuaxft-lv3ciNmDLx06n-2kqz4y-YMS0pUBHx7Qx7GVaO9knWkkz_tdHHNtGgiQbT_L9lMc">
                        <div class="absolute top-md left-md bg-secondary text-white text-[10px] px-2 py-1 font-bold rounded">SECURITY</div>
                    </div>
                    <div class="p-md">
                        <h4 class="font-title-sm text-body-md text-primary line-clamp-2 mb-xs">Tantangan Cyber Security 2024: AI sebagai Senjata Baru</h4>
                        <p class="text-body-sm text-on-surface-variant line-clamp-2">Ancaman ransomware semakin canggih dengan integrasi kecerdasan buatan...</p>
                        <a class="inline-block mt-md text-secondary font-bold text-body-sm hover:underline" href="#">Baca Selengkapnya</a>
                    </div>
                </div>
                <!-- Fallback News Card 3 -->
                <div class="bg-surface-container-lowest rounded-xl border border-outline-variant overflow-hidden shadow-sm group">
                    <div class="h-40 overflow-hidden relative">
                        <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCdvTyQNHmEXl5yhX5kv5rVkQWMF4POTGVwe4F2QMOpv5f52c0cYuLGh4vJET_KS0Y5zlc1-AywvwQBQdZ7zmkwpKnR-s8Cflc2axDttiLKjGzM6-kYLTjF6QUcjx7CH7oGFSbP8Jkzp4Ew5ODW-gs4mtjFKtVRVpr-zc-rfMFb27-75QIjly8x5pb8Xc9OnBqI-TRRSiMSs0JXIpo3Kusn5ijKvyz39qa7JitxAsmFrfZO6xakJHsHiHMjSyd6P8VTZrChQOTRZR4">
                        <div class="absolute top-md left-md bg-secondary text-white text-[10px] px-2 py-1 font-bold rounded">CLOUD</div>
                    </div>
                    <div class="p-md">
                        <h4 class="font-title-sm text-body-md text-primary line-clamp-2 mb-xs">Cloud Infrastructure: Skalabilitas Bisnis di Era Digital</h4>
                        <p class="text-body-sm text-on-surface-variant line-clamp-2">Penyedia layanan cloud terus memperluas kapasitas server global untuk mendukung demand...</p>
                        <a class="inline-block mt-md text-secondary font-bold text-body-sm hover:underline" href="#">Baca Selengkapnya</a>
                    </div>
                </div>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-3 gap-lg">
                @foreach($news as $index => $item)
                    @php
                        $categories = ['NETWORKS', 'SECURITY', 'CLOUD'];
                        $images = [
                            'https://lh3.googleusercontent.com/aida-public/AB6AXuCws4W7-mer8DzbPGHygbBS1yJBvzppHMpDe5XWsiUjvCI2U15_CS1hIAaGG9fOd38Wkf1-AFvl8P1NBa13xrO3s-Kl6ojhekehcmb-V13LG3BzIW0OmeooScUG7lJ__FL9de3fGKQv0RXJqf4_8R-XpsdhsC5zTlSsYO_te_tVT29BelbfCuVhDfN0DeQT0-So0tXB7sUAePzTXm9fiiTBCVEbuYGdbUabDCiK2BEYsbczs6nhMuF8H466rg3s4ai1xwVdmkVxlng',
                            'https://lh3.googleusercontent.com/aida-public/AB6AXuD4_i2CUX-AR_a5VQCQ_21GnyUTD6TOgtsNr1KKI3ZLWPajOoCxhvDtHz1LG7JfC8FPPEHlV2tP53a_p7KE3AoBUB5Dy0WgnQWn-DbpC9l-Xj3IB8dRwhEei7IvkcEI82ll8-TarOJa0AOvLSpuKZkLyyNDsnc_8B48yojfha6XkkrpyADf2qTWDF-kdUnFip6oqKkAYuaxft-lv3ciNmDLx06n-2kqz4y-YMS0pUBHx7Qx7GVaO9knWkkz_tdHHNtGgiQbT_L9lMc',
                            'https://lh3.googleusercontent.com/aida-public/AB6AXuCdvTyQNHmEXl5yhX5kv5rVkQWMF4POTGVwe4F2QMOpv5f52c0cYuLGh4vJET_KS0Y5zlc1-AywvwQBQdZ7zmkwpKnR-s8Cflc2axDttiLKjGzM6-kYLTjF6QUcjx7CH7oGFSbP8Jkzp4Ew5ODW-gs4mtjFKtVRVpr-zc-rfMFb27-75QIjly8x5pb8Xc9OnBqI-TRRSiMSs0JXIpo3Kusn5ijKvyz39qa7JitxAsmFrfZO6xakJHsHiHMjSyd6P8VTZrChQOTRZR4'
                        ];
                        $category = $categories[$index % 3];
                        $imgUrl = $item['image'] ?? $images[$index % 3];
                    @endphp
                    <div class="bg-surface-container-lowest rounded-xl border border-outline-variant overflow-hidden shadow-sm group">
                        <div class="h-40 overflow-hidden relative">
                            <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" src="{{ $imgUrl }}" alt="{{ $item['title'] }}">
                            <div class="absolute top-md left-md bg-secondary text-white text-[10px] px-2 py-1 font-bold rounded">{{ $category }}</div>
                        </div>
                        <div class="p-md">
                            <h4 class="font-title-sm text-body-md text-primary line-clamp-2 mb-xs">{{ $item['title'] }}</h4>
                            <p class="text-body-sm text-on-surface-variant line-clamp-2">{{ $item['description'] }}</p>
                            <a class="inline-block mt-md text-secondary font-bold text-body-sm hover:underline" href="{{ $item['link'] }}" target="_blank">Baca Selengkapnya</a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- 6. Scan Terakhir Table -->
    <div class="bg-surface-container-lowest rounded-xl border border-outline-variant shadow-sm overflow-hidden">
        <div class="px-lg py-md border-b border-outline-variant flex justify-between items-center">
            <h3 class="font-title-sm text-title-sm text-primary">Riwayat Scan Terakhir</h3>
            <a href="{{ route('history') }}" class="text-secondary font-title-sm text-body-sm hover:underline">Lihat Semua</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-surface-container-low text-on-surface-variant font-label-caps text-label-caps border-b border-outline-variant">
                    <tr>
                        <th class="px-lg py-md">SSID</th>
                        <th class="px-lg py-md">Download</th>
                        <th class="px-lg py-md">Upload</th>
                        <th class="px-lg py-md">Ping</th>
                        <th class="px-lg py-md">Status</th>
                        <th class="px-lg py-md text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">
                    @forelse($recentScans as $scan)
                        @php
                            $isWlan = strtoupper($scan->interface ?? 'WLAN') === 'WLAN';
                            $sc = $scan->score ?? 0;
                            if($sc >= 90) {
                                $statusLabel = 'OPTIMAL';
                                $badgeColor = 'bg-green-100 text-green-700';
                            } elseif($sc >= 75) {
                                $statusLabel = 'STABLE';
                                $badgeColor = 'bg-green-100 text-green-700';
                            } elseif($sc >= 60) {
                                $statusLabel = 'LATENCY';
                                $badgeColor = 'bg-yellow-100 text-yellow-700';
                            } else {
                                $statusLabel = 'CRITICAL';
                                $badgeColor = 'bg-red-100 text-red-700';
                            }
                        @endphp
                        <tr class="hover:bg-surface-container-low transition-colors group">
                            <td class="px-lg py-md font-title-sm text-body-md text-primary">
                                {{ $isWlan ? ($scan->ssid ?? 'wlan0') : 'LAN Connection' }}
                            </td>
                            <td class="px-lg py-md text-body-md">{{ number_format($scan->download ?? 0, 1) }} Mbps</td>
                            <td class="px-lg py-md text-body-md">{{ number_format($scan->upload ?? 0, 1) }} Mbps</td>
                            <td class="px-lg py-md text-body-md">{{ number_format($scan->ping ?? 0, 1) }} ms</td>
                            <td class="px-lg py-md">
                                <span class="{{ $badgeColor }} px-md py-base rounded-full text-[10px] font-bold">{{ $statusLabel }}</span>
                                <span class="hidden">{{ $scan->kategori }}</span>
                            </td>
                            <td class="px-lg py-md text-right">
                                <a href="{{ route('history', ['ssid' => $scan->ssid]) }}" class="p-xs rounded hover:bg-surface-container-high text-on-surface-variant inline-block" title="Lihat Detail">
                                    <span class="material-symbols-outlined">visibility</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-lg py-md text-center text-on-surface-variant">Belum ada riwayat pemindaian</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Floating Action Button -->
<a href="{{ route('scan.manual') }}" class="fixed bottom-lg right-lg w-14 h-14 bg-secondary text-on-secondary rounded-full shadow-lg flex items-center justify-center hover:scale-110 active:scale-95 transition-transform z-50 hover:shadow-secondary/30 select-none">
    <span class="material-symbols-outlined text-[28px]" style="font-variation-settings: 'FILL' 1;">add</span>
</a>
@endsection

@push('scripts')
<script>
    @if(!$chartMetrics->isEmpty())
    (function () {
        const labels    = @json($chartMetrics->map(fn($s) => \Carbon\Carbon::parse($s->created_at)->format('d/m H:i')));
        const downloads = @json($chartMetrics->pluck('download'));
        const uploads   = @json($chartMetrics->pluck('upload'));
        const pings     = @json($chartMetrics->pluck('ping'));
        new Chart(document.getElementById('chartMetrics'), {
            type: 'line',
            data: {
                labels,
                datasets: [
                    { label: 'DL',   data: downloads, borderColor: '#0051d5', backgroundColor: 'transparent', borderWidth: 3, pointRadius: 0, fill: false, tension: 0.35, yAxisID: 'ySpeed' },
                    { label: 'UL',   data: uploads,   borderColor: '#4cd7f6', backgroundColor: 'transparent', borderWidth: 2, pointRadius: 0, fill: false, tension: 0.35, yAxisID: 'ySpeed' },
                    { label: 'Ping', data: pings,     borderColor: '#ba1a1a', backgroundColor: 'transparent', borderWidth: 1.5, borderDash: [4,3], pointRadius: 0, fill: false, tension: 0.35, yAxisID: 'yPing' }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: { mode: 'index', intersect: false }
                },
                scales: {
                    x: { display: false },
                    ySpeed: { display: false },
                    yPing: { display: false }
                }
            }
        });
    })();
    @endif
</script>
@endpush
