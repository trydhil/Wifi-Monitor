<?php

namespace Tests\Feature;

use App\Models\Scan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AutoExportTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Mock storage disk
        Storage::fake('local');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function command_auto_export_skips_when_disabled()
    {
        $settingsPath = storage_path('app/export_settings.json');
        
        // Mock settings with auto_export = false
        $settings = [
            'format'      => 'xlsx',
            'prefix'      => 'TEST_EXPORT_',
            'columns'     => ['tanggal', 'ssid', 'score'],
            'auto_export' => false,
        ];
        
        if (!file_exists(dirname($settingsPath))) {
            mkdir(dirname($settingsPath), 0777, true);
        }
        file_put_contents($settingsPath, json_encode($settings));

        // Create today's scan
        Scan::factory()->create(['tanggal' => now()->toDateString()]);

        // Run Artisan command
        $this->artisan('app:auto-export')
             ->expectsOutput('Daily Auto-export is disabled. Skipping.')
             ->assertExitCode(0);

        // Verify no file is stored in mock local storage
        $files = Storage::disk('local')->allFiles('exports');
        $this->assertEmpty($files);

        // Cleanup temp file
        @unlink($settingsPath);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function command_auto_export_runs_when_enabled()
    {
        $settingsPath = storage_path('app/export_settings.json');
        
        // Mock settings with auto_export = true
        $settings = [
            'format'      => 'xlsx',
            'prefix'      => 'TEST_EXPORT_',
            'columns'     => ['tanggal', 'ssid', 'score'],
            'auto_export' => true,
        ];
        
        if (!file_exists(dirname($settingsPath))) {
            mkdir(dirname($settingsPath), 0777, true);
        }
        file_put_contents($settingsPath, json_encode($settings));

        // Create today's scan
        Scan::factory()->create([
            'tanggal' => now()->toDateString(),
            'created_at' => now(),
        ]);

        // Run Artisan command
        $this->artisan('app:auto-export')
             ->expectsOutputToContain('Running Daily Auto-export...')
             ->expectsOutputToContain('Successfully exported')
             ->assertExitCode(0);

        // Verify file is stored in mock local storage
        $fileName = 'TEST_EXPORT_' . now()->toDateString() . '.xlsx';
        Storage::disk('local')->assertExists('exports/' . $fileName);

        // Cleanup temp file
        @unlink($settingsPath);
    }
}
