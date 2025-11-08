<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jadwal_seleksis', function (Blueprint $table) {
            $table->string('file_hadir_peserta')->nullable()->after('id_penandatangan');
            $table->string('file_hadir_petugas')->nullable()->after('file_hadir_peserta');
            $table->string('file_berita_acara')->nullable()->after('file_hadir_petugas');
        });
    }

    public function down(): void
    {
        Schema::table('jadwal_seleksis', function (Blueprint $table) {
            $table->dropColumn(['file_hadir_peserta', 'file_hadir_petugas', 'file_berita_acara']);
        });
    }
};
