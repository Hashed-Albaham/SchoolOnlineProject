<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Admin Course Management Controller
 * 
 * [A4] Edit/Delete courses from admin panel
 * [A9] Review management (view/delete reviews)
 */
class CourseController extends Controller
{
    /**
     * Display all courses with status counts.
     */
    public function index()
    {
        $courses = Course::with('tutor')
            ->latest()
            ->paginate(10);

        // PERFORMANCE: Single query for all counts
        $stats = Course::selectRaw("
            COUNT(*) as all_count,
            SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_count,
            SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved_count,
            SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected_count
        ")->first();

        $allCount = $stats->all_count ?? 0;
        $pendingCount = $stats->pending_count ?? 0;
        $approvedCount = $stats->approved_count ?? 0;
        $rejectedCount = $stats->rejected_count ?? 0;

        return view('admin.courses.index', compact('courses', 'allCount', 'pendingCount', 'approvedCount', 'rejectedCount'));
    }

    /**
     * Display pending courses.
     */
    public function pending()
    {
        $courses = Course::where('status', 'pending')
            ->with('tutor')
            ->latest()
            ->paginate(10);

        return view('admin.courses.pending', compact('courses'));
    }

    /**
     * Approve a course.
     */
    public function approve(Course $course)
    {
        $course->update(['status' => 'approved']);

        if ($course->tutor) {
            $course->tutor->notify(new \App\Notifications\CourseStatusUpdated($course, 'approved'));
        }

        return back()->with('success', 'تم الموافقة على الكورس بنجاح');
    }

    /**
     * Reject a course.
     */
    public function reject(Course $course)
    {
        $course->update(['status' => 'rejected']);

        if ($course->tutor) {
            $course->tutor->notify(new \App\Notifications\CourseStatusUpdated($course, 'rejected'));
        }

        return back()->with('success', __('site.course_rejected_success'));
    }

    /**
     * Unapprove a course (revoke approval).
     */
    public function unapprove(Course $course)
    {
        $course->update(['status' => 'pending']);

        return back()->with('success', __('site.course_unapproved_success'));
    }

    /**
     * Show course details with reviews.
     * [A9] Reviews are now visible here for admin moderation.
     */
    public function show(Course $course)
    {
        $course->load(['tutor', 'contents', 'enrollments.user', 'reviews.user']);

        return view('admin.courses.show', compact('course'));
    }

    /**
     * [A4] Show edit form for a course.
     */
    public function edit(Course $course)
    {
        $course->load('tutor');

        return view('admin.courses.edit', compact('course'));
    }

    /**
     * [A4] Update course details from admin panel.
     */
    public function update(Request $request, Course $course)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:pending,approved,rejected',
            'thumbnail' => 'nullable|image|max:2048',
        ]);

        $course->title = $request->title;
        $course->description = $request->description;
        $course->price = $request->price;
        $course->status = $request->status;

        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail
            if ($course->thumbnail) {
                Storage::disk('public')->delete($course->thumbnail);
            }
            $course->thumbnail = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        $course->save();

        return redirect()->route('admin.courses.show', $course)
            ->with('success', __('site.course_updated_success'));
    }

    /**
     * [A4] Delete a course and its associated files.
     */
    public function destroy(Course $course)
    {
        // Delete associated files
        if ($course->thumbnail) {
            Storage::disk('public')->delete($course->thumbnail);
        }

        // Delete content files
        foreach ($course->contents as $content) {
            if ($content->file_path) {
                Storage::disk('public')->delete($content->file_path);
            }
        }

        $course->delete();

        return redirect()->route('admin.courses.index')
            ->with('success', __('site.course_deleted_success'));
    }

    /**
     * [A9] Delete an abusive/inappropriate review.
     */
    public function deleteReview(Course $course, Review $review)
    {
        if ($review->course_id !== $course->id) {
            abort(404);
        }

        $review->delete();

        return back()->with('success', __('site.review_deleted_success'));
    }

    /**
     * [A10] Delete a specific content/lesson from a course.
     */
    public function deleteContent(Course $course, \App\Models\CourseContent $content)
    {
        if ($content->course_id !== $course->id) {
            abort(404);
        }

        // Delete associated file
        if ($content->file_path) {
            Storage::disk('public')->delete($content->file_path);
        }

        $content->delete();

        return back()->with('success', __('site.content_deleted_success'));
    }
}

