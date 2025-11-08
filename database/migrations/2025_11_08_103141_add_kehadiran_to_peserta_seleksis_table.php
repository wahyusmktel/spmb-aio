<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('peserta_seleksis', function (Blueprint $table) {
            $table->boolean('kehadiran')->default(false)->after('id_akun_cbt');
        });
    }
    public function down(): void
    {
        Schema::table('peserta_seleksis', function (Blueprint $table) {
            $table->dropColumn('kehadiran');
        });
    }
};
