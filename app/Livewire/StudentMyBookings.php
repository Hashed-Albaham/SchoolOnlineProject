<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;

class StudentMyBookings extends Component
{
    use WithPagination;

    public function render()
    {
        $bookings = Booking::with(['sessionSlot.tutor', 'sessionSlot.course'])
            ->where('student_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.student-my-bookings', compact('bookings'));
    }
}
