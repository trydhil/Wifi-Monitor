<?php

namespace Tests\Feature;

use App\Models\CustomScoringSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomScoringConfigTest extends TestCase
{
    use RefreshDatabase;

    #[\PHPUnit\Framework\Attributes\Test]
    public function custom_scoring_config_is_loaded_from_database_dynamically()
    {
        // 1. Update data custom scoring setting di database
        CustomScoringSetting::current()->update([
            'weight_download'    => 0.70,
            'weight_upload'      => 0.10,
            'weight_ping'        => 0.10,
            'weight_signal'      => 0.10,
            'threshold_download' => 123.45,
            'threshold_upload'   => 45.67,
            'threshold_ping'     => 88.0,
        ]);

        // 2. Jalankan ulang method boot pada AppServiceProvider untuk menyimulasikan booting aplikasi dengan database yang sudah terisi
        $provider = new \App\Providers\AppServiceProvider($this->app);
        $provider->boot();

        // 3. Pastikan konfigurasi config('scoring.custom') ter-update sesuai data database
        $this->assertEquals(0.70, config('scoring.custom.weights.download'));
        $this->assertEquals(0.10, config('scoring.custom.weights.upload'));
        $this->assertEquals(123.45, config('scoring.custom.threshold.download'));
        $this->assertEquals(45.67, config('scoring.custom.threshold.upload'));
        $this->assertEquals(88.0, config('scoring.custom.threshold.ping'));
    }
}
