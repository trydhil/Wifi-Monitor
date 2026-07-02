<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Scan;
use App\Exports\ScansExport;
use App\Services\ScoringService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class AutoExportScans extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:auto-export';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically export daily scans to storage based on configuration settings';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $settingsPath = storage_path('app/export_settings.json');
        
        $exportSettings = [
            'format'      => 'xlsx',
            'prefix'      => 'NETRA_SCAN_',
            'columns'     => ['tanggal','jam','interface','ssid','download','upload','ping','signal','score','kategori'],
            'auto_export' => false,
        ];

        if (file_exists($settingsPath)) {
            $exportSettings = array_merge($exportSettings, json_decode(file_get_contents($settingsPath), true) ?? []);
        }

        // Only run if auto_export is explicitly enabled
        if (!$exportSettings['auto_export']) {
            $this->info('Daily Auto-export is disabled. Skipping.');
            return 0;
        }

        $this->info('Running Daily Auto-export...');

        // Query today's scans
        $todayStr = now()->toDateString();
        $scansCount = Scan::where('tanggal', $todayStr)->count();

        if ($scansCount === 0) {
            $this->info("No scans recorded for today ({$todayStr}). Nothing to export.");
            return 0;
        }

        // Mock HTTP Request for Excel Export builder
        $request = new Request([
            'tanggal_awal'  => $todayStr,
            'tanggal_akhir' => $todayStr,
        ]);

        $format = strtolower($exportSettings['format']);
        $prefix = $exportSettings['prefix'];
        $fileName = $prefix . $todayStr . '.' . $format;

        // Ensure exports directory exists
        if (!Storage::disk('local')->exists('exports')) {
            Storage::disk('local')->makeDirectory('exports');
        }

        $filePath = 'exports/' . $fileName;

        if ($format === 'json') {
            $export = new ScansExport($request);
            $scans = $export->query()->get();

            $jsonData = [];
            foreach ($scans as $row) {
                $scored = ScoringService::calculate(
                    download:   $row->download  ?? 0,
                    upload:     $row->upload    ?? 0,
                    ping:       $row->ping      ?? 0,
                    signal:     $row->signal    ?? null,
                    interface:  $row->interface ?? 'WLAN',
                    standarKey: ScoringService::activeKey(),
                );

                $rowMap = [];
                foreach ($exportSettings['columns'] as $col) {
                    $colLower = strtolower($col);
                    switch ($colLower) {
                        case 'tanggal':   $rowMap['tanggal'] = $row->tanggal; break;
                        case 'jam':       $rowMap['jam'] = $row->jam; break;
                        case 'interface': $rowMap['interface'] = strtoupper($row->interface ?? 'WLAN'); break;
                        case 'ssid':      $rowMap['ssid'] = $row->ssid ?? 'Ethernet'; break;
                        case 'download':  $rowMap['download'] = $row->download ?? 0; break;
                        case 'upload':    $rowMap['upload'] = $row->upload ?? 0; break;
                        case 'ping':      $rowMap['ping'] = $row->ping ?? 0; break;
                        case 'signal':    $rowMap['signal'] = $row->signal ?? '-'; break;
                        case 'score':     $rowMap['score'] = $scored['score']; break;
                        case 'kategori':  $rowMap['kategori'] = $scored['kategori']; break;
                    }
                }
                $rowMap['standar_penilaian'] = ScoringService::activeLabel();
                $jsonData[] = $rowMap;
            }

            Storage::disk('local')->put($filePath, json_encode($jsonData, JSON_PRETTY_PRINT));
        } elseif ($format === 'csv') {
            Excel::store(new ScansExport($request), $filePath, 'local', \Maatwebsite\Excel\Excel::CSV);
        } else {
            Excel::store(new ScansExport($request), $filePath, 'local', \Maatwebsite\Excel\Excel::XLSX);
        }

        $realPath = Storage::disk('local')->path($filePath);
        $this->info("Successfully exported {$scansCount} scans to: {$realPath}");

        return 0;
    }
}
