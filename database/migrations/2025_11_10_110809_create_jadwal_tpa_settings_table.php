<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Ini adalah tabel pivot antara 'jadwal_seleksis' dan 'tpa_grup_soals'
        Schema::create('jadwal_tpa_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jadwal_seleksi_id')->constrained('jadwal_seleksis')->cascadeOnDelete();
            $table->foreignId('tpa_grup_soal_id')->constrained('tpa_grup_soals')->cascadeOnDelete();

            // Opsional: tentukan urutan pengerjaan
            $table->integer('urutan')->default(1);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_tpa_settings');
    }
};
