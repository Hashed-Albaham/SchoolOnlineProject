<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * [BUG-01 FIX] PROSKILL-OMNI-AUDITOR-v10.0
 * إضافة 'booking' إلى ENUM type في جدول transactions.
 * 
 * السبب: FinancialService::recordBookingPayment() تستخدم type='booking'
 * لكن الـ ENUM الأصلي لا يحتوي هذه القيمة مما يسبب استثناء في MySQL.
 */
return new class extends Migration
{
    public function up(): void
    {
        // MySQL: تعديل ENUM مباشرة لإضافة 'booking'
        DB::statement("
            ALTER TABLE transactions 
            MODIFY COLUMN type 
            ENUM('enrollment','payout','refund','adjustment','booking') NOT NULL
        ");
    }

    public function down(): void
    {
        // تحذير: يجب حذف السجلات ذات type='booking' قبل التراجع
        DB::statement("
            ALTER TABLE transactions 
            MODIFY COLUMN type 
            ENUM('enrollment','payout','refund','adjustment') NOT NULL
        ");
    }
};
