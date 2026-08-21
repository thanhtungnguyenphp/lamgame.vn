<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Phase 1 E-E-A-T: Create Authors table for content attribution
 */
return new class extends Migration
{
    public function up(): void
    {
        // Create authors table if not exists
        if (!Schema::hasTable('authors')) {
            Schema::create('authors', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->string('title')->nullable()->comment('e.g. Unity Developer, Game Designer');
                $table->text('bio')->nullable();
                $table->integer('experience_years')->nullable();
                $table->json('expertise')->nullable()->comment('["Unity", "C#", "Mobile Game"]');
                $table->json('social_links')->nullable()->comment('{"github": "...", "linkedin": "..."}');
                $table->string('avatar')->nullable();
                $table->string('email')->nullable();
                $table->string('website')->nullable();
                
                // Link to customer account if author is a registered user
                $table->unsignedInteger('customer_id')->nullable();
                $table->foreign('customer_id')
                    ->references('id')
                    ->on('customers')
                    ->onDelete('set null');
                
                $table->boolean('is_staff')->default(false)->comment('LamGame team member');
                $table->boolean('is_verified')->default(false)->comment('Verified professional');
                $table->boolean('is_active')->default(true);
                
                $table->timestamps();
                
                $table->index(['is_active', 'is_staff']);
            });
        }
        
        // Add columns to blogs table if not exist
        Schema::table('blogs', function (Blueprint $table) {
            if (!Schema::hasColumn('blogs', 'author_id')) {
                $table->unsignedBigInteger('author_id')->nullable()->after('id');
            }
            
            if (!Schema::hasColumn('blogs', 'reviewed_at')) {
                $table->timestamp('reviewed_at')->nullable()->comment('Last technical review date');
            }
            
            if (!Schema::hasColumn('blogs', 'reviewed_by')) {
                $table->unsignedBigInteger('reviewed_by')->nullable()->comment('Author ID who reviewed');
            }
            
            if (!Schema::hasColumn('blogs', 'sources')) {
                $table->json('sources')->nullable()->comment('Citation sources');
            }
        });
        
        // Add foreign keys if authors table exists
        if (Schema::hasTable('authors')) {
            // Check and add foreign key for author_id
            if (Schema::hasColumn('blogs', 'author_id')) {
                try {
                    Schema::table('blogs', function (Blueprint $table) {
                        $table->foreign('author_id')
                            ->references('id')
                            ->on('authors')
                            ->onDelete('set null');
                    });
                } catch (\Exception $e) {
                    // Foreign key may already exist
                }
            }
        }
    }

    public function down(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
            if (Schema::hasColumn('blogs', 'author_id')) {
                try {
                    $table->dropForeign(['author_id']);
                } catch (\Exception $e) {}
                $table->dropColumn('author_id');
            }
            if (Schema::hasColumn('blogs', 'reviewed_at')) {
                $table->dropColumn('reviewed_at');
            }
            if (Schema::hasColumn('blogs', 'reviewed_by')) {
                $table->dropColumn('reviewed_by');
            }
            if (Schema::hasColumn('blogs', 'sources')) {
                $table->dropColumn('sources');
            }
        });
        
        Schema::dropIfExists('authors');
    }
};
