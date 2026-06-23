<?php

namespace App\Exports;

use App\Models\Scan;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Http\Request;

class ScansExport implements FromQuery, WithHeadings, WithMapping
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function query()
    {
        $query = Scan::query();

        // Filter tanggal (sama seperti di history)
        if ($this->request->filled('tanggal_awal') && $this->request->filled('tanggal_akhir')) {
            $query->whereBetween('tanggal', [$this->request->tanggal_awal, $this->request->tanggal_akhir]);
        }

        if ($this->request->filled('ssid')) {
            $query->where('ssid', $this->request->ssid);
        }

        if ($this->request->filled('kategori')) {
            $query->where('kategori', $this->request->kategori);
        }

        return $query->orderBy('created_at', 'desc');
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Jam',
            'SSID',
            'Download (Mbps)',
            'Upload (Mbps)',
            'Ping (ms)',
            'Signal (dBm)',
            'Score',
            'Kategori'
        ];
    }

    public function map($row): array
    {
        return [
            $row->tanggal,
            $row->jam,
            $row->ssid,
            $row->download ?? 0,
            $row->upload ?? 0,
            $row->ping ?? 0,
            $row->signal ?? 0,
            $row->score ?? 0,
            $row->kategori ?? '-',
        ];
    }
}