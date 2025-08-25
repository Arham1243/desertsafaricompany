<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('social_token');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->text('social_token')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('social_token');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('social_token', 255)->nullable();
        });
    }
};
