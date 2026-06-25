@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">

    {{-- ===== HEADER ===== --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0 fw-bold">
            <i class="bi bi-wifi me-2 text-primary"></i>Dashboard Monitoring
        </h4>
        @if($latest)
            @php
                $isWlan = strtoupper($latest->interface ?? 'WLAN') === 'WLAN';
            @endphp
            <span class="badge bg-success fs-6">
                <i class="bi bi-circle-fill me-1" style="font-size:.6rem"></i>
                @if($isWlan)
                    <i class="bi bi-wifi me-1"></i>{{ $latest->ssid ?? 'WLAN' }}
                @else
                    <i class="bi bi-ethernet me-1"></i>LAN
                @endif
            </span>
        @else
            <span class="badge bg-secondary fs-6">Belum ada data</span>
        @endif
    </div>

    {{-- ===== INTERFACE BANNER ===== --}}
    @if($latest)
    @php $isWlan = strtoupper($latest->interface ?? 'WLAN') === 'WLAN'; @endphp
    <div class="alert alert-primary border-0 d-flex align-items-center gap-3 mb-4 py-2" style="background:rgba(13,110,253,.08)">
        <i class="bi {{ $isWlan ? 'bi-wifi' : 'bi-ethernet' }} fs-5 text-primary"></i>
        <div class="flex-grow-1 small">
            <strong>Koneksi Aktif:</strong>
            @if($isWlan)
                WLAN &nbsp;·&nbsp; SSID: <strong>{{ $latest->ssid }}</strong>
                &nbsp;·&nbsp; Signal: {{ $latest->signal }} dBm
            @else
                LAN (Ethernet) &nbsp;·&nbsp; Kabel terhubung
                &nbsp;·&nbsp; Signal: <span class="text-muted">N/A</span>
            @endif
        </div>
        <span class="badge {{ $isWlan ? 'bg-primary' : 'bg-indigo' }} bg-opacity-75">
            {{ strtoupper($latest->interface ?? 'WLAN') }}
        </span>
    </div>
    @endif

    {{-- ===== STAT CARDS ===== --}}
    <div class="row g-3 mb-4">

        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-muted small mb-1">Total Scan</p>
                    <h3 class="fw-bold mb-0">{{ number_format($stats->total_scan) }}</h3>
                    <small class="text-muted">semua waktu</small>
                </div>
                <div class="card-footer bg-primary bg-opacity-10 border-0 py-1">
                    <i class="bi bi-database text-primary"></i>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-muted small mb-1">Skor Terakhir</p>
                    <h3 class="fw-bold mb-0 text-{{ $latest?->score >= 75 ? 'success' : ($latest?->score >= 60 ? 'warning' : 'danger') }}">
                        {{ $latest?->score ?? '—' }}
                    </h3>
                    <small class="text-muted">{{ $latest?->kategori ?? 'Belum ada scan' }}</small>
                </div>
                <div class="card-footer bg-success bg-opacity-10 border-0 py-1">
                    <i class="bi bi-speedometer2 text-success"></i>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-muted small mb-1">Download Maks</p>
                    <h3 class="fw-bold mb-0">
                        {{ $stats->max_download ? number_format($stats->max_download, 1) : '—' }}
                        <small class="fs-6 fw-normal text-muted">Mbps</small>
                    </h3>
                    <small class="text-muted">tertinggi tercatat</small>
                </div>
                <div class="card-footer bg-info bg-opacity-10 border-0 py-1">
                    <i class="bi bi-arrow-down-circle text-info"></i>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-muted small mb-1">Ping Terbaik</p>
                    <h3 class="fw-bold mb-0">
                        {{ $stats->min_ping ? number_format($stats->min_ping) : '—' }}
                        <small class="fs-6 fw-normal text-muted">ms</small>
                    </h3>
                    <small class="text-muted">terendah tercatat</small>
                </div>
                <div class="card-footer bg-warning bg-opacity-10 border-0 py-1">
                    <i class="bi bi-lightning text-warning"></i>
                </div>
            </div>
        </div>

    </div>

    {{-- ===== GRAFIK ROW ===== --}}
    <div class="row g-3 mb-4">
        <div class="col-12 col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 pt-3 pb-0">
                    <h6 class="fw-semibold mb-0">
                        <i class="bi bi-graph-up text-primary me-2"></i>Tren Skor Harian
                        <small class="text-muted fw-normal ms-1">(7 hari terakhir)</small>
                    </h6>
                </div>
                <div class="card-body">
                    @if($chartDaily->isEmpty())
                        <div class="d-flex align-items-center justify-content-center" style="height:220px">
                            <div class="text-center text-muted">
                                <i class="bi bi-bar-chart fs-1 d-block mb-2 opacity-25"></i>
                                Belum ada data scan
                            </div>
                        </div>
                    @else
                        <canvas id="chartSkorHarian" style="max-height:220px"></canvas>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 pt-3 pb-0">
                    <h6 class="fw-semibold mb-0">
                        <i class="bi bi-activity text-success me-2"></i>Download / Upload / Ping
                        <small class="text-muted fw-normal ms-1">(20 scan terakhir)</small>
                    </h6>
                </div>
                <div class="card-body">
                    @if($chartMetrics->isEmpty())
                        <div class="d-flex align-items-center justify-content-center" style="height:220px">
                            <div class="text-center text-muted">
                                <i class="bi bi-wifi-off fs-1 d-block mb-2 opacity-25"></i>
                                Belum ada data scan
                            </div>
                        </div>
                    @else
                        <canvas id="chartMetrics" style="max-height:220px"></canvas>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ===== DETAIL SCAN TERAKHIR ===== --}}
    @if($latest)
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 pt-3 pb-0">
            <h6 class="fw-semibold mb-0">
                <i class="bi bi-clock-history text-secondary me-2"></i>Detail Scan Terakhir
            </h6>
        </div>
        <div class="card-body">
            <div class="row g-3 text-center">
                {{-- Interface badge --}}
                <div class="col-6 col-md-2">
                    <div class="small text-muted mb-1">Interface</div>
                    @php $iface = strtoupper($latest->interface ?? 'WLAN'); @endphp
                    <span class="badge {{ $iface === 'LAN' ? 'bg-secondary' : 'bg-primary' }}">
                        <i class="bi {{ $iface === 'LAN' ? 'bi-ethernet' : 'bi-wifi' }} me-1"></i>{{ $iface }}
                    </span>
                </div>
                {{-- SSID — tampilkan "Ethernet" untuk LAN --}}
                <div class="col-6 col-md-2">
                    <div class="small text-muted mb-1">SSID / Adapter</div>
                    <div class="fw-semibold">
                        {{ $latest->ssid ?? ($latest->interface === 'LAN' ? 'Ethernet' : '—') }}
                    </div>
                </div>
                <div class="col-6 col-md-2">
                    <div class="small text-muted mb-1">Download</div>
                    <div class="fw-semibold text-info">{{ $latest->download }} <small>Mbps</small></div>
                </div>
                <div class="col-6 col-md-2">
                    <div class="small text-muted mb-1">Upload</div>
                    <div class="fw-semibold text-primary">{{ $latest->upload }} <small>Mbps</small></div>
                </div>
                <div class="col-6 col-md-2">
                    <div class="small text-muted mb-1">Ping</div>
                    <div class="fw-semibold text-warning">{{ $latest->ping }} <small>ms</small></div>
                </div>
                {{-- Signal — N/A untuk LAN --}}
                <div class="col-6 col-md-2">
                    <div class="small text-muted mb-1">Signal</div>
                    @if($latest->interface === 'LAN' || is_null($latest->signal))
                        <div class="fw-semibold text-muted">N/A</div>
                    @else
                        <div class="fw-semibold">{{ $latest->signal }} <small>dBm</small></div>
                    @endif
                </div>
            </div>
            <div class="text-center mt-2">
                <small class="text-muted">
                    <i class="bi bi-clock me-1"></i>{{ $latest->created_at->format('d/m/Y H:i') }}
                </small>
            </div>
        </div>
    </div>
    @endif

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    const chartDefaults = {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: { position: 'bottom', labels: { boxWidth: 12, padding: 16 } },
            tooltip: { mode: 'index', intersect: false }
        },
        scales: {
            x: { grid: { display: false }, ticks: { maxTicksLimit: 7 } }
        }
    };

    @if(!$chartDaily->isEmpty())
    (function () {
        const labels = @json($chartDaily->pluck('tanggal'));
        const scores = @json($chartDaily->pluck('avg_score'));
        new Chart(document.getElementById('chartSkorHarian'), {
            type: 'line',
            data: {
                labels,
                datasets: [{
                    label: 'Skor Rata-rata',
                    data: scores,
                    borderColor: '#0d6efd',
                    backgroundColor: 'rgba(13,110,253,.1)',
                    borderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    fill: true,
                    tension: 0.35
                }]
            },
            options: {
                ...chartDefaults,
                scales: {
                    ...chartDefaults.scales,
                    y: { min: 0, max: 100, ticks: { callback: v => v + ' pts', stepSize: 25 } }
                }
            }
        });
    })();
    @endif

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
                    { label: 'Download (Mbps)', data: downloads, borderColor: '#0dcaf0', backgroundColor: 'rgba(13,202,240,.08)', borderWidth: 2, pointRadius: 3, fill: true, tension: 0.3, yAxisID: 'ySpeed' },
                    { label: 'Upload (Mbps)',   data: uploads,   borderColor: '#0d6efd', backgroundColor: 'rgba(13,110,253,.08)', borderWidth: 2, pointRadius: 3, fill: true, tension: 0.3, yAxisID: 'ySpeed' },
                    { label: 'Ping (ms)',       data: pings,     borderColor: '#ffc107', backgroundColor: 'transparent', borderWidth: 2, borderDash: [5,3], pointRadius: 3, fill: false, tension: 0.3, yAxisID: 'yPing' }
                ]
            },
            options: {
                ...chartDefaults,
                scales: {
                    x: { ...chartDefaults.scales.x, ticks: { maxTicksLimit: 8 } },
                    ySpeed: { type: 'linear', position: 'left',  title: { display: true, text: 'Mbps', font: { size: 11 } }, min: 0, ticks: { callback: v => v + ' Mb' } },
                    yPing:  { type: 'linear', position: 'right', title: { display: true, text: 'ms',   font: { size: 11 } }, min: 0, grid: { drawOnChartArea: false }, ticks: { callback: v => v + 'ms' } }
                }
            }
        });
    })();
    @endif
</script>
@endpush
