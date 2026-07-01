<?php

namespace App\Http\Controllers;

use App\Models\CustomScoringSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SettingsController extends Controller
{
    public function index()
    {
        $setting = CustomScoringSetting::current();
        return view('settings', compact('setting'));
    }

    public function update(Request $request)
    {
        // ── Ganti password ────────────────────────────────────────────
        if ($request->filled('old_password')) {
            $request->validate([
                'old_password'          => 'required',
                'new_password'          => 'required|min:6|confirmed',
            ]);

            if (!Hash::check($request->old_password, auth()->user()->password)) {
                return back()->withErrors(['old_password' => 'Password lama tidak sesuai.'])->withInput();
            }

            auth()->user()->update(['password' => Hash::make($request->new_password)]);
            return back()->with('success', 'Password berhasil diubah.');
        }

        // ── Scoring config ────────────────────────────────────────────
        $validated = $request->validate([
            'weight_download'    => 'required|numeric|min:0|max:100',
            'weight_upload'      => 'required|numeric|min:0|max:100',
            'weight_ping'        => 'required|numeric|min:0|max:100',
            'weight_signal'      => 'required|numeric|min:0|max:100',
            'threshold_download' => 'required|numeric|min:0.1',
            'threshold_upload'   => 'required|numeric|min:0.1',
            'threshold_ping'     => 'required|numeric|min:0.1',
        ]);

        $total = $validated['weight_download'] + $validated['weight_upload']
               + $validated['weight_ping']    + $validated['weight_signal'];

        if (abs($total - 100) > 1) {
            return back()->withInput()
                ->withErrors(['weight_download' => "Total bobot harus = 100% (saat ini: {$total}%)"]);
        }

        // Konversi persen → desimal untuk storage
        CustomScoringSetting::current()->update([
            'weight_download'    => $validated['weight_download']    / 100,
            'weight_upload'      => $validated['weight_upload']      / 100,
            'weight_ping'        => $validated['weight_ping']        / 100,
            'weight_signal'      => $validated['weight_signal']      / 100,
            'threshold_download' => $validated['threshold_download'],
            'threshold_upload'   => $validated['threshold_upload'],
            'threshold_ping'     => $validated['threshold_ping'],
        ]);

        return back()->with('success', 'Konfigurasi berhasil disimpan.');
    }
}