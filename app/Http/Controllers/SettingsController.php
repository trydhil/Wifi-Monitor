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

        $exportSettings = [
            'format'  => 'xlsx',
            'prefix'  => 'NETRA_SCAN_',
            'columns' => ['tanggal','jam','interface','ssid','download','upload','ping','signal','score','kategori'],
        ];
        if (file_exists(storage_path('app/export_settings.json'))) {
            $exportSettings = array_merge($exportSettings, json_decode(file_get_contents(storage_path('app/export_settings.json')), true) ?? []);
        }

        return view('settings', compact('setting', 'exportSettings'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        // Validation rules
        $rules = [
            'name'               => 'sometimes|required|string|max:255',
            'new_password'       => 'nullable|string|min:6|confirmed',
            'weight_download'    => 'required|numeric|min:0|max:100',
            'weight_upload'      => 'required|numeric|min:0|max:100',
            'weight_ping'        => 'required|numeric|min:0|max:100',
            'weight_signal'      => 'required|numeric|min:0|max:100',
            'threshold_download' => 'required|numeric|min:0.1',
            'threshold_upload'   => 'required|numeric|min:0.1',
            'threshold_ping'     => 'required|numeric|min:0.1',
        ];

        $validated = $request->validate($rules);

        // Update profile name
        if ($request->filled('name')) {
            $user->update(['name' => $validated['name']]);
        }

        // Update password if typed
        if ($request->filled('new_password')) {
            $user->update(['password' => Hash::make($request->new_password)]);
        }

        // Save export settings
        $exportSettings = [
            'format'      => $request->input('export_format', 'xlsx'),
            'prefix'      => $request->input('export_prefix', 'NETRA_SCAN_'),
            'columns'     => $request->input('export_columns', ['tanggal','jam','interface','ssid','download','upload','ping','signal','score','kategori']),
            'auto_export' => $request->boolean('auto_export'),
        ];
        file_put_contents(storage_path('app/export_settings.json'), json_encode($exportSettings));

        // Bobot sum validation (expected decimal sum = 1.0 or 100%)
        $totalDecimal = $validated['weight_download'] + $validated['weight_upload']
                      + $validated['weight_ping']    + $validated['weight_signal'];

        if (abs($totalDecimal - 1.0) > 0.01) {
            $totalPct = round($totalDecimal * 100);
            return back()->withInput()
                ->withErrors(['weight_download' => "Total bobot harus = 100% (saat ini: {$totalPct}%)"]);
        }

        // Simpan desimal langsung ke storage
        CustomScoringSetting::current()->update([
            'weight_download'    => $validated['weight_download'],
            'weight_upload'      => $validated['weight_upload'],
            'weight_ping'        => $validated['weight_ping'],
            'weight_signal'      => $validated['weight_signal'],
            'threshold_download' => $validated['threshold_download'],
            'threshold_upload'   => $validated['threshold_upload'],
            'threshold_ping'     => $validated['threshold_ping'],
        ]);

        return back()->with('success', 'Konfigurasi berhasil disimpan.');
    }
}