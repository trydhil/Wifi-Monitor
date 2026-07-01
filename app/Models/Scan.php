<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tanggal',
        'jam',
        'interface',
        'ssid',
        'download',
        'upload',
        'ping',
        'signal',
        'score',
        'kategori',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function getActiveConnection()
    {
        return \Illuminate\Support\Facades\Cache::remember('active_network_info', 15, function () {
            try {
                $pythonPath = config('services.python.path', 'python');
                $workingDir = base_path('python-agent');
                $process = new \Symfony\Component\Process\Process([$pythonPath, 'network.py'], $workingDir);
                $process->run();
                if ($process->isSuccessful()) {
                    $output = $process->getOutput();
                    preg_match('/Interface\s*:\s*(.*)/', $output, $ifaceMatch);
                    preg_match('/SSID\/Name\s*:\s*(.*)/', $output, $ssidMatch);
                    preg_match('/Signal\s*:\s*(.*)/', $output, $signalMatch);

                    $interface = isset($ifaceMatch[1]) ? trim($ifaceMatch[1]) : null;
                    $ssid = isset($ssidMatch[1]) ? trim($ssidMatch[1]) : null;
                    $signalStr = isset($signalMatch[1]) ? trim($signalMatch[1]) : null;

                    $signal = null;
                    if ($signalStr && preg_match('/(-?\d+)/', $signalStr, $numMatch)) {
                        $signal = (int)$numMatch[1];
                    }

                    if ($interface) {
                        return [
                            'interface' => $interface,
                            'ssid' => $ssid,
                            'signal' => $signal
                        ];
                    }
                }
            } catch (\Throwable $e) {}
            return null;
        });
    }
}