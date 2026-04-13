<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * FIX-02: Add missing 'name_ar' column to payment_methods table.
 * 
 * The PaymentMethod model has 'name_ar' in $fillable and the boot()
 * method sets it automatically, but the column was never created in
 * the original migration, causing 'Column not found' errors.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payment_methods', function (Blueprint $table) {
            $table->string('name_ar')->nullable()->after('name');
        });
    }

    public function down(): void
    {
        Schema::table('payment_methods', function (Blueprint $table) {
            $table->dropColumn('name_ar');
        });
    }
};
