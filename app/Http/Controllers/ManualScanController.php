<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\Process\Process;

class ManualScanController extends Controller
{
    public function scan()
    {
        // Path absolut ke Python (sesuaikan dengan hasil (Get-Command python).Source)
        $pythonPath = 'C:\Users\ASUS\AppData\Local\Programs\Python\Python314\python.exe';
        
        // Working directory ke folder python-agent
        $workingDir = base_path('python-agent');
        
        // Jalankan agent.py dengan environment yang diwarisi dari sistem
        $process = new Process([$pythonPath, 'agent.py'], $workingDir);
        $process->setTimeout(120);
        $process->setEnv(array_merge(getenv(), [
            'PYTHONPATH' => $workingDir, // pastikan module ditemukan
        ]));
        $process->run();
        
        if (!$process->isSuccessful()) {
            return back()->with('error', 'Gagal menjalankan scan: ' . $process->getErrorOutput());
        }
        
        $output = $process->getOutput();
        preg_match('/\{.*\}/', $output, $matches);
        
        if (empty($matches)) {
            return back()->with('error', 'Output agent tidak valid: ' . $output);
        }
        
        $data = json_decode($matches[0], true);
        return view('scan-preview', ['scan' => $data]);
    }

    public function scanApi()
{
    $process = new Process(['python', 'agent.py'], base_path('python-agent'));
    $process->setTimeout(120);
    $process->run();

    if (!$process->isSuccessful()) {
        return response()->json(['error' => 'Gagal menjalankan scan: ' . $process->getErrorOutput()], 500);
    }

    $output = $process->getOutput();
    preg_match('/\{.*\}/', $output, $matches);

    if (empty($matches)) {
        return response()->json(['error' => 'Output agent tidak valid: ' . $output], 500);
    }

    $data = json_decode($matches[0], true);
    return response()->json($data);
}
}