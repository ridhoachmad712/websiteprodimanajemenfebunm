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
        Schema::table('dosen', function (Blueprint $table) {
            $table->string('link_scholar')->nullable()->after('bio_link');
            $table->string('link_sinta')->nullable()->after('link_scholar');
            $table->string('link_orcid')->nullable()->after('link_sinta');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dosen', function (Blueprint $table) {
            $table->dropColumn(['link_scholar', 'link_sinta', 'link_orcid']);
        });
    }
};
