@extends('layouts.superadmin')
@section('title', 'Blog Posts')
@section('page_title', 'Blog Posts')

@section('content')
<div style="margin-bottom:1.5rem;">
    <h1 style="font-family:'Space Grotesk',sans-serif;font-size:1.5rem;font-weight:700;">All Blog Posts</h1>
    <p style="color:#71717a;font-size:0.875rem;margin-top:0.25rem;">Blog posts published across all Xenoraa tenants</p>
</div>

<div class="sa-card">
    <div class="sa-card-header">
        <div class="sa-card-title">Posts</div>
        <span style="font-size:0.75rem;color:#52525b;">{{ $posts->total() }} total</span>
    </div>
    <table class="sa-table">
        <thead>
            <tr>
                <th>Title</th>
                <th>Author</th>
                <th>Profile</th>
                <th>Status</th>
                <th>Published</th>
            </tr>
        </thead>
        <tbody>
            @forelse($posts as $post)
            <tr>
                <td style="max-width:300px;">
                    <div style="font-weight:600;color:#fff;font-size:0.825rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $post->title }}</div>
                    @if($post->category)
                    <div style="font-size:0.72rem;color:#52525b;">{{ $post->category }}</div>
                    @endif
                </td>
                <td style="color:#a1a1aa;font-size:0.825rem;">{{ $post->author_name }}</td>
                <td>
                    <a href="{{ url('/'.$post->username) }}" target="_blank" style="color:#7c3aed;font-size:0.8rem;">xenoraa.com/{{ $post->username }}</a>
                </td>
                <td>
                    <span class="sa-badge {{ ($post->status ?? 'published') === 'published' ? 'sa-badge-active' : 'sa-badge-inactive' }}">
                        {{ ucfirst($post->status ?? 'published') }}
                    </span>
                </td>
                <td style="font-size:0.75rem;color:#52525b;">{{ \Carbon\Carbon::parse($post->created_at)->format('d M Y') }}</td>
            </tr>
            @empty
            <tr><td colspan="5" style="text-align:center;color:#3f3f46;padding:2rem;">No blog posts found</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($posts->hasPages())
    <div style="padding:1rem 1.5rem;border-top:1px solid #1a1a1a;">{{ $posts->links() }}</div>
    @endif
</div>
@endsection
