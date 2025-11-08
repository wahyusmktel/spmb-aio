<?php

// database/migrations/..._create_referensi_akun_cbts_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('referensi_akun_cbts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('id_tahun_pelajaran')
                ->constrained('tahun_pelajarans')
                ->cascadeOnDelete();

            $table->string('username');
            $table->string('password'); // Akan kita HASH
            $table->boolean('status')->default(false);
            $table->timestamps();

            // Buat username unik per tahun pelajaran
            $table->unique(['id_tahun_pelajaran', 'username']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('referensi_akun_cbts');
    }
};
