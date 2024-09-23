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
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255)->nullable();
            $table->string('slug', 255)->nullable();
            $table->text('content')->nullable();
            $table->string('featured_image')->nullable();
            $table->string('feature_image_alt_text')->nullable();
            $table->unsignedBigInteger('top_highlighted_tour_id')->nullable();
            $table->foreignId('category_id')->constrained('blog_categories')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['publish', 'draft'])->default('draft');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blogs');
    }
};