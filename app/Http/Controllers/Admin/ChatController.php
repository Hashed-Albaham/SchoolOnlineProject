<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    /**
     * [A8] Show all conversations for admin oversight
     */
    public function index(Request $request)
    {
        // Get unique conversation pairs with latest message
        $conversations = DB::table('messages')
            ->select(DB::raw('
                LEAST(sender_id, receiver_id) as user1_id,
                GREATEST(sender_id, receiver_id) as user2_id,
                MAX(id) as last_message_id,
                COUNT(*) as message_count
            '))
            ->groupBy('user1_id', 'user2_id')
            ->orderByDesc('last_message_id')
            ->paginate(20);

        // Get user data and last messages
        $userIds = collect();
        $messageIds = collect();
        foreach ($conversations as $conv) {
            $userIds->push($conv->user1_id, $conv->user2_id);
            $messageIds->push($conv->last_message_id);
        }

        $users = User::whereIn('id', $userIds->unique())->get()->keyBy('id');
        $lastMessages = Message::whereIn('id', $messageIds)->get()->keyBy('id');

        // Stats
        $stats = [
            'total_messages'       => Message::count(),
            'total_conversations'  => DB::table('messages')
                ->selectRaw('LEAST(sender_id, receiver_id), GREATEST(sender_id, receiver_id)')
                ->distinct()
                ->count(),
            'today_messages'       => Message::whereDate('created_at', today())->count(),
            'active_chatters'      => Message::whereDate('created_at', today())
                ->distinct('sender_id')
                ->count('sender_id'),
        ];

        return view('admin.chat.index', compact('conversations', 'users', 'lastMessages', 'stats'));
    }

    /**
     * [A8] View a specific conversation between two users
     */
    public function show(User $user1, User $user2)
    {
        $messages = Message::where(function ($q) use ($user1, $user2) {
            $q->where('sender_id', $user1->id)->where('receiver_id', $user2->id);
        })->orWhere(function ($q) use ($user1, $user2) {
            $q->where('sender_id', $user2->id)->where('receiver_id', $user1->id);
        })->with(['sender', 'receiver'])->orderBy('created_at', 'asc')->get();

        return view('admin.chat.show', compact('user1', 'user2', 'messages'));
    }

    /**
     * [A8] Delete a specific message
     */
    public function destroyMessage(Message $message)
    {
        $message->delete();
        return back()->with('success', __('site.message_deleted'));
    }
}
