<?php

// database/migrations/..._create_jadwal_seleksis_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jadwal_seleksis', function (Blueprint $table) {
            $table->id();
            $table->string('judul_kegiatan');
            $table->dateTime('tanggal_mulai_pelaksanaan');
            $table->dateTime('tanggal_akhir_pelaksanaan');
            $table->string('lokasi_kegiatan');
            $table->string('nomor_surat_tugas');
            $table->string('kota_surat');
            $table->date('tanggal_surat');

            // Relasi ke tabel users
            $table->foreignId('id_penandatangan')->constrained('users');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal_seleksis');
    }
};
