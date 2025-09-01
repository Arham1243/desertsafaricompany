<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('news', function (Blueprint $table) {
            $table->text('short_description')->nullable()->before('content');
        });

        Schema::table('blogs', function (Blueprint $table) {
            $table->text('short_description')->nullable()->before('content');
        });
    }

    public function down(): void
    {
        Schema::table('news', function (Blueprint $table) {
            $table->dropColumn('short_description');
        });

        Schema::table('blogs', function (Blueprint $table) {
            $table->dropColumn('short_description');
        });
    }
};
