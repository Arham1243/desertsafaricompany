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
        Schema::table('tours', function (Blueprint $table) {
            $table->integer('enable_includes')->default(1);
            $table->integer('enable_excludes')->default(1);
            $table->integer('enable_itinerary')->default(1);
            $table->integer('enable_plan_itinerary_experience')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tours', function (Blueprint $table) {
             $table->dropColumn('enable_includes');
             $table->dropColumn('enable_excludes');
             $table->dropColumn('enable_itinerary');
             $table->dropColumn('enable_plan_itinerary_experience');
        });
    }
};
