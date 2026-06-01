<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Http\Request;

class ChatAdminController extends Controller
{
    public function index(Request $request)
    {
        $query = ChatMessage::with('user')->where('is_deleted', false);

        if ($request->filled('channel')) {
            $query->where('channel', $request->channel);
        }
        if ($request->filled('search')) {
            $query->where('message', 'like', '%' . $request->search . '%');
        }

        $messages = $query->orderByDesc('created_at')->paginate(30);

        $channels = ChatMessage::select('channel')
            ->distinct()
            ->pluck('channel');

        $stats = [
            'total_messages'   => ChatMessage::where('is_deleted', false)->count(),
            'deleted_messages' => ChatMessage::where('is_deleted', true)->count(),
            'active_users'     => ChatMessage::where('is_deleted', false)
                                    ->distinct('user_id')
                                    ->count('user_id'),
            'channels'         => $channels->count(),
        ];

        return view('admin.chat.index', compact('messages', 'channels', 'stats'));
    }

    public function destroy(ChatMessage $message)
    {
        $message->update(['is_deleted' => true]);
        return back()->with('success', 'Message removed from chat.');
    }

    public function restore(ChatMessage $message)
    {
        $message->update(['is_deleted' => false]);
        return back()->with('success', 'Message restored.');
    }

    public function clearChannel(Request $request)
    {
        $request->validate(['channel' => 'required|string']);
        ChatMessage::where('channel', $request->channel)->update(['is_deleted' => true]);
        return back()->with('success', "All messages in #{$request->channel} have been cleared.");
    }
}
