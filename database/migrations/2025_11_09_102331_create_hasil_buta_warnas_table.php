<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hasil_buta_warnas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peserta_seleksi_id')->constrained('peserta_seleksis')->cascadeOnDelete();
            $table->foreignId('soal_buta_warna_id')->constrained('soal_buta_warnas')->cascadeOnDelete();
            $table->enum('jawaban_peserta', ['A', 'B', 'C', 'D', 'E']);
            $table->boolean('is_benar')->default(false); // Apakah jawabannya benar
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('hasil_buta_warnas');
    }
};
