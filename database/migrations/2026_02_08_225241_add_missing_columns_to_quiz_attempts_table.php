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
        Schema::table('quiz_attempts', function (Blueprint $table) {
            // Add missing columns for quiz attempts
            if (!Schema::hasColumn('quiz_attempts', 'user_id')) {
                $table->foreignId('user_id')->after('id')->constrained()->onDelete('cascade');
            }
            if (!Schema::hasColumn('quiz_attempts', 'quiz_id')) {
                $table->foreignId('quiz_id')->after('user_id')->constrained()->onDelete('cascade');
            }
            if (!Schema::hasColumn('quiz_attempts', 'score')) {
                $table->integer('score')->default(0)->after('quiz_id');
            }
            if (!Schema::hasColumn('quiz_attempts', 'total_points')) {
                $table->integer('total_points')->default(0)->after('score');
            }
            if (!Schema::hasColumn('quiz_attempts', 'passed')) {
                $table->boolean('passed')->default(false)->after('total_points');
            }
            if (!Schema::hasColumn('quiz_attempts', 'answers')) {
                $table->json('answers')->nullable()->after('passed');
            }
            if (!Schema::hasColumn('quiz_attempts', 'started_at')) {
                $table->timestamp('started_at')->nullable()->after('answers');
            }
            if (!Schema::hasColumn('quiz_attempts', 'completed_at')) {
                $table->timestamp('completed_at')->nullable()->after('started_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quiz_attempts', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['quiz_id']);
            $table->dropColumn(['user_id', 'quiz_id', 'score', 'total_points', 'passed', 'answers', 'started_at', 'completed_at']);
        });
    }
};
