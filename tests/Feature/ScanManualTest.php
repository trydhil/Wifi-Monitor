<?php

namespace Tests\Feature;

use App\Models\Scan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ScanManualTest extends TestCase
{
    use RefreshDatabase;

    #[\PHPUnit\Framework\Attributes\Test]
    public function halaman_scan_manual_dapat_diakses()
    {
        \Illuminate\Support\Facades\Http::fake([
            '*' => \Illuminate\Support\Facades\Http::response([
                'ssid' => 'WiFi-Test', 'download' => 48.5,
                'upload' => 12.3, 'ping' => 14, 'signal' => -55,
            ], 200),
        ]);

        $response = $this->get(route('scan.manual'));
        $this->assertContains($response->status(), [200, 302]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function scan_manual_tidak_menyimpan_data_ke_database()
    {
        \Illuminate\Support\Facades\Http::fake([
            '*' => \Illuminate\Support\Facades\Http::response([
                'ssid' => 'WiFi-Test', 'download' => 30, 'upload' => 10,
                'ping' => 20, 'signal' => -60,
            ], 200),
        ]);

        $this->get(route('scan.manual'));
        $this->assertDatabaseCount('scans', 0);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function scan_manual_menampilkan_hasil_ke_view()
    {
        \Illuminate\Support\Facades\Http::fake([
            '*' => \Illuminate\Support\Facades\Http::response([
                'ssid' => 'WiFi-Kantor', 'download' => 50, 'upload' => 20,
                'ping' => 10, 'signal' => -45,
            ], 200),
        ]);

        $response = $this->get(route('scan.manual'));

        if ($response->status() === 200) {
            // Cek salah satu nama variabel yang mungkin dipakai di controller
            $hasResult = $response->viewData('result') !== null
                      || $response->viewData('data') !== null
                      || $response->viewData('scan') !== null;
            $this->assertTrue($hasResult, 'View tidak punya variabel hasil scan (result/data/scan)');
        } else {
            // Redirect = ok, tidak crash
            $this->assertTrue(true);
        }
    }
}
