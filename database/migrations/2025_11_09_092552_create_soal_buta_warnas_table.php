<?php

// ...
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('soal_buta_warnas', function (Blueprint $table) {
            $table->id();
            $table->string('gambar_soal'); // Path ke file gambar (cth: soal/ishihara-1.jpg)

            // Opsi jawaban
            $table->string('pilihan_a');
            $table->string('pilihan_b');
            $table->string('pilihan_c');
            $table->string('pilihan_d')->nullable();
            $table->string('pilihan_e')->nullable();

            // Kunci Jawaban (A, B, C, D, atau E)
            $table->enum('jawaban_benar', ['A', 'B', 'C', 'D', 'E']);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('soal_buta_warnas');
    }
};
