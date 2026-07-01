@extends('layouts.app')

@section('title', 'Riwayat Scan')

@section('topbar-action')
<div class="flex items-center gap-md">
    {{-- Standar dropdown --}}
    <form method="GET" action="{{ route('history') }}" id="form-standar" class="m-0">
        <select name="standar" onchange="this.form.submit()" class="bg-surface-container border border-outline-variant rounded-lg font-body-md text-xs py-1.5 px-3 focus:ring-2 focus:ring-secondary/20 focus:border-secondary transition-all">
            @foreach($standards as $std)
                <option value="{{ $std['key'] }}" {{ $activeKey===$std['key']?'selected':'' }}>{{ $std['label'] }}</option>
            @endforeach
        </select>
        @foreach(request()->except('standar') as $k=>$v)
            <input type="hidden" name="{{ $k }}" value="{{ $v }}">
        @endforeach
    </form>
    
    {{-- Export dropdown --}}
    <details class="relative inline-block text-left select-none group">
        <summary class="list-none cursor-pointer flex items-center gap-1 bg-green-600 hover:bg-green-700 text-white px-md py-2 rounded-lg font-title-sm text-sm transition-all shadow-sm">
            <span class="material-symbols-outlined text-[18px]">download</span> Export <span class="material-symbols-outlined text-[16px] transition-transform group-open:rotate-180">expand_more</span>
        </summary>
        <div class="absolute right-0 mt-2 w-48 rounded-xl bg-surface-container-lowest border border-outline-variant shadow-lg py-2 z-50">
            <a class="flex items-center gap-2 px-4 py-2 hover:bg-surface-container-low text-xs text-on-surface" href="{{ route('export.excel', array_merge(request()->query(),['standar'=>$activeKey])) }}">
                <span class="material-symbols-outlined text-[16px] text-green-600">table_view</span> Excel (.xlsx)
            </a>
            <a class="flex items-center gap-2 px-4 py-2 hover:bg-surface-container-low text-xs text-on-surface" href="{{ route('export.excel', array_merge(request()->query(),['standar'=>$activeKey])) }}">
                <span class="material-symbols-outlined text-[16px] text-slate-500">csv</span> CSV (.csv)
            </a>
            <a class="flex items-center gap-2 px-4 py-2 hover:bg-surface-container-low text-xs text-on-surface" href="{{ route('export.excel', array_merge(request()->query(),['standar'=>$activeKey])) }}">
                <span class="material-symbols-outlined text-[16px] text-rose-600">picture_as_pdf</span> PDF (.pdf)
            </a>
        </div>
    </details>
</div>
@endsection

