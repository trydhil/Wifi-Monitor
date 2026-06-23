<?php

namespace App\Http\Controllers;

use App\Models\Scan;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Scan::query();

        // Filter tanggal (range)
        if ($request->filled('tanggal_awal') && $request->filled('tanggal_akhir')) {
            $query->whereBetween('tanggal', [$request->tanggal_awal, $request->tanggal_akhir]);
        }

        // Filter SSID
        if ($request->filled('ssid')) {
            $query->where('ssid', $request->ssid);
        }

        // Filter Kategori
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        // Urutkan dari terbaru
        $scans = $query->orderBy('created_at', 'desc')->paginate(15);

        // Ambil daftar SSID unik untuk dropdown filter
        $ssidList = Scan::distinct()->pluck('ssid');

        return view('history', compact('scans', 'ssidList'));
    }
}