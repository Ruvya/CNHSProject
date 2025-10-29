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
        // Drop grade approval columns if they exist
        Schema::table('grades', function (Blueprint $table) {
            // Drop approval-related columns if they exist
            if (Schema::hasColumn('grades', 'status')) {
                $table->dropColumn('status');
            }
            if (Schema::hasColumn('grades', 'submitted_at')) {
                $table->dropColumn('submitted_at');
            }
            if (Schema::hasColumn('grades', 'reviewed_by')) {
                $table->dropForeign(['reviewed_by']);
                $table->dropColumn('reviewed_by');
            }
            if (Schema::hasColumn('grades', 'reviewed_at')) {
                $table->dropColumn('reviewed_at');
            }
            if (Schema::hasColumn('grades', 'approval_notes')) {
                $table->dropColumn('approval_notes');
            }
            if (Schema::hasColumn('grades', 'rejection_reason')) {
                $table->dropColumn('rejection_reason');
            }
        });

        // Drop grade approval logs table if it exists
        Schema::dropIfExists('grade_approval_logs');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Re-add approval columns (for rollback purposes)
        Schema::table('grades', function (Blueprint $table) {
            $table->enum('status', ['draft', 'submitted', 'under_review', 'approved', 'rejected'])
                  ->default('draft')
                  ->after('remarks');
            
            $table->timestamp('submitted_at')->nullable()->after('status');
            $table->foreignId('reviewed_by')->nullable()->constrained('principals')->onDelete('set null')->after('submitted_at');
            $table->timestamp('reviewed_at')->nullable()->after('reviewed_by');
            $table->text('approval_notes')->nullable()->after('reviewed_at');
            $table->text('rejection_reason')->nullable()->after('approval_notes');
        });

        // Re-create grade approval logs table
        Schema::create('grade_approval_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grade_id')->constrained('grades')->onDelete('cascade');
            $table->enum('action', ['submitted', 'approved', 'rejected', 'returned_for_revision']);
            $table->foreignId('performed_by')->nullable()->constrained('principals')->onDelete('set null');
            $table->text('notes')->nullable();
            $table->json('grade_data_snapshot')->nullable();
            $table->timestamps();

            $table->index(['grade_id', 'created_at']);
        });
    }
};