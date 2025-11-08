<?php

// database/migrations/..._create_tahun_pelajarans_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tahun_pelajarans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_tahun_pelajaran')->unique();
            $table->boolean('status')->default(false); // default false (Tidak Aktif)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tahun_pelajarans');
    }
};
