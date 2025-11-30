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
        Schema::create('schemas', function (Blueprint $table) {
            $table->id();
            $table->string('entity_type')->nullable(); // 'tours', 'pages', 'blogs', 'news', 'tour-categories', 'cities', 'countries', 'listing'
            $table->string('entity_id')->nullable(); // ID of the entity, or 'listing' for listing pages, or specific listing type like 'blogs-listing'
            $table->string('schema_type')->nullable(); // For tours: 'boat', 'bus', 'inner', 'water'
            $table->longText('schema_json'); // The actual schema JSON data
            $table->timestamps();
            
            // Composite unique index to ensure one schema per entity/type combination
            $table->unique(['entity_type', 'entity_id', 'schema_type'], 'unique_schema_entity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schemas');
    }
};
