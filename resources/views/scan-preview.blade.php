@extends('layouts.app')

@section('title', 'Hasil Scan Manual')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <div>
        <h1 class="h2 mb-0">Hasil Scan Manual</h1>
        <small class="text-muted">
            Standar penilaian aktif:
            <span class="badge bg-primary ms-1">
                {{ collect($standards)->firstWhere('key', $activeKey)['label'] ?? $activeKey }}
            </span>
        </small>
    </div>
    <a href="{{ route('dashboard') }}" class="btn btn-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i>Dashboard
    </a>
</div>

{{-- ===== PILIH STANDAR ===== --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body py-2">
        <form method="GET" action="{{ route('scan.manual') }}" class="d-flex align-items-center gap-3 flex-wrap">
            <label class="form-label mb-0 fw-semibold text-nowrap">
                <i class="bi bi-sliders me-1 text-primary"></i>Standar Penilaian:
            </label>
            <select name="standar" class="form-select form-select-sm w-auto" onchange="this.form.submit()">
                @foreach($standards as $std)
                    <option value="{{ $std['key'] }}" {{ $activeKey === $std['key'] ? 'selected' : '' }}>
                        {{ $std['label'] }}
                    </option>
                @endforeach
            </select>
            <small class="text-muted fst-italic">
                {{ collect($standards)->firstWhere('key', $activeKey)['deskripsi'] ?? '' }}
            </small>
            {{-- Simpan state supaya scan ulang pakai standar yang sama --}}
            <button type="submit" class="btn btn-primary btn-sm ms-auto">
                <i class="bi bi-arrow-repeat me-1"></i>Scan Ulang
            </button>
        </form>
    </div>
</div>

@if(isset($scan))
<div class="row g-4">

    {{-- ===== HASIL SCAN ===== --}}
    <div class="col-md-7">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-primary text-white">
                <i class="bi {{ ($scan['interface'] ?? 'WLAN') === 'LAN' ? 'bi-ethernet' : 'bi-wifi' }} me-2"></i>
                Hasil Scan:
                {{ $scan['interface'] === 'WLAN' ? ($scan['ssid'] ?? 'Tidak diketahui') : 'LAN (Ethernet)' }}
                <span class="badge bg-white text-primary ms-2 float-end">
                    {{ strtoupper($scan['interface'] ?? 'WLAN') }}
                </span>
            </div>
            <div class="card-body p-0">
                <table class="table table-bordered mb-0">
                    <tr>
                        <th class="w-40 ps-3">Interface</th>
                        <td>
                            <span class="badge {{ ($scan['interface'] ?? 'WLAN') === 'LAN' ? 'bg-secondary' : 'bg-primary' }}">
                                {{ strtoupper($scan['interface'] ?? 'WLAN') }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th class="ps-3">SSID / Adapter</th>
                        <td>{{ $scan['ssid'] ?? ($scan['interface'] === 'LAN' ? 'Ethernet' : '-') }}</td>
                    </tr>
                    <tr>
                        <th class="ps-3">Tanggal</th>
                        <td>{{ $scan['tanggal'] ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th class="ps-3">Jam</th>
                        <td>{{ $scan['jam'] ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th class="ps-3">Download</th>
                        <td class="text-info fw-semibold">{{ $scan['download'] ?? 0 }} <small class="text-muted">Mbps</small></td>
                    </tr>
                    <tr>
                        <th class="ps-3">Upload</th>
                        <td class="text-primary fw-semibold">{{ $scan['upload'] ?? 0 }} <small class="text-muted">Mbps</small></td>
                    </tr>
                    <tr>
                        <th class="ps-3">Ping</th>
                        <td class="text-warning fw-semibold">{{ $scan['ping'] ?? 0 }} <small class="text-muted">ms</small></td>
                    </tr>
                    <tr>
                        <th class="ps-3">Signal</th>
                        <td>
                            @if(($scan['interface'] ?? 'WLAN') === 'LAN')
                                <span class="text-muted">N/A (LAN)</span>
                            @else
                                {{ $scan['signal'] ?? 0 }} <small class="text-muted">dBm</small>
                            @endif
                        </td>
                    </tr>
                    <tr class="table-light">
                        <th class="ps-3">Skor</th>
                        <td>
                            @php
                                $score = $scan['score'] ?? 0;
                                $scoreColor = $score >= 75 ? 'success' : ($score >= 60 ? 'warning' : 'danger');
                            @endphp
                            <span class="badge bg-{{ $scoreColor }} fs-5">{{ $score }}</span>
                        </td>
                    </tr>
                    <tr class="table-light">
                        <th class="ps-3">Kategori</th>
                        <td>
                            @php
                                $kategori   = $scan['kategori'] ?? 'Buruk';
                                $katColor   = match($kategori) {
                                    'Sangat Baik' => 'success',
                                    'Baik'        => 'primary',
                                    'Cukup'       => 'warning',
                                    default       => 'danger'
                                };
                            @endphp
                            <span class="badge bg-{{ $katColor }} fs-5">{{ $kategori }}</span>
                        </td>
                    </tr>
                    <tr class="table-light">
                        <th class="ps-3">Standar</th>
                        <td>
                            <span class="badge bg-primary">{{ $scan['standar'] ?? '-' }}</span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    {{-- ===== INFO PANEL ===== --}}
    <div class="col-md-5">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body">
                <h6 class="fw-semibold mb-2">
                    <i class="bi bi-info-circle text-info me-1"></i>Info Standar Aktif
                </h6>
                @php $activeStd = collect($standards)->firstWhere('key', $activeKey); @endphp
                <p class="small text-muted mb-2">{{ $activeStd['deskripsi'] ?? '' }}</p>
                @php $cfg = config("scoring.{$activeKey}.weights"); @endphp
                <ul class="list-unstyled small mb-0">
                    <li>📥 Download &nbsp;: <strong>{{ ($cfg['download'] ?? 0) * 100 }}%</strong></li>
                    <li>📤 Upload &nbsp;&nbsp;&nbsp;: <strong>{{ ($cfg['upload'] ?? 0) * 100 }}%</strong></li>
                    <li>⚡ Ping &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <strong>{{ ($cfg['ping'] ?? 0) * 100 }}%</strong></li>
                    <li>📶 Signal &nbsp;&nbsp;&nbsp;: <strong>{{ ($cfg['signal'] ?? 0) * 100 }}%</strong></li>
                </ul>
            </div>
        </div>

        <div class="alert alert-warning border-0 small">
            <i class="bi bi-exclamation-triangle me-1"></i>
            <strong>Preview saja</strong> — data ini <strong>tidak disimpan</strong> ke database.
            Scan otomatis berjalan setiap 1 jam dan menyimpan data secara otomatis.
        </div>
    </div>

</div>
@else
<div class="alert alert-danger">Tidak ada data scan.</div>
@endif
@endsection