<?php

namespace App\Http\Controllers;

use App\Models\CustomScoringSetting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $setting = CustomScoringSetting::current();

        return view('settings', compact('setting'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'weight_download'    => 'required|numeric|min:0|max:1',
            'weight_upload'      => 'required|numeric|min:0|max:1',
            'weight_ping'        => 'required|numeric|min:0|max:1',
            'weight_signal'      => 'required|numeric|min:0|max:1',
            'threshold_download' => 'required|numeric|min:0.1',
            'threshold_upload'   => 'required|numeric|min:0.1',
            'threshold_ping'     => 'required|numeric|min:0.1',
        ]);

        $totalWeight = $validated['weight_download'] + $validated['weight_upload']
            + $validated['weight_ping'] + $validated['weight_signal'];

        // Total bobot harus 1.0 (toleransi pembulatan kecil)
        if (abs($totalWeight - 1.0) > 0.01) {
            return back()
                ->withInput()
                ->withErrors(['weight_download' => "Total bobot harus = 100% (saat ini: " . round($totalWeight * 100) . "%)"]);
        }

        CustomScoringSetting::current()->update($validated);

        return back()->with('success', 'Bobot standar custom berhasil disimpan.');
    }
}
