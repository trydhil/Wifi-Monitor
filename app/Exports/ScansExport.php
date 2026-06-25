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
        return [
            'Tanggal',
            'Jam',
            'Interface',
            'SSID',
            'Download (Mbps)',
            'Upload (Mbps)',
            'Ping (ms)',
            'Signal (dBm)',
            'Score',
            'Kategori',
            'Standar Penilaian',   // ← kolom info standar yang dipakai
        ];
    }

    public function map($row): array
    {
        // Recalculate skor pakai standar aktif
        $scored = ScoringService::calculate(
            download:   $row->download  ?? 0,
            upload:     $row->upload    ?? 0,
            ping:       $row->ping      ?? 0,
            signal:     $row->signal    ?? null,
            interface:  $row->interface ?? 'WLAN',
            standarKey: $this->standarKey,
        );

        return [
            $row->tanggal,
            $row->jam,
            strtoupper($row->interface ?? 'WLAN'),
            $row->ssid ?? 'Ethernet',
            $row->download ?? 0,
            $row->upload   ?? 0,
            $row->ping     ?? 0,
            $row->signal   ?? '-',
            $scored['score'],
            $scored['kategori'],
            $this->standarLabel,
        ];
    }
}