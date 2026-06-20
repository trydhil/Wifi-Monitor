<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Scan extends Model
{
    protected $fillable = [
        'tanggal',
        'jam',
        'ssid',
        'download',
        'upload',
        'ping',
        'signal',
        'score',
        'kategori'
    ];
}