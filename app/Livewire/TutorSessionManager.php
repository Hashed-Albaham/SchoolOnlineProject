<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\SessionSlot;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;

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
                $q->whereIn('status', ['pending', 'confirmed']);
            }])
            ->orderBy('start_time', 'desc')
            ->paginate(10);

        $courses = Course::where('tutor_id', $user->id)
            ->where('status', 'approved')
            ->get();

        return view('livewire.tutor-session-manager', compact('sessions', 'courses'));
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
            'status' => 'scheduled',
        ];

        if ($this->editId) {
            $session = SessionSlot::where('tutor_id', Auth::id())->findOrFail($this->editId);
            $session->update($data);
            session()->flash('success', __('site.session_updated'));
        } else {
            SessionSlot::create($data);
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
}
