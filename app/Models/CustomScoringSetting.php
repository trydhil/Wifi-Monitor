<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomScoringSetting extends Model
{
    protected $fillable = [
        'weight_download',
        'weight_upload',
        'weight_ping',
        'weight_signal',
        'threshold_download',
        'threshold_upload',
        'threshold_ping',
    ];

    /**
     * Selalu ambil baris pertama (singleton). Kalau belum ada, buat default.
     */
    public static function current(): self
    {
        return self::first() ?? self::create([
            'weight_download'    => 0.25,
            'weight_upload'      => 0.25,
            'weight_ping'        => 0.25,
            'weight_signal'      => 0.25,
            'threshold_download' => 50,
            'threshold_upload'   => 20,
            'threshold_ping'     => 150,
        ]);
    }

    public function toScoringConfig(): array
    {
        return [
            'label'     => 'Custom',
            'deskripsi' => 'Bobot bebas ditentukan sendiri melalui halaman Pengaturan.',
            'weights'   => [
                'download' => $this->weight_download,
                'upload'   => $this->weight_upload,
                'ping'     => $this->weight_ping,
                'signal'   => $this->weight_signal,
            ],
            'threshold' => [
                'download' => $this->threshold_download,
                'upload'   => $this->threshold_upload,
                'ping'     => $this->threshold_ping,
            ],
        ];
    }
}
