<?php

// database/migrations/xxxx_..._add_id_tahun_pelajaran_to_jadwal_seleksis_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jadwal_seleksis', function (Blueprint $table) {
            $table->foreignId('id_tahun_pelajaran')
                  ->after('id') // Posisikan setelah kolom id
                  ->constrained('tahun_pelajarans') // Relasi ke tabel tahun_pelajarans
                  ->cascadeOnDelete(); // Jika tahun pelajaran dihapus, jadwal ikut terhapus
        });
    }

    public function down(): void
    {
        Schema::table('jadwal_seleksis', function (Blueprint $table) {
            $table->dropForeign(['id_tahun_pelajaran']);
            $table->dropColumn('id_tahun_pelajaran');
        });
    }
};
