@extends('layouts.admin')
@section('title', 'Page Manager')
@section('page-title', 'Page Manager')

@section('content')
<style>
.pm-header { display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:1.5rem; }
.pm-table-wrap { background:var(--bg-card); border:1px solid var(--border); border-radius:14px; overflow:hidden; }
.pm-table { width:100%; border-collapse:collapse; }
.pm-table th { padding:0.75rem 1.25rem; text-align:left; font-size:0.72rem; font-weight:700; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.06em; border-bottom:1px solid var(--border); background:var(--bg-secondary); }
.pm-table td { padding:1rem 1.25rem; border-bottom:1px solid var(--border); font-size:0.875rem; vertical-align:middle; }
.pm-table tr:last-child td { border-bottom:none; }
.pm-table tr:hover td { background:var(--bg-hover); }
.pm-status { display:inline-flex; align-items:center; gap:0.35rem; font-size:0.72rem; font-weight:700; padding:0.2rem 0.6rem; border-radius:20px; }
.pm-status.published { background:rgba(34,197,94,0.12); color:#22c55e; }
.pm-status.draft { background:rgba(245,158,11,0.12); color:#f59e0b; }
.pm-actions { display:flex; gap:0.5rem; }
.pm-empty { text-align:center; padding:4rem 2rem; color:var(--text-muted); }
.pm-empty i { font-size:3rem; margin-bottom:1rem; display:block; opacity:0.3; }
</style>

<div class="pm-header">
    <div>
        <div style="margin-bottom:0.25rem;">
            <a href="{{ route('admin.site.index') }}" style="color:var(--text-muted);text-decoration:none;font-size:0.85rem;"><i class="fas fa-arrow-left"></i> Site Builder</a>
        </div>
        <h1 style="font-size:1.75rem;font-weight:800;margin:0;">Page Manager</h1>
        <p style="color:var(--text-secondary);margin:0.25rem 0 0;font-size:0.9rem;">Create and manage custom pages for your site.</p>
    </div>
    <a href="{{ route('admin.site.pages.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> New Page
    </a>
</div>

<div class="pm-table-wrap">
    @if($pages->isEmpty())
        <div class="pm-empty">
            <i class="fas fa-file-alt"></i>
            <div style="font-size:1rem;font-weight:600;margin-bottom:0.5rem;">No pages yet</div>
            <p style="font-size:0.85rem;max-width:300px;margin:0 auto 1.5rem;">Create your first custom page — About, Services, Portfolio, or anything you need.</p>
            <a href="{{ route('admin.site.pages.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Create First Page</a>
        </div>
    @else
        <table class="pm-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Slug / URL</th>
                    <th>Status</th>
                    <th>Menu</th>
                    <th>Updated</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pages as $page)
                <tr>
                    <td>
                        <div style="font-weight:600;">{{ $page->title }}</div>
                        @if($page->meta_desc)
                            <div style="font-size:0.75rem;color:var(--text-muted);margin-top:0.2rem;">{{ Str::limit($page->meta_desc, 60) }}</div>
                        @endif
                    </td>
                    <td>
                        <code style="font-size:0.78rem;background:var(--bg-hover);padding:0.2rem 0.5rem;border-radius:4px;">/{{ auth()->user()->username }}/page/{{ $page->slug }}</code>
                    </td>
                    <td>
                        <span class="pm-status {{ $page->status }}">
                            <i class="fas fa-{{ $page->status === 'published' ? 'check-circle' : 'clock' }}"></i>
                            {{ ucfirst($page->status) }}
                        </span>
                    </td>
                    <td>
                        @if($page->show_in_menu)
                            <span style="color:var(--success);font-size:0.8rem;"><i class="fas fa-check"></i> Yes</span>
                        @else
                            <span style="color:var(--text-muted);font-size:0.8rem;">—</span>
                        @endif
                    </td>
                    <td style="color:var(--text-muted);font-size:0.8rem;">{{ $page->updated_at->diffForHumans() }}</td>
                    <td>
                        <div class="pm-actions">
                            <a href="{{ $page->public_url }}" target="_blank" class="btn btn-sm btn-outline" title="View"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('admin.site.pages.edit', $page) }}" class="btn btn-sm btn-outline" title="Edit"><i class="fas fa-pencil-alt"></i></a>
                            <form method="POST" action="{{ route('admin.site.pages.destroy', $page) }}" onsubmit="return confirm('Delete this page?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm" style="background:rgba(239,68,68,0.1);color:#ef4444;border:1px solid rgba(239,68,68,0.2);" title="Delete"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
