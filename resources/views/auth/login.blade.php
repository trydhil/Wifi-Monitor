<!DOCTYPE html>
<html class="light" lang="id">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>NETRA | Login</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&amp;display=swap" rel="stylesheet">
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
                        "display-lg-mobile": ["Inter"],
                        "display-lg": ["Inter"],
                        "body-sm": ["Inter"],
                        "label-caps": ["Inter"],
                        "headline-md": ["Inter"],
                        "body-md": ["Inter"]
                    },
                    "fontSize": {
                        "code-data": ["13px", {"lineHeight": "16px", "letterSpacing": "-0.01em", "fontWeight": "500"}],
                        "title-sm": ["16px", {"lineHeight": "24px", "fontWeight": "600"}],
                        "display-lg-mobile": ["24px", {"lineHeight": "32px", "letterSpacing": "-0.01em", "fontWeight": "700"}],
                        "display-lg": ["32px", {"lineHeight": "40px", "letterSpacing": "-0.02em", "fontWeight": "700"}],
                        "body-sm": ["12px", {"lineHeight": "18px", "fontWeight": "400"}],
                        "label-caps": ["11px", {"lineHeight": "16px", "letterSpacing": "0.05em", "fontWeight": "700"}],
                        "headline-md": ["20px", {"lineHeight": "28px", "fontWeight": "600"}],
                        "body-md": ["14px", {"lineHeight": "20px", "fontWeight": "400"}]
                    }
                },
            },
        }
    </script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f7f9fb;
        }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            vertical-align: middle;
        }
        .login-card {
            box-shadow: 0 1px 3px rgba(0,0,0,0.05), 0 1px 2px rgba(0,0,0,0.03);
            animation: cardIn 0.4s cubic-bezier(0.16, 1, 0.3, 1) both;
        }
        @keyframes cardIn {
            from { opacity: 0; transform: translateY(20px) }
            to { opacity: 1; transform: translateY(0) }
        }
        .input-focus-ring:focus-within {
            box-shadow: 0 0 0 2px rgba(0, 81, 213, 0.2);
        }
        .feature-item:nth-child(1) { animation: featureIn 0.4s 0.1s both }
        .feature-item:nth-child(2) { animation: featureIn 0.4s 0.2s both }
        .feature-item:nth-child(3) { animation: featureIn 0.4s 0.3s both }
        @keyframes featureIn {
            from { opacity: 0; transform: translateX(-10px) }
            to { opacity: 1; transform: translateX(0) }
        }
    </style>
