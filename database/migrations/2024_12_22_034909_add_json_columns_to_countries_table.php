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
        Schema::table('countries', function (Blueprint $table) {
            $table->json('section_content')->nullable()->after('featured_image_alt_text');
            $table->json('popular_tours_ids')->nullable()->after('section_content');
            $table->json('best_tours_ids')->nullable()->after('section_content');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('countries', function (Blueprint $table) {
            $table->dropColumn(['section_content', 'popular_tours_ids', 'best_tours_ids']);
        });
    }
};
