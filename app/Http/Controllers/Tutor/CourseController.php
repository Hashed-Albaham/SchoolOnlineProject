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
            ->withCount(['enrollments', 'contents'])
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

        // Notify Admins
        $admins = \App\Models\User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new \App\Notifications\CourseSubmitted($course));
        }

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

        $rules = [
            'title' => 'required|string|max:255',
            'type' => 'required|in:video,file,image,text,quiz,link',
            'description' => 'nullable|string',
        ];

        // Type-specific validation
        switch ($request->type) {
            case 'video':
                $rules['video_source'] = 'required|in:youtube,local,external';
                break;
            case 'file':
                $rules['content_file'] = 'required|file|max:51200'; // 50MB max
                break;
            case 'image':
                $rules['content_image'] = 'required|image|max:10240'; // 10MB max
                break;
            case 'text':
                $rules['text_content'] = 'required|string';
                break;
            case 'link':
                $rules['link_url'] = 'required|url';
                break;
            case 'quiz':
                $rules['quiz_id'] = 'required|exists:quizzes,id';
                break;
        }

        $request->validate($rules);

        $maxOrder = $course->contents()->max('order') ?? 0;

        $data = [
            'title' => $request->title,
            'type' => $request->type,
            'description' => $request->description,
            'order' => $maxOrder + 1,
        ];

        // Handle content based on type
        switch ($request->type) {
            case 'video':
                if ($request->video_source === 'youtube') {
                    $youtubeId = CourseContent::extractYoutubeId($request->youtube_url);
                    if (!$youtubeId)
                        return back()->with('error', 'رابط YouTube غير صالح');
                    $data['youtube_video_id'] = $youtubeId;
                } elseif ($request->video_source === 'local') {
                    $request->validate(['video_file' => 'required|file|mimetypes:video/mp4,video/mpeg,video/quicktime|max:512000']); // 500MB
                    $data['file_path'] = $request->file('video_file')->store("courses/{$course->id}/videos", 'public');
                } elseif ($request->video_source === 'external') {
                    $request->validate(['video_url' => 'required|url']);
                    $data['link_url'] = $request->video_url;
                }
                break;

            case 'file':
                $data['file_path'] = $request->file('content_file')->store("courses/{$course->id}/files", 'public');
                break;

            case 'image':
                $data['file_path'] = $request->file('content_image')->store("courses/{$course->id}/images", 'public');
                break;

            case 'text':
                $data['text_content'] = $request->text_content;
                break;

            case 'link':
                $data['link_url'] = $request->link_url;
                break;

            case 'quiz':
                $data['quiz_id'] = $request->quiz_id;
                break;
        }

        $course->contents()->create($data);

        return back()->with('success', 'تمت إضافة المحتوى بنجاح');
    }

    /**
     * Show edit content form
     */
    public function editContent(Course $course, CourseContent $content)
    {
        $this->authorize('update', $course);

        if ($content->course_id !== $course->id) {
            abort(403);
        }

        return view('tutor.courses.content.edit', compact('course', 'content'));
    }

    /**
     * Update content
     */
    public function updateContent(Request $request, Course $course, CourseContent $content)
    {
        $this->authorize('update', $course);

        if ($content->course_id !== $course->id) {
            abort(403);
        }

        $rules = [
            'title' => 'required|string|max:255',
            'type' => 'required|in:video,file,image,text,quiz,link',
            'description' => 'nullable|string',
        ];

        // Type-specific validation (same as store but nullable for files if not replacing)
        switch ($request->type) {
            case 'video':
                $rules['youtube_url'] = 'required|string';
                break;
            case 'file':
                $rules['content_file'] = 'nullable|file|max:51200';
                break;
            case 'image':
                $rules['content_image'] = 'nullable|image|max:10240';
                break;
            case 'text':
                $rules['text_content'] = 'required|string';
                break;
            case 'link':
                $rules['link_url'] = 'required|url';
                break;
            case 'quiz':
                $rules['quiz_id'] = 'required|exists:quizzes,id';
                break;
        }

        $request->validate($rules);

        $data = [
            'title' => $request->title,
            'type' => $request->type,
            'description' => $request->description,
        ];

        switch ($request->type) {
            case 'video':
                if ($request->video_source === 'youtube') {
                    $youtubeId = CourseContent::extractYoutubeId($request->youtube_url);
                    if (!$youtubeId)
                        return back()->with('error', 'رابط YouTube غير صالح');
                    $data['youtube_video_id'] = $youtubeId;
                    $data['file_path'] = null;
                    $data['link_url'] = null;
                } elseif ($request->video_source === 'local') {
                    if ($request->hasFile('video_file')) {
                        $request->validate(['video_file' => 'required|file|mimetypes:video/mp4,video/mpeg,video/quicktime|max:512000']);
                        if ($content->file_path && $content->isVideo())
                            Storage::disk('public')->delete($content->file_path);
                        $data['file_path'] = $request->file('video_file')->store("courses/{$course->id}/videos", 'public');
                        $data['youtube_video_id'] = null;
                        $data['link_url'] = null;
                    }
                } elseif ($request->video_source === 'external') {
                    $request->validate(['video_url' => 'required|url']);
                    $data['link_url'] = $request->video_url;
                    $data['youtube_video_id'] = null;
                    $data['file_path'] = null;
                }
                break;

            case 'file':
                if ($request->hasFile('content_file')) {
                    // Delete old file if exists
                    if ($content->file_path && $content->isFile()) {
                        Storage::disk('public')->delete($content->file_path);
                    }
                    $data['file_path'] = $request->file('content_file')->store("courses/{$course->id}/files", 'public');
                }
                break;

            case 'image':
                if ($request->hasFile('content_image')) {
                    if ($content->file_path && $content->isImage()) {
                        Storage::disk('public')->delete($content->file_path);
                    }
                    $data['file_path'] = $request->file('content_image')->store("courses/{$course->id}/images", 'public');
                }
                break;

            case 'text':
                $data['text_content'] = $request->text_content;
                break;

            case 'link':
                $data['link_url'] = $request->link_url;
                break;

            case 'quiz':
                $data['quiz_id'] = $request->quiz_id;
                break;
        }

        $content->update($data);

        return redirect()->route('tutor.courses.edit', $course)
            ->with('success', 'تم تحديث المحتوى بنجاح');
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

        // Get certificate requests for this course
        $certificateRequests = \App\Models\CourseCertificate::where('course_id', $course->id)
            ->with('user')
            ->latest()
            ->get();

        // Calculate progress for each enrollment
        $enrollmentsWithProgress = $course->enrollments->map(function ($enrollment) use ($course) {
            $totalContents = $course->contents->count();
            $completedContents = $enrollment->student->getCompletedContentsCount($course->id);
            $enrollment->progress_percent = $totalContents > 0 ? round(($completedContents / $totalContents) * 100) : 0;
            $enrollment->completed_count = $completedContents;
            $enrollment->total_count = $totalContents;
            return $enrollment;
        });

        return view('tutor.courses.show', compact('course', 'certificateRequests', 'enrollmentsWithProgress'));
    }

    /**
     * Issue certificate to student
     */
    public function issueCertificate(Request $request, \App\Models\CourseCertificate $certificate)
    {
        // Check if tutor owns the course
        $course = $certificate->course;
        $this->authorize('update', $course);

        if (!$certificate->isPending()) {
            return back()->with('error', 'هذا الطلب تمت معالجته مسبقاً');
        }

        $certificate->update([
            'status' => 'approved',
            'certificate_code' => \App\Models\CourseCertificate::generateCode(),
            'issued_at' => now(),
        ]);

        // Send Notification
        $certificate->user->notify(new \App\Notifications\CertificateIssued($certificate));

        return back()->with('success', 'تم إصدار الشهادة بنجاح للطالب: ' . $certificate->user->name);
    }

    /**
     * Reject certificate request
     */
    public function rejectCertificate(Request $request, \App\Models\CourseCertificate $certificate)
    {
        $course = $certificate->course;
        $this->authorize('update', $course);

        if (!$certificate->isPending()) {
            return back()->with('error', 'هذا الطلب تمت معالجته مسبقاً');
        }

        $request->validate(['reason' => 'nullable|string|max:500']);

        $certificate->update([
            'status' => 'rejected',
            'rejection_reason' => $request->reason ?? 'لم يتم استيفاء شروط الشهادة',
        ]);

        return back()->with('success', 'تم رفض طلب الشهادة');
    }
}
