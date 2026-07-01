@extends('layouts.app')

@section('title', 'Scan Manual')

@section('topbar-action')
<a href="{{ route('dashboard') }}" class="flex items-center gap-2 bg-surface-container-high text-primary px-md py-2 rounded-lg font-title-sm text-sm hover:opacity-90 active:scale-95 transition-all">
    <span class="material-symbols-outlined text-[18px]">arrow_back</span>
    Kembali ke Dashboard
</a>
@endsection

@section('content')
<div class="p-lg flex gap-lg max-w-container-max mx-auto w-full flex-1 flex-col lg:flex-row">
    <!-- Left Column (58%) -->
    <div class="w-full lg:w-[58%] space-y-lg">
        <section class="bg-surface-container-lowest custom-shadow rounded-xl border border-outline-variant/30 overflow-hidden">
            <div class="px-lg py-md border-b border-outline-variant/30 flex justify-between items-center">
                <h3 class="font-title-sm text-title-sm text-primary">Scan Control Interface</h3>
                <span class="text-on-surface-variant flex items-center gap-1 text-[12px]">
                    <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                    System Ready
                </span>
            </div>
            
            <div class="p-lg">
                <!-- Detection Zone -->
                <div class="relative h-64 w-full border-2 border-dashed border-outline-variant/50 rounded-xl bg-surface-container-low flex flex-col items-center justify-center overflow-hidden mb-lg">
                    <!-- Background Grid Effect -->
                    <div class="absolute inset-0 opacity-[0.03] pointer-events-none" style="background-image: radial-gradient(#000 1px, transparent 1px); background-size: 20px 20px;"></div>
                    
                    <!-- Animated Pulse -->
                    <div class="relative flex items-center justify-center">
                        <div class="absolute w-48 h-48 border border-secondary/20 rounded-full wifi-pulse"></div>
                        <div class="absolute w-32 h-32 border border-secondary/40 rounded-full wifi-pulse" style="animation-delay: 0.5s"></div>
                        <div class="absolute w-16 h-16 border border-secondary/60 rounded-full wifi-pulse" style="animation-delay: 1s"></div>
                        <div class="z-10 bg-secondary w-20 h-20 rounded-full flex items-center justify-center shadow-lg shadow-secondary/30">
                            @if(isset($scan) && ($scan['interface']??'WLAN')==='LAN')
                                <span class="material-symbols-outlined text-white text-4xl" style="font-variation-settings: 'FILL' 1;">ethernet</span>
                            @else
                                <span class="material-symbols-outlined text-white text-4xl" style="font-variation-settings: 'FILL' 1;">wifi</span>
                            @endif
                        </div>
                    </div>
                    
                    @if(isset($scan))
                        <p class="mt-6 font-title-sm text-on-surface-variant">
                            Terdeteksi: <strong>{{ strtoupper($scan['interface'] ?? 'WLAN') }}</strong>
                            @if(($scan['interface']??'WLAN')==='WLAN')
                                (SSID: {{ $scan['ssid'] ?? '—' }})
                            @else
                                (Ethernet Connected)
                            @endif
                        </p>
                    @else
                        <p class="mt-6 font-title-sm text-on-surface-variant">Scanning for active frequencies...</p>
                    @endif
                </div>

                <!-- Interface Controls -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-lg mb-xl">
                    <div>
                        <label class="font-label-caps text-on-surface-variant mb-2 block">Detection Mode</label>
                        @php
                            $isWlan = !isset($scan) || ($scan['interface']??'WLAN') === 'WLAN';
                        @endphp
                        <div class="flex items-center gap-3 bg-secondary-container/10 p-3 rounded-lg border border-secondary/20">
                            <div class="w-10 h-10 bg-secondary/10 text-secondary flex items-center justify-center rounded">
                                <span class="material-symbols-outlined">{{ $isWlan ? 'router' : 'settings_ethernet' }}</span>
                            </div>
                            <div>
                                <p class="font-title-sm text-sm text-primary leading-none">{{ $isWlan ? 'WLAN Jaringan' : 'LAN Connection' }}</p>
                                <p class="text-[11px] text-on-surface-variant">{{ $isWlan ? ($scan['ssid'] ?? 'Wi-Fi Standard') : 'Kabel terhubung' }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <form method="GET" action="{{ route('scan.manual.standar') }}" class="m-0" id="standarForm">
                            <label class="font-label-caps text-on-surface-variant mb-2 block">Standar Penilaian</label>
                            <select name="standar" onchange="this.form.submit()" class="w-full bg-surface-container border-outline-variant rounded-lg font-body-md text-sm py-2.5 focus:ring-2 focus:ring-secondary/20 focus:border-secondary transition-all">
                                @foreach($standards as $std)
                                    <option value="{{ $std['key'] }}" {{ $activeKey===$std['key']?'selected':'' }}>
                                        {{ $std['label'] }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </div>
                </div>

                <!-- Main Action Button -->
                @if(isset($scan))
                    <a href="{{ route('scan.manual', ['clear' => 1]) }}" class="w-full bg-secondary text-on-secondary py-4 rounded-xl font-display-lg text-lg flex items-center justify-center gap-3 shadow-lg shadow-secondary/20 hover:shadow-secondary/40 active:scale-[0.98] transition-all group">
                        Scan Ulang Jaringan
                        <span class="material-symbols-outlined group-hover:rotate-180 transition-transform duration-500">refresh</span>
                    </a>
                @else
                    <form method="POST" action="{{ route('scan.manual.run') }}" id="scanForm" class="m-0">
                        @csrf
                        <input type="hidden" name="standar" value="{{ $activeKey }}">
                        <button type="submit" class="w-full bg-secondary text-on-secondary py-4 rounded-xl font-display-lg text-lg flex items-center justify-center gap-3 shadow-lg shadow-secondary/20 hover:shadow-secondary/40 active:scale-[0.98] transition-all group">
                            Mulai Scan Sekarang
                            <span class="material-symbols-outlined group-hover:translate-x-1 transition-transform">arrow_forward_ios</span>
                        </button>
                    </form>
                @endif
            </div>
            
            <!-- Progress Steps Footer -->
            <div class="bg-surface-container-low px-lg py-md border-t border-outline-variant/30">
                <div class="flex justify-between items-center max-w-md mx-auto">
                    <div id="step-1-container" class="flex flex-col items-center gap-2 opacity-100 transition-all duration-300">
                        <div id="step-1-circle" class="w-8 h-8 bg-secondary text-on-secondary rounded-full flex items-center justify-center text-xs font-bold ring-4 ring-secondary/20 transition-all duration-300">1</div>
                        <span id="step-1-text" class="text-[11px] font-bold text-secondary uppercase tracking-tight transition-all duration-300">Initializing</span>
                    </div>
                    
                    <div class="h-1 w-full bg-outline-variant rounded-full overflow-hidden mx-4">
                        <div id="line-1-2" class="h-full bg-secondary w-0 transition-all duration-300"></div>
                    </div>
                    
                    <div id="step-2-container" class="flex flex-col items-center gap-2 opacity-50 transition-all duration-300">
                        <div id="step-2-circle" class="w-8 h-8 bg-outline-variant text-on-surface rounded-full flex items-center justify-center text-xs font-bold transition-all duration-300">2</div>
                        <span id="step-2-text" class="text-[11px] font-bold text-on-surface-variant uppercase tracking-tight transition-all duration-300">Calibration</span>
                    </div>
                    
                    <div class="h-1 w-full bg-outline-variant rounded-full overflow-hidden mx-4">
                        <div id="line-2-3" class="h-full bg-secondary w-0 transition-all duration-300"></div>
                    </div>
                    
                    <div id="step-3-container" class="flex flex-col items-center gap-2 opacity-50 transition-all duration-300">
                        <div id="step-3-circle" class="w-8 h-8 bg-outline-variant text-on-surface rounded-full flex items-center justify-center text-xs font-bold transition-all duration-300">3</div>
                        <span id="step-3-text" class="text-[11px] font-bold text-on-surface-variant uppercase tracking-tight transition-all duration-300">Data Collection</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- Additional Info / Help -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-lg">
            <div class="bg-surface-container-lowest custom-shadow p-md rounded-xl border border-outline-variant/30 flex gap-md items-start">
                <div class="text-secondary p-2 bg-secondary/10 rounded-lg">
                    <span class="material-symbols-outlined">info</span>
                </div>
                <div>
                    <h4 class="font-title-sm text-sm text-primary">Informasi Scan</h4>
                    <p class="text-xs text-on-surface-variant mt-1">Scan manual membutuhkan waktu sekitar 30-60 detik tergantung interferensi lokal.</p>
                </div>
            </div>
            
            <div class="bg-surface-container-lowest custom-shadow p-md rounded-xl border border-outline-variant/30 flex gap-md items-start">
                <div class="text-tertiary-fixed-dim p-2 bg-tertiary-fixed-dim/10 rounded-lg">
                    <span class="material-symbols-outlined">bolt</span>
                </div>
                <div>
                    <h4 class="font-title-sm text-sm text-primary">Ultra Precision</h4>
                    <p class="text-xs text-on-surface-variant mt-1">Menggunakan algoritma Netra DeepSense untuk hasil yang lebih akurat.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column (42%) -->
    @if(isset($scan))
    <div class="w-full lg:w-[42%]">
        <div class="sticky top-[88px] space-y-lg">
            <section class="bg-surface-container-lowest custom-shadow rounded-xl border border-outline-variant/30 overflow-hidden">
                <div class="px-lg py-md border-b border-outline-variant/30">
                    <h3 class="font-title-sm text-title-sm text-primary">Scan Results</h3>
                </div>
                
                <div class="p-lg">
                    <!-- Score Donut Gauge -->
                    @php
                        $score = $scan['score'] ?? 0;
                        $kategori = $scan['kategori'] ?? 'Buruk';
                        $radius = 45;
                        $circ = 2 * M_PI * $radius;
                        $offset = $circ * (1 - $score / 100);
                        
                        $scColorClass = $score >= 90 ? 'text-secondary' : ($score >= 75 ? 'text-secondary-container' : ($score >= 60 ? 'text-warning' : 'text-error'));
                        $scBgClass = $score >= 90 ? 'bg-green-100 text-green-700' : ($score >= 75 ? 'bg-blue-100 text-blue-700' : ($score >= 60 ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700'));
                    @endphp
                    <div class="flex flex-col items-center mb-xl">
                        <div class="relative w-48 h-48">
                            <svg class="w-full h-full -rotate-90" viewBox="0 0 100 100">
                                <circle class="text-surface-container-high" cx="50" cy="50" fill="transparent" r="{{ $radius }}" stroke="currentColor" stroke-width="8"></circle>
                                <circle class="{{ $scColorClass }} transition-all duration-1000" cx="50" cy="50" fill="transparent" r="{{ $radius }}" stroke="currentColor" stroke-dasharray="{{ $circ }}" stroke-dashoffset="{{ $offset }}" stroke-width="8" stroke-linecap="round"></circle>
                            </svg>
                            <div class="absolute inset-0 flex flex-col items-center justify-center">
                                <span class="font-display-lg text-4xl text-primary leading-none">{{ $score }}</span>
                                <span class="text-[10px] font-bold text-on-surface-variant uppercase tracking-[0.2em] mt-1">Netra Score</span>
                            </div>
                        </div>
                        <div class="mt-4 px-4 py-1.5 {{ $scBgClass }} rounded-full font-bold text-sm tracking-wide">
                            {{ strtoupper($kategori) }}
                        </div>
                    </div>

                    <!-- Metric Cards Grid -->
                    <div class="grid grid-cols-2 gap-md">
                        <div class="p-4 bg-surface-container-low border border-outline-variant/20 rounded-xl">
                            <div class="flex items-center gap-2 text-on-surface-variant mb-2">
                                <span class="material-symbols-outlined text-sm">download</span>
                                <span class="text-[11px] font-bold uppercase tracking-wider">Download</span>
                            </div>
                            <div class="flex items-baseline gap-1">
                                <span class="text-xl font-bold text-primary">{{ number_format($scan['download'] ?? 0, 1) }}</span>
                                <span class="text-[11px] text-on-surface-variant">Mbps</span>
                            </div>
                        </div>
                        
                        <div class="p-4 bg-surface-container-low border border-outline-variant/20 rounded-xl">
                            <div class="flex items-center gap-2 text-on-surface-variant mb-2">
                                <span class="material-symbols-outlined text-sm">upload</span>
                                <span class="text-[11px] font-bold uppercase tracking-wider">Upload</span>
                            </div>
                            <div class="flex items-baseline gap-1">
                                <span class="text-xl font-bold text-primary">{{ number_format($scan['upload'] ?? 0, 1) }}</span>
                                <span class="text-[11px] text-on-surface-variant">Mbps</span>
                            </div>
                        </div>
                        
                        <div class="p-4 bg-surface-container-low border border-outline-variant/20 rounded-xl">
                            <div class="flex items-center gap-2 text-on-surface-variant mb-2">
                                <span class="material-symbols-outlined text-sm">timer</span>
                                <span class="text-[11px] font-bold uppercase tracking-wider">Ping</span>
                            </div>
                            <div class="flex items-baseline gap-1">
                                <span class="text-xl font-bold text-primary">{{ number_format($scan['ping'] ?? 0, 1) }}</span>
                                <span class="text-[11px] text-on-surface-variant">ms</span>
                            </div>
                        </div>
                        
                        <div class="p-4 bg-surface-container-low border border-outline-variant/20 rounded-xl">
                            <div class="flex items-center gap-2 text-on-surface-variant mb-2">
                                <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">signal_cellular_alt</span>
                                <span class="text-[11px] font-bold uppercase tracking-wider">Signal</span>
                            </div>
                            <div class="flex items-baseline gap-1">
                                <span class="text-xl font-bold text-primary">
                                    @if(($scan['interface']??'WLAN')==='LAN')
                                        N/A
                                    @else
                                        {{ $scan['signal'] ?? 0 }}
                                    @endif
                                </span>
                                @if(($scan['interface']??'WLAN')!=='LAN')
                                    <span class="text-[11px] text-on-surface-variant">dBm</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Collapsible Table -->
                @if(!empty($comparisons))
                <div class="border-t border-outline-variant/30">
                    <details class="group" open>
                        <summary class="flex justify-between items-center px-lg py-md cursor-pointer hover:bg-surface-container-high transition-colors">
                            <h4 class="font-title-sm text-sm text-primary">Perbandingan Standar</h4>
                            <span class="material-symbols-outlined transition-transform group-open:rotate-180">expand_more</span>
                        </summary>
                        <div class="px-lg pb-lg">
                            <table class="w-full text-xs">
                                <thead>
                                    <tr class="text-on-surface-variant border-b border-outline-variant/30">
                                        <th class="py-2 text-left font-bold uppercase tracking-wider">Standar</th>
                                        <th class="py-2 text-center font-bold uppercase tracking-wider">Skor</th>
                                        <th class="py-2 text-right font-bold uppercase tracking-wider">Kategori</th>
                                    </tr>
                                </thead>
                                <tbody class="text-on-surface">
                                    @foreach($comparisons as $cmp)
                                        <tr class="border-b border-outline-variant/10 {{ $cmp['key'] === $activeKey ? 'bg-secondary/5 font-semibold' : '' }}">
                                            <td class="py-3">
                                                {{ $cmp['label'] }}
                                                @if($cmp['key'] === $activeKey)
                                                    <span class="material-symbols-outlined text-[14px] text-secondary ml-1" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                                                @endif
                                            </td>
                                            <td class="py-3 text-center">
                                                @php $cc = $cmp['score'] >= 75 ? 'text-secondary' : ($cmp['score'] >= 60 ? 'text-warning' : 'text-error'); @endphp
                                                <span class="{{ $cc }} font-bold">{{ $cmp['score'] }}</span>
                                            </td>
                                            <td class="py-3 text-right text-on-surface-variant">{{ $cmp['kategori'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </details>
                </div>
                @endif
                
                <!-- Preview warning -->
                <div class="m-3 p-3 bg-amber-50 text-amber-800 border border-amber-200 rounded-lg flex items-start gap-2 text-xs">
                    <span class="material-symbols-outlined text-[16px] mt-0.5" style="font-variation-settings: 'FILL' 1;">warning</span>
                    <span><strong>Preview saja</strong> — data ini tidak disimpan ke database. Scan otomatis berjalan tiap 1 jam.</span>
                </div>
            </section>
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const scanForm = document.getElementById('scanForm');
    const detectionZone = document.querySelector('.relative.h-64');
    
    // Dynamic progress steps function
    function updateProgressSteps(p) {
        const step1Circle = document.getElementById('step-1-circle');
        const step1Text = document.getElementById('step-1-text');
        const step1Container = document.getElementById('step-1-container');
        const line12 = document.getElementById('line-1-2');
        
        const step2Circle = document.getElementById('step-2-circle');
        const step2Text = document.getElementById('step-2-text');
        const step2Container = document.getElementById('step-2-container');
        const line23 = document.getElementById('line-2-3');
        
        const step3Circle = document.getElementById('step-3-circle');
        const step3Text = document.getElementById('step-3-text');
        const step3Container = document.getElementById('step-3-container');

        if (!step1Circle || !step2Circle || !step3Circle) return;

        if (p <= 33) {
            // Step 1 Active, filling line 1-2
            let pct = (p / 33) * 100;
            line12.style.width = pct + '%';
            line23.style.width = '0%';
            
            // Step 2 Inactive
            step2Container.classList.add('opacity-50');
            step2Container.classList.remove('opacity-100');
            step2Circle.className = "w-8 h-8 bg-outline-variant text-on-surface rounded-full flex items-center justify-center text-xs font-bold transition-all duration-300";
            step2Text.className = "text-[11px] font-bold text-on-surface-variant uppercase tracking-tight transition-all duration-300";
            
            // Step 3 Inactive
            step3Container.classList.add('opacity-50');
            step3Container.classList.remove('opacity-100');
            step3Circle.className = "w-8 h-8 bg-outline-variant text-on-surface rounded-full flex items-center justify-center text-xs font-bold transition-all duration-300";
            step3Text.className = "text-[11px] font-bold text-on-surface-variant uppercase tracking-tight transition-all duration-300";
        } else if (p <= 66) {
            // Step 1 done, line 1-2 full
            line12.style.width = '100%';
            
            // Step 2 Active, filling line 2-3
            let pct = ((p - 33) / 33) * 100;
            line23.style.width = pct + '%';
            
            step2Container.classList.remove('opacity-50');
            step2Container.classList.add('opacity-100');
            step2Circle.className = "w-8 h-8 bg-secondary text-on-secondary rounded-full flex items-center justify-center text-xs font-bold ring-4 ring-secondary/20 transition-all duration-300";
            step2Text.className = "text-[11px] font-bold text-secondary uppercase tracking-tight transition-all duration-300";
            
            // Step 3 Inactive
            step3Container.classList.add('opacity-50');
            step3Container.classList.remove('opacity-100');
            step3Circle.className = "w-8 h-8 bg-outline-variant text-on-surface rounded-full flex items-center justify-center text-xs font-bold transition-all duration-300";
            step3Text.className = "text-[11px] font-bold text-on-surface-variant uppercase tracking-tight transition-all duration-300";
        } else {
            // Step 1 and 2 done
            line12.style.width = '100%';
            line23.style.width = '100%';
            
            step2Circle.className = "w-8 h-8 bg-secondary text-on-secondary rounded-full flex items-center justify-center text-xs font-bold ring-4 ring-secondary/20 transition-all duration-300";
            step2Text.className = "text-[11px] font-bold text-secondary uppercase tracking-tight transition-all duration-300";
            
            // Step 3 Active
            step3Container.classList.remove('opacity-50');
            step3Container.classList.add('opacity-100');
            step3Circle.className = "w-8 h-8 bg-secondary text-on-secondary rounded-full flex items-center justify-center text-xs font-bold ring-4 ring-secondary/20 transition-all duration-300 animate-pulse";
            step3Text.className = "text-[11px] font-bold text-secondary uppercase tracking-tight transition-all duration-300";
        }
    }
    
    if (scanForm && detectionZone) {
        scanForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Disable button
            const submitBtn = scanForm.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.classList.add('opacity-50', 'pointer-events-none');
            }

            // Replace Detection Zone content with a premium holographic rotating squares spinner
            detectionZone.innerHTML = `
                <div class="absolute inset-0 opacity-[0.03] pointer-events-none" style="background-image: radial-gradient(#000 1px, transparent 1px); background-size: 20px 20px;"></div>
                
                <div class="relative flex flex-col items-center justify-center w-full px-lg">
                    <!-- Advanced Hologram Rotating Squares (Tilted diamonds) -->
                    <div class="relative w-36 h-36 mb-md flex items-center justify-center">
                        <!-- Outer dashed square rotating -->
                        <div class="absolute w-24 h-24 rounded-[1.5rem] border-2 border-dashed border-secondary/40 animate-[spin_12s_linear_infinite]"></div>
                        <!-- Inner solid square rotating in reverse -->
                        <div class="absolute w-24 h-24 rounded-[1.5rem] border-2 border-secondary animate-[spin_6s_linear_infinite_reverse]"></div>
                        <!-- Blurred glowing backdrop -->
                        <div class="absolute w-12 h-12 rounded-full bg-secondary/15 filter blur-sm animate-pulse"></div>
                        <!-- Core concentric targets -->
                        <div class="relative z-10 w-12 h-12 rounded-full bg-surface-container border-2 border-secondary/80 flex items-center justify-center shadow-sm">
                            <div class="w-8 h-8 rounded-full border border-secondary/60 flex items-center justify-center animate-pulse">
                                <div class="w-4 h-4 rounded-full bg-secondary"></div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Dynamic Status Text -->
                    <div class="text-[11px] font-bold text-on-surface-variant tracking-wider uppercase mt-2" id="scan-progress-text">Mendeteksi Antarmuka Jaringan...</div>
                </div>
            `;
            
            // Start AJAX call
            let ajaxCompleted = false;
            let scanError = null;
            
            fetch("{{ route('scan.manual.run') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Content-Type": "application/json"
                }
            })
            .then(res => {
                if (!res.ok) throw new Error("Gagal mengambil data scan.");
                return res.json();
            })
            .then(data => {
                ajaxCompleted = true;
            })
            .catch(err => {
                ajaxCompleted = true;
                scanError = err.message;
            });
            
            // Smooth progress simulation
            let progress = 0;
            const progressTexts = [
                { limit: 20, text: "Mendeteksi Antarmuka Jaringan..." },
                { limit: 45, text: "Mengukur Kecepatan Unduh (Download)..." },
                { limit: 70, text: "Mengukur Kecepatan Unggah (Upload)..." },
                { limit: 90, text: "Menguji Latensi & Ping..." },
                { limit: 100, text: "Menghitung Skor Kualitas Jaringan..." }
            ];
            
            const txtEl = document.getElementById('scan-progress-text');
            
            const interval = setInterval(() => {
                if (!ajaxCompleted) {
                    if (progress < 95) {
                        progress += Math.floor(Math.random() * 3) + 1;
                        if (progress > 95) progress = 95;
                    }
                } else {
                    if (progress < 100) {
                        progress += 4;
                        if (progress > 100) progress = 100;
                    }
                }
                
                // Update dynamic text
                const textObj = progressTexts.find(pt => progress <= pt.limit);
                if (textObj && txtEl) {
                    txtEl.textContent = textObj.text;
                }
                
                // Update steps dynamically
                updateProgressSteps(progress);
                
                // When done
                if (progress >= 100 && ajaxCompleted) {
                    clearInterval(interval);
                    if (scanError) {
                        alert(scanError);
                        window.location.reload();
                    } else {
                        window.location.href = "{{ route('scan.manual') }}";
                    }
                }
            }, 100);
        });
    }
});
</script>
@endpush