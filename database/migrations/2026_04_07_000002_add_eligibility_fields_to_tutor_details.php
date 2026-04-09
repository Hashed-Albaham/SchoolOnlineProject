<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * [v8.0] Add eligibility + historical fairness fields to tutor_details
     */
    public function up(): void
    {
        Schema::table('tutor_details', function (Blueprint $table) {
            // Eligibility data
            $table->decimal('gpa', 4, 2)->nullable()->after('portfolio_url');
            $table->decimal('gpa_scale', 3, 1)->nullable()->after('gpa');
            $table->integer('step_score')->nullable()->after('gpa_scale');

            // Historical fairness: requirements at time of registration
            $table->decimal('req_gpa_at_registration', 4, 2)->nullable()->after('step_score');
            $table->integer('req_step_at_registration')->nullable()->after('req_gpa_at_registration');
        });
    }

    public function down(): void
    {
        Schema::table('tutor_details', function (Blueprint $table) {
            $table->dropColumn([
                'gpa',
                'gpa_scale',
                'step_score',
                'req_gpa_at_registration',
                'req_step_at_registration',
            ]);
        });
    }
};