</head>
<body class="min-h-screen">
<main class="flex min-h-screen flex-col md:flex-row">
    <!-- Left Section (40%) -->
    <section class="w-full md:w-[40%] bg-[#0d1117] flex flex-col justify-between p-lg relative overflow-hidden">
        <!-- Decorative Atmosphere -->
        <div class="absolute inset-0 opacity-10 pointer-events-none">
            <div class="absolute top-0 right-0 w-96 h-96 bg-secondary-container rounded-full blur-[120px] -mr-48 -mt-48"></div>
            <div class="absolute bottom-0 left-0 w-96 h-96 bg-tertiary-container rounded-full blur-[120px] -ml-48 -mb-48"></div>
        </div>
        
        {{-- Logo --}}
        <div class="relative z-10 flex items-center gap-3">
            <svg width="40" height="40" viewBox="0 0 44 44" fill="none">
                <ellipse cx="22" cy="24" rx="18" ry="11" stroke="#0051d5" stroke-width="2" fill="none"/>
                <circle cx="22" cy="24" r="3" fill="#0ea5e9"/>
                <path d="M17 21 Q22 16 27 21" stroke="#0ea5e9" stroke-width="1.8" fill="none" stroke-linecap="round"/>
                <path d="M14 18 Q22 11 30 18" stroke="#0ea5e9" stroke-width="1.8" fill="none" stroke-linecap="round" opacity="0.6"/>
                <rect x="28" y="10" width="3" height="6" rx="1" fill="#0051d5" opacity="0.9"/>
                <rect x="32" y="8" width="3" height="8" rx="1" fill="#0051d5"/>
                <rect x="36" y="6" width="3" height="10" rx="1" fill="#0051d5" opacity="0.8"/>
            </svg>
            <div>
                <h1 class="font-display-lg text-title-sm font-bold text-white tracking-widest leading-none">NETRA</h1>
                <p class="text-[10px] text-on-primary-container tracking-wider uppercase mt-0.5">Network Intelligence</p>
            </div>
        </div>

        <div class="relative z-10 my-auto py-12">
            <h2 class="font-display-lg text-display-lg text-white mb-2 leading-tight">Network Intelligence.<br>Real-time Awareness.</h2>
            <p class="font-body-md text-on-primary-container mb-8">Sistem monitoring kualitas jaringan WiFi & LAN<br>Polrestabes Makassar.</p>
            
            <div class="space-y-md text-left w-full max-w-xs">
                <div class="flex items-start space-x-md group feature-item">
                    <div class="w-10 h-10 rounded-lg bg-primary-container flex items-center justify-center text-secondary border border-outline-variant/10 group-hover:bg-secondary group-hover:text-white transition-all flex-shrink-0">
                        <span class="material-symbols-outlined">wifi</span>
                    </div>
                    <div>
                        <h4 class="font-title-sm text-sm text-white leading-tight">Monitoring Real-time</h4>
                        <p class="text-body-sm text-on-primary-container opacity-85 mt-0.5">Pantau kualitas jaringan WLAN & LAN otomatis tiap jam dari setiap PC admin.</p>
                    </div>
                </div>
                <div class="flex items-start space-x-md group feature-item">
                    <div class="w-10 h-10 rounded-lg bg-primary-container flex items-center justify-center text-secondary border border-outline-variant/10 group-hover:bg-secondary group-hover:text-white transition-all flex-shrink-0">
                        <span class="material-symbols-outlined">analytics</span>
                    </div>
                    <div>
                        <h4 class="font-title-sm text-sm text-white leading-tight">5 Standar Penilaian</h4>
                        <p class="text-body-sm text-on-primary-container opacity-85 mt-0.5">ITU-T, MOS, Ookla, Polrestabes, dan Custom — bandingkan skor berbagai perspektif.</p>
                    </div>
                </div>
                <div class="flex items-start space-x-md group feature-item">
                    <div class="w-10 h-10 rounded-lg bg-primary-container flex items-center justify-center text-secondary border border-outline-variant/10 group-hover:bg-secondary group-hover:text-white transition-all flex-shrink-0">
                        <span class="material-symbols-outlined">admin_panel_settings</span>
                    </div>
                    <div>
                        <h4 class="font-title-sm text-sm text-white leading-tight">Admin Only Access</h4>
                        <p class="text-body-sm text-on-primary-container opacity-85 mt-0.5">Akses terbatas untuk personel Polrestabes Makassar yang berwenang.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="relative z-10 text-[11px] text-on-primary-container opacity-60">
            © {{ date('Y') }} NETRA — Polrestabes Makassar
        </div>
        
        <!-- Decorative Grid Pattern -->
        <div class="absolute inset-0 z-0 opacity-[0.03]" style="background-image: radial-gradient(#ffffff 1px, transparent 1px); background-size: 32px 32px;"></div>
    </section>
    
    <!-- Right Section (60%) -->
    <section class="w-full md:w-[60%] bg-surface flex items-center justify-center p-xl">
        <div class="w-full max-w-md">
            
            {{-- Mobile logo --}}
            <div class="flex md:hidden items-center gap-2 mb-6">
                <svg width="28" height="28" viewBox="0 0 44 44" fill="none">
                    <ellipse cx="22" cy="24" rx="18" ry="11" stroke="#0051d5" stroke-width="2" fill="none"/>
                    <circle cx="22" cy="24" r="3" fill="#0ea5e9"/>
                </svg>
                <span class="font-bold text-lg tracking-wider">NETRA</span>
            </div>

            <div class="login-card bg-surface-container-lowest p-xl rounded-xl border border-outline-variant/30">
                <header class="mb-xl">
                    <h2 class="font-display-lg text-display-lg text-on-surface mb-xs">Welcome back</h2>
                    <p class="font-body-md text-body-md text-on-surface-variant">Sign in to NETRA Dashboard</p>
                </header>

                @if($errors->any())
                    <div class="p-3 bg-error-container text-on-error-container border border-error/25 rounded-lg text-xs font-semibold mb-md flex items-center gap-1.5 animate-pulse">
                        <span class="material-symbols-outlined text-[16px]">warning</span>
                        {{ $errors->first('email') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-lg">
                    @csrf
                    <!-- Email Field -->
                    <div class="space-y-xs">
                        <label class="font-label-caps text-label-caps text-on-surface-variant block" for="email">EMAIL ADDRESS</label>
                        <div class="relative input-focus-ring rounded">
                            <div class="absolute inset-y-0 left-0 pl-md flex items-center pointer-events-none">
                                <span class="material-symbols-outlined text-outline text-[20px]">mail</span>
                            </div>
                            <input class="block w-full pl-11 pr-md py-md bg-surface-container text-on-surface border border-outline-variant rounded focus:ring-0 focus:border-secondary transition-colors font-body-md text-body-md" 
                                   id="email" name="email" placeholder="admin@polrestabes.go.id" required autofocus autocomplete="email" type="email" value="{{ old('email') }}">
                        </div>
                    </div>
                    <!-- Password Field -->
                    <div class="space-y-xs">
                        <div class="flex justify-between items-center">
                            <label class="font-label-caps text-label-caps text-on-surface-variant block" for="password">PASSWORD</label>
                            <a class="font-body-sm text-body-sm text-secondary hover:underline" href="#">Forgot password?</a>
                        </div>
                        <div class="relative input-focus-ring rounded" id="passwordContainer">
                            <div class="absolute inset-y-0 left-0 pl-md flex items-center pointer-events-none">
                                <span class="material-symbols-outlined text-outline text-[20px]">lock</span>
                            </div>
                            <input class="block w-full pl-11 pr-11 py-md bg-surface-container text-on-surface border border-outline-variant rounded focus:ring-0 focus:border-secondary transition-colors font-body-md text-body-md" 
                                   id="password" name="password" placeholder="••••••••••••" required autocomplete="current-password" type="password">
                            <button class="absolute inset-y-0 right-0 pr-md flex items-center text-outline hover:text-on-surface transition-colors" onclick="togglePassword()" type="button">
                                <span class="material-symbols-outlined text-[20px]" id="eyeIcon">visibility</span>
                            </button>
                        </div>
                    </div>
                    <!-- Remember Device -->
                    <div class="flex items-center space-x-md">
                        <input class="w-5 h-5 rounded border-outline-variant text-secondary focus:ring-secondary/20 cursor-pointer" id="remember" name="remember" type="checkbox">
                        <label class="font-body-md text-body-md text-on-surface-variant cursor-pointer select-none" for="remember">Remember this device</label>
                    </div>
                    <!-- Primary Action -->
                    <button class="w-full bg-secondary hover:bg-secondary-container text-white font-title-sm text-title-sm py-md px-lg rounded-lg shadow-sm hover:shadow-md transition-all active:scale-[0.98] flex justify-center items-center gap-xs" type="submit">
                        <span>Sign In</span>
                        <span class="material-symbols-outlined">arrow_forward</span>
                    </button>
                    
                    <div class="relative py-md">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-outline-variant/30"></div>
                        </div>
                        <div class="relative flex justify-center">
                            <span class="bg-surface-container-lowest px-md font-body-sm text-body-sm text-outline uppercase tracking-wider">OR ACCESS VIA</span>
                        </div>
                    </div>
                    <!-- SSO/Alternative Option -->
                    <div class="grid grid-cols-1 gap-md">
                        <button class="w-full border border-outline-variant flex items-center justify-center space-x-md py-md rounded-lg hover:bg-surface-container transition-colors" type="button">
                            <svg class="w-5 h-5" viewBox="0 0 24 24">
                                <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"></path>
                                <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"></path>
                                <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="#FBBC05"></path>
                                <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"></path>
                            </svg>
                            <span class="font-body-md text-body-md text-on-surface">Continue with Corporate SSO</span>
                        </button>
                    </div>
                </form>
                <footer class="mt-xl text-center">
                    <p class="font-body-sm text-body-sm text-on-surface-variant">
                        New installation? <a class="text-secondary font-semibold hover:underline" href="#">Provision device</a>
                    </p>
                </footer>
            </div>
            <!-- Footer Links -->
            <div class="mt-xl flex justify-between text-outline">
                <div class="flex space-x-lg">
                    <a class="font-body-sm text-body-sm hover:text-on-surface-variant transition-colors" href="#">Help</a>
                    <a class="font-body-sm text-body-sm hover:text-on-surface-variant transition-colors" href="#">Privacy</a>
                    <a class="font-body-sm text-body-sm hover:text-on-surface-variant transition-colors" href="#">Terms</a>
                </div>
                <span class="font-body-sm text-body-sm">© 2024 NETRA Systems</span>
            </div>
        </div>
    </section>
</main>
<script>
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.innerText = 'visibility_off';
        } else {
            passwordInput.type = 'password';
            eyeIcon.innerText = 'visibility';
        }
    }

    // Add some interaction delight
    document.querySelectorAll('input').forEach(input => {
        input.addEventListener('focus', () => {
            input.parentElement.classList.add('scale-[1.01]', 'transition-transform');
        });
        input.addEventListener('blur', () => {
            input.parentElement.classList.remove('scale-[1.01]');
        });
    });
</script>
</body>
</html>