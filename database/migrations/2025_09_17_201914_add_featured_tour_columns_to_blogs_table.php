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
        Schema::table('blogs', function (Blueprint $table) {
            $table->unsignedBigInteger('top_featured_tour_id')->nullable()->after('id');
            $table->unsignedBigInteger('bottom_featured_tour_id')->nullable()->after('top_featured_tour_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
            $table->dropColumn(['top_featured_tour_id', 'bottom_featured_tour_id']);
        });
    }
};
