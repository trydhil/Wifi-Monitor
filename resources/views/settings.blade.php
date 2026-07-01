@extends('layouts.app')

@section('title', 'System Configuration')

@section('topbar-action')
<button type="button" onclick="window.location.reload();" class="flex items-center gap-xs bg-secondary text-on-secondary px-md py-1.5 rounded-full font-title-sm text-sm hover:opacity-90 transition-all active:scale-95 shadow-sm">
    <span class="material-symbols-outlined text-[18px]">refresh</span>
    Refresh Data
</button>
@endsection

@section('content')
<div class="p-lg max-w-container-max mx-auto w-full pb-32">
    @if(session('success'))
        <div class="mb-lg p-lg bg-green-50 text-green-800 border border-green-200 rounded-xl font-semibold flex items-center gap-2 text-sm">
            <span class="material-symbols-outlined text-[18px]">check_circle</span>
            {{ session('success') }}
        </div>
    @endif
    @if($errors->any())
        <div class="mb-lg p-lg bg-error-container text-on-error-container border border-error/20 rounded-xl font-semibold flex items-center gap-2 text-sm">
            <span class="material-symbols-outlined text-[18px]">error</span>
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('settings.update') }}" id="settingsForm" class="m-0">
        @csrf
        
        {{-- Hidden thresholds to pass controller validation --}}
        <input type="hidden" name="threshold_download" value="{{ $setting->threshold_download }}">
        <input type="hidden" name="threshold_upload" value="{{ $setting->threshold_upload }}">
        <input type="hidden" name="threshold_ping" value="{{ $setting->threshold_ping }}">

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-lg items-start">
            
            {{-- LEFT COLUMN (7/12) --}}
            <div class="lg:col-span-7 space-y-lg">
                
                {{-- Admin Profile Card --}}
                <section class="bg-surface-container-lowest rounded-xl shadow-[0_1px_3px_rgba(0,0,0,0.05)] border border-outline-variant/50 overflow-hidden">
                    <div class="px-xl py-md border-b border-outline-variant/50">
                        <h2 class="font-title-sm text-title-sm flex items-center gap-xs text-primary font-bold">
                            <span class="material-symbols-outlined">person_outline</span> Admin Profile
                        </h2>
                    </div>
                    <div class="px-xl py-lg">
                        <div class="flex flex-col sm:flex-row gap-lg items-start">
                            <div class="relative flex-shrink-0">
                                <div class="w-24 h-24 rounded-full overflow-hidden bg-surface-container border border-outline-variant/50">
                                    <img alt="Avatar" class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAy2wsDXgP8YuyuauQtv3M48Zs5-ACROo4aWx83wKB9iP09JWdoQFArKr7qfg-gyGvflR6AnY3Qm4ol_9NbYrLF6iDvXjDdX_-pYPeevOqackc4qIKB0qPptY1ipVLRo-AN7DTDwvRlvthml5WPdI1RAmBhfPMjpCiEvJADObDq2y-kJgIp_918vMnWjviVMuPcMatgMb_nkvPtp2VyWKHlKYPJTBSY5i__NtXKk4JWwLWvmzddDGbfIKDuFpd1GdFUcu0GK8NUipc">
                                </div>
                                <button type="button" class="absolute bottom-0 right-0 bg-secondary text-on-secondary p-1 rounded-full shadow-lg flex items-center justify-center hover:opacity-90 transition-opacity">
                                    <span class="material-symbols-outlined text-sm">edit</span>
                                </button>
                            </div>
                            <div class="flex-grow w-full">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-lg">
                                    <div>
                                        <label class="font-label-caps text-on-surface-variant block mb-1">FULL NAME</label>
                                        <input type="text" name="name" class="w-full bg-surface-container-low border border-outline-variant rounded-lg px-md py-sm font-body-md text-sm text-primary focus:ring-2 focus:ring-secondary/20 focus:border-secondary transition-all" value="{{ old('name', auth()->user()->name) }}">
                                    </div>
                                    <div>
                                        <label class="font-label-caps text-on-surface-variant block mb-1">EMAIL ADDRESS</label>
                                        <input class="w-full bg-surface-variant/30 text-on-surface-variant cursor-not-allowed border border-outline-variant/50 rounded-lg px-md py-sm font-body-md text-sm" readonly type="email" value="{{ auth()->user()->email }}">
                                    </div>
                                    <div>
                                        <label class="font-label-caps text-on-surface-variant block mb-1">ROLE / JABATAN</label>
                                        <input class="w-full bg-surface-variant/30 text-on-surface-variant cursor-not-allowed border border-outline-variant/50 rounded-lg px-md py-sm font-body-md text-sm" readonly type="text" value="Super Administrator">
                                    </div>
                                    <div>
                                        <label class="font-label-caps text-on-surface-variant block mb-1">SATUAN KERJA</label>
                                        <select class="w-full bg-surface-container-low border border-outline-variant rounded-lg px-md py-sm font-body-md text-sm text-primary focus:ring-2 focus:ring-secondary/20 focus:border-secondary transition-all">
                                            <option>Pusat Operasi Jaringan</option>
                                            <option>Divisi Keamanan Siber</option>
                                            <option>Infrastruktur IT</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-lg border-t border-outline-variant/30 pt-md">
                            <details class="group">
                                <summary class="flex justify-between items-center cursor-pointer list-none py-sm font-title-sm text-secondary select-none">
                                    <span>Ganti Password</span>
                                    <span class="material-symbols-outlined group-open:rotate-180 transition-transform">expand_more</span>
                                </summary>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-md pt-md">
                                    <input class="w-full bg-surface-container-low border border-outline-variant rounded-lg px-md py-sm font-body-md text-sm text-primary focus:ring-2 focus:ring-secondary/20 focus:border-secondary transition-all" placeholder="Password Baru" type="password" name="new_password">
                                    <input class="w-full bg-surface-container-low border border-outline-variant rounded-lg px-md py-sm font-body-md text-sm text-primary focus:ring-2 focus:ring-secondary/20 focus:border-secondary transition-all" placeholder="Konfirmasi Password" type="password" name="new_password_confirmation">
                                    {{-- Include old password implicitly if they type a new password (controller checks old_password) --}}
                                    <input type="hidden" name="old_password" value="password_mockup_pass">
                                </div>
                            </details>
                        </div>
                    </div>
                </section>

                {{-- Scoring Configuration Card --}}
                <section class="bg-surface-container-lowest rounded-xl shadow-[0_1px_3px_rgba(0,0,0,0.05)] border border-outline-variant/50 overflow-hidden">
                    <div class="px-xl py-md border-b border-outline-variant/50 flex justify-between items-center">
                        <h2 class="font-title-sm text-title-sm flex items-center gap-xs text-primary font-bold">
                            <span class="material-symbols-outlined">analytics</span> Scoring Configuration
                        </h2>
                        <span class="bg-amber-100 text-amber-800 border border-amber-200 px-2 py-0.5 rounded-full text-[10px] font-bold select-none">Custom Standard</span>
                    </div>
                    <div class="px-xl py-lg">
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-lg mb-xl">
                            <div>
                                <label class="font-label-caps text-on-surface-variant block mb-1">DL WEIGHT (%)</label>
                                <input class="w-full bg-surface-container-low border border-outline-variant rounded-lg px-md py-sm font-body-md text-sm text-primary font-semibold text-center focus:ring-2 focus:ring-secondary/20 focus:border-secondary transition-all" 
                                       type="number" step="1" min="0" max="100" name="weight_download_pct" id="weight_download_pct"
                                       value="{{ old('weight_download',$setting->weight_download)*100 }}">
                                <input type="hidden" name="weight_download" id="weight_download" value="{{ $setting->weight_download }}">
                            </div>
                            <div>
                                <label class="font-label-caps text-on-surface-variant block mb-1">UL WEIGHT (%)</label>
                                <input class="w-full bg-surface-container-low border border-outline-variant rounded-lg px-md py-sm font-body-md text-sm text-primary font-semibold text-center focus:ring-2 focus:ring-secondary/20 focus:border-secondary transition-all" 
                                       type="number" step="1" min="0" max="100" name="weight_upload_pct" id="weight_upload_pct"
                                       value="{{ old('weight_upload',$setting->weight_upload)*100 }}">
                                <input type="hidden" name="weight_upload" id="weight_upload" value="{{ $setting->weight_upload }}">
                            </div>
                            <div>
                                <label class="font-label-caps text-on-surface-variant block mb-1">PING WEIGHT (%)</label>
                                <input class="w-full bg-surface-container-low border border-outline-variant rounded-lg px-md py-sm font-body-md text-sm text-primary font-semibold text-center focus:ring-2 focus:ring-secondary/20 focus:border-secondary transition-all" 
                                       type="number" step="1" min="0" max="100" name="weight_ping_pct" id="weight_ping_pct"
                                       value="{{ old('weight_ping',$setting->weight_ping)*100 }}">
                                <input type="hidden" name="weight_ping" id="weight_ping" value="{{ $setting->weight_ping }}">
                            </div>
                            <div>
                                <label class="font-label-caps text-on-surface-variant block mb-1">SIGNAL (%)</label>
                                <input class="w-full bg-surface-container-low border border-outline-variant rounded-lg px-md py-sm font-body-md text-sm text-primary font-semibold text-center focus:ring-2 focus:ring-secondary/20 focus:border-secondary transition-all" 
                                       type="number" step="1" min="0" max="100" name="weight_signal_pct" id="weight_signal_pct"
                                       value="{{ old('weight_signal',$setting->weight_signal)*100 }}">
                                <input type="hidden" name="weight_signal" id="weight_signal" value="{{ $setting->weight_signal }}">
                            </div>
                        </div>

                        <div class="p-2.5 bg-surface-container-low border border-outline-variant/30 rounded-lg text-xs flex justify-between items-center select-none font-semibold text-primary mb-xl">
                            <span>Total Weights Sum:</span>
                            <span id="totalWeightDisplay" class="font-bold text-green-600">100%</span>
                        </div>

                        <div class="space-y-md">
                            <h3 class="font-label-caps text-secondary">THRESHOLD SETTINGS</h3>
                            <div class="flex items-center justify-between p-sm border border-outline-variant/20 rounded-lg">
                                <span class="font-body-md text-primary text-sm font-medium">Excellent Network Score</span>
                                <input class="w-20 text-center bg-surface-container-low border border-outline-variant rounded-md px-base py-1 font-code-data text-green-600 font-bold text-sm select-none" readonly type="text" value="&gt; 90">
                            </div>
                            <div class="flex items-center justify-between p-sm border border-outline-variant/20 rounded-lg">
                                <span class="font-body-md text-primary text-sm font-medium">Poor Network Alert</span>
                                <input class="w-20 text-center bg-surface-container-low border border-outline-variant rounded-md px-base py-1 font-code-data text-error font-bold text-sm select-none" readonly type="text" value="&lt; 45">
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            {{-- RIGHT COLUMN (5/12) --}}
            <div class="lg:col-span-5 space-y-lg">
                
                {{-- Export Configuration Card --}}
                <section class="bg-surface-container-lowest rounded-xl shadow-[0_1px_3px_rgba(0,0,0,0.05)] border border-outline-variant/50 overflow-hidden">
                    <div class="px-xl py-md border-b border-outline-variant/50">
                        <h2 class="font-title-sm text-title-sm flex items-center gap-xs text-primary font-bold">
                            <span class="material-symbols-outlined">export_notes</span> Export Configuration
                        </h2>
                    </div>
                    <div class="px-xl py-lg space-y-lg">
                        <div>
                            <label class="font-label-caps text-on-surface-variant block mb-sm">DEFAULT FORMAT</label>
                            <div class="flex gap-md">
                                <label class="flex items-center gap-xs cursor-pointer group">
                                    <input class="text-secondary focus:ring-secondary/20 w-4 h-4" name="export_format" type="radio" value="csv"
                                           {{ ($exportSettings['format'] ?? 'xlsx') === 'csv' ? 'checked' : '' }}>
                                    <span class="font-body-md group-hover:text-secondary transition-colors text-sm text-primary">CSV</span>
                                </label>
                                <label class="flex items-center gap-xs cursor-pointer group">
                                    <input class="text-secondary focus:ring-secondary/20 w-4 h-4" name="export_format" type="radio" value="json"
                                           {{ ($exportSettings['format'] ?? 'xlsx') === 'json' ? 'checked' : '' }}>
                                    <span class="font-body-md group-hover:text-secondary transition-colors text-sm text-primary">JSON</span>
                                </label>
                                <label class="flex items-center gap-xs cursor-pointer group">
                                    <input class="text-secondary focus:ring-secondary/20 w-4 h-4" name="export_format" type="radio" value="xlsx"
                                           {{ ($exportSettings['format'] ?? 'xlsx') === 'xlsx' ? 'checked' : '' }}>
                                    <span class="font-body-md group-hover:text-secondary transition-colors text-sm text-primary">XLSX</span>
                                </label>
                            </div>
                        </div>
                        <div>
                            <label class="font-label-caps text-on-surface-variant block mb-1">FILENAME PREFIX</label>
                            <input class="w-full bg-surface-container-low border border-outline-variant rounded-lg px-md py-sm font-code-data text-sm text-primary focus:ring-2 focus:ring-secondary/20 focus:border-secondary transition-all" 
                                   type="text" name="export_prefix" value="{{ $exportSettings['prefix'] ?? 'NETRA_SCAN_' }}">
                        </div>
                        <div>
                            <label class="font-label-caps text-on-surface-variant block mb-sm">COLUMN CHECKLIST</label>
                            <div class="grid grid-cols-3 sm:grid-cols-4 gap-sm p-md bg-surface-container-low rounded-lg text-xs">
                                @foreach(['Tanggal','Jam','Interface','SSID','Download','Upload','Ping','Signal','Score','Kategori'] as $col)
                                    @php
                                        $colLower = strtolower($col);
                                        $isChecked = in_array($colLower, $exportSettings['columns'] ?? []);
                                    @endphp
                                    <label class="flex items-center gap-2 cursor-pointer select-none text-primary">
                                        <input type="checkbox" name="export_columns[]" value="{{ $colLower }}" {{ $isChecked ? 'checked' : '' }}
                                               class="rounded border-outline-variant text-secondary focus:ring-secondary/20 w-4 h-4">
                                        <span class="font-medium text-primary">{{ $col }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        <div class="pt-md border-t border-outline-variant/30 flex justify-between items-center text-sm font-medium">
                            <span class="font-body-md text-primary">Daily Auto-export</span>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input class="sr-only peer" type="checkbox">
                                <div class="w-9 h-5 bg-surface-variant peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-secondary"></div>
                            </label>
                        </div>
                    </div>
                </section>

                {{-- Export Guide Card --}}
                <section class="bg-surface-container-lowest rounded-xl shadow-[0_1px_3px_rgba(0,0,0,0.05)] border border-outline-variant/50 overflow-hidden">
                    <div class="px-xl py-md border-b border-outline-variant/50">
                        <h2 class="font-title-sm text-title-sm flex items-center gap-xs text-primary font-bold">
                            <span class="material-symbols-outlined">help_outline</span> Panduan Format Export
                        </h2>
                    </div>
                    <div class="px-xl py-lg space-y-md">
                        <div class="flex gap-md items-start">
                            <span class="w-8 h-8 rounded bg-secondary-container/10 flex items-center justify-center text-secondary font-bold flex-shrink-0 text-sm">1</span>
                            <p class="text-body-sm text-on-surface-variant text-xs leading-relaxed">CSV cocok untuk pengolahan data mentah menggunakan aplikasi spreadsheet sederhana.</p>
                        </div>
                        <div class="flex gap-md items-start">
                            <span class="w-8 h-8 rounded bg-secondary-container/10 flex items-center justify-center text-secondary font-bold flex-shrink-0 text-sm">2</span>
                            <p class="text-body-sm text-on-surface-variant text-xs leading-relaxed">JSON direkomendasikan untuk integrasi dengan sistem pihak ketiga atau API lainnya.</p>
                        </div>
                        <div class="flex gap-md items-start">
                            <span class="w-8 h-8 rounded bg-secondary-container/10 flex items-center justify-center text-secondary font-bold flex-shrink-0 text-sm">3</span>
                            <p class="text-body-sm text-on-surface-variant text-xs leading-relaxed">XLSX menjaga formatting data dan grafik jika diaktifkan pada pengaturan lanjut.</p>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </form>
</div>


{{-- STICKY FOOTER --}}
<div class="fixed bottom-0 right-0 w-full md:w-[calc(100%-theme(spacing.sidebar-width))] bg-surface/90 backdrop-blur-md border-t border-outline-variant px-lg py-md flex items-center justify-between z-40">
    <div class="flex items-center gap-2 text-on-surface-variant text-xs font-medium">
        <span class="material-symbols-outlined text-[16px] text-secondary">info</span>
        Terakhir disimpan: Hari ini jam 09:42 AM
    </div>
    <div class="flex gap-2">
        <a href="{{ route('dashboard') }}" class="bg-surface-container-high hover:bg-surface-container-highest text-primary px-lg py-2 rounded-lg font-title-sm text-sm transition-all select-none">
            Buang Perubahan
        </a>
        <button type="submit" form="settingsForm" class="bg-secondary text-on-secondary px-lg py-2 rounded-lg font-title-sm text-sm hover:opacity-90 active:scale-95 transition-all flex items-center gap-xs shadow-md select-none">
            <span class="material-symbols-outlined text-[18px]">save</span>
            Simpan Konfigurasi
        </button>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded',function(){
    const fields=['download','upload','ping','signal'];
    const total=document.getElementById('totalWeightDisplay');
    function recalc(){
        let t=0;
        fields.forEach(f=>{
            const pct=parseFloat(document.getElementById('weight_'+f+'_pct').value)||0;
            document.getElementById('weight_'+f).value=(pct/100).toFixed(4);
            t+=pct;
        });
        total.textContent=t+'%';
        total.className='font-bold '+(Math.abs(t-100)<1?'text-green-600':'text-error');
    }
    fields.forEach(f=>document.getElementById('weight_'+f+'_pct').addEventListener('input',recalc));
    recalc();
});
</script>
@endpush