<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * FIX-01: Expand payment_status enum to include 'completed' and 'refunded'.
 * 
 * The original enum only had ['pending','paid','failed'], but the
 * Admin\EnrollmentController and FinancialService use 'completed' and 'refunded'
 * values, causing DB errors when attempting refunds or status transitions.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE enrollments MODIFY COLUMN payment_status ENUM('pending','paid','failed','completed','refunded') DEFAULT 'pending'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE enrollments MODIFY COLUMN payment_status ENUM('pending','paid','failed') DEFAULT 'pending'");
    }
};
