<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private function dropForeignIfExists(string $table, string $constraint): void
    {
        $exists = DB::selectOne('
            SELECT CONSTRAINT_NAME 
            FROM information_schema.KEY_COLUMN_USAGE 
            WHERE TABLE_SCHEMA = DATABASE() 
              AND TABLE_NAME = ? 
              AND CONSTRAINT_NAME = ?
        ', [$table, $constraint]);

        if ($exists) {
            DB::statement("ALTER TABLE {$table} DROP FOREIGN KEY {$constraint}");
        }
    }

    public function up(): void
    {
        $this->dropForeignIfExists('blogs', 'blogs_top_highlighted_tour_id_foreign');
        $this->dropForeignIfExists('blogs', 'blogs_user_id_foreign');

        Schema::table('blogs', function (Blueprint $table) {
            if (Schema::hasColumn('blogs', 'top_highlighted_tour_id')) {
                $table->dropColumn('top_highlighted_tour_id');
            }
            if (Schema::hasColumn('blogs', 'user_id')) {
                $table->dropColumn('user_id');
            }
            if (Schema::hasColumn('blogs', 'featured_tours_ids')) {
                $table->dropColumn('featured_tours_ids');
            }
        });
    }

    public function down(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
            if (! Schema::hasColumn('blogs', 'top_highlighted_tour_id')) {
                $table->unsignedBigInteger('top_highlighted_tour_id')->nullable();
            }
            if (! Schema::hasColumn('blogs', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable();
            }
            if (! Schema::hasColumn('blogs', 'featured_tours_ids')) {
                $table->text('featured_tours_ids')->nullable();
            }
        });
    }
};
