<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\CalendarEvent;
use App\Models\UserNote;
use Illuminate\Http\Request;

class UserDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $eventCount = CalendarEvent::where('user_id', $user->id)->count();
        $noteCount = UserNote::where('user_id', $user->id)->count();

        // Forum post count (if table exists)
        $forumPostCount = 0;
        try {
            $forumPostCount = \App\Models\ForumReply::where('user_id', $user->id)->count();
        } catch (\Exception $e) {}

        // Chat count (if table exists)
        $chatCount = 0;
        try {
            $chatCount = \App\Models\ChatMessage::where('user_id', $user->id)->count();
        } catch (\Exception $e) {}

        return view('portfolio.user-dashboard', compact(
            'eventCount',
            'noteCount',
            'forumPostCount',
            'chatCount'
        ));
    }
}
