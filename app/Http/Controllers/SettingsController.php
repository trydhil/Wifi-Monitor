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

        // Bobot sum validation
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