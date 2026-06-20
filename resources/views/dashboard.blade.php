@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Dashboard Monitoring</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
    <a href="{{ route('scan.manual') }}" class="btn btn-sm btn-primary">
        <i class="bi bi-arrow-clockwise"></i> Scan Sekarang
    </a>
</div>
</div>

<!-- Kartu Statistik -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card text-bg-primary h-100">
            <div class="card-body">
                <h6 class="card-title"><i class="bi bi-bar-chart-fill"></i> Total Scan</h6>
                <p class="card-text display-6 fw-bold">{{ $totalScans }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-bg-success h-100">
            <div class="card-body">
                <h6 class="card-title"><i class="bi bi-wifi"></i> SSID Aktif</h6>
                <p class="card-text fw-bold">{{ $activeSSID }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-bg-warning h-100">
            <div class="card-body">
                <h6 class="card-title"><i class="bi bi-trophy"></i> Skor Terakhir</h6>
                <p class="card-text display-6 fw-bold">{{ $lastScore }}</p>
                <small class="text-dark">{{ $lastCategory }}</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-bg-info h-100 text-white">
            <div class="card-body">
                <h6 class="card-title"><i class="bi bi-clock"></i> Status</h6>
                <p class="card-text fw-bold">🟢 Aktif</p>
                <small>Monitoring berjalan</small>
            </div>
        </div>
    </div>
</div>

<!-- Area Grafik (Placeholder untuk Tahap 11) -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-graph-up"></i> Tren Skor Harian (Placeholder)
            </div>
            <div class="card-body" style="height: 250px; background: #f8f9fa; display: flex; align-items: center; justify-content: center; color: #6c757d;">
                <i class="bi bi-bar-chart-line fs-1 me-2"></i> Grafik akan muncul di sini (Tahap 11)
            </div>
        </div>
    </div>
</div>

<!-- Tabel Ringkasan Terakhir -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-table"></i> Scan Terakhir
            </div>
            <div class="card-body">
                @if($totalScans > 0)
                <table class="table table-sm table-hover">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Jam</th>
                            <th>SSID</th>
                            <th>Score</th>
                            <th>Kategori</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(\App\Models\Scan::orderBy('created_at', 'desc')->limit(5)->get() as $scan)
                        <tr>
                            <td>{{ $scan->tanggal }}</td>
                            <td>{{ $scan->jam }}</td>
                            <td>{{ $scan->ssid }}</td>
                            <td><span class="badge bg-primary">{{ $scan->score }}</span></td>
                            <td>{{ $scan->kategori }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <p class="text-muted">Belum ada data scan. Lakukan scan pertama!</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection