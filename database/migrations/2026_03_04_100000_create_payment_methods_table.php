<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name');              // اسم طريقة الدفع (عربي)
            $table->string('name_en')->nullable(); // الاسم الإنجليزي
            $table->string('type');              // bank_transfer, crypto, wallet, cash, etc.
            $table->string('icon')->nullable();  // أيقونة SVG أو emoji
            $table->text('instructions_ar');     // تعليمات التحويل بالعربية
            $table->text('instructions_en')->nullable(); // تعليمات بالإنجليزية
            $table->string('account_number')->nullable(); // رقم الحساب أو المحفظة
            $table->string('account_name')->nullable();   // اسم صاحب الحساب
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Link payment methods to courses (optional per-course customization)
        Schema::create('course_payment_methods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->foreignId('payment_method_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['course_id', 'payment_method_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_payment_methods');
        Schema::dropIfExists('payment_methods');
    }
};
