<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add marketplace fields to product_flat for homepage V2
        Schema::table('product_flat', function (Blueprint $table) {
            if (!Schema::hasColumn('product_flat', 'engine')) {
                $table->string('engine', 50)->nullable()->after('status')
                    ->comment('Game engine: Unity, Unreal, Godot, Phaser');
            }
            if (!Schema::hasColumn('product_flat', 'platform')) {
                $table->json('platform')->nullable()->after('engine')
                    ->comment('Target platforms: ["PC","Mobile","Console","Web"]');
            }
            if (!Schema::hasColumn('product_flat', 'difficulty_level')) {
                $table->string('difficulty_level', 20)->nullable()->after('platform')
                    ->comment('beginner, intermediate, advanced');
            }
            if (!Schema::hasColumn('product_flat', 'features')) {
                $table->json('features')->nullable()->after('difficulty_level')
                    ->comment('Features: ["Multiplayer","AI","Networking"]');
            }
            if (!Schema::hasColumn('product_flat', 'genre')) {
                $table->string('genre', 50)->nullable()->after('features')
                    ->comment('Primary genre: FPS, RPG, Action, MOBA, Racing, Puzzle, Strategy, Survival, Platformer');
            }
            if (!Schema::hasColumn('product_flat', 'genre_tags')) {
                $table->json('genre_tags')->nullable()->after('genre')
                    ->comment('Multiple genre tags: ["FPS","Multiplayer","Shooter"]');
            }
            if (!Schema::hasColumn('product_flat', 'sales_count')) {
                $table->unsignedInteger('sales_count')->default(0)->after('genre_tags');
            }
            if (!Schema::hasColumn('product_flat', 'is_staff_pick')) {
                $table->boolean('is_staff_pick')->default(false)->after('sales_count');
            }
            if (!Schema::hasColumn('product_flat', 'badge_type')) {
                $table->string('badge_type', 20)->nullable()->after('is_staff_pick')
                    ->comment('hot, bestseller, new, verified, trending, discount');
            }
            if (!Schema::hasColumn('product_flat', 'display_price_usd')) {
                $table->decimal('display_price_usd', 8, 2)->nullable()->after('badge_type')
                    ->comment('Display price in USD for homepage');
            }
        });

        // Add indexes
        Schema::table('product_flat', function (Blueprint $table) {
            $table->index('engine', 'idx_product_flat_engine');
            $table->index('genre', 'idx_product_flat_genre');
            $table->index('sales_count', 'idx_product_flat_sales_count');
            $table->index('is_staff_pick', 'idx_product_flat_staff_pick');
            $table->index('badge_type', 'idx_product_flat_badge_type');
        });
    }

    public function down(): void
    {
        Schema::table('product_flat', function (Blueprint $table) {
            $columns = ['engine', 'platform', 'difficulty_level', 'features', 'genre', 'genre_tags', 'sales_count', 'is_staff_pick', 'badge_type', 'display_price_usd'];
            foreach ($columns as $col) {
                if (Schema::hasColumn('product_flat', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
