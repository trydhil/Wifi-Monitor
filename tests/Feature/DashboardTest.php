<?php

namespace Tests\Feature;

use App\Models\Scan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    #[\PHPUnit\Framework\Attributes\Test]
    public function dashboard_terbuka_dan_return_200()
    {
        $response = $this->get(route('dashboard'));
        $response->assertStatus(200);
        $response->assertViewIs('dashboard');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function dashboard_tidak_crash_saat_database_kosong()
    {
        $response = $this->get(route('dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Belum ada data');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function dashboard_menampilkan_ssid_scan_terakhir()
    {
        Scan::factory()->create(['ssid' => 'WiFi-Kantor', 'score' => 88]);

        $response = $this->get(route('dashboard'));
        $response->assertStatus(200);
        $response->assertSee('WiFi-Kantor');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function stat_card_total_scan_sesuai_jumlah_data()
    {
        Scan::factory()->count(5)->create();

        $response = $this->get(route('dashboard'));
        $response->assertStatus(200);
        $response->assertSee('5');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function kategori_scan_terakhir_tampil_di_dashboard()
    {
        Scan::factory()->create(['score' => 92, 'kategori' => 'Sangat Baik']);

        $response = $this->get(route('dashboard'));
        $response->assertSee('Sangat Baik');
    }
}
