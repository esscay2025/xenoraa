<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index()
    {
        $channels = ['general', 'ai-automation', 'startups', 'tech-talk', 'off-topic'];
        $channel = request('channel', 'general');
        $messages = ChatMessage::visible()
            ->channel($channel)
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->take(100)
            ->get()
            ->map(function ($msg) {
                return [
                    'id'         => $msg->id,
                    'user_id'    => $msg->user_id,
                    'user_name'  => $msg->user->name,
                    'user_email' => $msg->user->email,
                    'message'    => $msg->message,
                    'channel'    => $msg->channel,
                    'time'       => $msg->created_at->format('h:i A'),
                    'date'       => $msg->created_at->diffForHumans(),
                    'is_mine'    => $msg->user_id === auth()->id(),
                    'can_delete' => $msg->user_id === auth()->id() || auth()->user()->isAdmin(),
                ];
            });

        return view('portfolio.chat', compact('messages', 'channels', 'channel'));
    }

    public function getMessages(Request $request)
    {
        $channel = $request->get('channel', 'general');
        $after   = $request->get('after_id', 0);

        $messages = ChatMessage::visible()
            ->channel($channel)
            ->with('user')
            ->where('id', '>', $after)
            ->orderBy('created_at', 'asc')
            ->take(50)
            ->get()
            ->map(function ($msg) {
                return [
                    'id'         => $msg->id,
                    'user_id'    => $msg->user_id,
                    'user_name'  => $msg->user->name,
                    'message'    => $msg->message,
                    'time'       => $msg->created_at->format('h:i A'),
                    'date'       => $msg->created_at->diffForHumans(),
                    'is_mine'    => $msg->user_id === auth()->id(),
                    'can_delete' => $msg->user_id === auth()->id() || auth()->user()->isAdmin(),
                ];
            });

        return response()->json(['messages' => $messages]);
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'channel' => 'required|string|in:general,ai-automation,startups,tech-talk,off-topic',
        ]);

        $msg = ChatMessage::create([
            'user_id' => auth()->id(),
            'message' => strip_tags($request->message),
            'channel' => $request->channel,
        ]);

        $msg->load('user');

        return response()->json([
            'success' => true,
            'message' => [
                'id'         => $msg->id,
                'user_id'    => $msg->user_id,
                'user_name'  => $msg->user->name,
                'message'    => $msg->message,
                'time'       => $msg->created_at->format('h:i A'),
                'date'       => 'Just now',
                'is_mine'    => true,
                'can_delete' => true,
            ],
        ]);
    }

    public function deleteMessage(ChatMessage $message)
    {
        if ($message->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $message->update(['is_deleted' => true]);

        return response()->json(['success' => true]);
    }
}
