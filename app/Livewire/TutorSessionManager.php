<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\SessionSlot;
use App\Models\Course;
use App\Models\Booking;
use App\Services\FinancialService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TutorSessionManager extends Component
{
    use WithPagination;

    public $type = '1-on-1';
    public $course_id = null;
    public $price = 0;
    public $max_participants = 1;
    public $start_time;
    public $end_time;
    public $meeting_link;

    public $showForm = false;
    public $editId = null;

    protected $rules = [
        'type' => 'required|in:1-on-1,group',
        'course_id' => 'nullable|exists:courses,id',
        'price' => 'required|numeric|min:0',
        'max_participants' => 'required|integer|min:1',
        'start_time' => 'required|date|after:now',
        'end_time' => 'required|date|after:start_time',
        'meeting_link' => 'nullable|url',
    ];

    public function mount()
    {
        // Init default times
        $this->start_time = now()->addHour()->format('Y-m-d\TH:i');
        $this->end_time = now()->addHours(2)->format('Y-m-d\TH:i');
    }

    public function render()
    {
        $user = Auth::user();
        
        $sessions = SessionSlot::where('tutor_id', $user->id)
            ->with(['course', 'bookings' => function($q) {
                $q->whereIn('status', ['pending', 'pending_tutor_approval', 'confirmed']);
            }])
            ->orderBy('start_time', 'desc')
            ->paginate(10);

        $courses = Course::where('tutor_id', $user->id)
            ->where('status', 'approved')
            ->get();

        $pendingBookings = \App\Models\Booking::whereHas('sessionSlot', function($q) use ($user) {
            $q->where('tutor_id', $user->id);
        })->where('status', 'pending_tutor_approval')
          ->with(['student', 'sessionSlot.course'])
          ->latest()
          ->get();

        return view('livewire.tutor-session-manager', compact('sessions', 'courses', 'pendingBookings'));
    }

    public function toggleForm()
    {
        $this->showForm = !$this->showForm;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->editId = null;
        $this->type = '1-on-1';
        $this->course_id = null;
        $this->price = 0;
        $this->max_participants = 1;
        $this->start_time = now()->addHour()->format('Y-m-d\TH:i');
        $this->end_time = now()->addHours(2)->format('Y-m-d\TH:i');
        $this->meeting_link = null;
    }

    public function save()
    {
        $this->validate();

        // Enforce max participants for 1-on-1
        if ($this->type === '1-on-1') {
            $this->max_participants = 1;
        }

        $data = [
            'tutor_id' => Auth::id(),
            'course_id' => $this->course_id ?: null,
            'type' => $this->type,
            'price' => $this->price,
            'max_participants' => $this->max_participants,
            // Carbon will handle conversion to UTC assuming app timezone is set
            'start_time' => \Carbon\Carbon::parse($this->start_time)->timezone('UTC'),
            'end_time' => \Carbon\Carbon::parse($this->end_time)->timezone('UTC'),
            'meeting_link' => $this->meeting_link,
            // [BUG-02 FIX] 'status' removed from $data — set explicitly below
        ];

        if ($this->editId) {
            $session = SessionSlot::where('tutor_id', Auth::id())->findOrFail($this->editId);
            $session->update($data);
            session()->flash('success', __('site.session_updated'));
        } else {
            $session = SessionSlot::create($data);
            // [BUG-02 FIX] Explicit status assignment after removal from $fillable
            $session->status = 'scheduled';
            $session->save();
            session()->flash('success', __('site.session_created'));
        }

        $this->showForm = false;
        $this->resetForm();
    }

    public function delete($id)
    {
        $session = SessionSlot::where('tutor_id', Auth::id())->findOrFail($id);
        
        // Cannot delete if there are active bookings
        if ($session->bookings()->whereIn('status', ['confirmed'])->exists()) {
            session()->flash('error', __('site.cannot_delete_booked_session'));
            return;
        }

        $session->delete();
        session()->flash('success', __('site.session_deleted'));
    }

    public function approveBooking($bookingId)
    {
        $booking = Booking::where("status", "pending_tutor_approval")->findOrFail($bookingId);
        
        // Ensure standard security checks
        if ($booking->sessionSlot->tutor_id !== Auth::id()) abort(403);

        $booking->status = 'confirmed';
        $booking->save();
        session()->flash('success', __('site.booking_confirmed_successfully') ?? 'تم الموافقة على الحجز بنجاح.');
    }

    public function rejectBooking($bookingId, FinancialService $financialService)
    {
        $booking = Booking::where("status", "pending_tutor_approval")->findOrFail($bookingId);
        if ($booking->sessionSlot->tutor_id !== Auth::id()) abort(403);

        DB::transaction(function () use ($booking, $financialService) {
            $booking->status = 'rejected_by_tutor';
            $booking->save();

            // If it was paid, we trigger failBookingPayment to refund or revert pending balance.
            if ($booking->payment_method_id != null && $booking->transaction_id) {
                $financialService->failBookingPayment($booking);
            }
        });

        session()->flash('success', __('site.booking_rejected') ?? 'تم رفض الحجز.');
    }
}
