<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->string('jenis', 20)->default('berita')->after('judul');
            $table->timestamp('submitted_at')->nullable()->after('status');
            $table->index(['jenis', 'status', 'published_at']);
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropIndex(['jenis', 'status', 'published_at']);
            $table->dropColumn(['jenis', 'submitted_at']);
        });
    }
};
