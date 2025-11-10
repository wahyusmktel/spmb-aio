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
        Schema::table('peserta_seleksis', function (Blueprint $table) {
            $table->boolean('status_tes_tpa')->default(false)->after('nilai_tes_buta_warna');
            $table->integer('nilai_tes_tpa')->nullable()->after('status_tes_tpa');
        });
    }
    public function down(): void
    {
        Schema::table('peserta_seleksis', function (Blueprint $table) {
            $table->dropColumn(['status_tes_tpa', 'nilai_tes_tpa']);
        });
    }
};
