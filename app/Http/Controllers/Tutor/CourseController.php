<?php

namespace App\Http\Controllers\Tutor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{
    /**
     * Display tutor's courses
     */
    public function index()
    {
        $courses = Auth::user()->courses()
            ->withCount('enrollments')
            ->latest()
            ->paginate(10);

        return view('tutor.courses.index', compact('courses'));
    }

    /**
     * Show create course form
     */
    public function create()
    {
        return view('tutor.courses.create');
    }

    /**
     * Store a new course
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'thumbnail' => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['title', 'description', 'price']);
        $data['tutor_id'] = Auth::id();
        $data['status'] = 'pending';

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        $course = Course::create($data);

        return redirect()->route('tutor.courses.edit', $course)
            ->with('success', 'تم إنشاء الكورس بنجاح. يمكنك الآن إضافة المحتوى.');
    }

    /**
     * Show edit course form
     */
    public function edit(Course $course)
    {
        $this->authorize('update', $course);

        $course->load('contents');

        return view('tutor.courses.edit', compact('course'));
    }

    /**
     * Update course
     */
    public function update(Request $request, Course $course)
    {
        $this->authorize('update', $course);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'thumbnail' => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['title', 'description', 'price']);

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail
            if ($course->thumbnail) {
                Storage::disk('public')->delete($course->thumbnail);
            }
            $data['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        $course->update($data);

        return back()->with('success', 'تم تحديث الكورس بنجاح');
    }

    /**
     * Delete course
     */
    public function destroy(Course $course)
    {
        $this->authorize('delete', $course);

        // Delete thumbnail
        if ($course->thumbnail) {
            Storage::disk('public')->delete($course->thumbnail);
        }

        $course->delete();

        return redirect()->route('tutor.courses.index')
            ->with('success', 'تم حذف الكورس بنجاح');
    }

    /**
     * Add content to course
     */
    public function addContent(Request $request, Course $course)
    {
        $this->authorize('update', $course);

        $request->validate([
            'title' => 'required|string|max:255',
            'youtube_url' => 'required|string',
            'description' => 'nullable|string',
        ]);

        // Extract YouTube ID
        $youtubeId = CourseContent::extractYoutubeId($request->youtube_url);

        if (!$youtubeId) {
            return back()->with('error', 'رابط YouTube غير صالح');
        }

        $maxOrder = $course->contents()->max('order') ?? 0;

        $course->contents()->create([
            'title' => $request->title,
            'youtube_video_id' => $youtubeId,
            'description' => $request->description,
            'order' => $maxOrder + 1,
        ]);

        return back()->with('success', 'تمت إضافة المحتوى بنجاح');
    }

    /**
     * Delete content
     */
    public function deleteContent(Course $course, CourseContent $content)
    {
        $this->authorize('update', $course);

        if ($content->course_id !== $course->id) {
            abort(403);
        }

        $content->delete();

        return back()->with('success', 'تم حذف المحتوى بنجاح');
    }

    /**
     * Reorder contents
     */
    public function reorderContents(Request $request, Course $course)
    {
        $this->authorize('update', $course);

        $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer|exists:course_contents,id',
        ]);

        foreach ($request->order as $index => $contentId) {
            CourseContent::where('id', $contentId)
                ->where('course_id', $course->id)
                ->update(['order' => $index + 1]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Show course details
     */
    public function show(Course $course)
    {
        $this->authorize('view', $course);

        $course->load(['contents', 'enrollments.student']);

        return view('tutor.courses.show', compact('course'));
    }
}
