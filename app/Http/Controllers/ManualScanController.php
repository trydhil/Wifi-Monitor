<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\Process\Process;

class ManualScanController extends Controller
{
    private function runAgent()
    {
        $pythonPath = 'C:\\Users\\ASUS\\AppData\\Local\\Programs\\Python\\Python314\\python.exe';
        $workingDir = base_path('python-agent');
        
        $process = new Process([$pythonPath, 'agent.py'], $workingDir);
        $process->setTimeout(120);
        $process->setEnv(array_merge(getenv(), [
            'PYTHONPATH' => $workingDir,
        ]));
        $process->run();
        
        if (!$process->isSuccessful()) {
            return ['error' => 'Gagal menjalankan scan: ' . $process->getErrorOutput()];
        }
        
        $output = $process->getOutput();
        preg_match('/\{.*\}/', $output, $matches);
        
        if (empty($matches)) {
            return ['error' => 'Output agent tidak valid: ' . $output];
        }
        
        return json_decode($matches[0], true);
    }

    public function scan()
    {
        $data = $this->runAgent();
        if (isset($data['error'])) {
            return back()->with('error', $data['error']);
        }
        return view('scan-preview', ['scan' => $data]);
    }

    public function scanApi()
    {
        $data = $this->runAgent();
        if (isset($data['error'])) {
            return response()->json(['error' => $data['error']], 500);
        }
        return response()->json($data);
    }
}