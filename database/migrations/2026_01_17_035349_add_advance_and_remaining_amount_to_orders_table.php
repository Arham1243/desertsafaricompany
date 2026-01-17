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
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('advance_amount', 10, 2)->nullable()->after('total_amount');
            $table->decimal('remaining_amount', 10, 2)->nullable()->after('advance_amount');
            $table->decimal('paid_amount', 10, 2)->nullable()->after('remaining_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['advance_amount', 'remaining_amount', 'paid_amount']);
        });
    }
};
