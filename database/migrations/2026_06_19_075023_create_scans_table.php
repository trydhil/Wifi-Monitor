<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('scans', function (Blueprint $table) {
        $table->id();
        $table->date('tanggal');
        $table->time('jam');
        $table->string('ssid');
        $table->float('download')->nullable();   // dalam Mbps
        $table->float('upload')->nullable();     // dalam Mbps
        $table->float('ping')->nullable();       // dalam ms
        $table->integer('signal')->nullable();   // dalam dBm (negatif)
        $table->integer('score')->nullable();    // skor 0-100
        $table->string('kategori')->nullable();  // Sangat Baik, Baik, Cukup, Buruk
        $table->timestamps(); // otomatis buat created_at & updated_at
    });
}

    public function down(): void
    {
        Schema::dropIfExists('scans');
    }
};
