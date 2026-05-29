@extends('layouts.app')
@section('title', $post->title . ' | Gopi K Blog')

@section('content')
<div class="container" style="padding-top: 3rem; padding-bottom: 3rem; max-width: 800px;">

    <!-- Breadcrumb -->
    <div style="margin-bottom: 2rem; font-size: 0.875rem; color: var(--text-muted);">
        <a href="{{ route('home') }}" style="color: var(--text-muted); text-decoration: none;">Home</a>
        <span style="margin: 0 0.5rem;">/</span>
        <a href="{{ route('blog') }}" style="color: var(--text-muted); text-decoration: none;">Blog</a>
        <span style="margin: 0 0.5rem;">/</span>
        <span style="color: var(--text-secondary);">{{ Str::limit($post->title, 50) }}</span>
    </div>

    <!-- Post Header -->
    @if($post->category)
    <span class="badge badge-secondary" style="margin-bottom: 1rem;">{{ $post->category->name }}</span>
    @endif
    <h1 style="font-size: 2.5rem; font-weight: 800; line-height: 1.2; margin-bottom: 1rem; letter-spacing: -0.5px;">{{ $post->title }}</h1>

    <div style="display: flex; align-items: center; gap: 1.5rem; margin-bottom: 2rem; padding-bottom: 2rem; border-bottom: 1px solid var(--border);">
        <div style="display: flex; align-items: center; gap: 0.5rem;">
            <div style="width: 36px; height: 36px; border-radius: 50%; background: var(--bg-card); border: 1px solid var(--border); display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-user" style="font-size: 0.875rem; color: var(--text-secondary);"></i>
            </div>
            <span style="font-size: 0.875rem; color: var(--text-secondary);">{{ $post->author->name }}</span>
        </div>
        <span style="font-size: 0.875rem; color: var(--text-muted);">{{ $post->published_at?->format('F d, Y') }}</span>
        <span style="font-size: 0.875rem; color: var(--text-muted);"><i class="fas fa-eye"></i> {{ $post->views_count }} views</span>
    </div>

    <!-- Featured Image -->
    @if($post->featured_image)
    <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" style="width: 100%; border-radius: 12px; margin-bottom: 2rem; max-height: 400px; object-fit: cover;">
    @endif

    <!-- Post Content -->
    <div style="color: var(--text-secondary); line-height: 1.8; font-size: 1.05rem; margin-bottom: 3rem;">
        {!! nl2br(e($post->content)) !!}
    </div>

    <!-- Share Section -->
    <div style="padding: 1.5rem; background: var(--bg-card); border: 1px solid var(--border); border-radius: 12px; margin-bottom: 3rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
        <span style="font-weight: 600;">Share this post</span>
        <div class="social-links">
            @foreach($socialLinks as $social)
            <a href="{{ $social->url }}" class="social-link" target="_blank" rel="noopener" title="{{ ucfirst($social->platform) }}">
                <i class="{{ $social->icon_class }}"></i>
            </a>
            @endforeach
        </div>
    </div>

    <!-- Comments Section -->
    <div>
        <h2 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 2rem;">
            Comments ({{ $comments->count() }})
        </h2>

        @if($comments->count() > 0)
        <div style="margin-bottom: 2rem;">
            @foreach($comments as $comment)
            <div style="padding: 1.25rem; background: var(--bg-card); border: 1px solid var(--border); border-radius: 12px; margin-bottom: 1rem;">
                <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.75rem;">
                    <div style="width: 36px; height: 36px; border-radius: 50%; background: var(--bg-secondary); border: 1px solid var(--border); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <i class="fas fa-user" style="font-size: 0.875rem; color: var(--text-secondary);"></i>
                    </div>
                    <div>
                        <p style="font-weight: 600; font-size: 0.9rem; margin: 0;">{{ $comment->user?->name ?? $comment->visitor_name ?? 'Anonymous' }}</p>
                        <p style="font-size: 0.75rem; color: var(--text-muted); margin: 0;">{{ $comment->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                <p style="color: var(--text-secondary); font-size: 0.9rem; margin: 0;">{{ $comment->comment }}</p>
            </div>
            @endforeach
        </div>
        @endif

        <!-- Comment Form -->
        <div style="background: var(--bg-card); border: 1px solid var(--border); border-radius: 12px; padding: 1.5rem;">
            <h3 style="font-size: 1.1rem; font-weight: 600; margin-bottom: 1.25rem;">Leave a Comment</h3>
            <form method="POST" action="{{ route('blog.comment', $post->slug) }}">
                @csrf
                @if(!auth()->check())
                <div class="grid-2" style="margin-bottom: 1rem;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Your Name</label>
                        <input type="text" name="visitor_name" class="form-control" placeholder="John Doe" value="{{ old('visitor_name') }}">
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Your Email</label>
                        <input type="email" name="visitor_email" class="form-control" placeholder="john@example.com" value="{{ old('visitor_email') }}">
                    </div>
                </div>
                @endif
                <div class="form-group">
                    <label class="form-label">Comment *</label>
                    <textarea name="comment" class="form-control" rows="4" placeholder="Share your thoughts, ideas, or feedback..." required>{{ old('comment') }}</textarea>
                    @error('comment')<p style="color: var(--danger); font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</p>@enderror
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-paper-plane"></i> Submit Comment
                </button>
            </form>
        </div>
    </div>

</div>
@endsection
