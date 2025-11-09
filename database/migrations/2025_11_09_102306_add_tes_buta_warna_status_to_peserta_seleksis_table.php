<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('peserta_seleksis', function (Blueprint $table) {
            // Status: 0=Belum Mengerjakan, 1=Sudah Mengerjakan
            $table->boolean('status_tes_buta_warna')->default(false)->after('kehadiran');
            // Nilai akhir (misal: 100, 90, 80)
            $table->integer('nilai_tes_buta_warna')->nullable()->after('status_tes_buta_warna');
        });
    }
    public function down(): void
    {
        Schema::table('peserta_seleksis', function (Blueprint $table) {
            $table->dropColumn(['status_tes_buta_warna', 'nilai_tes_buta_warna']);
        });
    }
};
