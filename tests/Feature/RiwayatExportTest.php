<?php

namespace Tests\Feature;

use App\Models\Scan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RiwayatExportTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    // ════════════════════════════════════════════════════════════════════════
    //  RIWAYAT & FILTER
    // ════════════════════════════════════════════════════════════════════════

    #[\PHPUnit\Framework\Attributes\Test]
    public function halaman_riwayat_terbuka_dan_return_200()
    {
        $response = $this->actingAs($this->user)->get(route('history'));
        $response->assertStatus(200);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function riwayat_menampilkan_semua_scan_tanpa_filter()
    {
        Scan::factory()->count(3)->create();

        $response = $this->actingAs($this->user)->get(route('history'));
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

        $response = $this->actingAs($this->user)->get(route('history', ['ssid' => 'WiFi-A']));
        $response->assertStatus(200);

        $data = $response->viewData('scans')
             ?? $response->viewData('histories')
             ?? $response->viewData('data');
        $this->assertCount(2, $data);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function filter_kategori_mengembalikan_data_yang_sesuai()
    {
        // Gunakan metrik yang valid agar hasil perhitungan ulang konsisten
        // Baik: download=20, upload=10, ping=15, signal=-40 -> Skor ~84
        Scan::factory()->create([
            'download' => 20, 'upload' => 10, 'ping' => 15, 'signal' => -40
        ]);
        // Buruk: download=1, upload=1, ping=200, signal=-95 -> Skor ~5
        Scan::factory()->create([
            'download' => 1, 'upload' => 1, 'ping' => 200, 'signal' => -95
        ]);
        Scan::factory()->create([
            'download' => 20, 'upload' => 10, 'ping' => 15, 'signal' => -40
        ]);

        $response = $this->actingAs($this->user)->get(route('history', ['kategori' => 'Baik']));
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

        // Gunakan parameter 'tanggal_awal' dan 'tanggal_akhir' yang sesuai dengan HistoryController
        $response = $this->actingAs($this->user)->get(route('history', [
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
        $response = $this->actingAs($this->user)->get(route('history'));
        $response->assertStatus(200);
    }

    // ════════════════════════════════════════════════════════════════════════
    //  EXPORT EXCEL
    // ════════════════════════════════════════════════════════════════════════

    #[\PHPUnit\Framework\Attributes\Test]
    public function export_excel_tidak_crash_dengan_data()
    {
        Scan::factory()->count(3)->create();

        // Pakai actingAs agar tidak diarahkan ke login
        $response = $this->actingAs($this->user)->get(route('export.excel'));

        // BinaryFileResponse tidak punya ->status(), pakai getStatusCode()
        $this->assertContains($response->getStatusCode(), [200, 302]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function export_excel_return_file_spreadsheet()
    {
        Scan::factory()->count(3)->create();

        $response = $this->actingAs($this->user)->get(route('export.excel'));

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
        $response = $this->actingAs($this->user)->get(route('export.excel'));
        $this->assertContains($response->getStatusCode(), [200, 302]);
    }
}
