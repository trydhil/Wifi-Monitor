@extends('layouts.app')

@section('title', 'Riwayat Scan')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Riwayat Scan</h1>
</div>

<!-- Form Filter -->
<form method="GET" action="{{ route('history') }}" class="row g-3 mb-4">
    <div class="col-md-3">
        <label class="form-label">Tanggal Awal</label>
        <input type="date" name="tanggal_awal" class="form-control" value="{{ request('tanggal_awal') }}">
    </div>
    <div class="col-md-3">
        <label class="form-label">Tanggal Akhir</label>
        <input type="date" name="tanggal_akhir" class="form-control" value="{{ request('tanggal_akhir') }}">
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
            <option value="Baik" {{ request('kategori') == 'Baik' ? 'selected' : '' }}>Baik</option>
            <option value="Cukup" {{ request('kategori') == 'Cukup' ? 'selected' : '' }}>Cukup</option>
            <option value="Buruk" {{ request('kategori') == 'Buruk' ? 'selected' : '' }}>Buruk</option>
        </select>
    </div>
    <div class="col-md-2 d-flex align-items-end">
        <button type="submit" class="btn btn-primary w-100">Filter</button>
    </div>
</form>

<!-- Tabel Data -->
<div class="table-responsive">
    <table class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Jam</th>
                <th>SSID</th>
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
            <tr>
                <td>{{ $scans->firstItem() + $index }}</td>
                <td>{{ $scan->tanggal }}</td>
                <td>{{ $scan->jam }}</td>
                <td>{{ $scan->ssid }}</td>
                <td>{{ $scan->download ?? '-' }}</td>
                <td>{{ $scan->upload ?? '-' }}</td>
                <td>{{ $scan->ping ?? '-' }}</td>
                <td>{{ $scan->signal ?? '-' }}</td>
                <td><span class="badge bg-primary">{{ $scan->score }}</span></td>
                <td>
                    @php
                        $color = match($scan->kategori) {
                            'Sangat Baik' => 'success',
                            'Baik' => 'primary',
                            'Cukup' => 'warning',
                            default => 'danger'
                        };
                    @endphp
                    <span class="badge bg-{{ $color }}">{{ $scan->kategori }}</span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="10" class="text-center">Belum ada data scan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
<div class="d-flex justify-content-center">
    {{ $scans->links() }}
</div>

@endsection