<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * [BUG-11 FIX] PROSKILL-OMNI-AUDITOR-v10.0
 * إضافة SoftDeletes إلى جدول payout_requests.
 * 
 * السبب: السجلات المالية يجب الاحتفاظ بها تاريخياً.
 * الحذف الفعلي لطلب سحب يجب أن يكون منطقياً فقط.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payout_requests', function (Blueprint $table) {
            $table->softDeletes()->after('paid_at');
        });
    }

    public function down(): void
    {
        Schema::table('payout_requests', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
