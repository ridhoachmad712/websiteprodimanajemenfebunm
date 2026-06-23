<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seminars', function (Blueprint $table) {
            $table->id();
            $table->string('nama');                 // nama mahasiswa
            $table->string('nim')->nullable();
            $table->text('judul');                  // judul tugas akhir/skripsi
            $table->string('jenis')->default('proposal'); // proposal | hasil | tutup
            $table->dateTime('tanggal');
            $table->string('tempat')->nullable();
            $table->string('pembimbing')->nullable();
            $table->string('penguji')->nullable();
            $table->string('status')->default('published'); // published | draft
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seminars');
    }
};
