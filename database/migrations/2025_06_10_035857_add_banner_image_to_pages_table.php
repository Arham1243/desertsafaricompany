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
        Schema::table('pages', function (Blueprint $table) {
            $table->string('banner_image')->nullable()->after('content');
            $table->string('banner_image_alt_text')->nullable()->after('banner_image');
            $table->boolean('show_page_builder_sections')->default(0)->after('banner_image');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn(['banner_image', 'banner_image_alt_text', 'show_page_builder_sections']);
        });
    }
};
