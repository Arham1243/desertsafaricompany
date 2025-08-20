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
            $table->string('pricing_box_heading')->nullable()->after('regular_price');
            $table->boolean('pricing_box_heading_enabled')->default(false)->after('pricing_box_heading');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tours', function (Blueprint $table) {
            $table->dropColumn(['pricing_box_heading', 'pricing_box_heading_enabled']);
        });
    }
};
