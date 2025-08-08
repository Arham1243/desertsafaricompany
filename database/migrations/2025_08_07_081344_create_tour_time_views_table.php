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
        Schema::create('tour_time_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tour_time_id')->constrained('tour_times')->onDelete('cascade');
            $table->string('ip_address', 45);
            $table->date('view_date');
            $table->timestamps();
            $table->unique(['tour_time_id', 'ip_address', 'view_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tour_time_views');
    }
};
