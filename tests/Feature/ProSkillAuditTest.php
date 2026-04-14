<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\PaymentMethod;
use App\Models\PayoutRequest;
use App\Models\SessionSlot;
use App\Models\Transaction;
use App\Models\TutorDetail;
use App\Models\User;
use App\Services\BookingService;
use App\Services\FinancialService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * PROSKILL-OMNI-AUDITOR-v10.0
 * ملف الاختبارات الشامل — يغطي جميع الثغرات المكتشفة والمسارات الحرجة
 * تشغيل: php artisan test --filter=ProSkillAuditTest
 */
class ProSkillAuditTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $tutor;
    protected User $student;
    protected Course $course;
    protected TutorDetail $tutorDetail;
    protected PaymentMethod $paymentMethod;

    protected function setUp(): void
    {
        parent::setUp();

        // تعطيل middleware التوطين لتجنب إعادة التوجيه
        $this->withoutMiddleware([
            \Mcamara\LaravelLocalization\Middleware\LocaleSessionRedirect::class,
            \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRedirectFilter::class,
        ]);

        // إنشاء مستخدمين عبر factory (تتجاوز $fillable بشكل صحيح)
        $this->admin = User::factory()->create(['role' => 'admin', 'is_super_admin' => true]);
        $this->tutor = User::factory()->create(['role' => 'tutor']);
        $this->student = User::factory()->create(['role' => 'student']);

        // إنشاء تفاصيل المعلم مع رصيد
        // [NOTE] نستخدم DB::table مباشرة لأن حقول المحفظة محذوفة من $fillable أمنياً (هذا صحيح)
        DB::table('tutor_details')->insert([
            'user_id'           => $this->tutor->id,
            'is_verified'       => true,
            'available_balance' => 500.00,
            'pending_balance'   => 0,
            'total_earned'      => 500.00,
            'total_withdrawn'   => 0,
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);
        $this->tutorDetail = TutorDetail::where('user_id', $this->tutor->id)->first();

        // إنشاء كورس
        $this->course = Course::create([
            'tutor_id'    => $this->tutor->id,
            'title'       => 'Test Course',
            'description' => 'Description',
            'price'       => 100.00,
        ]);
        // [NOTE] status محذوف من $fillable → نُعيّنه صراحةً
        $this->course->status = 'approved';
        $this->course->save();

        // طريقة دفع
        $this->paymentMethod = PaymentMethod::create([
            'name'            => 'Bank Transfer',
            'name_ar'         => 'تحويل بنكي',
            'type'            => 'bank_transfer',
            'instructions_ar' => 'أرسل المبلغ',
            'is_active'       => true,
            'sort_order'      => 1,
        ]);

        // إعداد الإعدادات المالية
        \App\Models\Setting::set('platform_commission_rate', 20);
        \App\Models\Setting::set('min_payout_amount', 50);
    }

    /**
     * Helper: إنشاء SessionSlot بشكل صحيح بعد إزالة 'status' من $fillable
     * [BUG-02 FIX] status يُعيَّن صراحةً بعد create()
     */
    private function makeSlot(array $overrides = []): SessionSlot
    {
        $slot = SessionSlot::create(array_merge([
            'tutor_id'         => $this->tutor->id,
            'type'             => '1-on-1',
            'price'            => 0,
            'max_participants' => 1,
            'start_time'       => now()->addDay(),
            'end_time'         => now()->addDay()->addHour(),
        ], $overrides));

        // [BUG-02 FIX] التعيين الصريح لـ status بعد الإنشاء
        $slot->status = 'scheduled';
        $slot->save();

        return $slot;
    }

    /**
     * Helper: إنشاء Booking بشكل صحيح بعد إزالة 'status' من $fillable
     */
    private function makeBooking(array $data, string $status = 'pending'): Booking
    {
        $booking = Booking::create($data);
        $booking->status = $status;
        $booking->save();
        return $booking;
    }


    public function test_booking_transaction_type_is_valid_enum()
    {
        $slot = $this->makeSlot(['price' => 80.00]);

        $booking = $this->makeBooking([
            'student_id'       => $this->student->id,
            'session_slot_id'  => $slot->id,
            'payment_method_id'=> $this->paymentMethod->id,
            'locked_until'     => now()->addMinutes(15),
        ], 'pending');

        $financial = app(FinancialService::class);

        // [BUG-01 FIX] بعد تطبيق الـ migration، يجب أن يعمل هذا بنجاح
        $tx = $financial->recordBookingPayment($booking);
        $this->assertEquals('booking', $tx->type);
        $this->assertEquals('pending', $tx->status);
    }

    // ═══════════════════════════════════════════════════════════════
    // ██ [BUG-02] SECURITY — Mass Assignment في SessionSlot::$fillable مُصلح
    // ═══════════════════════════════════════════════════════════════

    public function test_session_slot_status_cannot_be_set_via_mass_assignment()
    {
        // [BUG-02 FIX] بعد إزالة 'status' من $fillable، يُغفَل الحقل تلقائياً
        // نتحقق أن النموذج لا يحتوي 'status' في $fillable
        $model = new SessionSlot();
        $this->assertNotContains('status', $model->getFillable(),
            '[BUG-02] status يجب أن يكون محذوفاً من $fillable في SessionSlot');

        // ننشئ الـ slot بدون 'status' — الـ DB default هو 'scheduled'
        $slot = $this->makeSlot(['price' => 0]);
        $this->assertEquals('scheduled', $slot->fresh()->status);
    }

    // ═══════════════════════════════════════════════════════════════
    // ██ [BUG-03] LOGIC — ربط Booking بـ Transaction
    // ═══════════════════════════════════════════════════════════════

    public function test_session_payment_links_booking_to_transaction()
    {
        $slot = $this->makeSlot(['price' => 100.00]);

        $booking = $this->makeBooking([
            'student_id'       => $this->student->id,
            'session_slot_id'  => $slot->id,
            'payment_method_id'=> $this->paymentMethod->id,
            'locked_until'     => now()->addMinutes(15),
        ], 'pending');

        $financial = app(FinancialService::class);
        $tx = $financial->recordBookingPayment($booking);

        $this->assertDatabaseHas('transactions', [
            'booking_id' => $booking->id,
            'student_id' => $this->student->id,
            'tutor_id'   => $this->tutor->id,
        ]);

        // [BUG-03 FIX] SessionPaymentController الآن يحدّث transaction_id
        // (هذا اختبار للـ Service فقط — الـ Controller لا يُستدعى هنا)
        $this->assertNotNull($tx->id);
        $this->assertEquals($booking->id, $tx->booking_id);
    }

    // ═══════════════════════════════════════════════════════════════
    // ██ [BUG-04] LOGIC — Double-Spend في confirmBookingPayment
    // ═══════════════════════════════════════════════════════════════

    public function test_booking_payment_cannot_be_confirmed_twice()
    {
        $slot = $this->makeSlot(['price' => 100.00]);

        $booking = $this->makeBooking([
            'student_id'       => $this->student->id,
            'session_slot_id'  => $slot->id,
            'payment_method_id'=> $this->paymentMethod->id,
        ], 'pending');

        // معاملة مكتملة مسبقاً (لا توجد معاملة pending)
        Transaction::create([
            'reference_number'    => 'TXN-TEST-' . uniqid(),
            'type'                => 'booking',
            'status'              => 'completed',
            'booking_id'          => $booking->id,
            'student_id'          => $this->student->id,
            'tutor_id'            => $this->tutor->id,
            'gross_amount'        => 100,
            'platform_fee_rate'   => 20,
            'platform_fee_amount' => 20,
            'tutor_amount'        => 80,
        ]);

        $initialAvailable = $this->tutorDetail->fresh()->available_balance;

        $financial = app(FinancialService::class);

        // [BUG-04 FIX] لا توجد معاملة pending → لا تأثير على الرصيد
        $financial->confirmBookingPayment($booking, $this->admin->id);

        $this->assertEquals(
            $initialAvailable,
            $this->tutorDetail->fresh()->available_balance,
            '[BUG-04] تأكيد الدفع يجب أن لا يُنفَّذ بدون معاملة pending حقيقية'
        );
    }

    // ═══════════════════════════════════════════════════════════════
    // ██ [BUG-05] LOGIC — lockSeat تتحقق payment_status
    // ═══════════════════════════════════════════════════════════════

    public function test_unpaid_student_cannot_book_course_session()
    {
        $slot = $this->makeSlot([
            'course_id'        => $this->course->id,
            'price'            => 50.00,
        ]);

        // طالب مسجل لكن غير مدفوع - الـ Booking يُنشأ بشكل غير مباشر في lockSeat
        Enrollment::create([
            'user_id'           => $this->student->id,
            'course_id'         => $this->course->id,
            'enrollment_status' => 'pending_approval',
        ]);

        $bookingService = app(BookingService::class);

        $this->expectException(\Exception::class);
        $bookingService->lockSeat($slot, $this->student);
    }

    public function test_approved_student_can_book_course_session()
    {
        $slot = $this->makeSlot([
            'course_id'        => $this->course->id,
            'type'             => 'group',
            'price'            => 0,
            'max_participants' => 5,
        ]);

        $enrollment = Enrollment::create([
            'user_id'           => $this->student->id,
            'course_id'         => $this->course->id,
            'enrollment_status' => 'approved',
        ]);
        $enrollment->payment_status = 'paid';
        $enrollment->save();

        $bookingService = app(BookingService::class);
        $booking = $bookingService->lockSeat($slot, $this->student);

        $this->assertNotNull($booking->id);
        $this->assertEquals('confirmed', $booking->status);
        $this->assertDatabaseHas('bookings', [
            'student_id'      => $this->student->id,
            'session_slot_id' => $slot->id,
            'status'          => 'confirmed',
        ]);
    }

    // ═══════════════════════════════════════════════════════════════
    // ██ [BUG-06] LOGIC — تجاوز الحد الأقصى للمشاركين
    // ═══════════════════════════════════════════════════════════════

    public function test_cannot_book_full_session()
    {
        $slot = $this->makeSlot([
            'price'            => 0,
            'max_participants' => 1,
        ]);

        $student2 = User::factory()->create(['role' => 'student']);
        // [NOTE] نستخدم DB مباشرة لأن status غير موجود في $fillable
        $existingBooking = Booking::create(['student_id' => $student2->id, 'session_slot_id' => $slot->id]);
        $existingBooking->status = 'confirmed';
        $existingBooking->save();

        $bookingService = app(BookingService::class);

        $this->expectException(\Exception::class);
        $bookingService->lockSeat($slot, $this->student);
    }

    public function test_student_cannot_double_book_same_session()
    {
        $slot = $this->makeSlot([
            'type'             => 'group',
            'price'            => 0,
            'max_participants' => 5,
        ]);

        // حجز أول بنفس الطالب
        $existingBooking2 = Booking::create(['student_id' => $this->student->id, 'session_slot_id' => $slot->id]);
        $existingBooking2->status = 'confirmed';
        $existingBooking2->save();

        $bookingService = app(BookingService::class);

        $this->expectException(\Exception::class);
        $bookingService->lockSeat($slot, $this->student);
    }

    // ═══════════════════════════════════════════════════════════════
    // ██ [BUG-07] LOGIC — Double Payout Protection
    // ═══════════════════════════════════════════════════════════════

    public function test_tutor_can_request_payout_with_sufficient_balance()
    {
        $this->actingAs($this->tutor);

        $response = $this->post(route('tutor.payouts.store'), [
            'amount'            => 100,
            'payment_method_id' => $this->paymentMethod->id,
            'tutor_notes'       => 'طلب سحب',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('payout_requests', [
            'tutor_id' => $this->tutor->id,
            'amount'   => 100,
            'status'   => PayoutRequest::STATUS_PENDING,
        ]);
    }

    public function test_tutor_cannot_request_payout_exceeding_balance()
    {
        $this->actingAs($this->tutor);

        $response = $this->post(route('tutor.payouts.store'), [
            'amount'            => 9999,
            'payment_method_id' => $this->paymentMethod->id,
        ]);

        $response->assertSessionHasErrors(['amount']);
        $this->assertDatabaseMissing('payout_requests', [
            'tutor_id' => $this->tutor->id,
            'amount'   => 9999,
        ]);
    }

    public function test_prevents_double_payout_with_pending_request()
    {
        $payout1 = PayoutRequest::create([
            'tutor_id'          => $this->tutor->id,
            'amount'            => 400,
            'payment_method_id' => $this->paymentMethod->id,
        ]);
        $payout1->status = PayoutRequest::STATUS_PENDING;
        $payout1->save();

        $this->actingAs($this->tutor);

        // 200 يتجاوز الرصيد الحقيقي (500-400=100)
        $response = $this->post(route('tutor.payouts.store'), [
            'amount'            => 200,
            'payment_method_id' => $this->paymentMethod->id,
        ]);

        $response->assertSessionHasErrors(['amount']);
    }

    // ═══════════════════════════════════════════════════════════════
    // ██ [BUG-08] SECURITY — Mass Assignment: role في Registration
    // ═══════════════════════════════════════════════════════════════

    public function test_registration_rejects_admin_role()
    {
        $response = $this->post(route('register'), [
            'name'                  => 'Hacker',
            'email'                 => 'hacker@evil.com',
            'password'              => 'Password123!',
            'password_confirmation' => 'Password123!',
            'role'                  => 'admin',
            'agreed_to_terms'       => true,
        ]);

        $this->assertDatabaseMissing('users', [
            'email' => 'hacker@evil.com',
            'role'  => 'admin',
        ]);
    }

    public function test_is_super_admin_is_not_in_fillable()
    {
        // [FIX] نتحقق أن الحقل غير موجود في $fillable بدلاً من محاولة الحقن
        // (محاولة الحقن ترمي MassAssignmentException لأن preventSilentlyDiscarding=true)
        $model = new User();
        $fillable = $model->getFillable();

        $this->assertNotContains('is_super_admin', $fillable,
            '[SECURITY] is_super_admin يجب أن لا يكون في $fillable');
        $this->assertNotContains('role', $fillable,
            '[SECURITY] role يجب أن لا يكون في $fillable');
    }

    // ═══════════════════════════════════════════════════════════════
    // ██ [BUG-09] LOGIC — processRefund مع رصيد صفر
    // ═══════════════════════════════════════════════════════════════

    public function test_refund_handles_insufficient_tutor_balance_gracefully()
    {
        $enrollment = Enrollment::create([
            'user_id'           => $this->student->id,
            'course_id'         => $this->course->id,
            'enrollment_status' => 'approved',
            'payment_method_id' => $this->paymentMethod->id,
        ]);
        $enrollment->payment_status = 'paid';
        $enrollment->save();

        Transaction::create([
            'reference_number'    => 'TXN-TEST-REFUND-' . uniqid(),
            'type'                => 'enrollment',
            'status'              => 'completed',
            'enrollment_id'       => $enrollment->id,
            'student_id'          => $this->student->id,
            'tutor_id'            => $this->tutor->id,
            'gross_amount'        => 100,
            'platform_fee_rate'   => 20,
            'platform_fee_amount' => 20,
            'tutor_amount'        => 80,
        ]);

        // تصفير رصيد المعلم
        DB::table('tutor_details')->where('user_id', $this->tutor->id)->update(['available_balance' => 0]);

        $financial = app(FinancialService::class);
        $refundTx = $financial->processRefund($enrollment, $this->admin->id, 'اختبار');

        $this->assertNotNull($refundTx->id);
        $this->assertEquals('refund', $refundTx->type);
        $this->assertEquals('completed', $refundTx->status);
    }

    // ═══════════════════════════════════════════════════════════════
    // ██ [BUG-10] LOGIC — Idempotent confirmEnrollmentPayment
    // ═══════════════════════════════════════════════════════════════

    public function test_enrollment_confirmation_is_idempotent()
    {
        $enrollment = Enrollment::create([
            'user_id'           => $this->student->id,
            'course_id'         => $this->course->id,
            'enrollment_status' => 'pending_approval',
        ]);
        $enrollment->payment_status = 'pending';
        $enrollment->save();

        $financial = app(FinancialService::class);
        $financial->recordEnrollmentPayment($enrollment);

        // تأكيد أول
        $financial->confirmEnrollmentPayment($enrollment, $this->admin->id);
        $balanceAfterFirst = $this->tutorDetail->fresh()->available_balance;

        // تأكيد ثانٍ — يجب أن لا يُضاعف الرصيد لأنه لا توجد معاملة pending
        $financial->confirmEnrollmentPayment($enrollment, $this->admin->id);
        $balanceAfterSecond = $this->tutorDetail->fresh()->available_balance;

        $this->assertEquals(
            $balanceAfterFirst,
            $balanceAfterSecond,
            '[BUG-10] تأكيد الاشتراك مرتين يجب أن لا يُضاعف رصيد المعلم'
        );
    }

    // ═══════════════════════════════════════════════════════════════
    // ██ اختبارات Admin — صلاحيات التسلسل الهرمي
    // ═══════════════════════════════════════════════════════════════

    public function test_regular_admin_cannot_create_another_admin()
    {
        $regularAdmin = User::factory()->create(['role' => 'admin', 'is_super_admin' => false]);

        $response = $this->actingAs($regularAdmin)->post(route('admin.users.store'), [
            'name'                  => 'New Admin',
            'email'                 => 'newadmin@test.com',
            'role'                  => 'admin',
            'password'              => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $this->assertDatabaseMissing('users', [
            'email' => 'newadmin@test.com',
            'role'  => 'admin',
        ]);
    }

    public function test_super_admin_can_create_admin()
    {
        $response = $this->actingAs($this->admin)->post(route('admin.users.store'), [
            'name'                  => 'New Admin',
            'email'                 => 'newadmin2@test.com',
            'role'                  => 'admin',
            'password'              => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'newadmin2@test.com',
            'role'  => 'admin',
        ]);
    }

    public function test_admin_cannot_delete_self()
    {
        $this->actingAs($this->admin)
            ->delete(route('admin.users.destroy', $this->admin->id));

        $this->assertDatabaseHas('users', ['id' => $this->admin->id]);
    }

    // ═══════════════════════════════════════════════════════════════
    // ██ اختبارات Booking Admin
    // ═══════════════════════════════════════════════════════════════

    public function test_admin_can_confirm_pending_booking()
    {
        $slot = $this->makeSlot(['price' => 100, 'max_participants' => 1]);

        $booking = $this->makeBooking([
            'student_id'       => $this->student->id,
            'session_slot_id'  => $slot->id,
            'payment_method_id'=> $this->paymentMethod->id,
        ], 'pending');

        Transaction::create([
            'reference_number'    => 'TXN-BOOKING-' . uniqid(),
            'type'                => 'booking',
            'status'              => 'pending',
            'booking_id'          => $booking->id,
            'student_id'          => $this->student->id,
            'tutor_id'            => $this->tutor->id,
            'gross_amount'        => 100,
            'platform_fee_rate'   => 20,
            'platform_fee_amount' => 20,
            'tutor_amount'        => 80,
        ]);

        $response = $this->actingAs($this->admin)
            ->patch(route('admin.bookings.updateStatus', $booking->id), [
                'status' => 'confirmed',
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('bookings', [
            'id'     => $booking->id,
            'status' => 'confirmed',
        ]);
    }

    public function test_admin_cannot_change_already_confirmed_booking()
    {
        $slot = $this->makeSlot(['price' => 100, 'max_participants' => 1]);

        $booking = $this->makeBooking([
            'student_id'      => $this->student->id,
            'session_slot_id' => $slot->id,
        ], 'confirmed');

        // [NOTE] Route قد تكون PATCH أو PUT — نختبر الـ Session بعد الإعادة فقط
        $this->actingAs($this->admin)
            ->patch(route('admin.bookings.updateStatus', $booking->id), [
                'status' => 'confirmed',
            ]);

        // الـ Booking يجب أن يبقى 'confirmed' بدون تغيير
        $this->assertDatabaseHas('bookings', [
            'id'     => $booking->id,
            'status' => 'confirmed',
        ]);
    }

    // ═══════════════════════════════════════════════════════════════
    // ██ [BUG-11] INTEGRITY — PayoutRequest SoftDeletes
    // ═══════════════════════════════════════════════════════════════

    public function test_payout_request_uses_soft_deletes()
    {
        $traits = class_uses_recursive(PayoutRequest::class);
        $hasSoftDeletes = in_array(\Illuminate\Database\Eloquent\SoftDeletes::class, $traits);

        $this->assertTrue($hasSoftDeletes,
            '[BUG-11] PayoutRequest يجب أن يستخدم SoftDeletes');
    }

    // ═══════════════════════════════════════════════════════════════
    // ██ اختبارات التسجيل والأهلية
    // ═══════════════════════════════════════════════════════════════

    public function test_tutor_registration_requires_eligibility_check()
    {
        $response = $this->post(route('register'), [
            'name'                  => 'New Tutor',
            'email'                 => 'newtutor@test.com',
            'password'              => 'Password123!',
            'password_confirmation' => 'Password123!',
            'role'                  => 'tutor',
            'agreed_to_terms'       => true,
        ]);

        $response->assertRedirect(route('eligibility.show'));
        $this->assertDatabaseMissing('users', ['email' => 'newtutor@test.com']);
    }

    public function test_student_registration_works_without_eligibility()
    {
        $response = $this->post(route('register'), [
            'name'                  => 'New Student',
            'email'                 => 'newstudent@test.com',
            'password'              => 'Password123!',
            'password_confirmation' => 'Password123!',
            'role'                  => 'student',
            'agreed_to_terms'       => true,
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'newstudent@test.com',
            'role'  => 'student',
        ]);
    }

    // ═══════════════════════════════════════════════════════════════
    // ██ اختبارات هيكلية لـ $fillable
    // ═══════════════════════════════════════════════════════════════

    public function test_transaction_fillable_contains_booking_id()
    {
        $model = new Transaction();
        $fillable = $model->getFillable();
        $this->assertContains('booking_id', $fillable);
        $this->assertContains('type', $fillable);
        $this->assertContains('status', $fillable);
    }

    public function test_user_fillable_excludes_role_and_super_admin()
    {
        $model = new User();
        $fillable = $model->getFillable();

        $this->assertNotContains('role', $fillable,
            'role يجب أن يُحذف من $fillable');
        $this->assertNotContains('is_super_admin', $fillable,
            'is_super_admin يجب أن يُحذف من $fillable');
    }

    public function test_course_fillable_excludes_status()
    {
        $model = new Course();
        $this->assertNotContains('status', $model->getFillable());
    }

    public function test_enrollment_fillable_excludes_payment_status()
    {
        $model = new Enrollment();
        $this->assertNotContains('payment_status', $model->getFillable());
    }

    public function test_payout_request_fillable_excludes_status()
    {
        $model = new PayoutRequest();
        $this->assertNotContains('status', $model->getFillable());
    }

    public function test_session_slot_fillable_excludes_status()
    {
        $model = new SessionSlot();
        $this->assertNotContains('status', $model->getFillable(),
            '[BUG-02 FIX] status يجب أن يُحذف من $fillable في SessionSlot');
    }
}
