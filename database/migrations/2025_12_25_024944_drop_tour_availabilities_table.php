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
        Schema::dropIfExists('tour_availabilities');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('tour_availabilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tour_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->unsignedInteger('capacity')->nullable();
            $table->unsignedInteger('booked')->default(0);
            $table->boolean('is_available')->default(true);
            $table->timestamps();

            $table->unique(['tour_id', 'date']);
        });
    }
};
