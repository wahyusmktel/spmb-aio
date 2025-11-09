<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('penugasan_petugas', function (Blueprint $table) {
            // Ubah nama 'kehadiran' jadi 'absensi_admin' (ini ceklis admin)
            $table->renameColumn('kehadiran', 'absensi_admin');

            // Tambah 2 kolom baru untuk absensi mandiri
            $table->timestamp('absensi_mandiri_at')->nullable()->after('id_referensi_tugas');
            $table->string('file_bukti_mandiri')->nullable()->after('absensi_mandiri_at');
        });
    }
    public function down(): void
    {
        Schema::table('penugasan_petugas', function (Blueprint $table) {
            $table->renameColumn('absensi_admin', 'kehadiran');
            $table->dropColumn(['absensi_mandiri_at', 'file_bukti_mandiri']);
        });
    }
};
