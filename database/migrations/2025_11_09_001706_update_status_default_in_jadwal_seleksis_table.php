<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jadwal_seleksis', function (Blueprint $table) {
            // Ubah default jadi 'menunggu_nst' (menunggu nomor surat tugas)
            $table->string('status')->default('menunggu_nst')->change();
        });
    }
    public function down(): void
    {
        Schema::table('jadwal_seleksis', function (Blueprint $table) {
            $table->string('status')->default('menunggu')->change();
        });
    }
};
