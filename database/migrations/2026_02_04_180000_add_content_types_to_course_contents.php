<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('course_contents', function (Blueprint $table) {
            // Add content type field (including 'link' for external links)
            $table->enum('type', ['video', 'file', 'image', 'text', 'quiz', 'link'])->default('video')->after('title');

            // Add file path for uploaded files/images
            $table->string('file_path')->nullable()->after('youtube_video_id');

            // Add external link URL
            $table->string('link_url')->nullable()->after('file_path');

            // Add quiz relationship
            $table->unsignedBigInteger('quiz_id')->nullable()->after('link_url');
            $table->foreign('quiz_id')->references('id')->on('quizzes')->onDelete('set null');

            // Add text content field (supports rich text/notes)
            $table->longText('text_content')->nullable()->after('description');
        });

        // Make youtube_video_id nullable
        Schema::table('course_contents', function (Blueprint $table) {
            $table->string('youtube_video_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('course_contents', function (Blueprint $table) {
            $table->dropForeign(['quiz_id']);
            $table->dropColumn(['type', 'file_path', 'link_url', 'quiz_id', 'text_content']);
        });
    }
};
