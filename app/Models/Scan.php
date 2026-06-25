<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scan extends Model
{
    use HasFactory;

    protected $fillable = [
        'tanggal',
        'jam',
        'interface',   // ← baru: 'WLAN' atau 'LAN'
        'ssid',
        'download',
        'upload',
        'ping',
        'signal',
        'score',
        'kategori',
    ];
}
