<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Drop the singular table if it exists
        Schema::dropIfExists('announcement');

        // Create or update the plural table
        if (!Schema::hasTable('announcements')) {
            Schema::create('announcements', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->text('content');
                $table->enum('status', ['active', 'draft'])->default('draft');
                $table->string('author_type')->nullable();
                $table->unsignedBigInteger('author_id')->nullable();
                $table->timestamps();
            });
        } else {
            Schema::table('announcements', function (Blueprint $table) {
                // Add author fields if they don't exist
                if (!Schema::hasColumn('announcements', 'author_type')) {
                    $table->string('author_type')->nullable();
                }
                if (!Schema::hasColumn('announcements', 'author_id')) {
                    $table->unsignedBigInteger('author_id')->nullable();
                }
                // Make sure the status column exists
                if (!Schema::hasColumn('announcements', 'status')) {
                    $table->enum('status', ['active', 'draft'])->default('draft');
                }
            });
        }
    }

    public function down()
    {
        // No down method needed as we don't want to risk data loss
    }
}; 