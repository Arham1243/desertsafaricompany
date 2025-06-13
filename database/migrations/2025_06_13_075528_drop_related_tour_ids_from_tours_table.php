<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('tours', function (Blueprint $table) {
            $table->dropColumn('related_tour_ids');
        });
    }

    public function down()
    {
        Schema::table('tours', function (Blueprint $table) {
            $table->json('related_tour_ids')->nullable();
        });
    }
};
