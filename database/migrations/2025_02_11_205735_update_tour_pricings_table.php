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
            $table->dropColumn(['offer_expire_at', 'promo_price', 'discount_price']);

            $table->json('discount')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tour_pricings', function (Blueprint $table) {
            $table->timestamp('offer_expire_at')->nullable();
            $table->decimal('promo_price', 10, 2)->nullable();
            $table->decimal('discount_price', 10, 2)->nullable();

            $table->dropColumn('discount');
        });
    }
};
