<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('grades', function (Blueprint $table) {
            // Rename quarter columns to match the model's fillable array
            $table->renameColumn('quarter_1', 'quarter1');
            $table->renameColumn('quarter_2', 'quarter2');
            $table->renameColumn('quarter_3', 'quarter3');
            $table->renameColumn('quarter_4', 'quarter4');
        });
    }

    public function down()
    {
        Schema::table('grades', function (Blueprint $table) {
            // Revert the column names back to underscore format
            $table->renameColumn('quarter1', 'quarter_1');
            $table->renameColumn('quarter2', 'quarter_2');
            $table->renameColumn('quarter3', 'quarter_3');
            $table->renameColumn('quarter4', 'quarter_4');
        });
    }
};
