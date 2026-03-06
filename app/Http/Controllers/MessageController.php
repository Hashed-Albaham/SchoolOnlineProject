<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    /**
     * [MSG1] Get IDs of users the current user is allowed to chat with.
     * Rules:
     *   - Admin: can chat with everyone
     *   - Tutor: can chat with their enrolled students + admins
     *   - Student: can chat with tutors of enrolled courses + classmates + admins
     */
    private function getAllowedContactIds(): \Illuminate\Support\Collection
    {
        $user = Auth::user();
        $userId = $user->id;

        // Admin can chat with everyone
        if ($user->role === 'admin') {
            return User::where('id', '!=', $userId)->pluck('id');
        }

        $allowedIds = collect();

        // Always allow chatting with admins
        $adminIds = User::where('role', 'admin')->pluck('id');
        $allowedIds = $allowedIds->merge($adminIds);

        if ($user->role === 'tutor') {
            // Tutor can chat with students enrolled in their courses (paid & approved)
            $studentIds = Enrollment::whereHas('course', fn($q) => $q->where('tutor_id', $userId))
                ->where('payment_status', 'paid')
                ->where('enrollment_status', 'approved')
                ->pluck('user_id');
            $allowedIds = $allowedIds->merge($studentIds);
        }

        if ($user->role === 'student') {
            // Student can chat with tutors of courses they're enrolled in
            $enrolledCourseIds = Enrollment::where('user_id', $userId)
                ->where('payment_status', 'paid')
                ->where('enrollment_status', 'approved')
                ->pluck('course_id');

            // Tutors of enrolled courses
            $tutorIds = \App\Models\Course::whereIn('id', $enrolledCourseIds)->pluck('tutor_id');
            $allowedIds = $allowedIds->merge($tutorIds);

            // Classmates (students in same courses)
            $classmateIds = Enrollment::whereIn('course_id', $enrolledCourseIds)
                ->where('user_id', '!=', $userId)
                ->where('payment_status', 'paid')
                ->where('enrollment_status', 'approved')
                ->pluck('user_id');
            $allowedIds = $allowedIds->merge($classmateIds);
        }

        return $allowedIds->unique();
    }

    /**
     * [MSG1] Public static method for use from ChatBox Livewire component
     */
    public function getAllowedContactIdsStatic(User $user): \Illuminate\Support\Collection
    {
        $userId = $user->id;

        if ($user->role === 'admin') {
            return User::where('id', '!=', $userId)->pluck('id');
        }

        $allowedIds = collect();
        $adminIds = User::where('role', 'admin')->pluck('id');
        $allowedIds = $allowedIds->merge($adminIds);

        if ($user->role === 'tutor') {
            $studentIds = Enrollment::whereHas('course', fn($q) => $q->where('tutor_id', $userId))
                ->where('payment_status', 'paid')
                ->where('enrollment_status', 'approved')
                ->pluck('user_id');
            $allowedIds = $allowedIds->merge($studentIds);
        }

        if ($user->role === 'student') {
            $enrolledCourseIds = Enrollment::where('user_id', $userId)
                ->where('payment_status', 'paid')
                ->where('enrollment_status', 'approved')
                ->pluck('course_id');

            $tutorIds = \App\Models\Course::whereIn('id', $enrolledCourseIds)->pluck('tutor_id');
            $allowedIds = $allowedIds->merge($tutorIds);

            $classmateIds = Enrollment::whereIn('course_id', $enrolledCourseIds)
                ->where('user_id', '!=', $userId)
                ->where('payment_status', 'paid')
                ->where('enrollment_status', 'approved')
                ->pluck('user_id');
            $allowedIds = $allowedIds->merge($classmateIds);
        }

        return $allowedIds->unique();
    }

    /**
     * Get unread message counts per sender for the current user
     */
    private function getUnreadCounts($userId)
    {
        return Message::where('receiver_id', $userId)
            ->where('is_read', false)
            ->selectRaw('sender_id, COUNT(*) as unread_count')
            ->groupBy('sender_id')
            ->pluck('unread_count', 'sender_id');
    }

    /**
     * Get last message for each contact.
     *
     * PERFORMANCE FIX [M2]: Replaced N+1 loop with single optimized query.
     * Before: 1 query per contact (N queries)
     * After: 1 query total using subquery + groupBy
     *
     * @param  int  $userId
     * @param  \Illuminate\Support\Collection  $contactIds
     * @return array
     */
    private function getLastMessages($userId, $contactIds)
    {
        if ($contactIds->isEmpty()) {
            return [];
        }

        // PERFORMANCE FIX [M2]: Single query to get all last messages
        $messages = Message::whereIn('id', function ($query) use ($userId, $contactIds) {
            $query->selectRaw('MAX(id)')
                ->from('messages')
                ->where(function ($q) use ($userId, $contactIds) {
                    $q->where('sender_id', $userId)
                        ->whereIn('receiver_id', $contactIds);
                })
                ->orWhere(function ($q) use ($userId, $contactIds) {
                    $q->whereIn('sender_id', $contactIds)
                        ->where('receiver_id', $userId);
                })
                ->groupByRaw('CASE WHEN sender_id = ? THEN receiver_id ELSE sender_id END', [$userId]);
        })->get();

        $lastMessages = [];
        foreach ($messages as $message) {
            $contactId = $message->sender_id === $userId ? $message->receiver_id : $message->sender_id;
            $lastMessages[$contactId] = $message;
        }

        return $lastMessages;
    }

    /**
     * Display a listing of conversations.
     */
    public function index()
    {
        $userId = Auth::id();
        $allowedIds = $this->getAllowedContactIds();

        $searchQuery = request()->query('search');
        $isSearch = false;

        if ($searchQuery) {
            $sanitizedSearch = addcslashes($searchQuery, '%_\\');
            // [MSG1] Search only within allowed contacts
            $contacts = User::where('name', 'like', '%' . $sanitizedSearch . '%')
                ->where('id', '!=', $userId)
                ->whereIn('id', $allowedIds)
                ->limit(50)
                ->get();
            $isSearch = true;
        } else {
            // Show existing conversations (only with allowed contacts)
            $sentIDs = Message::where('sender_id', $userId)->pluck('receiver_id');
            $receivedIDs = Message::where('receiver_id', $userId)->pluck('sender_id');

            $contactIDs = $sentIDs->merge($receivedIDs)->unique()->intersect($allowedIds);
            $contacts = User::whereIn('id', $contactIDs)->get();
        }

        $unreadCounts = $this->getUnreadCounts($userId);
        $lastMessages = $this->getLastMessages($userId, $contacts->pluck('id'));

        // Sort contacts: those with unread messages first, then by last message time
        $contacts = $contacts->sortByDesc(function ($contact) use ($unreadCounts, $lastMessages) {
            $hasUnread = $unreadCounts->get($contact->id, 0) > 0 ? 1 : 0;
            $lastTime = isset($lastMessages[$contact->id]) ? $lastMessages[$contact->id]->created_at->timestamp : 0;
            return $hasUnread * 10000000000 + $lastTime;
        })->values();

        return view('messages.index', compact('contacts', 'searchQuery', 'isSearch', 'unreadCounts', 'lastMessages'));
    }

    /**
     * Show chat with a specific user.
     */
    public function show(User $user)
    {
        $userId = Auth::id();
        $allowedIds = $this->getAllowedContactIds();

        // [MSG1] Check if the user is allowed to chat with this person
        if (!$allowedIds->contains($user->id)) {
            abort(403, __('site.chat_not_allowed'));
        }

        // Mark messages from this user as read
        Message::where('sender_id', $user->id)
            ->where('receiver_id', $userId)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $searchQuery = request()->query('search');
        $isSearch = false;

        if ($searchQuery) {
            $sanitizedSearch = addcslashes($searchQuery, '%_\\');
            // [MSG1] Search only within allowed contacts
            $contacts = User::where('name', 'like', '%' . $sanitizedSearch . '%')
                ->where('id', '!=', $userId)
                ->whereIn('id', $allowedIds)
                ->limit(50)
                ->get();
            $isSearch = true;
        } else {
            $sentIDs = Message::where('sender_id', $userId)->pluck('receiver_id');
            $receivedIDs = Message::where('receiver_id', $userId)->pluck('sender_id');
            $contactIDs = $sentIDs->merge($receivedIDs)->unique()->intersect($allowedIds);
            $contacts = User::whereIn('id', $contactIDs)->get();

            if (!$contacts->contains('id', $user->id)) {
                $contacts->prepend($user);
            }
        }

        $unreadCounts = $this->getUnreadCounts($userId);
        $lastMessages = $this->getLastMessages($userId, $contacts->pluck('id'));

        $contacts = $contacts->sortByDesc(function ($contact) use ($unreadCounts, $lastMessages) {
            $hasUnread = $unreadCounts->get($contact->id, 0) > 0 ? 1 : 0;
            $lastTime = isset($lastMessages[$contact->id]) ? $lastMessages[$contact->id]->created_at->timestamp : 0;
            return $hasUnread * 10000000000 + $lastTime;
        })->values();

        return view('messages.index', compact('contacts', 'user', 'searchQuery', 'isSearch', 'unreadCounts', 'lastMessages'));
    }
}
