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
        Schema::create('dosen', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('slug')->unique();
            $table->string('nip')->nullable();
            $table->string('foto')->nullable();
            // Guru Besar, Dosen Tetap Prodi, Dosen MKDU, Dosen Luar Biasa
            $table->enum('kategori', ['guru_besar', 'tetap_prodi', 'mkdu', 'luar_biasa']);
            $table->string('konsentrasi')->nullable(); // Keuangan / Pemasaran / SDM / MKDU
            $table->string('bio_link')->nullable();
            $table->unsignedInteger('urutan')->default(0);
            $table->timestamps();

            $table->index(['kategori', 'urutan']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dosen');
    }
};
