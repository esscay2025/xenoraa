@extends('layouts.app')
@section('title', 'Blog | Gopi K')

@section('content')
<div class="container" style="padding-top: 3rem; padding-bottom: 3rem;">
    <div style="margin-bottom: 2.5rem;">
        <h1 style="font-size: 2.5rem; font-weight: 800; margin-bottom: 0.5rem;">Blog</h1>
        <p style="color: var(--text-secondary);">Thoughts, ideas, and insights on technology, entrepreneurship, and open-source.</p>
    </div>

    <!-- Search & Filter -->
    <form method="GET" action="{{ route('blog') }}" style="display: flex; gap: 1rem; margin-bottom: 2rem; flex-wrap: wrap;">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search posts..." class="form-control" style="max-width: 300px;">
        <button type="submit" class="btn btn-outline">Search</button>
        @if(request('search'))
        <a href="{{ route('blog') }}" class="btn btn-outline">Clear</a>
        @endif
    </form>

    @if($posts->count() > 0)
    <div class="grid-3">
        @foreach($posts as $post)
        <a href="{{ route('blog.show', $post->slug) }}" style="text-decoration: none; color: inherit; display: block; background: var(--bg-card); border: 1px solid var(--border); border-radius: 12px; overflow: hidden; transition: all 0.2s;" onmouseover="this.style.borderColor='#444'" onmouseout="this.style.borderColor='var(--border)'">
            @if($post->featured_image)
            @php
                $imgSrc = str_starts_with($post->featured_image, 'http') ? $post->featured_image : asset('storage/' . $post->featured_image);
            @endphp
            <img src="{{ $imgSrc }}" alt="{{ $post->title }}" style="width: 100%; height: 200px; object-fit: cover;" onerror="this.parentElement.innerHTML='<div style=\'width:100%;height:200px;background:linear-gradient(135deg,#1a1a1a,#2a2a2a);display:flex;align-items:center;justify-content:center;\'><i class=\'fas fa-pen-nib\' style=\'font-size:2.5rem;color:#555;\'></i></div>';">
            @else
            <div style="width: 100%; height: 200px; background: linear-gradient(135deg, #1a1a1a, #2a2a2a); display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-pen-nib" style="font-size: 2.5rem; color: var(--text-muted);"></i>
            </div>
            @endif
            <div style="padding: 1.25rem;">
                @if($post->category)
                <span class="badge badge-secondary" style="margin-bottom: 0.75rem;">{{ $post->category->name }}</span>
                @endif
                <h2 style="font-size: 1.1rem; font-weight: 600; margin-bottom: 0.5rem;">{{ $post->title }}</h2>
                <p style="color: var(--text-secondary); font-size: 0.875rem; margin-bottom: 1rem;">{{ Str::limit($post->summary ?? strip_tags($post->content), 120) }}</p>
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-size: 0.75rem; color: var(--text-muted);">{{ $post->published_at?->format('M d, Y') }}</span>
                    <span style="font-size: 0.75rem; color: var(--text-muted);"><i class="fas fa-eye"></i> {{ $post->views_count }}</span>
                </div>
            </div>
        </a>
        @endforeach
    </div>

    <div style="margin-top: 2rem;">
        {{ $posts->links() }}
    </div>
    @else
    <div style="text-align: center; padding: 4rem 2rem;">
        <i class="fas fa-pen-nib" style="font-size: 3rem; color: var(--text-muted); margin-bottom: 1rem; display: block;"></i>
        <h3 style="color: var(--text-secondary);">No posts found</h3>
        <p class="text-muted">Check back soon for new content.</p>
    </div>
    @endif
</div>
@endsection
