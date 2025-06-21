<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop any existing announcement table (singular)
        if (Schema::hasTable('announcement')) {
            // Migrate data if exists
            if (Schema::hasTable('announcements')) {
                // Copy data from singular to plural if both exist
                $singularData = DB::table('announcement')->get();
                foreach ($singularData as $data) {
                    DB::table('announcements')->insertOrIgnore((array) $data);
                }
            } else {
                // Rename singular to plural
                Schema::rename('announcement', 'announcements');
            }
            
            // Drop the singular table if it still exists
            if (Schema::hasTable('announcement')) {
                Schema::drop('announcement');
            }
        }

        // Ensure announcements table exists with correct structure
        if (!Schema::hasTable('announcements')) {
            Schema::create('announcements', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->text('content');
                $table->enum('status', ['active', 'draft'])->default('draft');
                $table->string('author_type')->nullable();
                $table->unsignedBigInteger('author_id')->nullable();
                $table->boolean('is_published')->default(false);
                $table->timestamp('published_at')->nullable();
                $table->timestamps();
                $table->softDeletes(); // Add soft deletes
                
                // Add indexes for better performance
                $table->index(['status', 'is_published']);
                $table->index(['author_type', 'author_id']);
                $table->index('created_at');
            });
        } else {
            // Update existing table structure
            Schema::table('announcements', function (Blueprint $table) {
                // Add missing columns if they don't exist
                if (!Schema::hasColumn('announcements', 'status')) {
                    $table->enum('status', ['active', 'draft'])->default('draft');
                }
                if (!Schema::hasColumn('announcements', 'author_type')) {
                    $table->string('author_type')->nullable();
                }
                if (!Schema::hasColumn('announcements', 'author_id')) {
                    $table->unsignedBigInteger('author_id')->nullable();
                }
                if (!Schema::hasColumn('announcements', 'is_published')) {
                    $table->boolean('is_published')->default(false);
                }
                if (!Schema::hasColumn('announcements', 'published_at')) {
                    $table->timestamp('published_at')->nullable();
                }
                if (!Schema::hasColumn('announcements', 'deleted_at')) {
                    $table->softDeletes();
                }
            });
        }

        // Update existing records to have proper published status
        DB::table('announcements')
            ->where('status', 'active')
            ->whereNull('is_published')
            ->update([
                'is_published' => true,
                'published_at' => now()
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Don't drop the table to prevent data loss
        // Just log that this migration was reversed
        \Log::info('Announcements table migration reversed - table preserved to prevent data loss');
    }
};
