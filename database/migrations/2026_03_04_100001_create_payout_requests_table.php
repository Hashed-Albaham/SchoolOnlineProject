<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payout_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tutor_id')->constrained('users')->cascadeOnDelete();
            $table->decimal('amount', 10, 2);               // المبلغ المطلوب سحبه
            $table->foreignId('payment_method_id')->nullable()->constrained()->nullOnDelete();
            $table->string('status')->default('pending');     // pending, approved, rejected, paid
            $table->text('tutor_notes')->nullable();          // ملاحظات المعلم
            $table->text('admin_notes')->nullable();          // ملاحظات الأدمن
            $table->timestamp('reviewed_at')->nullable();     // تاريخ المراجعة
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('paid_at')->nullable();         // تاريخ الصرف الفعلي
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payout_requests');
    }
};
