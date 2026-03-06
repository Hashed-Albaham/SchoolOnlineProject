<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * [REQ] Add qualification fields for tutors and terms agreement for all users.
     */
    public function up(): void
    {
        // Add qualification fields to tutor_details
        Schema::table('tutor_details', function (Blueprint $table) {
            $table->string('university')->nullable()->after('specialization');
            $table->unsignedSmallInteger('graduation_year')->nullable()->after('university');
            $table->string('degree_certificate_path')->nullable()->after('graduation_year');
            $table->text('skills')->nullable()->after('degree_certificate_path');
            $table->string('portfolio_url')->nullable()->after('skills');
            $table->boolean('agreed_to_terms')->default(false)->after('portfolio_url');
        });

        // Add terms agreement timestamp to users
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('agreed_to_terms_at')->nullable()->after('remember_token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tutor_details', function (Blueprint $table) {
            $table->dropColumn([
                'university', 'graduation_year', 'degree_certificate_path',
                'skills', 'portfolio_url', 'agreed_to_terms',
            ]);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('agreed_to_terms_at');
        });
    }
};
