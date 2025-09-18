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
        Schema::table('tour_authors', function (Blueprint $table) {
            $table->string('profile_image')->nullable();
            $table->string('profile_image_alt_text')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tour_authors', function (Blueprint $table) {
            $table->dropColumn('profile_image');
            $table->dropColumn('profile_image_alt_text');
        });
    }
};
