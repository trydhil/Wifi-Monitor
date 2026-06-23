<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>WiFi Monitor - @yield('title', 'Dashboard')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body>
    <div class="container-fluid">
        <div class="row flex-nowrap">
            <!-- Sidebar -->
            <div class="col-auto col-md-3 col-xl-2 px-sm-2 px-0 bg-dark" style="min-height: 100vh;">
                <div class="d-flex flex-column align-items-center align-items-sm-start px-3 pt-2 text-white min-vh-100">
                    <a href="/" class="d-flex align-items-center pb-3 mb-md-0 me-md-auto text-white text-decoration-none">
                        <span class="fs-5 d-none d-sm-inline fw-bold">📡 WiFi Monitor</span>
                    </a>
                    <ul class="nav nav-pills flex-column mb-sm-auto mb-0 align-items-center align-items-sm-start w-100" id="menu">
                        <li class="nav-item w-100">
                            <a href="{{ route('dashboard') }}" class="nav-link text-white {{ request()->routeIs('dashboard') ? 'active bg-primary' : '' }}" aria-current="page">
                                <i class="bi bi-speedometer2 me-2"></i> <span class="d-none d-sm-inline">Dashboard</span>
                            </a>
                        </li>
                      <li class="nav-item w-100">
    <a href="{{ route('history') }}" class="nav-link text-white {{ request()->routeIs('history') ? 'active bg-primary' : '' }}">
        <i class="bi bi-clock-history me-2"></i> <span class="d-none d-sm-inline">Riwayat</span>
    </a>
</li>
                      <li class="nav-item w-100">
    <a href="#" id="scanMenu" class="nav-link text-white">
        <i class="bi bi-wifi me-2"></i> <span class="d-none d-sm-inline">Scan</span>
    </a>
</li>
<li class="nav-item w-100">
    <a href="#" id="exportMenu" class="nav-link text-white">
        <i class="bi bi-file-earmark-excel me-2"></i> <span class="d-none d-sm-inline">Export</span>
    </a>
</li>
                    </ul>
                    <hr class="text-white w-100 d-none d-sm-block">
                    <div class="dropdown pb-4 mt-auto">
                        <span class="text-white-50 small d-none d-sm-inline">v1.0 - Magang</span>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col py-3">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Modal Scan -->
<div class="modal fade" id="scanModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-wifi"></i> Proses Scan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="scanModalBody">
                <div id="scanProgress">
                    <p>Sedang melakukan scan jaringan...</p>
                    <div class="progress">
                        <div id="scanProgressBar" class="progress-bar progress-bar-striped progress-bar-animated" style="width: 0%">0%</div>
                    </div>
                </div>
                <div id="scanResult" style="display:none;">
                    <!-- Hasil scan akan ditampilkan di sini -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div> 

<!-- Modal Export -->
<div class="modal fade" id="exportModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-file-earmark-excel"></i> Export Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="exportForm" action="{{ route('export.excel') }}" method="GET" target="_blank">
                    <div class="mb-3">
                        <label class="form-label">Tanggal Awal</label>
                        <input type="date" name="tanggal_awal" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tanggal Akhir</label>
                        <input type="date" name="tanggal_akhir" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">SSID</label>
                        <select name="ssid" class="form-select" id="ssidSelect">
                            <option value="">Semua</option>
                            <!-- Opsi akan diisi via JavaScript -->
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kategori</label>
                        <select name="kategori" class="form-select">
                            <option value="">Semua</option>
                            <option value="Sangat Baik">Sangat Baik</option>
                            <option value="Baik">Baik</option>
                            <option value="Cukup">Cukup</option>
                            <option value="Buruk">Buruk</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success w-100">
                        <i class="bi bi-file-earmark-excel"></i> Download Excel
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')

    <script>
document.addEventListener('DOMContentLoaded', function() {
    // --- Menu Scan ---
    const scanMenu = document.getElementById('scanMenu');
    if (scanMenu) {
        scanMenu.addEventListener('click', function(e) {
            e.preventDefault();
            startScan();
        });
    }

    // --- Menu Export ---
    const exportMenu = document.getElementById('exportMenu');
    if (exportMenu) {
        exportMenu.addEventListener('click', function(e) {
            e.preventDefault();
            // Ambil daftar SSID untuk dropdown
            fetch('/api/ssid-list')
                .then(response => response.json())
                .then(data => {
                    const select = document.getElementById('ssidSelect');
                    // Kosongkan kecuali opsi "Semua"
                    select.innerHTML = '<option value="">Semua</option>';
                    data.forEach(ssid => {
                        const option = document.createElement('option');
                        option.value = ssid;
                        option.textContent = ssid;
                        select.appendChild(option);
                    });
                })
                .catch(() => { /* abaikan jika gagal */ });
            
            const modal = new bootstrap.Modal(document.getElementById('exportModal'));
            modal.show();
        });
    }
});

// Fungsi untuk menjalankan scan
function startScan() {
    const modal = new bootstrap.Modal(document.getElementById('scanModal'));
    const progressBar = document.getElementById('scanProgressBar');
    const progressDiv = document.getElementById('scanProgress');
    const resultDiv = document.getElementById('scanResult');

    // Reset tampilan
    progressDiv.style.display = 'block';
    resultDiv.style.display = 'none';
    progressBar.style.width = '0%';
    progressBar.textContent = '0%';

    modal.show();

    // Simulasi progress (0-95%) sambil menunggu response
    let progress = 0;
    const interval = setInterval(() => {
        progress += Math.floor(Math.random() * 10) + 1;
        if (progress > 95) progress = 95;
        progressBar.style.width = progress + '%';
        progressBar.textContent = progress + '%';
    }, 300);

    // Panggil API scan
    fetch('/api/scan-manual')
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => { throw new Error(err.error || 'Gagal scan'); });
            }
            return response.json();
        })
        .then(data => {
            clearInterval(interval);
            progressBar.style.width = '100%';
            progressBar.textContent = '100%';

            setTimeout(() => {
                progressDiv.style.display = 'none';
                resultDiv.style.display = 'block';
                resultDiv.innerHTML = renderResult(data);
            }, 500);
        })
        .catch(error => {
            clearInterval(interval);
            progressDiv.style.display = 'none';
            resultDiv.style.display = 'block';
            resultDiv.innerHTML = `<div class="alert alert-danger">Error: ${error.message}</div>`;
        });
}

// Fungsi untuk menampilkan hasil scan
function renderResult(data) {
    if (data.error) {
        return `<div class="alert alert-danger">${data.error}</div>`;
    }
    let html = `<table class="table table-bordered">
        <tr><th>SSID</th><td>${data.ssid || '-'}</td></tr>
        <tr><th>Tanggal</th><td>${data.tanggal || '-'}</td></tr>
        <tr><th>Jam</th><td>${data.jam || '-'}</td></tr>
        <tr><th>Download</th><td>${data.download || 0} Mbps</td></tr>
        <tr><th>Upload</th><td>${data.upload || 0} Mbps</td></tr>
        <tr><th>Ping</th><td>${data.ping || 0} ms</td></tr>
        <tr><th>Signal</th><td>${data.signal || 0} dBm</td></tr>
        <tr><th>Score</th><td><span class="badge bg-primary">${data.score || 0}</span></td></tr>
        <tr><th>Kategori</th><td>${data.kategori || '-'}</td></tr>
    </table>`;
    return html;
}
</script>
</body>
</html>