<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\Process\Process;

class ManualScanController extends Controller
{
    public function scan()
    {
        // Jalankan agent.py di dalam folder python-agent
$process = new Process(['python', 'agent.py'], base_path('python-agent'));
        $process->setTimeout(120);
        $process->run();
        
        if (!$process->isSuccessful()) {
            return back()->with('error', 'Gagal menjalankan scan: ' . $process->getErrorOutput());
        }
        
        $output = $process->getOutput();
        preg_match('/\{.*\}/', $output, $matches);
        
        if (empty($matches)) {
            // Tampilkan output mentah untuk debugging (bisa dihapus nanti)
            return back()->with('error', 'Output agent tidak valid: ' . $output);
        }
        
        $data = json_decode($matches[0], true);
        
        return view('scan-preview', ['scan' => $data]);
    }
}