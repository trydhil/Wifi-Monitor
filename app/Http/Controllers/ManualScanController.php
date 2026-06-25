<?php

namespace App\Http\Controllers;

use App\Services\ScoringService;
use Illuminate\Http\Request;
use Symfony\Component\Process\Process;

class ManualScanController extends Controller
{
    private function runAgent(): array
    {
        $pythonPath = config('services.python.path');
        $workingDir = base_path('python-agent');

        $process = new Process([$pythonPath, 'agent.py'], $workingDir);
        $process->setTimeout(120);
        $process->setEnv(array_merge(getenv(), ['PYTHONPATH' => $workingDir]));
        $process->run();

        if (!$process->isSuccessful()) {
            return ['error' => 'Gagal menjalankan scan: ' . $process->getErrorOutput()];
        }

        preg_match('/\{.*\}/', $process->getOutput(), $matches);

        if (empty($matches)) {
            return ['error' => 'Output agent tidak valid: ' . $process->getOutput()];
        }

        return json_decode($matches[0], true);
    }

    public function scan(Request $request)
    {
        // Simpan pilihan standar ke session kalau user ganti via form
        if ($request->filled('standar')) {
            $request->session()->put('scoring_standar', $request->standar);
        }

        $data = $this->runAgent();

        if (isset($data['error'])) {
            return back()->with('error', $data['error']);
        }

        // Recalculate skor pakai standar aktif dari session
        $scored = ScoringService::calculate(
            download:  $data['download']  ?? 0,
            upload:    $data['upload']    ?? 0,
            ping:      $data['ping']      ?? 0,
            signal:    $data['signal']    ?? null,
            interface: $data['interface'] ?? 'WLAN',
        );

        // Merge hasil recalculate ke data (override skor dari agent)
        $data = array_merge($data, $scored);

        return view('scan-preview', [
            'scan'      => $data,
            'standards' => ScoringService::allStandards(),
            'activeKey' => ScoringService::activeKey(),
        ]);
    }

    public function scanApi(Request $request)
    {
        if ($request->filled('standar')) {
            $request->session()->put('scoring_standar', $request->standar);
        }

        $data = $this->runAgent();

        if (isset($data['error'])) {
            return response()->json(['error' => $data['error']], 500);
        }

        $scored = ScoringService::calculate(
            download:  $data['download']  ?? 0,
            upload:    $data['upload']    ?? 0,
            ping:      $data['ping']      ?? 0,
            signal:    $data['signal']    ?? null,
            interface: $data['interface'] ?? 'WLAN',
        );

        return response()->json(array_merge($data, $scored));
    }
}