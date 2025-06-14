<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tour_pricings', function (Blueprint $table) {
            $table->dropColumn('discount');
        });
    }

    public function down(): void
    {
        Schema::table('tour_pricings', function (Blueprint $table) {
            $table->json('discount')->nullable();
        });
    }
};
