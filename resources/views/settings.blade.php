@extends('layouts.app')

@section('title', 'Pengaturan')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2 mb-0"><i class="bi bi-sliders me-2"></i>Pengaturan Standar Custom</h1>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="row g-4">
    <div class="col-md-7">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-dark text-white">
                <i class="bi bi-pencil-square me-2"></i>Atur Bobot &amp; Threshold
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('settings.update') }}">
                    @csrf

                    <h6 class="fw-semibold mb-3">Bobot Penilaian <small class="text-muted">(total harus 100%)</small></h6>

                    @if($errors->any())
                        <div class="alert alert-danger small">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <div class="row g-3 mb-4">
                        <div class="col-6">
                            <label class="form-label">📥 Download (%)</label>
                            <input type="number" step="1" min="0" max="100" name="weight_download_pct"
                                   id="weight_download_pct"
                                   class="form-control"
                                   value="{{ old('weight_download', $setting->weight_download) * 100 }}">
                            <input type="hidden" name="weight_download" id="weight_download" value="{{ old('weight_download', $setting->weight_download) }}">
                        </div>
                        <div class="col-6">
                            <label class="form-label">📤 Upload (%)</label>
                            <input type="number" step="1" min="0" max="100" name="weight_upload_pct"
                                   id="weight_upload_pct"
                                   class="form-control"
                                   value="{{ old('weight_upload', $setting->weight_upload) * 100 }}">
                            <input type="hidden" name="weight_upload" id="weight_upload" value="{{ old('weight_upload', $setting->weight_upload) }}">
                        </div>
                        <div class="col-6">
                            <label class="form-label">⚡ Ping (%)</label>
                            <input type="number" step="1" min="0" max="100" name="weight_ping_pct"
                                   id="weight_ping_pct"
                                   class="form-control"
                                   value="{{ old('weight_ping', $setting->weight_ping) * 100 }}">
                            <input type="hidden" name="weight_ping" id="weight_ping" value="{{ old('weight_ping', $setting->weight_ping) }}">
                        </div>
                        <div class="col-6">
                            <label class="form-label">📶 Signal (%)</label>
                            <input type="number" step="1" min="0" max="100" name="weight_signal_pct"
                                   id="weight_signal_pct"
                                   class="form-control"
                                   value="{{ old('weight_signal', $setting->weight_signal) * 100 }}">
                            <input type="hidden" name="weight_signal" id="weight_signal" value="{{ old('weight_signal', $setting->weight_signal) }}">
                        </div>
                    </div>

                    <div class="mb-4">
                        <span class="small text-muted">Total saat ini: </span>
                        <span id="totalWeightDisplay" class="fw-bold">100%</span>
                    </div>

                    <hr>

                    <h6 class="fw-semibold mb-3">Threshold <small class="text-muted">(nilai dianggap "sempurna" = skor 100 di metrik itu)</small></h6>
                    <div class="row g-3 mb-3">
                        <div class="col-4">
                            <label class="form-label">Download (Mbps)</label>
                            <input type="number" step="0.1" min="0.1" name="threshold_download" class="form-control"
                                   value="{{ old('threshold_download', $setting->threshold_download) }}">
                        </div>
                        <div class="col-4">
                            <label class="form-label">Upload (Mbps)</label>
                            <input type="number" step="0.1" min="0.1" name="threshold_upload" class="form-control"
                                   value="{{ old('threshold_upload', $setting->threshold_upload) }}">
                        </div>
                        <div class="col-4">
                            <label class="form-label">Ping (ms)</label>
                            <input type="number" step="0.1" min="0.1" name="threshold_ping" class="form-control"
                                   value="{{ old('threshold_ping', $setting->threshold_ping) }}">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-1"></i>Simpan Pengaturan
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-5">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6 class="fw-semibold mb-2"><i class="bi bi-info-circle text-info me-1"></i>Cara Pakai</h6>
                <ul class="small text-muted ps-3">
                    <li class="mb-2">Bobot keempat metrik (Download, Upload, Ping, Signal) harus <strong>total 100%</strong>.</li>
                    <li class="mb-2">Threshold adalah nilai yang dianggap "sempurna" (skor 100) untuk metrik itu. Misal threshold Download = 50 Mbps, artinya kecepatan 50 Mbps atau lebih dianggap skor download maksimal.</li>
                    <li class="mb-2">Untuk koneksi <strong>LAN</strong> (tanpa sinyal WiFi), bobot Signal otomatis dipindahkan ke Ping saat menghitung skor.</li>
                    <li>Setelah disimpan, pilih standar <strong>"Custom"</strong> di halaman Scan Manual untuk memakai bobot ini.</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Sinkronkan input persen (tampilan) <-> input desimal (dikirim ke server) + live total
document.addEventListener('DOMContentLoaded', function () {
    const fields = ['download', 'upload', 'ping', 'signal'];
    const totalDisplay = document.getElementById('totalWeightDisplay');

    function recalcTotal() {
        let total = 0;
        fields.forEach(f => {
            const pct = parseFloat(document.getElementById('weight_' + f + '_pct').value) || 0;
            document.getElementById('weight_' + f).value = (pct / 100).toFixed(4);
            total += pct;
        });
        totalDisplay.textContent = total + '%';
        totalDisplay.className = (Math.abs(total - 100) < 1) ? 'fw-bold text-success' : 'fw-bold text-danger';
    }

    fields.forEach(f => {
        document.getElementById('weight_' + f + '_pct').addEventListener('input', recalcTotal);
    });

    recalcTotal();
});
</script>
@endpush
