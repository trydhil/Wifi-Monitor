<?php

namespace Database\Factories;

use App\Models\Scan;
use Illuminate\Database\Eloquent\Factories\Factory;

class ScanFactory extends Factory
{
    protected $model = Scan::class;

    public function definition(): array
    {
        $download = $this->faker->randomFloat(1, 1, 100);
        $upload   = $this->faker->randomFloat(1, 0.5, 50);
        $ping     = $this->faker->numberBetween(5, 300);
        $signal   = $this->faker->numberBetween(-90, -30);
        $score    = $this->hitungSkor($download, $upload, $ping, $signal);
        $kategori = $this->hitungKategori($score);
        $waktu    = $this->faker->dateTimeBetween('-7 days', 'now');

        return [
            'tanggal'  => $waktu->format('Y-m-d'),   // ← wajib, NOT NULL
            'jam'      => $waktu->format('H:i:s'),   // ← wajib, NOT NULL
            'ssid'     => $this->faker->randomElement(['WiFi-Kantor', 'WiFi-Rumah', 'Hotspot-4G', 'FiberNet']),
            'download' => $download,
            'upload'   => $upload,
            'ping'     => $ping,
            'signal'   => $signal,
            'score'    => $score,
            'kategori' => $kategori,
            'created_at' => $waktu,
            'updated_at' => $waktu,
        ];
    }

    private function hitungSkor(float $download, float $upload, int $ping, int $signal): int
    {
        $sDownload = min(($download / 50) * 40, 40);
        $sUpload   = min(($upload / 20) * 20, 20);
        $sPing     = $ping <= 20  ? 25 : ($ping <= 50 ? 20 : ($ping <= 100 ? 10 : 0));
        $sSignal   = $signal >= -50 ? 15 : ($signal >= -65 ? 10 : ($signal >= -75 ? 5 : 0));

        return (int) round($sDownload + $sUpload + $sPing + $sSignal);
    }

    private function hitungKategori(int $score): string
    {
        return match (true) {
            $score >= 90 => 'Sangat Baik',
            $score >= 75 => 'Baik',
            $score >= 60 => 'Cukup',
            default      => 'Buruk',
        };
    }
}
