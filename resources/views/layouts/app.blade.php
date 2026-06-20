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
                            <a href="#" class="nav-link text-white">
                                <i class="bi bi-clock-history me-2"></i> <span class="d-none d-sm-inline">Riwayat</span>
                            </a>
                        </li>
                        <li class="nav-item w-100">
                            <a href="#" class="nav-link text-white">
                                <i class="bi bi-wifi me-2"></i> <span class="d-none d-sm-inline">Scan</span>
                            </a>
                        </li>
                        <li class="nav-item w-100">
                            <a href="#" class="nav-link text-white">
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>