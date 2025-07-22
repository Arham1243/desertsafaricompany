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
        Schema::table('tour_pricings', function (Blueprint $table) {
            $table->boolean('promo_is_free')->default(false)->after('promo_title');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tour_pricings', function (Blueprint $table) {
            $table->dropColumn('promo_is_free');
        });
    }
};
