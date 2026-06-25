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

        // Cache data mentah supaya ganti standar nanti tidak perlu scan ulang
        $request->session()->put('last_scan_raw', $data);

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
            'scan'        => $data,
            'standards'   => ScoringService::allStandards(),
            'activeKey'   => ScoringService::activeKey(),
            'comparisons' => $this->compareAllStandards($data),
        ]);
    }

    /**
     * Ganti standar penilaian TANPA menjalankan scan ulang.
     * Pakai data mentah scan terakhir yang di-cache di session,
     * cuma hitung ulang skornya pakai standar baru.
     */
    public function setStandar(Request $request)
    {
        if ($request->filled('standar')) {
            $request->session()->put('scoring_standar', $request->standar);
        }

        $raw = $request->session()->get('last_scan_raw');

        // Belum pernah scan sama sekali -> jalankan scan pertama kali
        if (!$raw) {
            return $this->scan($request);
        }

        $scored = ScoringService::calculate(
            download:  $raw['download']  ?? 0,
            upload:    $raw['upload']    ?? 0,
            ping:      $raw['ping']      ?? 0,
            signal:    $raw['signal']    ?? null,
            interface: $raw['interface'] ?? 'WLAN',
        );

        $data = array_merge($raw, $scored);

        return view('scan-preview', [
            'scan'        => $data,
            'standards'   => ScoringService::allStandards(),
            'activeKey'   => ScoringService::activeKey(),
            'comparisons' => $this->compareAllStandards($raw),
        ]);
    }

    /**
     * Hitung skor data scan yang sama di SEMUA standar sekaligus,
     * untuk ditampilkan sebagai tabel perbandingan.
     */
    private function compareAllStandards(array $raw): array
    {
        return collect(ScoringService::allStandards())
            ->map(function ($std) use ($raw) {
                $result = ScoringService::calculate(
                    download:   $raw['download']  ?? 0,
                    upload:     $raw['upload']    ?? 0,
                    ping:       $raw['ping']      ?? 0,
                    signal:     $raw['signal']    ?? null,
                    interface:  $raw['interface'] ?? 'WLAN',
                    standarKey: $std['key'],
                );

                return [
                    'key'      => $std['key'],
                    'label'    => $std['label'],
                    'score'    => $result['score'],
                    'kategori' => $result['kategori'],
                ];
            })
            ->sortByDesc('score')
            ->values()
            ->toArray();
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
