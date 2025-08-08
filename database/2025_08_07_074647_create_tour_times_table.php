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
        Schema::create('tour_times', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('city_id')->nullable();
            $table->foreign(['city_id'])->references(['id'])->on('cities')->onUpdate('restrict')->onDelete('set null');
            $table->string('name')->nullable();
            $table->string('slug')->nullable();
            $table->json('tour_reviews_ids')->nullable();
            $table->text('long_description')->nullable();
            $table->integer('long_description_line_limit')->nullable();
            $table->json('json_content')->nullable();
            $table->text('section_content')->nullable();
            $table->enum('status', ['publish', 'draft'])->default('draft');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tour_time_types');
    }
};
