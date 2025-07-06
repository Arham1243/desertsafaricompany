<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('tour_pricings', function (Blueprint $table) {
            $table->json('promo_addons')->nullable();
        });
    }

    public function down()
    {
        Schema::table('tour_pricings', function (Blueprint $table) {
            $table->dropColumn('promo_addons');
        });
    }
};
