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
        Schema::table('cities', function (Blueprint $table) {
            $table->json('json_content')->nullable()->after('section_content');
        });

        Schema::table('countries', function (Blueprint $table) {
            $table->json('json_content')->nullable()->after('section_content');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('cities', function (Blueprint $table) {
            $table->dropColumn('json_content');
        });

        Schema::table('countries', function (Blueprint $table) {
            $table->dropColumn('json_content');
        });
    }
};
