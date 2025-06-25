<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tour_views', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tour_id');
            $table->string('ip_address', 45);
            $table->date('view_date');
            $table->timestamps();
            $table->unique(['tour_id', 'ip_address', 'view_date']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('tour_views');
    }
};
