<!doctype html>
<html class="light" lang="id">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>NETRA – @yield('title', 'Dashboard')</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&amp;display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet">
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "on-surface-variant": "#44474c",
                        "on-secondary-fixed-variant": "#003ea8",
                        "error-container": "#ffdad6",
                        "error": "#ba1a1a",
                        "on-error": "#ffffff",
                        "on-error-container": "#93000a",
                        "secondary-fixed-dim": "#b4c5ff",
                        "primary-fixed-dim": "#bbc7dc",
                        "outline": "#75777d",
                        "on-secondary-fixed": "#00174b",
                        "on-surface": "#191c1e",
                        "on-tertiary": "#ffffff",
                        "background": "#f7f9fb",
                        "on-secondary": "#ffffff",
                        "tertiary-fixed-dim": "#4cd7f6",
                        "surface-variant": "#e0e3e5",
                        "on-tertiary-container": "#009eb9",
                        "secondary": "#0051d5",
                        "secondary-fixed": "#dbe1ff",
                        "on-background": "#191c1e",
                        "tertiary": "#00181e",
                        "on-primary-container": "#8591a5",
                        "tertiary-fixed": "#acedff",
                        "inverse-primary": "#bbc7dc",
                        "surface-container-lowest": "#ffffff",
                        "on-tertiary-fixed-variant": "#004e5c",
                        "inverse-on-surface": "#eff1f3",
                        "surface-container": "#eceef0",
                        "surface-bright": "#f7f9fb",
                        "secondary-container": "#316bf3",
                        "primary-fixed": "#d7e3f9",
                        "primary-container": "#1e2a3a",
                        "surface-dim": "#d8dadc",
                        "on-primary-fixed": "#101c2c",
                        "on-tertiary-fixed": "#001f26",
                        "outline-variant": "#c5c6cc",
                        "on-primary-fixed-variant": "#3c4859",
                        "surface-container-low": "#f2f4f6",
                        "surface-container-highest": "#e0e3e5",
                        "primary": "#091525",
                        "on-primary": "#ffffff",
                        "tertiary-container": "#002e37",
                        "surface": "#f7f9fb",
                        "on-secondary-container": "#fefcff",
                        "surface-container-high": "#e6e8ea",
                        "surface-tint": "#535f71",
                        "inverse-surface": "#2d3133"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.125rem",
                        "lg": "0.25rem",
                        "xl": "0.5rem",
                        "full": "0.75rem"
                    },
                    "spacing": {
                        "lg": "24px",
                        "sm": "12px",
                        "sidebar-width": "260px",
                        "xs": "8px",
                        "md": "16px",
                        "base": "4px",
                        "xl": "32px",
                        "container-max": "1440px"
                    },
                    "fontFamily": {
                        "code-data": ["Inter"],
                        "title-sm": ["Inter"],
                        "display-lg": ["Inter"],
                        "body-sm": ["Inter"],
                        "label-caps": ["Inter"],
                        "headline-md": ["Inter"],
                        "body-md": ["Inter"]
                    },
                    "fontSize": {
                        "code-data": ["13px", {"lineHeight": "16px", "letterSpacing": "-0.01em", "fontWeight": "500"}],
                        "title-sm": ["16px", {"lineHeight": "24px", "fontWeight": "600"}],
                        "display-lg": ["32px", {"lineHeight": "40px", "letterSpacing": "-0.02em", "fontWeight": "700"}],
                        "body-sm": ["12px", {"lineHeight": "18px", "fontWeight": "400"}],
                        "label-caps": ["11px", {"lineHeight": "16px", "letterSpacing": "0.05em", "fontWeight": "700"}],
                        "headline-md": ["20px", {"lineHeight": "28px", "fontWeight": "600"}],
                        "body-md": ["14px", {"lineHeight": "20px", "fontWeight": "400"}]
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            vertical-align: middle;
        }
        .status-dot-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: .5; transform: scale(1.2); }
        }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        
        .wifi-pulse {
            animation: pulse-ring 2.5s cubic-bezier(0.215, 0.61, 0.355, 1) infinite;
        }
        @keyframes pulse-ring {
            0% { transform: scale(.3); opacity: 0; }
            50% { opacity: 0.5; }
            100% { transform: scale(1.1); opacity: 0; }
        }
        .step-active {
            position: relative;
        }
        .step-active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 100%;
            height: 2px;
            background-color: theme('colors.secondary');
        }
        .custom-shadow {
            box-shadow: 0 1px 3px rgba(0,0,0,0.05), 0 1px 2px rgba(0,0,0,0.03);
        }
    </style>
    @stack('styles')