@section('content')
<div class="p-lg max-w-container-max mx-auto w-full space-y-lg">
    
    {{-- FILTER BAR --}}
    <div class="bg-surface-container-lowest custom-shadow rounded-xl p-md border border-outline-variant">
        <form method="GET" action="{{ route('history') }}" class="grid grid-cols-2 md:grid-cols-6 gap-md items-end m-0">
            <input type="hidden" name="standar" value="{{ $activeKey }}">
            
            <div>
                <label class="font-label-caps text-on-surface-variant mb-1.5 block text-[10px] font-bold tracking-wider uppercase">Tanggal Awal</label>
                <input type="date" name="tanggal_awal" class="w-full bg-surface-container border-outline-variant rounded-lg font-body-md text-xs py-1.5 focus:ring-2 focus:ring-secondary/20 focus:border-secondary transition-all" value="{{ request('tanggal_awal') }}">
            </div>
            
            <div>
                <label class="font-label-caps text-on-surface-variant mb-1.5 block text-[10px] font-bold tracking-wider uppercase">Tanggal Akhir</label>
                <input type="date" name="tanggal_akhir" class="w-full bg-surface-container border-outline-variant rounded-lg font-body-md text-xs py-1.5 focus:ring-2 focus:ring-secondary/20 focus:border-secondary transition-all" value="{{ request('tanggal_akhir') }}">
            </div>
            
            <div>
                <label class="font-label-caps text-on-surface-variant mb-1.5 block text-[10px] font-bold tracking-wider uppercase">Interface</label>
                <select name="interface" class="w-full bg-surface-container border-outline-variant rounded-lg font-body-md text-xs py-1.5 focus:ring-2 focus:ring-secondary/20 focus:border-secondary transition-all">
                    <option value="">All Interfaces</option>
                    <option value="WLAN" {{ request('interface')=='WLAN'?'selected':'' }}>📶 WLAN</option>
                    <option value="LAN"  {{ request('interface')=='LAN' ?'selected':'' }}>🔌 LAN</option>
                </select>
            </div>
            
            <div>
                <label class="font-label-caps text-on-surface-variant mb-1.5 block text-[10px] font-bold tracking-wider uppercase">SSID</label>
                <select name="ssid" class="w-full bg-surface-container border-outline-variant rounded-lg font-body-md text-xs py-1.5 focus:ring-2 focus:ring-secondary/20 focus:border-secondary transition-all">
                    <option value="">All SSIDs</option>
                    @foreach($ssidList as $s)
                        <option value="{{ $s }}" {{ request('ssid')==$s?'selected':'' }}>{{ $s }}</option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="font-label-caps text-on-surface-variant mb-1.5 block text-[10px] font-bold tracking-wider uppercase">Kategori</label>
                <select name="kategori" class="w-full bg-surface-container border-outline-variant rounded-lg font-body-md text-xs py-1.5 focus:ring-2 focus:ring-secondary/20 focus:border-secondary transition-all">
                    <option value="">All</option>
                    <option value="Sangat Baik" {{ request('kategori')=='Sangat Baik'?'selected':'' }}>Sangat Baik</option>
                    <option value="Baik"        {{ request('kategori')=='Baik'       ?'selected':'' }}>Baik</option>
                    <option value="Cukup"       {{ request('kategori')=='Cukup'      ?'selected':'' }}>Cukup</option>
                    <option value="Buruk"       {{ request('kategori')=='Buruk'      ?'selected':'' }}>Buruk</option>
                </select>
            </div>
            
            <div class="flex gap-2">
                <button type="submit" class="flex-grow bg-secondary text-on-secondary py-1.5 px-3 rounded-lg font-title-sm text-xs hover:opacity-90 active:scale-[0.98] transition-all flex items-center justify-center gap-1">
                    <span class="material-symbols-outlined text-[16px]">filter_alt</span> Apply
                </button>
                <a href="{{ route('history') }}" class="bg-surface-container-high hover:bg-surface-container-highest text-primary py-1.5 px-3 rounded-lg font-title-sm text-xs flex items-center justify-center transition-all">
                    Reset
                </a>
            </div>
        </form>
    </div>

    {{-- TABEL DATA --}}
    <div class="bg-surface-container-lowest rounded-xl border border-outline-variant shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-surface-container-low text-on-surface-variant font-label-caps text-[11px] border-b border-outline-variant uppercase tracking-wider font-bold">
                    <tr>
                        <th class="px-lg py-md">No</th>
                        <th class="px-lg py-md">Waktu & User</th>
                        <th class="px-lg py-md">Interface/SSID</th>
                        <th class="px-lg py-md text-center">Download</th>
                        <th class="px-lg py-md text-center">Upload</th>
                        <th class="px-lg py-md text-center">Ping</th>
                        <th class="px-lg py-md text-center">Signal</th>
                        <th class="px-lg py-md text-center">Skor</th>
                        <th class="px-lg py-md text-center">Status</th>
                        <th class="px-lg py-md text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/60 text-body-md text-on-surface">
                    @forelse($scans as $index => $scan)
                        @php
                            $ifaceLower = strtolower($scan->interface ?? 'wlan');
                            $isWlan = str_contains($ifaceLower, 'wlan') || str_contains($ifaceLower, 'wifi') || str_contains($ifaceLower, 'wi-fi');
                            $cleanIface = preg_replace('/[0-9]+/', '', $scan->interface ?? ($isWlan ? 'wlan' : 'eth'));
                            $sc     = $scan->score ?? 0;
                            $scC    = $sc>=90?'text-secondary':($sc>=75?'text-secondary-container':($sc>=60?'text-warning':'text-error'));
                            $kBadge = $sc>=90?'bg-green-100 text-green-700':($sc>=75?'bg-blue-100 text-blue-700':($sc>=60?'bg-yellow-100 text-yellow-700':'bg-red-100 text-red-700'));
                            $userName = $scan->user?->name ?? null;
                            $userInit = $userName ? strtoupper(substr($userName,0,1)) : 'S';
                        @endphp
                        <tr class="hover:bg-surface-container-low transition-colors">
                            <td class="px-lg py-md text-on-surface-variant font-bold text-xs">
                                {{ str_pad($scans->firstItem()+$index, 3, '0', STR_PAD_LEFT) }}
                            </td>
                            <td class="px-lg py-md">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-full bg-secondary/10 text-secondary flex items-center justify-center font-bold text-xs flex-shrink-0">
                                        {{ $userInit }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-sm text-primary leading-tight">{{ $userName ?? 'System Account' }}</div>
                                        <div class="text-[11px] text-on-surface-variant">{{ $scan->tanggal }} {{ $scan->jam }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-lg py-md">
                                <div class="font-bold text-secondary text-sm flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[16px]">{{ $isWlan ? 'wifi' : 'ethernet' }}</span>
                                    {{ $cleanIface }}
                                </div>
                                <div class="text-xs text-on-surface-variant">{{ $isWlan ? ($scan->ssid ?? '—') : 'Gigabit Adapter' }}</div>
                            </td>
                            <td class="px-lg py-md text-center font-bold text-secondary-container">
                                {{ number_format($scan->download ?? 0, 1) }} <span class="text-[10px] text-on-surface-variant font-normal">Mbps</span>
                            </td>
                            <td class="px-lg py-md text-center font-bold text-tertiary-fixed-dim">
                                {{ number_format($scan->upload ?? 0, 1) }} <span class="text-[10px] text-on-surface-variant font-normal">Mbps</span>
                            </td>
                            <td class="px-lg py-md text-center">
                                <div class="font-bold text-primary">{{ number_format($scan->ping ?? 0, 1) }}<span class="text-[10px] text-on-surface-variant font-normal">ms</span></div>
                                @if($scan->ping && $scan->ping<=20)
                                    <div class="text-[9px] font-bold text-green-600 tracking-wider">STABLE</div>
                                @elseif($scan->ping && $scan->ping>100)
                                    <div class="text-[9px] font-bold text-error tracking-wider">LAGGY</div>
                                @endif
                            </td>
                            <td class="px-lg py-md text-center">
                                @if($isWlan && $scan->signal)
                                    <span class="material-symbols-outlined text-[16px] {{ $scan->signal>=-60 ? 'text-green-600' : ($scan->signal>=-75 ? 'text-amber-500' : 'text-error') }}">signal_cellular_alt</span>
                                    <div class="text-[11px] text-on-surface-variant">{{ $scan->signal }} dBm</div>
                                @else
                                    <span class="material-symbols-outlined text-[16px] text-secondary">settings_ethernet</span>
                                    <div class="text-[11px] text-on-surface-variant">Kabel</div>
                                @endif
                            </td>
                            <td class="px-lg py-md text-center font-display-lg text-lg text-primary">
                                {{ $sc }}
                            </td>
                            <td class="px-lg py-md text-center">
                                <span class="{{ $kBadge }} px-2 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider">
                                    {{ $scan->kategori ?? '—' }}
                                </span>
                            </td>
                            <td class="px-lg py-md text-right">
                                <form action="{{ route('history.destroy', $scan->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data scan ini?');" class="inline-block m-0">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-error hover:text-red-800 transition-colors flex items-center justify-center p-1 rounded-full hover:bg-red-50" title="Hapus Data">
                                        <span class="material-symbols-outlined text-[18px]">delete</span>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="px-lg py-12 text-center text-on-surface-variant">
                                <span class="material-symbols-outlined text-4xl opacity-30 mb-2">inbox</span>
                                <p class="text-sm">Belum ada data scan sesuai filter.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- PAGINASI --}}
        <div class="flex justify-between items-center px-lg py-md bg-surface-container-low border-t border-outline-variant/30 text-xs flex-wrap gap-md">
            <span class="text-on-surface-variant">Showing {{ $scans->firstItem() }}–{{ $scans->lastItem() }} of {{ $scans->total() }} results</span>
            <div class="laravel-pagination">
                {{ $scans->appends(request()->query())->links() }}
            </div>
        </div>
    </div>

    {{-- ANALISIS SECTION --}}
    <div class="space-y-md">
        <div class="flex items-center gap-2">
            <h3 class="font-title-sm text-title-sm text-primary">📊 Analisis & Perbandingan</h3>
            <span class="text-xs text-on-surface-variant">· {{ $scans->total() }} total data · Standar: {{ collect($standards)->firstWhere('key', $activeKey)['label'] ?? $activeKey }}</span>
        </div>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-lg">
            {{-- Card 1: Avg Score per SSID --}}
            <div class="bg-surface-container-lowest rounded-xl p-lg border border-outline-variant shadow-sm">
                <div class="flex justify-between items-center mb-md">
                    <div>
                        <h4 class="font-title-sm text-sm text-primary">Avg Score per SSID</h4>
                        <p class="text-xs text-on-surface-variant mt-0.5">Perbandingan kualitas antar jaringan</p>
                    </div>
                    <span class="material-symbols-outlined text-secondary">bar_chart</span>
                </div>
                
                <div class="space-y-sm">
                    @forelse($ssidComparison as $item)
                        @php
                            $pct   = min($item->avg_score, 100);
                            $colorClass = $pct>=75 ? 'bg-secondary' : ($pct>=60 ? 'bg-amber-500' : 'bg-error');
                            $textColor = $pct>=75 ? 'text-secondary' : ($pct>=60 ? 'text-amber-600' : 'text-error');
                        @endphp
                        <div>
                            <div class="flex justify-between mb-1 text-xs">
                                <span class="font-semibold text-primary">{{ $item->ssid }}</span>
                                <span class="font-bold {{ $textColor }}">{{ round($item->avg_score) }} pts</span>
                            </div>
                            <div class="h-1.5 w-full bg-outline-variant/30 rounded-full overflow-hidden">
                                <div class="h-full {{ $colorClass }} rounded-full" style="width: {{ $pct }}%"></div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-on-surface-variant py-8">Belum ada data pembandingan SSID</div>
                    @endforelse
                </div>
            </div>

            {{-- Card 2: Hourly Performance --}}
            <div class="bg-surface-container-lowest rounded-xl p-lg border border-outline-variant shadow-sm flex flex-col">
                <div class="flex justify-between items-center mb-md">
                    <div>
                        <h4 class="font-title-sm text-sm text-primary">Hourly Performance</h4>
                        <p class="text-xs text-on-surface-variant mt-0.5">Daily average load per jam</p>
                    </div>
                    @if(!empty($peakHour))
                        <span class="bg-error-container/30 text-error px-2 py-0.5 rounded text-[10px] font-bold uppercase">
                            JAM RAWAN: {{ $peakHour }}:00
                        </span>
                    @endif
                </div>
                <div class="h-44 relative flex-1">
                    <canvas id="chartHourly" class="w-full h-full"></canvas>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-lg">
            {{-- Card 3: Interface Proportion --}}
            <div class="bg-surface-container-lowest rounded-xl p-lg border border-outline-variant shadow-sm flex flex-col justify-between h-full">
                <div>
                    <h4 class="font-title-sm text-sm text-primary mb-md">Interface Proportion</h4>
                    @php
                        $wlanCount = $interfaceComparison['wlan']['count'] ?? 0;
                        $lanCount  = $interfaceComparison['lan']['count']  ?? 0;
                        $total2    = $wlanCount + $lanCount;
                        $wlanPct   = $total2>0 ? round($wlanCount/$total2*100) : 0;
                        $lanPct    = 100-$wlanPct;
                    @endphp
                    <div class="flex items-center gap-lg">
                        <div class="relative w-24 h-24 flex-shrink-0">
                            <svg class="w-full h-full transform -rotate-90" viewBox="0 0 36 36">
                                <circle class="stroke-surface-container-highest" cx="18" cy="18" fill="none" r="16" stroke-width="4" stroke="#eceef0"></circle>
                                @if($total2 > 0)
                                    <circle cx="18" cy="18" fill="none" r="16" stroke-width="4" stroke="#0051d5" pathLength="100" stroke-dasharray="{{ $wlanPct }} 100"></circle>
                                    <circle cx="18" cy="18" fill="none" r="16" stroke-width="4" stroke="#4cd7f6" pathLength="100" stroke-dasharray="{{ $lanPct }} 100" stroke-dashoffset="-{{ $wlanPct }}"></circle>
                                @else
                                    <circle cx="18" cy="18" fill="none" r="16" stroke-width="4" stroke="#0051d5" pathLength="100" stroke-dasharray="100 100"></circle>
                                @endif
                            </svg>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <span class="font-title-sm text-title-sm text-primary font-bold">{{ $total2 }}</span>
                            </div>
                        </div>
                        <div class="space-y-sm flex-1 select-none">
                            <div class="flex items-center gap-sm">
                                <div class="w-3 h-3 rounded-full bg-secondary"></div>
                                <div class="flex-1">
                                    <p class="text-body-sm font-medium leading-none text-primary">WLAN</p>
                                    <p class="text-[10px] text-on-surface-variant mt-0.5">{{ $wlanCount }} Scans ({{ $total2 > 0 ? $wlanPct : 100 }}%)</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-sm">
                                <div class="w-3 h-3 rounded-full bg-tertiary-fixed-dim"></div>
                                <div class="flex-1">
                                    <p class="text-body-sm font-medium leading-none text-primary">LAN</p>
                                    <p class="text-[10px] text-on-surface-variant mt-0.5">{{ $lanCount }} Scans ({{ $total2 > 0 ? $lanPct : 0 }}%)</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card 4: Weekly Trend --}}
            <div class="bg-surface-container-lowest rounded-xl p-lg border border-outline-variant shadow-sm lg:col-span-2 overflow-hidden flex flex-col justify-between h-full">
                <div>
                    <div class="flex justify-between items-center mb-md">
                        <div>
                            <h3 class="font-title-sm text-title-sm text-primary">Score Improvement Trend</h3>
                            <p class="text-body-sm text-on-surface-variant mt-0.5">Weekly performance baseline</p>
                        </div>
                        @if(!empty($weeklyTrendPct))
                            <div class="flex items-center gap-sm bg-green-50 text-green-700 px-md py-sm rounded-lg text-xs font-semibold">
                                <span class="material-symbols-outlined text-sm">trending_up</span>
                                <span class="font-title-sm text-title-sm">{{ $weeklyTrendPct }}</span>
                            </div>
                        @endif
                    </div>
                    
                    <div class="relative h-32 mt-lg pb-md">
                        @php
                            $s1 = $weeklyTrend[0]['avg_score'] ?? 0;
                            $s2 = $weeklyTrend[1]['avg_score'] ?? 0;
                            $s3 = $weeklyTrend[2]['avg_score'] ?? 0;
                            $s4 = $weeklyTrend[3]['avg_score'] ?? 0;
                            
                            $y1 = 90 - ($s1 / 100) * 80;
                            $y2 = 90 - ($s2 / 100) * 80;
                            $y3 = 90 - ($s3 / 100) * 80;
                            $y4 = 90 - ($s4 / 100) * 80;
                        @endphp
                        <!-- Simple Trend Line Visualization using SVG -->
                        <svg class="w-full h-full" preserveAspectRatio="none" viewBox="0 0 400 100">
                            <path d="M0,{{ $y1 }} Q66,{{ ($y1+$y2)/2 }} 133,{{ $y2 }} T266,{{ $y3 }} T400,{{ $y4 }}" fill="none" stroke="#0051d5" stroke-width="3"></path>
                            <path d="M0,{{ $y1 }} Q66,{{ ($y1+$y2)/2 }} 133,{{ $y2 }} T266,{{ $y3 }} T400,{{ $y4 }} L400,100 L0,100 Z" fill="url(#trendGradient)" opacity="0.1"></path>
                            <defs>
                                <linearGradient id="trendGradient" x1="0%" x2="0%" y1="0%" y2="100%">
                                    <stop offset="0%" stop-color="#0051d5"></stop>
                                    <stop offset="100%" stop-color="transparent"></stop>
                                </linearGradient>
                            </defs>
                            <!-- Data Points -->
                            <circle cx="0" cy="{{ $y1 }}" fill="#0051d5" r="4"></circle>
                            <circle cx="133" cy="{{ $y2 }}" fill="#0051d5" r="4"></circle>
                            <circle cx="266" cy="{{ $y3 }}" fill="#0051d5" r="4"></circle>
                            <circle cx="400" cy="{{ $y4 }}" fill="#0051d5" r="4"></circle>
                        </svg>
                        <div class="absolute bottom-[-10px] left-0 right-0 flex justify-between text-[10px] text-on-surface-variant font-code-data select-none">
                            <span>Week 1</span>
                            <span>Week 2</span>
                            <span>Week 3</span>
                            <span>Week 4</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Hourly chart
@if(!empty($hourlyComparison))
(function(){
    const hours = @json(collect($hourlyComparison)->pluck('hour'));
    const scores= @json(collect($hourlyComparison)->pluck('avg_score'));
    const peak  = @json($peakHour ?? null);
    new Chart(document.getElementById('chartHourly'),{
        type:'bar',
        data:{
            labels: hours.map(h=>h+':00'),
            datasets:[{
                label:'Avg Score',
                data:scores,
                backgroundColor:scores.map((s,i)=>hours[i]==peak?'#ba1a1a':'#0051d5'),
                borderRadius:4,
                maxBarThickness: 32
            }]
        },
        options:{
            responsive:true,
            maintainAspectRatio:false,
            plugins:{legend:{display:false}},
            scales:{
                x:{grid:{display:false},ticks:{font:{size:9}}},
                y:{min:0,max:100,ticks:{font:{size:9},stepSize:25}}
            }
        }
    });
})();
@endif


</script>
@endpush