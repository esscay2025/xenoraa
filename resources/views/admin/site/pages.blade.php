@extends('layouts.admin')
@section('title', 'Page Manager')
@section('page-title', 'Page Manager')

@section('content')
<style>
.pm-header { display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:1.5rem; gap:1rem; flex-wrap:wrap; }
.pm-table-wrap { background:var(--bg-card); border:1px solid var(--border); border-radius:14px; overflow:hidden; margin-bottom:1.5rem; }
.pm-table { width:100%; border-collapse:collapse; }
.pm-table th { padding:0.75rem 1.25rem; text-align:left; font-size:0.72rem; font-weight:700; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.06em; border-bottom:1px solid var(--border); background:var(--bg-secondary); }
.pm-table td { padding:1rem 1.25rem; border-bottom:1px solid var(--border); font-size:0.875rem; vertical-align:middle; }
.pm-table tr:last-child td { border-bottom:none; }
.pm-table tr:hover td { background:var(--bg-hover); }
.pm-status { display:inline-flex; align-items:center; gap:0.35rem; font-size:0.72rem; font-weight:700; padding:0.2rem 0.6rem; border-radius:20px; }
.pm-status.published { background:rgba(34,197,94,0.12); color:#22c55e; }
.pm-status.draft { background:rgba(245,158,11,0.12); color:#f59e0b; }
.pm-type { display:inline-block; font-size:0.7rem; font-weight:600; padding:0.15rem 0.5rem; border-radius:20px; background:var(--bg-hover); color:var(--text-secondary); }
.pm-type.system { background:rgba(99,102,241,0.12); color:#818cf8; }
.pm-actions { display:flex; gap:0.5rem; }
.pm-empty { text-align:center; padding:4rem 2rem; color:var(--text-muted); }
.pm-empty i { font-size:3rem; margin-bottom:1rem; display:block; opacity:0.3; }
.pm-section-header { padding:0.75rem 1.25rem; background:var(--bg-secondary); border-bottom:1px solid var(--border); font-size:0.75rem; font-weight:700; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.06em; display:flex; align-items:center; gap:0.5rem; }
.pm-section-header i { opacity:0.6; }
.pm-info-bar { background:rgba(99,102,241,0.08); border:1px solid rgba(99,102,241,0.2); border-radius:10px; padding:1rem 1.25rem; margin-bottom:1.5rem; display:flex; align-items:center; gap:1rem; }
.pm-info-bar i { color:#818cf8; font-size:1.1rem; flex-shrink:0; }
.pm-info-bar p { margin:0; font-size:0.85rem; color:var(--text-secondary); }
.pm-info-bar strong { color:var(--text-primary); }
</style>

<div class="pm-header">
    <div>
        <div style="margin-bottom:0.25rem;">
            <a href="{{ route('admin.site.index') }}" style="color:var(--text-muted);text-decoration:none;font-size:0.85rem;"><i class="fas fa-arrow-left"></i> Site Builder</a>
        </div>
        <h1 style="font-size:1.75rem;font-weight:800;margin:0;">Page Manager</h1>
        <p style="color:var(--text-secondary);margin:0.25rem 0 0;font-size:0.9rem;">Manage all pages on your site — system pages and custom pages.</p>
    </div>
    <div style="display:flex;gap:0.75rem;flex-wrap:wrap;align-items:center;">
        <form method="POST" action="{{ route('admin.site.pages.reset') }}" onsubmit="return confirm('This will reset all pages to the default theme content. Your custom pages will be preserved. Continue?')">
            @csrf
            <button type="submit" class="btn btn-outline" style="font-size:0.85rem;">
                <i class="fas fa-redo"></i> Reset to Default
            </button>
        </form>
        <a href="{{ route('admin.site.pages.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> New Page
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success" style="background:rgba(34,197,94,0.1);border:1px solid rgba(34,197,94,0.2);color:#22c55e;padding:0.75rem 1rem;border-radius:8px;margin-bottom:1rem;font-size:0.875rem;">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
@endif

@if($pages->isEmpty())
    <div class="pm-info-bar">
        <i class="fas fa-info-circle"></i>
        <p>No pages found. Click <strong>Reset to Default</strong> to generate all pages for your current theme, or <strong>New Page</strong> to create a custom page.</p>
    </div>
    <div class="pm-table-wrap">
        <div class="pm-empty">
            <i class="fas fa-file-alt"></i>
            <div style="font-size:1rem;font-weight:600;margin-bottom:0.5rem;">No pages yet</div>
            <p style="font-size:0.85rem;max-width:360px;margin:0 auto 1.5rem;">Your theme comes with default pages. Click <strong>Reset to Default</strong> to generate them, or create a custom page below.</p>
            <div style="display:flex;gap:0.75rem;justify-content:center;flex-wrap:wrap;">
                <form method="POST" action="{{ route('admin.site.pages.reset') }}" onsubmit="return confirm('Generate default pages for your theme?')">
                    @csrf
                    <button type="submit" class="btn btn-primary"><i class="fas fa-magic"></i> Generate Default Pages</button>
                </form>
                <a href="{{ route('admin.site.pages.create') }}" class="btn btn-outline"><i class="fas fa-plus"></i> New Custom Page</a>
            </div>
        </div>
    </div>
@else
    {{-- Group pages by type --}}
    @php
        $systemSlugs = ['home','about','blog','jobs','vacancies','shop','contact','services','solutions','portfolio','practice-areas','case-studies','appointments','ventures','vision','initiatives','collaborations'];
        $systemPages = $pages->filter(fn($p) => in_array($p->slug, $systemSlugs));
        $customPages  = $pages->filter(fn($p) => !in_array($p->slug, $systemSlugs));
    @endphp

    {{-- System / Theme Pages --}}
    @if($systemPages->count())
    <div class="pm-table-wrap">
        <div class="pm-section-header">
            <i class="fas fa-layer-group"></i> Theme Pages ({{ $systemPages->count() }})
            <span style="font-weight:400;text-transform:none;letter-spacing:0;margin-left:0.5rem;color:var(--text-muted);">Generated by your active theme — edit content freely</span>
        </div>
        <table class="pm-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Public URL</th>
                    <th>Status</th>
                    <th>In Menu</th>
                    <th>Updated</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($systemPages->sortBy('sort_order') as $page)
                <tr>
                    <td>
                        <div style="font-weight:600;">{{ $page->title }}</div>
                        @if($page->page_type)
                            <span class="pm-type system">{{ ucfirst($page->page_type) }}</span>
                        @endif
                    </td>
                    <td>
                        <code style="font-size:0.78rem;background:var(--bg-hover);padding:0.2rem 0.5rem;border-radius:4px;">{{ $page->public_url }}</code>
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
                            <a href="{{ $page->public_url }}" target="_blank" class="btn btn-sm btn-outline" title="View Live"><i class="fas fa-eye"></i></a>
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
    </div>
    @endif

    {{-- Custom Pages --}}
    @if($customPages->count())
    <div class="pm-table-wrap">
        <div class="pm-section-header">
            <i class="fas fa-file-alt"></i> Custom Pages ({{ $customPages->count() }})
        </div>
        <table class="pm-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Public URL</th>
                    <th>Status</th>
                    <th>In Menu</th>
                    <th>Updated</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($customPages->sortBy('sort_order') as $page)
                <tr>
                    <td>
                        <div style="font-weight:600;">{{ $page->title }}</div>
                        <span class="pm-type">Custom</span>
                    </td>
                    <td>
                        <code style="font-size:0.78rem;background:var(--bg-hover);padding:0.2rem 0.5rem;border-radius:4px;">{{ $page->public_url }}</code>
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
                            <a href="{{ $page->public_url }}" target="_blank" class="btn btn-sm btn-outline" title="View Live"><i class="fas fa-eye"></i></a>
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
    </div>
    @endif
@endif
@endsection
