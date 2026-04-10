<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('reference_number', 32)->unique(); // TXN-2026-XXXXXXXX
            $table->enum('type', ['enrollment', 'payout', 'refund', 'adjustment']);
            $table->enum('status', ['pending', 'completed', 'failed', 'refunded'])->default('pending');

            // الروابط
            $table->foreignId('enrollment_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('payout_request_id')->nullable()->constrained('payout_requests')->nullOnDelete();
            $table->foreignId('student_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('tutor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('payment_method_id')->nullable()->constrained('payment_methods')->nullOnDelete();
            $table->foreignId('processed_by')->nullable()->constrained('users')->nullOnDelete();

            // المبالغ
            $table->decimal('gross_amount', 10, 2)->default(0);      // المبلغ الكامل الذي دفعه الطالب
            $table->decimal('platform_fee_rate', 5, 2)->default(0);  // نسبة عمولة المنصة وقت المعاملة
            $table->decimal('platform_fee_amount', 10, 2)->default(0); // مبلغ عمولة المنصة
            $table->decimal('tutor_amount', 10, 2)->default(0);      // صافي المبلغ للمعلم

            // معلومات إضافية
            $table->string('payment_proof')->nullable(); // مسار صورة الإيصال
            $table->text('notes')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
