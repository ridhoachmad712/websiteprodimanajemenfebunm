<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('berita_eksternal', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('sumber');            // nama media, mis. Tribun Timur
            $table->string('url');               // tautan artikel asli
            $table->date('tanggal')->nullable(); // tanggal terbit di media
            $table->text('ringkasan')->nullable();
            $table->string('status')->default('published'); // published | draft
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('berita_eksternal');
    }
};
