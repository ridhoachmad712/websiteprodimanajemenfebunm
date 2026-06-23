<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prestasi', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('kategori')->default('mahasiswa'); // mahasiswa | dosen | prodi
            $table->string('tingkat')->nullable();            // lokal | regional | nasional | internasional
            $table->string('peraih')->nullable();             // nama mahasiswa/dosen
            $table->string('penyelenggara')->nullable();
            $table->date('tanggal');
            $table->string('gambar')->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('status')->default('published');   // published | draft
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prestasi');
    }
};
