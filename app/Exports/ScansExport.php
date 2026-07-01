<?php

namespace App\Exports;

use App\Models\Scan;
use App\Services\ScoringService;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Http\Request;

class ScansExport implements FromQuery, WithHeadings, WithMapping
{
    protected $request;
    protected string $standarKey;
    protected string $standarLabel;

    public function __construct(Request $request)
    {
        $this->request      = $request;
        $this->standarKey   = ScoringService::activeKey();
        $this->standarLabel = ScoringService::activeLabel();
    }

    public function query()
    {
        $query = Scan::query();

        if ($this->request->filled('tanggal_awal') && $this->request->filled('tanggal_akhir')) {
            $query->whereBetween('tanggal', [$this->request->tanggal_awal, $this->request->tanggal_akhir]);
        }
        if ($this->request->filled('ssid')) {
            $query->where('ssid', $this->request->ssid);
        }
        if ($this->request->filled('interface')) {
            $query->where('interface', strtoupper($this->request->interface));
        }

        return $query->orderBy('created_at', 'desc');
    }

    public function headings(): array
    {
        $exportSettings = [
            'columns' => ['tanggal','jam','interface','ssid','download','upload','ping','signal','score','kategori'],
        ];
        if (file_exists(storage_path('app/export_settings.json'))) {
            $exportSettings = array_merge($exportSettings, json_decode(file_get_contents(storage_path('app/export_settings.json')), true) ?? []);
        }

        $allHeadings = [
            'tanggal'   => 'Tanggal',
            'jam'       => 'Jam',
            'interface' => 'Interface',
            'ssid'      => 'SSID',
            'download'  => 'Download (Mbps)',
            'upload'    => 'Upload (Mbps)',
            'ping'      => 'Ping (ms)',
            'signal'    => 'Signal (dBm)',
            'score'     => 'Score',
            'kategori'  => 'Kategori',
        ];

        $headings = [];
        foreach ($exportSettings['columns'] as $col) {
            $colLower = strtolower($col);
            if (isset($allHeadings[$colLower])) {
                $headings[] = $allHeadings[$colLower];
            }
        }
        $headings[] = 'Standar Penilaian';
        return $headings;
    }

    public function map($row): array
    {
        $exportSettings = [
            'columns' => ['tanggal','jam','interface','ssid','download','upload','ping','signal','score','kategori'],
        ];
        if (file_exists(storage_path('app/export_settings.json'))) {
            $exportSettings = array_merge($exportSettings, json_decode(file_get_contents(storage_path('app/export_settings.json')), true) ?? []);
        }

        $scored = ScoringService::calculate(
            download:   $row->download  ?? 0,
            upload:     $row->upload    ?? 0,
            ping:       $row->ping      ?? 0,
            signal:     $row->signal    ?? null,
            interface:  $row->interface ?? 'WLAN',
            standarKey: $this->standarKey,
        );

        $mapped = [];
        foreach ($exportSettings['columns'] as $col) {
            $colLower = strtolower($col);
            switch ($colLower) {
                case 'tanggal':
                    $mapped[] = $row->tanggal;
                    break;
                case 'jam':
                    $mapped[] = $row->jam;
                    break;
                case 'interface':
                    $mapped[] = strtoupper($row->interface ?? 'WLAN');
                    break;
                case 'ssid':
                    $mapped[] = $row->ssid ?? 'Ethernet';
                    break;
                case 'download':
                    $mapped[] = $row->download ?? 0;
                    break;
                case 'upload':
                    $mapped[] = $row->upload ?? 0;
                    break;
                case 'ping':
                    $mapped[] = $row->ping ?? 0;
                    break;
                case 'signal':
                    $mapped[] = $row->signal ?? '-';
                    break;
                case 'score':
                    $mapped[] = $scored['score'];
                    break;
                case 'kategori':
                    $mapped[] = $scored['kategori'];
                    break;
            }
        }
        $mapped[] = $this->standarLabel;
        return $mapped;
    }
}