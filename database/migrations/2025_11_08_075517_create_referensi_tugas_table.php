<?php

// database/migrations/..._create_referensi_tugas_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('referensi_tugas', function (Blueprint $table) {
            $table->id();
            $table->text('deskripsi_tugas');
            $table->boolean('status')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('referensi_tugas');
    }
};
