<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('peserta_seleksis', function (Blueprint $table) {
            $table->timestamp('tpa_mulai_at')->nullable()->after('nilai_tes_tpa');
        });
    }
    public function down(): void
    {
        Schema::table('peserta_seleksis', function (Blueprint $table) {
            $table->dropColumn('tpa_mulai_at');
        });
    }
};
