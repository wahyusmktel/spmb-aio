<?php

// database/migrations/..._create_penugasan_petugas_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penugasan_petugas', function (Blueprint $table) {
            $table->id();

            $table->foreignId('id_jadwal_seleksi')
                  ->constrained('jadwal_seleksis')
                  ->cascadeOnDelete(); // Jika jadwal dihapus, penugasan ikut terhapus

            $table->foreignId('id_guru')
                  ->constrained('gurus')
                  ->cascadeOnDelete(); // Jika guru dihapus, penugasan ikut terhapus

            $table->foreignId('id_referensi_tugas')
                  ->constrained('referensi_tugas')
                  ->cascadeOnDelete(); // Jika referensi dihapus, penugasan ikut terhapus

            $table->timestamps();

            // Tambahkan unique constraint agar 1 guru tidak bisa ditugaskan 2x
            // di jadwal yang sama (meskipun beda tugas)
            // Hapus ini jika 1 guru boleh punya banyak tugas di 1 jadwal.
            $table->unique(['id_jadwal_seleksi', 'id_guru']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penugasan_petugas');
    }
};
