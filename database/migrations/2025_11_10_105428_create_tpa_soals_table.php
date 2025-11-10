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
        Schema::create('tpa_soals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tpa_grup_soal_id')->constrained('tpa_grup_soals')->cascadeOnDelete();

            $table->text('pertanyaan_teks'); // Untuk soal dalam bentuk teks
            $table->string('gambar_soal')->nullable(); // Jika soalnya pakai gambar

            $table->text('pilihan_a');
            $table->text('pilihan_b');
            $table->text('pilihan_c');
            $table->text('pilihan_d');
            $table->text('pilihan_e'); // Kita buat 5 pilihan (TEXT agar muat panjang)

            $table->enum('jawaban_benar', ['A', 'B', 'C', 'D', 'E']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tpa_soals');
    }
};
