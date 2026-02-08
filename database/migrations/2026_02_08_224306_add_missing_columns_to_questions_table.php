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
        Schema::table('questions', function (Blueprint $table) {
            // Add missing columns for quiz questions
            if (!Schema::hasColumn('questions', 'quiz_id')) {
                $table->foreignId('quiz_id')->after('id')->constrained()->onDelete('cascade');
            }
            if (!Schema::hasColumn('questions', 'question_text')) {
                $table->text('question_text')->after('quiz_id');
            }
            if (!Schema::hasColumn('questions', 'points')) {
                $table->integer('points')->default(1)->after('question_text');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropForeign(['quiz_id']);
            $table->dropColumn(['quiz_id', 'question_text', 'points']);
        });
    }
};