</head>
<body class="bg-background text-on-surface font-body-md overflow-x-hidden min-h-screen">

    <!-- Side Navigation Shell -->
    <aside class="w-sidebar-width h-screen fixed left-0 top-0 bg-primary flex flex-col py-md px-sm z-50">
        <div class="mb-xl px-md flex items-center gap-3">
            <img class="h-8 w-8 object-contain rounded-md" src="{{ asset('images/ChatGPT Image 25 Jun 2026, 11.53.40.png') }}" alt="NETRA Logo">
            <div>
                <h1 class="font-display-lg text-title-sm font-bold text-secondary-fixed tracking-wider leading-none">NETRA</h1>
                <p class="font-title-sm text-on-primary-container text-[9px] uppercase tracking-widest mt-0.5">Network Intelligence</p>
            </div>
        </div>
        
        <nav class="flex-1 space-y-base">
            <a class="flex items-center gap-md px-md py-sm rounded transition-all {{ request()->routeIs('dashboard') ? 'border-l-4 border-secondary text-secondary-fixed bg-secondary-container/10 font-semibold' : 'text-on-primary-fixed-variant hover:text-secondary-fixed hover:bg-primary-container/50' }}" 
               href="{{ route('dashboard') }}">
                <span class="material-symbols-outlined">dashboard</span>
                <span class="font-title-sm text-title-sm">Dashboard</span>
            </a>
            
            <a class="flex items-center gap-md px-md py-sm rounded transition-all {{ request()->routeIs('scan.manual*') ? 'border-l-4 border-secondary text-secondary-fixed bg-secondary-container/10 font-semibold' : 'text-on-primary-fixed-variant hover:text-secondary-fixed hover:bg-primary-container/50' }}" 
               href="{{ route('scan.manual') }}">
                <span class="material-symbols-outlined">radar</span>
                <span class="font-title-sm text-title-sm">Scan Manual</span>
            </a>
            
            <a class="flex items-center gap-md px-md py-sm rounded transition-all {{ request()->routeIs('history') ? 'border-l-4 border-secondary text-secondary-fixed bg-secondary-container/10 font-semibold' : 'text-on-primary-fixed-variant hover:text-secondary-fixed hover:bg-primary-container/50' }}" 
               href="{{ route('history') }}">
                <span class="material-symbols-outlined">history</span>
                <span class="font-title-sm text-title-sm">Riwayat</span>
            </a>
            
            <a class="flex items-center gap-md px-md py-sm rounded transition-all {{ request()->routeIs('settings') ? 'border-l-4 border-secondary text-secondary-fixed bg-secondary-container/10 font-semibold' : 'text-on-primary-fixed-variant hover:text-secondary-fixed hover:bg-primary-container/50' }}" 
               href="{{ route('settings') }}">
                <span class="material-symbols-outlined">settings</span>
                <span class="font-title-sm text-title-sm">Settings</span>
            </a>
        </nav>
        
        <div class="mt-auto border-t border-on-primary-fixed-variant/20 pt-md space-y-base">
            @php
                $_activeNet = \App\Models\Scan::getActiveConnection();
                $_iface = strtoupper($_activeNet['interface'] ?? 'WLAN');
                $_ssid = $_iface === 'LAN' ? 'Ethernet' : ($_activeNet['ssid'] ?? '—');
            @endphp
            <div class="px-md py-xs mb-sm rounded bg-secondary-container/20 border border-secondary/30">
                <p class="text-[10px] text-secondary-fixed-dim uppercase font-bold">Interface</p>
                <p class="text-secondary-fixed text-sm">{{ $_iface }} · {{ $_ssid }}</p>
            </div>
            
            <a class="flex items-center gap-md px-md py-sm rounded text-on-primary-fixed-variant hover:text-secondary-fixed hover:bg-primary-container/50 transition-all" 
               href="{{ route('settings') }}">
                <span class="material-symbols-outlined">account_circle</span>
                <span class="font-title-sm text-title-sm">Account</span>
            </a>
            
            <form method="POST" action="{{ route('logout') }}" class="m-0">
                @csrf
                <button type="submit" class="w-full flex items-center gap-md px-md py-sm rounded text-on-primary-fixed-variant hover:text-secondary-fixed hover:bg-primary-container/50 transition-all text-left">
                    <span class="material-symbols-outlined">logout</span>
                    <span class="font-title-sm text-title-sm">Logout</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Area -->
    <main class="ml-sidebar-width min-h-screen flex flex-col">
        <!-- Top Navigation Bar -->
        <header class="sticky top-0 right-0 z-40 bg-surface/80 backdrop-blur-md flex justify-between items-center h-16 px-lg border-b border-outline-variant shadow-sm">
            <div class="flex items-center gap-lg">
                <h2 class="font-headline-md text-headline-md text-primary">@yield('title', 'Dashboard')</h2>
                <div class="relative hidden lg:block">
                    <span class="material-symbols-outlined absolute left-md top-1/2 -translate-y-1/2 text-on-surface-variant">search</span>
                    <input class="pl-10 pr-md py-xs bg-surface-container-low border border-outline-variant rounded-full text-body-md focus:ring-2 focus:ring-secondary focus:border-secondary transition-all outline-none w-64" placeholder="Search data..." type="text">
                </div>
            </div>
            
            <div class="flex items-center gap-md">
                @yield('topbar-action')
                
                <div class="h-6 border-l border-outline-variant mx-xs"></div>
                
                <div class="flex items-center gap-md">
                    <button class="p-2 text-on-surface-variant hover:bg-surface-container-high transition-colors rounded-full relative">
                        <span class="material-symbols-outlined">notifications</span>
                        <span class="absolute top-2 right-2 w-2 h-2 bg-error rounded-full"></span>
                    </button>
                    <button class="p-2 text-on-surface-variant hover:bg-surface-container-high transition-colors rounded-full">
                        <span class="material-symbols-outlined">help_outline</span>
                    </button>
                </div>
                
                <div class="w-8 h-8 rounded-full overflow-hidden border border-outline-variant">
                    <img class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAD5xl3AX5sq3gP27dmzaQqlOVi8FdywfY4OAQuJBzzgn8xfZV4tVkqfxcCL7o0z_6ssmGnFQR3AdGd_e04bvn7GSbyCl3WK9RIxfVIUqdNWpL4P56wX6VLs0XBo1qvoD1u5XRAeSUUv9JuMrnljPBSIBxllpWShFHHEV6HK5y49un5w-G122Kbsjx7AcOTHThMVztrHNhrG4WgLsQdPAeu9U6EQ0KwagWnkhqQBisDeaaUXQ9mpxCcC9JezQz24ZHTjQhr5zTia6E" alt="Profile">
                </div>
            </div>
        </header>

        <!-- Dynamic Content -->
        @if(session('error'))
            <div class="m-3 p-3 bg-error-container text-on-error-container border border-error/20 rounded-lg flex items-center gap-2 text-sm">
                <span class="material-symbols-outlined text-[18px]">warning</span>
                {{ session('error') }}
            </div>
        @endif
        @if(session('success'))
            <div class="m-3 p-3 bg-secondary-container/20 text-secondary border border-secondary/20 rounded-lg flex items-center gap-2 text-sm">
                <span class="material-symbols-outlined text-[18px]">check_circle</span>
                {{ session('success') }}
            </div>
        @endif

        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        // Micro-interaction for button click effect
        document.querySelectorAll('button').forEach(btn => {
            btn.addEventListener('click', function(e) {
                let ripple = document.createElement('span');
                ripple.classList.add('ripple');
                this.appendChild(ripple);
                setTimeout(() => ripple.remove(), 600);
            });
        });
    </script>
    @stack('scripts')
</body>
</html>