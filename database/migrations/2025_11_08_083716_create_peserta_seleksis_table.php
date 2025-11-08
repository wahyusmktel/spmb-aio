<?php

// database/migrations/..._create_peserta_seleksis_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('peserta_seleksis', function (Blueprint $table) {
            $table->id();

            $table->foreignId('id_jadwal_seleksi')
                ->constrained('jadwal_seleksis')
                ->cascadeOnDelete();

            // Akun CBT harus unik. 1 akun = 1 peserta.
            $table->foreignId('id_akun_cbt')
                ->constrained('referensi_akun_cbts')
                ->onDelete('restrict') // Jangan hapus akun jika masih dipakai
                ->unique();

            $table->string('nomor_pendaftaran');
            $table->string('nama_pendaftar');
            $table->string('nomor_telepon');
            $table->timestamps();

            // Nomor pendaftaran harus unik per jadwal
            $table->unique(['id_jadwal_seleksi', 'nomor_pendaftaran']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peserta_seleksis');
    }
};
