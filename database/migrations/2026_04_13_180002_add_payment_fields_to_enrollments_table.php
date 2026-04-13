<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * FIX-03: Add payment_method_id and payment_proof to enrollments table.
 * 
 * The FinancialService reads $enrollment->payment_method_id and
 * $enrollment->payment_proof when recording transactions, but these
 * columns don't exist in the enrollments table, causing them to
 * always be null in Transaction records.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            $table->foreignId('payment_method_id')
                  ->nullable()
                  ->after('enrollment_status')
                  ->constrained('payment_methods')
                  ->nullOnDelete();

            $table->string('payment_proof')
                  ->nullable()
                  ->after('payment_method_id');
        });
    }

    public function down(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropForeign(['payment_method_id']);
            $table->dropColumn(['payment_method_id', 'payment_proof']);
        });
    }
};
