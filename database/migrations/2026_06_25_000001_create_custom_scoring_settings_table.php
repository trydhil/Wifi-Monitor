<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('custom_scoring_settings', function (Blueprint $table) {
            $table->id();
            // Bobot (harus total = 1.0)
            $table->float('weight_download')->default(0.25);
            $table->float('weight_upload')->default(0.25);
            $table->float('weight_ping')->default(0.25);
            $table->float('weight_signal')->default(0.25);
            // Threshold "nilai sempurna" per metrik
            $table->float('threshold_download')->default(50);
            $table->float('threshold_upload')->default(20);
            $table->float('threshold_ping')->default(150);
            $table->timestamps();
        });

        // Seed 1 baris default supaya selalu ada row aktif (singleton pattern)
        \Illuminate\Support\Facades\DB::table('custom_scoring_settings')->insert([
            'weight_download'    => 0.25,
            'weight_upload'      => 0.25,
            'weight_ping'        => 0.25,
            'weight_signal'      => 0.25,
            'threshold_download' => 50,
            'threshold_upload'   => 20,
            'threshold_ping'     => 150,
            'created_at'         => now(),
            'updated_at'         => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('custom_scoring_settings');
    }
};
