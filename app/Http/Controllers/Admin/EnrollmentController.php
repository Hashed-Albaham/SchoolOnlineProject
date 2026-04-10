<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Services\FinancialService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;

/**
 * [A6] Admin Enrollment Management Controller
 * 
 * Allows admin to view and manage all enrollments/subscriptions.
 * Created as part of the LARA-PROSKILL-ARCHITECT-v4.0 audit.
 */
class EnrollmentController extends Controller
{
    /**
     * Display all enrollments with filtering.
     */
    public function index(Request $request)
    {
        $status = $request->get('status');
        $enrollmentStatus = $request->get('enrollment_status');
        $search = $request->get('search');

        $query = Enrollment::with(['user', 'course.tutor'])
            ->when($status, fn($q) => $q->where('payment_status', $status))
            ->when($enrollmentStatus, fn($q) => $q->where('enrollment_status', $enrollmentStatus))
            ->when($search, function ($q) use ($search) {
                $safe = str_replace(['%', '_'], ['\%', '\_'], $search);
                $q->whereHas('user', fn($q2) => $q2->where('name', 'like', "%{$safe}%"))
                  ->orWhereHas('course', fn($q2) => $q2->where('title', 'like', "%{$safe}%"));
            })
            ->latest();

        // Counts
        $counts = Enrollment::selectRaw("
            COUNT(*) as total,
            SUM(CASE WHEN payment_status = 'paid' THEN 1 ELSE 0 END) as paid,
            SUM(CASE WHEN payment_status = 'pending' THEN 1 ELSE 0 END) as pending,
            SUM(CASE WHEN enrollment_status = 'approved' THEN 1 ELSE 0 END) as approved_enrollments,
            SUM(CASE WHEN enrollment_status = 'pending_approval' THEN 1 ELSE 0 END) as pending_enrollments
        ")->first();

        $enrollments = $query->paginate(15)->withQueryString();

        // Revenue calculation
        $totalRevenue = Enrollment::where('payment_status', 'paid')
            ->join('courses', 'enrollments.course_id', '=', 'courses.id')
            ->sum('courses.price');

        return view('admin.enrollments.index', compact('enrollments', 'counts', 'status', 'enrollmentStatus', 'search', 'totalRevenue'));
    }

    /**
     * Update enrollment statuses manually.
     *
    public function updateStatus(Request $request, Enrollment $enrollment): RedirectResponse
    {
        $this->authorize('update', $enrollment);

        $request->validate([
            'payment_status' => ['required', 'string', 'in:paid,pending,completed,failed,refunded'],
            'enrollment_status' => ['required', 'string', 'in:pending_approval,approved,rejected,enrolled'],
        ]);

        $oldStatus = $enrollment->payment_status;
        $newStatus = $request->payment_status;
        $enrollmentStatus = $request->enrollment_status;
        $financial = app(FinancialService::class);

        DB::transaction(function () use ($enrollment, $oldStatus, $newStatus, $enrollmentStatus, $financial) {
            $enrollment->update([
                'payment_status' => $newStatus,
                'enrollment_status' => $enrollmentStatus,
            ]);

            // [FIN] تحديث المعاملات المالية حسب الحالة الجديدة
            if ($newStatus === 'paid' && $oldStatus !== 'paid') {
                $financial->confirmEnrollmentPayment($enrollment, auth()->id());
                // تأكد أن enrollment_status = approved إذا تم تأكيد الدفع وكانت مطلوبة
                $enrollment->update(['enrollment_status' => 'approved']);
            } elseif ($newStatus === 'failed' && $oldStatus === 'pending') {
                $financial->failEnrollmentPayment($enrollment);
            }
        });

        return back()->with('success', __('site.fin_payment_status_updated'));
    }

    /**
     * Refund an enrollment
     */
    /**
     * Update enrollment statuses manually.
     */
    public function updateStatus(Request $request, Enrollment $enrollment): RedirectResponse
    {
        $this->authorize('update', $enrollment);

        $request->validate([
            'payment_status' => ['required', 'string', 'in:paid,pending,completed,failed,refunded'],
            'enrollment_status' => ['required', 'string', 'in:pending_approval,approved,rejected,enrolled'],
        ]);

        $oldStatus = $enrollment->payment_status;
        $newStatus = $request->payment_status;
        $enrollmentStatus = $request->enrollment_status;
        $financial = app(FinancialService::class);

        DB::transaction(function () use ($enrollment, $oldStatus, $newStatus, $enrollmentStatus, $financial) {
            // 1. تحديث حالة الاشتراك في جدول enrollments
            $enrollment->update([
                'payment_status' => $newStatus,
                'enrollment_status' => $enrollmentStatus,
            ]);

            // 2. الربط المالي: إذا تحولت الحالة إلى "مدفوع" ولم تكن كذلك من قبل
            if ($newStatus === 'paid' && $oldStatus !== 'paid') {
                
                // [تأكد من وجود سجل مالي] - إذا لم تكن هناك معاملة مسجلة لهذا الاشتراك، أنشئها الآن
                $exists = \App\Models\Transaction::where('enrollment_id', $enrollment->id)
                    ->where('type', 'enrollment')
                    ->exists();

                if (!$exists) {
                    $financial->recordEnrollmentPayment($enrollment);
                }

                // [توزيع الأرباح] - تأكيد الدفع ونقل المبلغ لمحفظة المعلم وحساب عمولة المنصة
                $financial->confirmEnrollmentPayment($enrollment, auth()->id());
                
                // [تفعيل الوصول] - تأكيد حالة القبول ليتمكن الطالب من مشاهدة الكورس
                $enrollment->update(['enrollment_status' => 'approved']);

            } elseif ($newStatus === 'failed' && $oldStatus === 'pending') {
                // في حالة الفشل، قم بإلغاء أي رصيد معلق
                $financial->failEnrollmentPayment($enrollment);
            }
        });

        return back()->with('success', __('site.fin_payment_status_updated'));
    }

    public function refund(Request $request, Enrollment $enrollment): RedirectResponse
    {
        $request->validate(['notes' => 'nullable|string|max:500']);

        // التحقق: يجب أن يكون الاشتراك مكتملاً
        if ($enrollment->payment_status !== 'paid') {
            return back()->withErrors(['error' => __('site.fin_refund_only_completed')]);
        }

        try {
            app(FinancialService::class)->processRefund(
                $enrollment,
                auth()->id(),
                $request->notes ?? ''
            );

            DB::transaction(function () use ($enrollment) {
                $enrollment->update([
                    'payment_status'    => 'refunded',
                    'enrollment_status' => 'rejected',
                ]);
            });

            return back()->with('success', __('site.fin_refund_success'));
        } catch (\Exception $e) {
            return back()->withErrors(['error' => __('site.fin_refund_failed')]);
        }
    }
}
