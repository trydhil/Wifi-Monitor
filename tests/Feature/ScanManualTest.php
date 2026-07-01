<?php

namespace Tests\Feature;

use App\Models\Scan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ScanManualTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function halaman_scan_manual_dapat_diakses()
    {
        \Illuminate\Support\Facades\Http::fake([
            '*' => \Illuminate\Support\Facades\Http::response([
                'ssid' => 'WiFi-Test', 'download' => 48.5,
                'upload' => 12.3, 'ping' => 14, 'signal' => -55,
                'interface' => 'WLAN',
            ], 200),
        ]);

        $response = $this->actingAs($this->user)->get(route('scan.manual'));
        $response->assertStatus(200);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function scan_manual_tidak_menyimpan_data_ke_database()
    {
        \Illuminate\Support\Facades\Http::fake([
            '*' => \Illuminate\Support\Facades\Http::response([
                'ssid' => 'WiFi-Test', 'download' => 30, 'upload' => 10,
                'ping' => 20, 'signal' => -60,
                'interface' => 'WLAN',
            ], 200),
        ]);

        $this->actingAs($this->user)->get(route('scan.manual'));
        $this->assertDatabaseCount('scans', 0);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function scan_manual_menampilkan_hasil_ke_view()
    {
        \Illuminate\Support\Facades\Http::fake([
            '*' => \Illuminate\Support\Facades\Http::response([
                'ssid' => 'WiFi-Kantor', 'download' => 50, 'upload' => 20,
                'ping' => 10, 'signal' => -45,
                'interface' => 'WLAN',
            ], 200),
        ]);

        $response = $this->actingAs($this->user)->get(route('scan.manual'));
        $response->assertStatus(200);

        // Cek salah satu nama variabel yang dipakai di controller
        $viewData = $response->viewData();
        $hasResult = isset($viewData['result'])
                  || isset($viewData['data'])
                  || isset($viewData['scan']);
        $this->assertTrue($hasResult, 'View tidak punya variabel hasil scan (result/data/scan)');
    }
}
