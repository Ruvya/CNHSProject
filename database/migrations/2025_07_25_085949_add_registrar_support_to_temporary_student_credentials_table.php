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
        // Ensure table exists before altering
        if (Schema::hasTable('temporary_student_credentials')) {
            // Safely change created_by_admin_id to nullable
            try {
                if (Schema::hasColumn('temporary_student_credentials', 'created_by_admin_id')) {
                    Schema::table('temporary_student_credentials', function (Blueprint $table) {
                        $table->unsignedBigInteger('created_by_admin_id')->nullable()->change();
                    });
                }
            } catch (\Throwable $e) {
                // Ignore if change is not supported in current environment
            }

            // Add created_by_registrar_id if missing
            if (!Schema::hasColumn('temporary_student_credentials', 'created_by_registrar_id')) {
                Schema::table('temporary_student_credentials', function (Blueprint $table) {
                    $table->unsignedBigInteger('created_by_registrar_id')->nullable()->after('created_by_admin_id');
                });

                // Add foreign key for registrar, guard against duplicate
                try {
                    Schema::table('temporary_student_credentials', function (Blueprint $table) {
                        $table->foreign('created_by_registrar_id')->references('id')->on('registrars')->onDelete('cascade');
                    });
                } catch (\Throwable $e) {
                    // FK might already exist
                }
            }

            // Add source column if missing
            if (!Schema::hasColumn('temporary_student_credentials', 'source')) {
                Schema::table('temporary_student_credentials', function (Blueprint $table) {
                    $table->string('source')->default('manual')->after('notes');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('temporary_student_credentials')) {
            // Drop FK if present
            try {
                // Detect FK name dynamically to be safe
                $fkName = DB::table('information_schema.KEY_COLUMN_USAGE')
                    ->where('TABLE_SCHEMA', DB::getDatabaseName())
                    ->where('TABLE_NAME', 'temporary_student_credentials')
                    ->where('COLUMN_NAME', 'created_by_registrar_id')
                    ->whereNotNull('CONSTRAINT_NAME')
                    ->value('CONSTRAINT_NAME');
                if ($fkName) {
                    DB::statement("ALTER TABLE `temporary_student_credentials` DROP FOREIGN KEY `{$fkName}`");
                }
            } catch (\Throwable $e) {
                // Ignore
            }

            // Drop columns if they exist
            Schema::table('temporary_student_credentials', function (Blueprint $table) {
                if (Schema::hasColumn('temporary_student_credentials', 'created_by_registrar_id')) {
                    $table->dropColumn('created_by_registrar_id');
                }
                if (Schema::hasColumn('temporary_student_credentials', 'source')) {
                    $table->dropColumn('source');
                }
            });

            // Attempt to revert admin column to not nullable
            try {
                if (Schema::hasColumn('temporary_student_credentials', 'created_by_admin_id')) {
                    Schema::table('temporary_student_credentials', function (Blueprint $table) {
                        $table->unsignedBigInteger('created_by_admin_id')->nullable(false)->change();
                    });
                }
            } catch (\Throwable $e) {
                // Ignore if change() unsupported
            }
        }
    }
};
