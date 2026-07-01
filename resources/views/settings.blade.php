@extends('layouts.app')

@section('title', 'System Configuration')

@section('topbar-action')
<div class="flex items-center gap-2">
    <a href="{{ route('dashboard') }}" class="bg-surface-container-high hover:bg-surface-container-highest text-primary px-md py-2 rounded-lg font-title-sm text-sm transition-all">
        Batal
    </a>
    <button type="submit" form="settingsForm" class="bg-secondary text-on-secondary px-md py-2 rounded-lg font-title-sm text-sm hover:opacity-90 active:scale-95 transition-all flex items-center gap-xs shadow-sm">
        <span class="material-symbols-outlined text-[18px]">save</span>
        Simpan Konfigurasi
    </button>
</div>
@endsection

@section('content')
<div class="p-lg max-w-container-max mx-auto w-full pb-32">
    <form method="POST" action="{{ route('settings.update') }}" id="settingsForm" class="m-0">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-lg items-start">
            
            {{-- LEFT COLUMN (7/12) --}}
            <div class="lg:col-span-7 space-y-lg">
                
                {{-- Admin Profile --}}
                <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 overflow-hidden shadow-sm">
                    <div class="px-lg py-md border-b border-outline-variant/30 flex items-center gap-2">
                        <span class="material-symbols-outlined text-secondary">account_circle</span>
                        <h3 class="font-title-sm text-sm text-primary">Admin Profile</h3>
                    </div>
                    <div class="p-lg space-y-md">
                        <div class="flex flex-col md:flex-row gap-lg items-start">
                            <div class="relative flex-shrink-0">
                                <div class="w-20 h-20 rounded-full bg-gradient-to-tr from-secondary to-tertiary-fixed-dim flex items-center justify-center text-white text-3xl font-extrabold shadow-sm">
                                    {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                                </div>
                                <div class="absolute bottom-0 right-0 w-7 h-7 rounded-full bg-secondary border-2 border-white text-white flex items-center justify-center cursor-pointer hover:opacity-90 transition-opacity">
                                    <span class="material-symbols-outlined text-[14px]">edit</span>
                                </div>
                            </div>
                            <div class="flex-grow w-full">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-md">
                                    <div>
                                        <label class="font-label-caps text-on-surface-variant mb-1.5 block text-[10px] font-bold uppercase">Nama Lengkap</label>
                                        <input type="text" class="w-full bg-surface-container/50 border-outline-variant rounded-lg font-body-md text-xs py-2 px-3 text-on-surface-variant cursor-not-allowed" value="{{ auth()->user()->name }}" readonly>
                                    </div>
                                    <div>
                                        <label class="font-label-caps text-on-surface-variant mb-1.5 block text-[10px] font-bold uppercase">Email Address</label>
                                        <div class="relative">
                                            <input type="email" class="w-full bg-surface-container/50 border-outline-variant rounded-lg font-body-md text-xs py-2 pl-3 pr-10 text-on-surface-variant cursor-not-allowed" value="{{ auth()->user()->email }}" readonly>
                                            <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-[16px]">lock</span>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="font-label-caps text-on-surface-variant mb-1.5 block text-[10px] font-bold uppercase">Role / Jabatan</label>
                                        <input type="text" class="w-full bg-surface-container/50 border-outline-variant rounded-lg font-body-md text-xs py-2 px-3 text-on-surface-variant cursor-not-allowed" value="Super Administrator" readonly>
                                    </div>
                                    <div>
                                        <label class="font-label-caps text-on-surface-variant mb-1.5 block text-[10px] font-bold uppercase">Satuan Kerja</label>
                                        <select class="w-full bg-surface-container border-outline-variant rounded-lg font-body-md text-xs py-2 px-3 focus:ring-2 focus:ring-secondary/20 focus:border-secondary transition-all">
                                            <option>Pusat Operasi Jaringan</option>
                                            <option>Divisi Keamanan Siber</option>
                                            <option>Infrastruktur IT</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="pt-md border-t border-outline-variant/30">
                            <details class="group">
                                <summary class="flex items-center gap-1 text-xs font-semibold text-secondary cursor-pointer hover:underline list-none select-none">
                                    <span class="material-symbols-outlined text-[16px]">key</span> Ganti Password
                                    <span class="material-symbols-outlined text-[14px] transition-transform group-open:rotate-185">expand_more</span>
                                </summary>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-md mt-md">
                                    <div class="md:col-span-2">
                                        <input type="password" class="w-full bg-surface-container border-outline-variant rounded-lg font-body-md text-xs py-2 px-3 focus:ring-2 focus:ring-secondary/20 focus:border-secondary transition-all" name="old_password" placeholder="Password lama">
                                    </div>
                                    <div>
                                        <input type="password" class="w-full bg-surface-container border-outline-variant rounded-lg font-body-md text-xs py-2 px-3 focus:ring-2 focus:ring-secondary/20 focus:border-secondary transition-all" name="new_password" placeholder="Password baru (min 8 karakter)">
                                    </div>
                                    <div>
                                        <input type="password" class="w-full bg-surface-container border-outline-variant rounded-lg font-body-md text-xs py-2 px-3 focus:ring-2 focus:ring-secondary/20 focus:border-secondary transition-all" name="new_password_confirmation" placeholder="Konfirmasi password">
                                    </div>
                                </div>
                            </details>
                        </div>
                    </div>
                </div>

                {{-- Scoring Config --}}
                <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 overflow-hidden shadow-sm">
                    <div class="px-lg py-md border-b border-outline-variant/30 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-secondary">tune</span>
                            <h3 class="font-title-sm text-sm text-primary">Scoring Configuration</h3>
                        </div>
                        <span class="bg-amber-100 text-amber-800 border border-amber-200 px-2 py-0.5 rounded-full text-[10px] font-bold">Custom Standard</span>
                    </div>
                    <div class="p-lg space-y-md">
                        @if($errors->any())
                            <div class="p-2.5 bg-error-container text-on-error-container border border-error/20 rounded-lg text-xs font-semibold">{{ $errors->first() }}</div>
                        @endif
                        
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-md">
                            <div>
                                <label class="font-label-caps text-on-surface-variant mb-1 block text-[10px] font-bold uppercase">DL Weight (%)</label>
                                <input type="number" step="1" min="0" max="100" name="weight_download_pct" id="weight_download_pct"
                                       class="w-full bg-surface-container border-outline-variant rounded-lg font-body-md text-xs py-1.5 focus:ring-2 focus:ring-secondary/20 focus:border-secondary transition-all"
                                       value="{{ old('weight_download',$setting->weight_download)*100 }}">
                                <input type="hidden" name="weight_download" id="weight_download" value="{{ $setting->weight_download }}">
                            </div>
                            <div>
                                <label class="font-label-caps text-on-surface-variant mb-1 block text-[10px] font-bold uppercase">UL Weight (%)</label>
                                <input type="number" step="1" min="0" max="100" name="weight_upload_pct" id="weight_upload_pct"
                                       class="w-full bg-surface-container border-outline-variant rounded-lg font-body-md text-xs py-1.5 focus:ring-2 focus:ring-secondary/20 focus:border-secondary transition-all"
                                       value="{{ old('weight_upload',$setting->weight_upload)*100 }}">
                                <input type="hidden" name="weight_upload" id="weight_upload" value="{{ $setting->weight_upload }}">
                            </div>
                            <div>
                                <label class="font-label-caps text-on-surface-variant mb-1 block text-[10px] font-bold uppercase">Ping Weight (%)</label>
                                <input type="number" step="1" min="0" max="100" name="weight_ping_pct" id="weight_ping_pct"
                                       class="w-full bg-surface-container border-outline-variant rounded-lg font-body-md text-xs py-1.5 focus:ring-2 focus:ring-secondary/20 focus:border-secondary transition-all"
                                       value="{{ old('weight_ping',$setting->weight_ping)*100 }}">
                                <input type="hidden" name="weight_ping" id="weight_ping" value="{{ $setting->weight_ping }}">
                            </div>
                            <div>
                                <label class="font-label-caps text-on-surface-variant mb-1 block text-[10px] font-bold uppercase">Signal (%)</label>
                                <input type="number" step="1" min="0" max="100" name="weight_signal_pct" id="weight_signal_pct"
                                       class="w-full bg-surface-container border-outline-variant rounded-lg font-body-md text-xs py-1.5 focus:ring-2 focus:ring-secondary/20 focus:border-secondary transition-all"
                                       value="{{ old('weight_signal',$setting->weight_signal)*100 }}">
                                <input type="hidden" name="weight_signal" id="weight_signal" value="{{ $setting->weight_signal }}">
                            </div>
                        </div>
                        
                        <div class="p-2.5 bg-surface-container-low border border-outline-variant/30 rounded-lg text-xs flex justify-between items-center select-none font-semibold text-primary">
                            <span>Total Weights Sum:</span>
                            <span id="totalWeightDisplay" class="font-bold">100%</span>
                        </div>
                        
                        <div class="text-[10px] font-bold text-secondary uppercase tracking-wider">Threshold Settings</div>
                        <div class="space-y-sm">
                            <div class="flex items-center justify-between p-2 rounded-lg border border-outline-variant bg-surface-container-low text-xs text-primary">
                                <span>Excellent Network Score</span>
                                <input type="text" class="bg-surface-container-lowest border-none font-bold text-center text-green-600 rounded w-16 py-1 select-none" value="&gt; 90" readonly>
                            </div>
                            <div class="flex items-center justify-between p-2 rounded-lg border border-outline-variant bg-surface-container-low text-xs text-primary">
                                <span>Poor Network Alert</span>
                                <input type="text" class="bg-surface-container-lowest border-none font-bold text-center text-error rounded w-16 py-1 select-none" value="&lt; 45" readonly>
                            </div>
                        </div>
                        
                        <p class="text-[11px] text-on-surface-variant flex items-center gap-1 mt-2">
                            <span class="material-symbols-outlined text-[14px]" style="font-variation-settings: 'FILL' 1;">lightbulb</span>
                            Setelah simpan, pilih standar <strong>Custom</strong> di halaman Scan Manual.
                        </p>
                    </div>
                </div>

            </div>

            {{-- RIGHT COLUMN (5/12) --}}
            <div class="lg:col-span-5 space-y-lg">
                
                {{-- Export Config --}}
                <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 overflow-hidden shadow-sm">
                    <div class="px-lg py-md border-b border-outline-variant/30 flex items-center gap-2">
                        <span class="material-symbols-outlined text-secondary">cloud_download</span>
                        <h3 class="font-title-sm text-sm text-primary">Export Configuration</h3>
                    </div>
                    <div class="p-lg space-y-md">
                        <span class="font-label-caps text-on-surface-variant block text-[10px] font-bold uppercase tracking-wider mb-2">Default Format</span>
                        
                        <div class="space-y-sm">
                            <label class="flex items-center gap-3 p-3 border border-outline-variant rounded-lg cursor-pointer hover:bg-surface-container-low/50 transition-colors select-none">
                                <input type="radio" name="export_format" value="xlsx" checked class="text-secondary focus:ring-secondary/20">
                                <span class="material-symbols-outlined text-green-600 text-xl">table_view</span>
                                <div>
                                    <div class="font-semibold text-xs text-primary leading-tight">XLSX Format</div>
                                    <div class="text-[10px] text-on-surface-variant">Standard Excel spreadsheet with formulas</div>
                                </div>
                            </label>
                            
                            <label class="flex items-center gap-3 p-3 border border-outline-variant rounded-lg cursor-pointer hover:bg-surface-container-low/50 transition-colors select-none">
                                <input type="radio" name="export_format" value="csv" class="text-secondary focus:ring-secondary/20">
                                <span class="material-symbols-outlined text-slate-500 text-xl">csv</span>
                                <div>
                                    <div class="font-semibold text-xs text-primary leading-tight">CSV Format</div>
                                    <div class="text-[10px] text-on-surface-variant">Plain text data, lightweight compatibility</div>
                                </div>
                            </label>
                            
                            <label class="flex items-center gap-3 p-3 border border-outline-variant rounded-lg cursor-pointer hover:bg-surface-container-low/50 transition-colors select-none">
                                <input type="radio" name="export_format" value="pdf" class="text-secondary focus:ring-secondary/20">
                                <span class="material-symbols-outlined text-rose-600 text-xl">picture_as_pdf</span>
                                <div>
                                    <div class="font-semibold text-xs text-primary leading-tight">PDF Report</div>
                                    <div class="text-[10px] text-on-surface-variant">Print-ready document, read-only template</div>
                                </div>
                            </label>
                        </div>
                        
                        <div class="pt-sm">
                            <label class="font-label-caps text-on-surface-variant mb-1.5 block text-[10px] font-bold uppercase">Filename Prefix</label>
                            <input type="text" class="w-full bg-surface-container border-outline-variant rounded-lg font-body-md text-xs py-2 px-3 focus:ring-2 focus:ring-secondary/20 focus:border-secondary transition-all" name="export_prefix" value="NETRA_SCAN_">
                        </div>

                        <div>
                            <span class="font-label-caps text-on-surface-variant block text-[10px] font-bold uppercase tracking-wider mb-2">Column Checklist</span>
                            <div class="grid grid-cols-2 gap-sm p-3 rounded-lg bg-surface-container-low border border-outline-variant/30 text-xs">
                                @foreach(['Tanggal','Jam','User','Interface','SSID','Download','Upload','Ping','Signal','Skor','Kategori'] as $col)
                                    <label class="flex items-center gap-2 cursor-pointer select-none">
                                        <input type="checkbox" checked class="rounded text-secondary focus:ring-secondary/20 w-4 h-4">
                                        <span class="text-primary font-medium">{{ $col }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="flex justify-between items-center pt-md border-t border-outline-variant/20 text-xs">
                            <span class="font-medium text-primary">Daily Auto-export</span>
                            <label class="relative inline-flex items-center cursor-pointer select-none">
                                <input type="checkbox" class="sr-only peer">
                                <div class="w-9 h-5 bg-outline-variant peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:height-4 after:width-4 after:h-4 after:w-4 after:transition-all peer-checked:bg-secondary"></div>
                            </label>
                        </div>
                    </div>
                </div>

                {{-- Export Guide Card --}}
                <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 overflow-hidden shadow-sm">
                    <div class="px-lg py-md border-b border-outline-variant/30 flex items-center gap-2">
                        <span class="material-symbols-outlined text-secondary">help_outline</span>
                        <h3 class="font-title-sm text-sm text-primary font-bold">Panduan Format Export</h3>
                    </div>
                    <div class="p-lg space-y-md">
                        <div class="flex gap-md items-start">
                            <span class="w-6 h-6 rounded-full bg-secondary-container/15 flex items-center justify-center text-secondary font-bold text-xs flex-shrink-0">1</span>
                            <p class="text-xs text-on-surface-variant leading-relaxed">CSV cocok untuk pengolahan data mentah menggunakan aplikasi spreadsheet sederhana.</p>
                        </div>
                        <div class="flex gap-md items-start">
                            <span class="w-6 h-6 rounded-full bg-secondary-container/15 flex items-center justify-center text-secondary font-bold text-xs flex-shrink-0">2</span>
                            <p class="text-xs text-on-surface-variant leading-relaxed">JSON direkomendasikan untuk integrasi dengan sistem pihak ketiga atau API lainnya.</p>
                        </div>
                        <div class="flex gap-md items-start">
                            <span class="w-6 h-6 rounded-full bg-secondary-container/15 flex items-center justify-center text-secondary font-bold text-xs flex-shrink-0">3</span>
                            <p class="text-xs text-on-surface-variant leading-relaxed">XLSX menjaga formatting data dan grafik jika diaktifkan pada pengaturan lanjut.</p>
                        </div>
                    </div>
                </div>

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