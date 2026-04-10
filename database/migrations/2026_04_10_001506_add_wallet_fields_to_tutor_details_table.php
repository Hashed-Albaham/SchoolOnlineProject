<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('tutor_details', function (Blueprint $table) {
            $table->decimal('available_balance', 10, 2)->default(0)->after('step_score')
                  ->comment('الرصيد المتاح للسحب');
            $table->decimal('pending_balance', 10, 2)->default(0)->after('available_balance')
                  ->comment('الرصيد قيد المراجعة (اشتراكات لم يؤكدها الأدمن بعد)');
            $table->decimal('total_earned', 10, 2)->default(0)->after('pending_balance')
                  ->comment('إجمالي ما كسبه المعلم على مدار الوقت');
            $table->decimal('total_withdrawn', 10, 2)->default(0)->after('total_earned')
                  ->comment('إجمالي ما سحبه المعلم');
        });
    }

    public function down(): void
    {
        Schema::table('tutor_details', function (Blueprint $table) {
            $table->dropColumn(['available_balance', 'pending_balance', 'total_earned', 'total_withdrawn']);
        });
    }
};