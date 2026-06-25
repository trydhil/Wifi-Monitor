<?php

namespace App\Services;

class ScoringService
{
    /**
     * Ambil config standar yang sedang aktif dari session.
     * Default: 'polrestabes' karena konteks Polrestabes.
     */
    public static function activeKey(): string
    {
        return session('scoring_standar', 'polrestabes');
    }

    public static function activeConfig(): array
    {
        $key = self::activeKey();
        return config("scoring.{$key}", config('scoring.polrestabes'));
    }

    public static function activeLabel(): string
    {
        return self::activeConfig()['label'];
    }

    /**
     * Hitung ulang skor dari raw data menggunakan standar tertentu.
     *
     * @param float      $download  Mbps
     * @param float      $upload    Mbps
     * @param float      $ping      ms
     * @param int|null   $signal    dBm (null untuk LAN)
     * @param string     $interface 'WLAN' | 'LAN'
     * @param string|null $standarKey  null = pakai standar aktif dari session
     */
    public static function calculate(
        float $download,
        float $upload,
        float $ping,
        ?int $signal,
        string $interface = 'WLAN',
        ?string $standarKey = null
    ): array {
        $key    = $standarKey ?? self::activeKey();
        $config = config("scoring.{$key}", config('scoring.polrestabes'));

        $w  = $config['weights'];
        $th = $config['threshold'];

        // Skor per metrik (0–100)
        $sDownload = min($download / $th['download'] * 100, 100);
        $sUpload   = min($upload   / $th['upload']   * 100, 100);
        $sPing     = max(0, 100 - ($ping / $th['ping'] * 100));

        // Signal hanya relevan untuk WLAN
        if ($interface === 'WLAN' && $signal !== null && $w['signal'] > 0) {
            $sSignal = max(0, 100 - (abs($signal + 100) * 1.5));
        } else {
            // LAN: redistribut bobot signal ke ping
            $sSignal       = 0;
            $w['ping']    += $w['signal'];
            $w['signal']   = 0;
        }

        $score = round(
            $sDownload * $w['download'] +
            $sUpload   * $w['upload']   +
            $sPing     * $w['ping']     +
            $sSignal   * $w['signal']
        );

        return [
            'score'    => $score,
            'kategori' => self::kategori($score),
            'standar'  => $config['label'],
        ];
    }

    public static function kategori(int $score): string
    {
        return match (true) {
            $score >= 90 => 'Sangat Baik',
            $score >= 75 => 'Baik',
            $score >= 60 => 'Cukup',
            default      => 'Buruk',
        };
    }

    /** Semua standar untuk dropdown */
    public static function allStandards(): array
    {
        return collect(config('scoring'))
            ->map(fn($v, $k) => ['key' => $k, 'label' => $v['label'], 'deskripsi' => $v['deskripsi']])
            ->values()
            ->toArray();
    }
}