<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('tours', function (Blueprint $table) {
            if (Schema::hasColumn('tours', 'tour_time_id')) {
                $table->dropColumn('tour_time_id');
            }
        });
    }

    public function down()
    {
        Schema::table('tours', function (Blueprint $table) {
            $table->foreignId('tour_time_id')->nullable()->constrained()->onDelete('set null');
        });
    }
};
