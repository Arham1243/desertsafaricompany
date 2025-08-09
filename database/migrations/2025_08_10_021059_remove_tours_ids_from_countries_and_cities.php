<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('countries', function (Blueprint $table) {
            if (Schema::hasColumn('countries', 'best_tours_ids')) {
                $table->dropColumn('best_tours_ids');
            }
            if (Schema::hasColumn('countries', 'popular_tours_ids')) {
                $table->dropColumn('popular_tours_ids');
            }
        });

        Schema::table('cities', function (Blueprint $table) {
            if (Schema::hasColumn('cities', 'best_tours_ids')) {
                $table->dropColumn('best_tours_ids');
            }
            if (Schema::hasColumn('cities', 'popular_tours_ids')) {
                $table->dropColumn('popular_tours_ids');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('countries', function (Blueprint $table) {
            $table->string('best_tours_ids')->nullable();
            $table->string('popular_tours_ids')->nullable();
        });

        Schema::table('cities', function (Blueprint $table) {
            $table->string('best_tours_ids')->nullable();
            $table->string('popular_tours_ids')->nullable();
        });
    }
};
