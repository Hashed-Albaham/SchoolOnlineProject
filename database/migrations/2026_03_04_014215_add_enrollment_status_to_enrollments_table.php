<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * [E1] Add enrollment_status to enrollments table
 * 
 * Allows tutors/admins to approve/reject student enrollment requests.
 * Flow: Student enrolls → enrollment_status='pending_approval' → 
 *       Tutor/Admin approves → enrollment_status='approved' → Student can access
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            $table->enum('enrollment_status', ['pending_approval', 'approved', 'rejected'])
                  ->default('pending_approval')
                  ->after('payment_status');
        });

        // Auto-approve existing paid enrollments
        DB::table('enrollments')
            ->where('payment_status', 'paid')
            ->update(['enrollment_status' => 'approved']);
    }

    public function down(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropColumn('enrollment_status');
        });
    }
};
