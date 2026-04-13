<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\SessionSlot;
use App\Models\Course;
use App\Services\BookingService;
use Illuminate\Support\Facades\Auth;
use Exception;

class StudentSessionFinder extends Component
{
    use WithPagination;

    public $filter_type = 'all'; // all, 1-on-1, group
    public $filter_course_id = 'all';
    public $filter_price = 'all'; // all, free, paid

    protected $queryString = [
        'filter_type' => ['except' => 'all'],
        'filter_course_id' => ['except' => 'all'],
        'filter_price' => ['except' => 'all'],
    ];

    public function render()
    {
        $student = Auth::user();
        
        // Get list of courses this student is approved in to filter allowed sessions
        $enrolledCourseIds = $student->enrollments()
            ->where('enrollment_status', 'approved')
            ->pluck('course_id')
            ->toArray();

        $query = SessionSlot::with(['tutor.tutorDetails', 'course', 'bookings'])
            ->where('status', 'scheduled')
            ->where('start_time', '>', now()) // Only future sessions
            ->where(function($q) use ($enrolledCourseIds) {
                // Public sessions OR sessions of courses the student is enrolled in
                $q->whereNull('course_id')
                  ->orWhereIn('course_id', $enrolledCourseIds);
            });

        // Apply filters
        if ($this->filter_type !== 'all') {
            $query->where('type', $this->filter_type);
        }

        if ($this->filter_course_id !== 'all' && is_numeric($this->filter_course_id)) {
            $query->where('course_id', $this->filter_course_id);
        }

        if ($this->filter_price === 'free') {
            $query->where('price', 0);
        } elseif ($this->filter_price === 'paid') {
            $query->where('price', '>', 0);
        }

        $sessions = $query->orderBy('start_time', 'asc')->paginate(12);

        // For filter dropdown options
        $studentCourses = Course::whereIn('id', $enrolledCourseIds)->get();

        return view('livewire.student-session-finder', compact('sessions', 'studentCourses'));
    }

    public function bookSeat($slotId, BookingService $bookingService)
    {
        $student = Auth::user();
        $slot = SessionSlot::findOrFail($slotId);

        try {
            $booking = $bookingService->lockSeat($slot, $student);

            if ($booking->status === 'pending') {
                // Redirect to payment
                return redirect()->route('student.sessions.payment', $booking->id);
            }

            session()->flash('success', __('site.booking_confirmed_free'));
        } catch (Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function updating($name, $value)
    {
        $this->resetPage(); // Reset pagination on filter change
    }
}
