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
        Schema::create('kegiatan', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->dateTime('mulai');
            $table->dateTime('selesai')->nullable();
            $table->boolean('seharian')->default(true);
            $table->string('lokasi')->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('warna', 20)->default('#1b3a5b');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kegiatan');
    }
};
