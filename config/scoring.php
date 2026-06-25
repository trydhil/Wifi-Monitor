<?php

/**
 * Konfigurasi 5 standar penilaian kualitas jaringan.
 * Bobot total harus = 1.0 untuk setiap standar.
 * Threshold: nilai maksimum yang dianggap "sempurna" per metrik.
 */

return [

    // ── 1. ITU-T G.1010 ─────────────────────────────────────────────────────
    'itu' => [
        'label'       => 'ITU-T G.1010',
        'deskripsi'   => 'Standar internasional kualitas jaringan end-user. Prioritas utama latency.',
        'weights'     => ['download' => 0.30, 'upload' => 0.20, 'ping' => 0.40, 'signal' => 0.10],
        'threshold'   => ['download' => 50,   'upload' => 20,   'ping' => 150],
    ],

    // ── 2. MOS / Telco ──────────────────────────────────────────────────────
    'mos' => [
        'label'       => 'MOS / Telco',
        'deskripsi'   => 'Mean Opinion Score — standar industri telekomunikasi. Fokus VoIP & suara.',
        'weights'     => ['download' => 0.15, 'upload' => 0.30, 'ping' => 0.50, 'signal' => 0.05],
        'threshold'   => ['download' => 50,   'upload' => 20,   'ping' => 150],
    ],

    // ── 3. Ookla / Speedtest ────────────────────────────────────────────────
    'ookla' => [
        'label'       => 'Ookla / Speedtest',
        'deskripsi'   => 'Formula berbasis Speedtest.net — umum dipakai untuk uji kecepatan internet.',
        'weights'     => ['download' => 0.35, 'upload' => 0.25, 'ping' => 0.40, 'signal' => 0.00],
        'threshold'   => ['download' => 100,  'upload' => 50,   'ping' => 150],
    ],

    // ── 4. Polrestabes / Layanan 110 ────────────────────────────────────────
    'polrestabes' => [
        'label'       => 'Polrestabes / Layanan 110',
        'deskripsi'   => 'Standar khusus layanan darurat kepolisian. Prioritas stabilitas & upload real-time.',
        'weights'     => ['download' => 0.15, 'upload' => 0.30, 'ping' => 0.45, 'signal' => 0.10],
        'threshold'   => ['download' => 20,   'upload' => 10,   'ping' => 100],
    ],

    // ── 5. Custom ────────────────────────────────────────────────────────────
    'custom' => [
        'label'       => 'Custom',
        'deskripsi'   => 'Bobot bebas ditentukan sendiri melalui halaman Pengaturan.',
        'weights'     => ['download' => 0.25, 'upload' => 0.25, 'ping' => 0.25, 'signal' => 0.25],
        'threshold'   => ['download' => 50,   'upload' => 20,   'ping' => 150],
    ],

];