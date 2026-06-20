<?php

namespace App\Http\Controllers;

use App\Models\Scan;
use Illuminate\Http\Request;

class ApiScanController extends Controller
{
    public function store(Request $request)
    {
        // Validasi data masuk
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'jam' => 'required|date_format:H:i:s',
            'ssid' => 'required|string|max:255',
            'download' => 'nullable|numeric',
            'upload' => 'nullable|numeric',
            'ping' => 'nullable|numeric',
            'signal' => 'nullable|integer',
            'score' => 'nullable|integer',
            'kategori' => 'nullable|string|max:50',
        ]);

        // Simpan ke database
        $scan = Scan::create($validated);

        // Respons sukses
        return response()->json([
            'status' => 'success',
            'message' => 'Data scan berhasil disimpan',
            'data' => $scan
        ], 201);
    }
}