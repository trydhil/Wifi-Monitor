<?php

namespace Tests\Feature;

use App\Models\Scan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ScanOtomatisTest extends TestCase
{
    use RefreshDatabase;

    #[\PHPUnit\Framework\Attributes\Test]
    public function data_scan_bisa_disimpan_ke_database()
    {
        Scan::create([
            'tanggal'  => now()->toDateString(),
            'jam'      => now()->toTimeString(),
            'ssid'     => 'WiFi-Kantor',
            'download' => 48.0,
            'upload'   => 12.0,
            'ping'     => 15,
            'signal'   => -55,
            'score'    => 82,
            'kategori' => 'Baik',
        ]);

        $this->assertDatabaseCount('scans', 1);
        $this->assertDatabaseHas('scans', ['ssid' => 'WiFi-Kantor']);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function data_yang_tersimpan_memiliki_semua_field_wajib()
    {
        $scan = Scan::factory()->create();

        $this->assertNotNull($scan->tanggal);
        $this->assertNotNull($scan->jam);
        $this->assertNotNull($scan->ssid);
        $this->assertNotNull($scan->score);
        $this->assertNotNull($scan->kategori);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function beberapa_scan_tersimpan_sebagai_record_terpisah()
    {
        Scan::factory()->count(3)->create();
        $this->assertDatabaseCount('scans', 3);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function kategori_sangat_baik_untuk_skor_90_ke_atas()
    {
        Scan::factory()->create(['score' => 95, 'kategori' => 'Sangat Baik']);
        $this->assertDatabaseHas('scans', ['score' => 95, 'kategori' => 'Sangat Baik']);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function kategori_baik_untuk_skor_75_sampai_89()
    {
        Scan::factory()->create(['score' => 80, 'kategori' => 'Baik']);
        $this->assertDatabaseHas('scans', ['score' => 80, 'kategori' => 'Baik']);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function kategori_cukup_untuk_skor_60_sampai_74()
    {
        Scan::factory()->create(['score' => 65, 'kategori' => 'Cukup']);
        $this->assertDatabaseHas('scans', ['score' => 65, 'kategori' => 'Cukup']);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function kategori_buruk_untuk_skor_di_bawah_60()
    {
        Scan::factory()->create(['score' => 40, 'kategori' => 'Buruk']);
        $this->assertDatabaseHas('scans', ['score' => 40, 'kategori' => 'Buruk']);
    }
}
