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
        // First, rename the table from 'announcement' to 'announcements'
        if (Schema::hasTable('announcement') && !Schema::hasTable('announcements')) {
            Schema::rename('announcement', 'announcements');
        }

        // Add missing columns to the announcements table
        if (Schema::hasTable('announcements')) {
            Schema::table('announcements', function (Blueprint $table) {
                // Add author fields if they don't exist
                if (!Schema::hasColumn('announcements', 'author_type')) {
                    $table->string('author_type')->nullable()->after('status');
                }
                if (!Schema::hasColumn('announcements', 'author_id')) {
                    $table->unsignedBigInteger('author_id')->nullable()->after('author_type');
                }
                // Add published fields if they don't exist
                if (!Schema::hasColumn('announcements', 'is_published')) {
                    $table->boolean('is_published')->default(false)->after('author_id');
                }
                if (!Schema::hasColumn('announcements', 'published_at')) {
                    $table->timestamp('published_at')->nullable()->after('is_published');
                }
            });

            // Update existing records to be published if they are active
            DB::table('announcements')
                ->where('status', 'active')
                ->update([
                    'is_published' => true,
                    'published_at' => now()
                ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rename back to singular if needed
        if (Schema::hasTable('announcements')) {
            Schema::table('announcements', function (Blueprint $table) {
                $table->dropColumn(['author_type', 'author_id', 'is_published', 'published_at']);
            });
            Schema::rename('announcements', 'announcement');
        }
    }
};
