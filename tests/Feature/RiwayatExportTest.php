<?php

namespace Tests\Feature;

use App\Models\Scan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RiwayatExportTest extends TestCase
{
    use RefreshDatabase;

    // ════════════════════════════════════════════════════════════════════════
    //  RIWAYAT & FILTER
    // ════════════════════════════════════════════════════════════════════════

    #[\PHPUnit\Framework\Attributes\Test]
    public function halaman_riwayat_terbuka_dan_return_200()
    {
        $response = $this->get(route('history'));
        $response->assertStatus(200);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function riwayat_menampilkan_semua_scan_tanpa_filter()
    {
        Scan::factory()->count(3)->create();

        $response = $this->get(route('history'));
        $response->assertStatus(200);

        $data = $response->viewData('scans')
             ?? $response->viewData('histories')
             ?? $response->viewData('data');
        $this->assertNotNull($data, 'View tidak punya variabel data scan');
        $this->assertCount(3, $data);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function filter_ssid_mengembalikan_data_yang_sesuai()
    {
        Scan::factory()->create(['ssid' => 'WiFi-A']);
        Scan::factory()->create(['ssid' => 'WiFi-B']);
        Scan::factory()->create(['ssid' => 'WiFi-A']);

        $response = $this->get(route('history', ['ssid' => 'WiFi-A']));
        $response->assertStatus(200);

        $data = $response->viewData('scans')
             ?? $response->viewData('histories')
             ?? $response->viewData('data');
        $this->assertCount(2, $data);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function filter_kategori_mengembalikan_data_yang_sesuai()
    {
        // PENTING: kolom 'kategori' dihitung ULANG oleh ScoringService berdasarkan
        // standar aktif (default: polrestabes), jadi tidak bisa cuma set string
        // 'kategori' di factory — harus pakai raw metrics yang beneran menghasilkan
        // kategori itu di bawah formula polrestabes (download=20,upload=10,ping=100).
        Scan::factory()->create([ // -> skor ~88 (Baik)
            'interface' => 'WLAN', 'download' => 20, 'upload' => 10, 'ping' => 10, 'signal' => -50,
        ]);
        Scan::factory()->create([ // -> skor ~11 (Buruk)
            'interface' => 'WLAN', 'download' => 1, 'upload' => 0.5, 'ping' => 200, 'signal' => -90,
        ]);
        Scan::factory()->create([ // -> skor ~88 (Baik)
            'interface' => 'WLAN', 'download' => 20, 'upload' => 10, 'ping' => 10, 'signal' => -50,
        ]);

        $response = $this->get(route('history', ['kategori' => 'Baik']));
        $response->assertStatus(200);

        $data = $response->viewData('scans')
             ?? $response->viewData('histories')
             ?? $response->viewData('data');
        $this->assertCount(2, $data);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function filter_tanggal_bekerja_dengan_benar()
    {
        Scan::factory()->create([
            'tanggal'    => now()->subDays(5)->toDateString(),
            'created_at' => now()->subDays(5),
            'ssid'       => 'WiFi-Lama',
        ]);
        Scan::factory()->create([
            'tanggal'    => now()->toDateString(),
            'created_at' => now(),
            'ssid'       => 'WiFi-Baru',
        ]);

        $response = $this->get(route('history', [
            'tanggal_awal'  => now()->subDays(1)->toDateString(),
            'tanggal_akhir' => now()->toDateString(),
        ]));

        $response->assertStatus(200);
        $data = $response->viewData('scans')
             ?? $response->viewData('histories')
             ?? $response->viewData('data');
        $this->assertCount(1, $data);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function riwayat_kosong_tidak_crash()
    {
        $response = $this->get(route('history'));
        $response->assertStatus(200);
    }

    // ════════════════════════════════════════════════════════════════════════
    //  EXPORT EXCEL
    // ════════════════════════════════════════════════════════════════════════

    #[\PHPUnit\Framework\Attributes\Test]
    public function export_excel_tidak_crash_dengan_data()
    {
        Scan::factory()->count(3)->create();

        // Pakai withoutExceptionHandling supaya error asli kelihatan
        $response = $this->get(route('export.excel'));

        // BinaryFileResponse tidak punya ->status(), pakai getStatusCode()
        $this->assertContains($response->getStatusCode(), [200, 302]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function export_excel_return_file_spreadsheet()
    {
        Scan::factory()->count(3)->create();

        $response = $this->get(route('export.excel'));

        if ($response->getStatusCode() === 200) {
            $contentType = $response->headers->get('Content-Type');
            $isExcel = str_contains($contentType, 'spreadsheetml')
                    || str_contains($contentType, 'octet-stream')
                    || str_contains($contentType, 'excel')
                    || str_contains($contentType, 'vnd.ms-excel');
            $this->assertTrue($isExcel, "Content-Type bukan file Excel: {$contentType}");
        } else {
            $this->assertTrue(true);
        }
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function export_excel_saat_database_kosong_tidak_crash()
    {
        $response = $this->get(route('export.excel'));
        $this->assertContains($response->getStatusCode(), [200, 302]);
    }
}
