@extends('layouts.app')

@section('title', 'Riwayat Scan')

@section('content')

{{-- ===== HEADER + DROPDOWN STANDAR ===== --}}
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <div>
        <h1 class="h2 mb-0">Riwayat Scan</h1>
        <small class="text-muted">
            Standar penilaian:
            <span class="badge bg-primary ms-1" id="standar-badge">
                {{ collect($standards)->firstWhere('key', $activeKey)['label'] ?? $activeKey }}
            </span>
        </small>
    </div>
    <div class="d-flex gap-2 align-items-center">
        {{-- Dropdown standar — submit otomatis saat ganti --}}
        <form method="GET" action="{{ route('history') }}" id="form-standar">
            <select name="standar" class="form-select form-select-sm" style="min-width:200px"
                    onchange="document.getElementById('form-standar').submit()">
                @foreach($standards as $std)
                    <option value="{{ $std['key'] }}" {{ $activeKey === $std['key'] ? 'selected' : '' }}>
                        {{ $std['label'] }}
                    </option>
                @endforeach
            </select>
            {{-- Preserve filter lain --}}
            @foreach(request()->except('standar') as $k => $v)
                <input type="hidden" name="{{ $k }}" value="{{ $v }}">
            @endforeach
        </form>
        <a href="{{ route('export.excel', array_merge(request()->query(), ['standar' => $activeKey])) }}"
           class="btn btn-success btn-sm text-nowrap">
            <i class="bi bi-file-earmark-excel me-1"></i>Export Excel
        </a>
    </div>
</div>

{{-- ===== FILTER ===== --}}
<form method="GET" action="{{ route('history') }}" class="row g-3 mb-4">
    <input type="hidden" name="standar" value="{{ $activeKey }}">
    <div class="col-md-2">
        <label class="form-label">Tanggal Awal</label>
        <input type="date" name="tanggal_awal" class="form-control" value="{{ request('tanggal_awal') }}">
    </div>
    <div class="col-md-2">
        <label class="form-label">Tanggal Akhir</label>
        <input type="date" name="tanggal_akhir" class="form-control" value="{{ request('tanggal_akhir') }}">
    </div>
    <div class="col-md-2">
        <label class="form-label">Interface</label>
        <select name="interface" class="form-select">
            <option value="">Semua</option>
            <option value="WLAN" {{ request('interface') == 'WLAN' ? 'selected' : '' }}>📶 WLAN</option>
            <option value="LAN"  {{ request('interface') == 'LAN'  ? 'selected' : '' }}>🔌 LAN</option>
        </select>
    </div>
    <div class="col-md-2">
        <label class="form-label">SSID</label>
        <select name="ssid" class="form-select">
            <option value="">Semua</option>
            @foreach($ssidList as $ssid)
                <option value="{{ $ssid }}" {{ request('ssid') == $ssid ? 'selected' : '' }}>{{ $ssid }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2">
        <label class="form-label">Kategori</label>
        <select name="kategori" class="form-select">
            <option value="">Semua</option>
            <option value="Sangat Baik" {{ request('kategori') == 'Sangat Baik' ? 'selected' : '' }}>Sangat Baik</option>
            <option value="Baik"        {{ request('kategori') == 'Baik'        ? 'selected' : '' }}>Baik</option>
            <option value="Cukup"       {{ request('kategori') == 'Cukup'       ? 'selected' : '' }}>Cukup</option>
            <option value="Buruk"       {{ request('kategori') == 'Buruk'       ? 'selected' : '' }}>Buruk</option>
        </select>
    </div>
    <div class="col-md-2 d-flex align-items-end gap-2">
        <button type="submit" class="btn btn-primary flex-grow-1">Filter</button>
        <a href="{{ route('history') }}" class="btn btn-outline-secondary">Reset</a>
    </div>
</form>

{{-- ===== TABEL ===== --}}
<div class="table-responsive">
    <table class="table table-bordered table-hover align-middle">
        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Jam</th>
                <th>Interface</th>
                <th>SSID / Adapter</th>
                <th>Download (Mbps)</th>
                <th>Upload (Mbps)</th>
                <th>Ping (ms)</th>
                <th>Signal (dBm)</th>
                <th>Score</th>
                <th>Kategori</th>
            </tr>
        </thead>
        <tbody>
            @forelse($scans as $index => $scan)
            @php
                $iface    = strtoupper($scan->interface ?? 'WLAN');
                $isWlan   = $iface === 'WLAN';
                $scoreColor = match(true) {
                    $scan->score >= 90 => 'success',
                    $scan->score >= 75 => 'primary',
                    $scan->score >= 60 => 'warning',
                    default            => 'danger'
                };
                $katColor = match($scan->kategori) {
                    'Sangat Baik' => 'success',
                    'Baik'        => 'primary',
                    'Cukup'       => 'warning',
                    default       => 'danger'
                };
            @endphp
            <tr>
                <td>{{ $scans->firstItem() + $index }}</td>
                <td>{{ $scan->tanggal }}</td>
                <td>{{ $scan->jam }}</td>
                <td>
                    <span class="badge {{ $isWlan ? 'bg-primary' : 'bg-secondary' }}">
                        <i class="bi {{ $isWlan ? 'bi-wifi' : 'bi-ethernet' }} me-1"></i>{{ $iface }}
                    </span>
                </td>
                <td>
                    @if($isWlan)
                        {{ $scan->ssid ?? '—' }}
                    @else
                        <span class="text-muted fst-italic">Ethernet</span>
                    @endif
                </td>
                <td>{{ $scan->download ?? '-' }}</td>
                <td>{{ $scan->upload ?? '-' }}</td>
                <td>{{ $scan->ping ?? '-' }}</td>
                <td>
                    @if($isWlan && $scan->signal)
                        {{ $scan->signal }}
                    @else
                        <span class="text-muted">—</span>
                    @endif
                </td>
                <td><span class="badge bg-{{ $scoreColor }}">{{ $scan->score }}</span></td>
                <td><span class="badge bg-{{ $katColor }}">{{ $scan->kategori }}</span></td>
            </tr>
            @empty
            <tr>
                <td colspan="11" class="text-center text-muted py-4">
                    <i class="bi bi-inbox fs-3 d-block mb-1"></i>Belum ada data scan.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="d-flex justify-content-center">
    {{ $scans->appends(request()->query())->links() }}
</div>

@endsection