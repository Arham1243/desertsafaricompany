<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::dropIfExists('tour_time_views');
        Schema::dropIfExists('tour_category_tour_time');
        Schema::dropIfExists('tour_times');
    }

    public function down()
    {
        Schema::create('tour_times', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });

        Schema::create('tour_category_tour_time', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });

        Schema::create('tour_time_views', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });
    }
};
