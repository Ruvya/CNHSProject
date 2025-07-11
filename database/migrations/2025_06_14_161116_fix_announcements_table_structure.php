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
        Schema::table('announcements', function (Blueprint $table) {
            // Add status column if it doesn't exist
            if (!Schema::hasColumn('announcements', 'status')) {
                $table->enum('status', ['active', 'draft'])->default('active')->after('content');
            }

            // Add author_type column if it doesn't exist
            if (!Schema::hasColumn('announcements', 'author_type')) {
                $table->string('author_type')->nullable()->after('status');
            }

            // Add author_id column if it doesn't exist
            if (!Schema::hasColumn('announcements', 'author_id')) {
                $table->unsignedBigInteger('author_id')->nullable()->after('author_type');
            }

            // Add is_published column if it doesn't exist
            if (!Schema::hasColumn('announcements', 'is_published')) {
                $table->boolean('is_published')->default(true)->after('author_id');
            }

            // Add published_at column if it doesn't exist
            if (!Schema::hasColumn('announcements', 'published_at')) {
                $table->timestamp('published_at')->nullable()->after('is_published');
            }
        });

        // Set default values for existing announcements
        DB::table('announcements')->update([
            'author_type' => 'App\Models\Principal',
            'is_published' => true,
            'published_at' => DB::raw('created_at')
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('announcements', function (Blueprint $table) {
            $table->dropColumn(['status', 'author_type', 'author_id', 'is_published', 'published_at']);
        });
    }
};
