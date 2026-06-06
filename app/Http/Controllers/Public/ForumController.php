<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\ForumTopic;
use App\Models\ForumReply;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ForumController extends Controller
{
    /**
     * Resolve the current tenant from the HTTP host or username.
     */
    protected function resolveTenant(Request $request, ?string $username = null): ?User
    {
        $host       = $request->getHost();
        $mainDomain = config('xenoraa.main_domain', 'xenoraa.com');

        // Custom domain (e.g. gopi.blog)
        if ($host !== $mainDomain && $host !== 'www.' . $mainDomain) {
            $tenant = User::where('custom_domain', $host)
                ->orWhere('custom_domain', 'www.' . $host)
                ->first();
            if ($tenant) return $tenant;
        }

        // Username-based route (xenoraa.com/priya/forum)
        if ($username) {
            return User::where('username', $username)->first();
        }

        return null;
    }

    public function index(Request $request, ?string $username = null)
    {
        $tenant   = $this->resolveTenant($request, $username);
        $tenantId = $tenant?->id;

        $category = $request->get('category');
        $search   = $request->get('search');

        $query = ForumTopic::with(['user', 'replies'])
            ->withCount(['replies'])
            ->orderByDesc('is_pinned')
            ->orderByDesc('created_at');

        // Scope to tenant
        if ($tenantId) {
            $query->where('tenant_owner_id', $tenantId);
        }

        if ($category) {
            $query->where('category', $category);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('body', 'like', "%{$search}%");
            });
        }

        $topics = $query->paginate(15);

        $categories = [
            'general'          => ['label' => 'General Discussion',       'icon' => 'fas fa-comments',  'color' => '#3b82f6'],
            'ai-automation'    => ['label' => 'AI & Automation',          'icon' => 'fas fa-robot',     'color' => '#8b5cf6'],
            'startup-business' => ['label' => 'Startup & Business',       'icon' => 'fas fa-rocket',    'color' => '#f59e0b'],
            'tech-development' => ['label' => 'Tech & Development',       'icon' => 'fas fa-code',      'color' => '#22c55e'],
            'career-branding'  => ['label' => 'Career & Personal Brand',  'icon' => 'fas fa-user-tie',  'color' => '#ec4899'],
        ];

        $stats = [
            'topics'  => ForumTopic::when($tenantId, fn($q) => $q->where('tenant_owner_id', $tenantId))->count(),
            'replies' => ForumReply::where('is_deleted', false)
                ->when($tenantId, fn($q) => $q->whereHas('topic', fn($tq) => $tq->where('tenant_owner_id', $tenantId)))
                ->count(),
        ];

        return view('portfolio.forum-index', compact('topics', 'categories', 'category', 'search', 'stats', 'tenant'));
    }

    public function show(Request $request, ForumTopic $topic)
    {
        $tenant = $this->resolveTenant($request);

        // Increment view count
        $topic->increment('views');

        $replies = ForumReply::where('topic_id', $topic->id)
            ->where('is_deleted', false)
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->get();

        $categories = [
            'general'          => ['label' => 'General Discussion',      'icon' => 'fas fa-comments',  'color' => '#3b82f6'],
            'ai-automation'    => ['label' => 'AI & Automation',         'icon' => 'fas fa-robot',     'color' => '#8b5cf6'],
            'startup-business' => ['label' => 'Startup & Business',      'icon' => 'fas fa-rocket',    'color' => '#f59e0b'],
            'tech-development' => ['label' => 'Tech & Development',      'icon' => 'fas fa-code',      'color' => '#22c55e'],
            'career-branding'  => ['label' => 'Career & Personal Brand', 'icon' => 'fas fa-user-tie',  'color' => '#ec4899'],
        ];

        return view('portfolio.forum-show', compact('topic', 'replies', 'categories', 'tenant'));
    }

    public function reply(Request $request, ForumTopic $topic)
    {
        if ($topic->is_locked) {
            return back()->with('error', 'This topic is locked. No new replies are allowed.');
        }

        $request->validate([
            'body' => 'required|string|min:5|max:5000',
        ]);

        ForumReply::create([
            'topic_id' => $topic->id,
            'user_id'  => auth()->id(),
            'body'     => strip_tags($request->body),
        ]);

        return redirect()->route('forum.show', $topic->slug)
            ->with('success', 'Your reply has been posted!');
    }

    public function deleteReply(ForumReply $reply)
    {
        if ($reply->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            return back()->with('error', 'Unauthorized.');
        }

        $reply->update(['is_deleted' => true]);

        return back()->with('success', 'Reply removed.');
    }

    public function createTopic(Request $request)
    {
        $tenant = $this->resolveTenant($request);

        $request->validate([
            'title'    => 'required|string|min:5|max:255',
            'body'     => 'required|string|min:20|max:10000',
            'category' => 'required|string|in:general,ai-automation,startup-business,tech-development,career-branding',
            'tags'     => 'nullable|string|max:200',
        ]);

        $topic = ForumTopic::create([
            'tenant_owner_id' => $tenant?->id,
            'user_id'         => auth()->id(),
            'title'           => $request->title,
            'slug'            => ForumTopic::generateSlug($request->title),
            'body'            => strip_tags($request->body),
            'category'        => $request->category,
            'tags'            => $request->tags,
        ]);

        return redirect()->route('forum.show', $topic->slug)
            ->with('success', 'Your topic has been created!');
    }

    public function togglePin(ForumTopic $topic)
    {
        $topic->update(['is_pinned' => !$topic->is_pinned]);
        return back()->with('success', $topic->is_pinned ? 'Topic pinned.' : 'Topic unpinned.');
    }

    public function toggleLock(ForumTopic $topic)
    {
        $topic->update(['is_locked' => !$topic->is_locked]);
        return back()->with('success', $topic->is_locked ? 'Topic locked.' : 'Topic unlocked.');
    }

    public function deleteTopic(ForumTopic $topic)
    {
        $topic->delete();
        return redirect()->route('forum.index')->with('success', 'Topic deleted.');
    }
}
