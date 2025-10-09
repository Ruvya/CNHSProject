<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Add approval fields to grades table
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

        // Create grade approval logs table
        Schema::create('grade_approval_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grade_id')->constrained('grades')->onDelete('cascade');
            $table->enum('action', ['submitted', 'approved', 'rejected', 'returned_for_revision']);
            $table->foreignId('performed_by')->nullable()->constrained('principals')->onDelete('set null');
            $table->text('notes')->nullable();
            $table->json('grade_data_snapshot')->nullable(); // Store grade data at time of action
            $table->timestamps();

            $table->index(['grade_id', 'created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('grade_approval_logs');
        
        Schema::table('grades', function (Blueprint $table) {
            $table->dropForeign(['reviewed_by']);
            $table->dropColumn([
                'status',
                'submitted_at', 
                'reviewed_by', 
                'reviewed_at', 
                'approval_notes',
                'rejection_reason'
            ]);
        });
    }
};


