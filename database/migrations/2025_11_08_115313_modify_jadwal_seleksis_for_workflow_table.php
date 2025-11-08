<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jadwal_seleksis', function (Blueprint $table) {
            // 1. Ubah nomor_surat_tugas jadi NULLABLE
            $table->string('nomor_surat_tugas')->nullable()->change();

            // 2. Tambah kolom STATUS
            $table->string('status')->default('menunggu')->after('id_penandatangan');

            // 3. Tambah kolom siapa Staff TU yang menerbitkan (opsional tapi bagus)
            $table->foreignId('published_by_user_id')->nullable()->after('status')
                ->constrained('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('jadwal_seleksis', function (Blueprint $table) {
            $table->string('nomor_surat_tugas')->nullable(false)->change();
            $table->dropForeign(['published_by_user_id']);
            $table->dropColumn(['status', 'published_by_user_id']);
        });
    }
};
