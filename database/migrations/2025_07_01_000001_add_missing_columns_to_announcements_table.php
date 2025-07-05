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
        Schema::table('announcements', function (Blueprint $table) {
            // Check if columns don't exist before adding them
            if (!Schema::hasColumn('announcements', 'status')) {
                $table->enum('status', ['active', 'draft'])->default('draft')->after('content');
            }
            
            if (!Schema::hasColumn('announcements', 'is_published')) {
                $table->boolean('is_published')->default(false)->after('author_id');
            }
            
            if (!Schema::hasColumn('announcements', 'published_at')) {
                $table->timestamp('published_at')->nullable()->after('is_published');
            }
            
            if (!Schema::hasColumn('announcements', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('announcements', function (Blueprint $table) {
            $table->dropColumn(['status', 'is_published', 'published_at', 'deleted_at']);
        });
    }
};
