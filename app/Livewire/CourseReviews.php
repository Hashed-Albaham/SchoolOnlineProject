<?php

namespace App\Livewire;

use App\Models\Course;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class CourseReviews extends Component
{
    use WithPagination;

    public $courseId;
    public $rating = 5;
    public $comment = '';
    public $canReview = false;

    protected $rules = [
        'rating' => 'required|integer|min:1|max:5',
        'comment' => 'nullable|string|max:1000',
    ];

    public function mount($courseId)
    {
        $this->courseId = $courseId;
        $this->checkReviewCapability();
    }

    public function checkReviewCapability()
    {
        if (!Auth::check()) {
            $this->canReview = false;
            return;
        }

        $user = Auth::user();
        $course = Course::find($this->courseId);

        // Check availability: User is enrolled + User hasn't reviewed yet + User is student
        $isEnrolled = $course->enrollments()->where('user_id', $user->id)->exists();
        $hasReviewed = $course->reviews()->where('user_id', $user->id)->exists();

        $this->canReview = $isEnrolled && !$hasReviewed && $user->role === 'student';
    }

    public function submitReview()
    {
        if (!$this->canReview) {
            return;
        }

        $this->validate();

        Review::create([
            'user_id' => Auth::id(),
            'course_id' => $this->courseId,
            'rating' => $this->rating,
            'comment' => $this->comment,
        ]);

        $this->comment = '';
        $this->canReview = false; // Disable form after submission
        $this->dispatch('review-added'); // Optional: Notification
    }

    public function render()
    {
        $reviews = Review::where('course_id', $this->courseId)
            ->with('user')
            ->latest()
            ->paginate(5);

        return view('livewire.course-reviews', [
            'reviews' => $reviews,
            'averageRating' => Review::where('course_id', $this->courseId)->avg('rating') ?? 0,
            'totalReviews' => Review::where('course_id', $this->courseId)->count(),
        ]);
    }
}
