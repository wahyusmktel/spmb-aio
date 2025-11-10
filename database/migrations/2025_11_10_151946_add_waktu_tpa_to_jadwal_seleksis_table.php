<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jadwal_seleksis', function (Blueprint $table) {
            // Durasi pengerjaan TPA dalam MENIT
            $table->integer('waktu_tpa_menit')->nullable()->after('status');
        });
    }
    public function down(): void
    {
        Schema::table('jadwal_seleksis', function (Blueprint $table) {
            $table->dropColumn('waktu_tpa_menit');
        });
    }
};
