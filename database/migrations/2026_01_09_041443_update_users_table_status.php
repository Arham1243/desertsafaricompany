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
        Schema::table('users', function (Blueprint $table) {
            // Drop the old column
            if (Schema::hasColumn('users', 'is_active')) {
                $table->dropColumn('is_active');
            }

            // Add new column with default 'active'
            $table->string('status')->default('active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop the new column
            $table->dropColumn('status');

            // Re-add old column
            $table->boolean('is_active')->default(1);
        });
    }
};
