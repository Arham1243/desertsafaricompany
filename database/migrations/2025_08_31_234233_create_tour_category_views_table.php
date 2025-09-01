<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tour_category_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('tour_categories')->onDelete('cascade');
            $table->string('ip_address', 45);
            $table->date('view_date');
            $table->integer('start_count')->default(252);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tour_category_views');
    }
};
