@extends('layouts.app')

@section('title', 'Hasil Scan Manual')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Hasil Scan Manual</h1>
    <a href="{{ route('dashboard') }}" class="btn btn-secondary">Kembali ke Dashboard</a>
</div>

@if(isset($scan))
<div class="card">
    <div class="card-header bg-primary text-white">
        <i class="bi bi-wifi"></i> Hasil Scan: {{ $scan['ssid'] ?? 'Tidak diketahui' }}
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-bordered">
                    <tr>
                        <th>SSID</th>
                        <td>{{ $scan['ssid'] ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal</th>
                        <td>{{ $scan['tanggal'] ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Jam</th>
                        <td>{{ $scan['jam'] ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Download</th>
                        <td>{{ $scan['download'] ?? 0 }} Mbps</td>
                    </tr>
                    <tr>
                        <th>Upload</th>
                        <td>{{ $scan['upload'] ?? 0 }} Mbps</td>
                    </tr>
                    <tr>
                        <th>Ping</th>
                        <td>{{ $scan['ping'] ?? 0 }} ms</td>
                    </tr>
                    <tr>
                        <th>Signal</th>
                        <td>{{ $scan['signal'] ?? 0 }} dBm</td>
                    </tr>
                    <tr>
                        <th>Skor</th>
                        <td><span class="badge bg-primary fs-5">{{ $scan['score'] ?? 0 }}</span></td>
                    </tr>
                    <tr>
                        <th>Kategori</th>
                        <td>
                            @php
                                $category = $scan['kategori'] ?? 'Buruk';
                                $badgeColor = match($category) {
                                    'Sangat Baik' => 'success',
                                    'Baik' => 'primary',
                                    'Cukup' => 'warning',
                                    default => 'danger'
                                };
                            @endphp
                            <span class="badge bg-{{ $badgeColor }} fs-5">{{ $category }}</span>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> <strong>Preview</strong><br>
                    Data ini hanya tampilan sementara dan <strong>tidak disimpan</strong> ke database.
                    Jika ingin menyimpan, klik tombol "Simpan ke Database" (belum tersedia di versi ini).
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer text-center">
        <a href="{{ route('dashboard') }}" class="btn btn-secondary">Kembali</a>
    </div>
</div>
@else
<div class="alert alert-danger">Tidak ada data scan.</div>
@endif
@endsection