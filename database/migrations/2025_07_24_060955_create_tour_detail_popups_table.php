<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tour_detail_popups', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('main_heading')->nullable();
            $table->string('popup_trigger_text')->nullable();
            $table->string('user_showing_text')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('inactive');
            $table->json('content')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tour_detail_popups');
    }
};
