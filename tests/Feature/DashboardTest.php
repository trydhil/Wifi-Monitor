<?php

namespace Tests\Feature;

use App\Models\Scan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function dashboard_terbuka_dan_return_200()
    {
        $response = $this->actingAs($this->user)->get(route('dashboard'));
        $response->assertStatus(200);
        $response->assertViewIs('dashboard');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function dashboard_tidak_crash_saat_database_kosong()
    {
        $response = $this->actingAs($this->user)->get(route('dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Belum ada data');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function dashboard_menampilkan_ssid_scan_terakhir()
    {
        Scan::factory()->create(['ssid' => 'WiFi-Kantor', 'score' => 88]);

        $response = $this->actingAs($this->user)->get(route('dashboard'));
        $response->assertStatus(200);
        $response->assertSee('WiFi-Kantor');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function stat_card_total_scan_sesuai_jumlah_data()
    {
        Scan::factory()->count(5)->create();

        $response = $this->actingAs($this->user)->get(route('dashboard'));
        $response->assertStatus(200);
        $response->assertSee('5');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function kategori_scan_terakhir_tampil_di_dashboard()
    {
        Scan::factory()->create(['score' => 92, 'kategori' => 'Sangat Baik']);

        $response = $this->actingAs($this->user)->get(route('dashboard'));
        $response->assertSee('Sangat Baik');
    }
}
