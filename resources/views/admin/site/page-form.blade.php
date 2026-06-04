@extends('layouts.admin')
@section('title', isset($page) && $page ? 'Edit Page' : 'New Page')
@section('page-title', isset($page) && $page ? 'Edit Page' : 'New Page')

@section('content')
<style>
.pf-layout { display:grid; grid-template-columns:1fr 300px; gap:1.5rem; align-items:start; }
.pf-card { background:var(--bg-card); border:1px solid var(--border); border-radius:14px; padding:1.5rem; }
.pf-card + .pf-card { margin-top:1.25rem; }
.pf-section-title { font-size:0.85rem; font-weight:700; color:var(--text-secondary); text-transform:uppercase; letter-spacing:0.06em; margin:0 0 1rem; padding-bottom:0.75rem; border-bottom:1px solid var(--border); }
/* Quill editor */
#editor-container { background:var(--bg-secondary); border:1px solid var(--border); border-radius:8px; min-height:400px; }
.ql-toolbar { background:var(--bg-hover); border:none !important; border-bottom:1px solid var(--border) !important; border-radius:8px 8px 0 0; }
.ql-container { border:none !important; border-radius:0 0 8px 8px; font-family:'Inter',sans-serif; font-size:0.9rem; }
.ql-editor { min-height:380px; color:var(--text-primary); }
.ql-editor p, .ql-editor li { color:var(--text-primary); }
.ql-picker-label, .ql-picker-item, .ql-stroke { color:var(--text-secondary) !important; stroke:var(--text-secondary) !important; }
.ql-fill { fill:var(--text-secondary) !important; }
.ql-picker-options { background:var(--bg-card) !important; border-color:var(--border) !important; }
@media(max-width:768px) { .pf-layout { grid-template-columns:1fr; } }
</style>

<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">

<div style="margin-bottom:1rem;">
    <a href="{{ route('admin.site.pages') }}" style="color:var(--text-muted);text-decoration:none;font-size:0.85rem;"><i class="fas fa-arrow-left"></i> Page Manager</a>
</div>

<form method="POST" action="{{ isset($page) && $page ? route('admin.site.pages.update', $page) : route('admin.site.pages.store') }}" id="pageForm">
    @csrf
    @if(isset($page) && $page) @method('PUT') @endif

    <div class="pf-layout">
        {{-- Main Content --}}
        <div>
            <div class="pf-card">
                <div class="form-group" style="margin-bottom:1.25rem;">
                    <label class="form-label" style="font-weight:700;">Page Title <span style="color:var(--danger);">*</span></label>
                    <input type="text" name="title" id="pageTitle" class="form-control" style="font-size:1.1rem;font-weight:600;"
                        value="{{ old('title', $page?->title ?? '') }}" required placeholder="e.g. About Me, Services, Portfolio">
                </div>

                <div class="form-group" style="margin-bottom:1.25rem;">
                    <label class="form-label" style="font-weight:700;">URL Slug</label>
                    <div style="display:flex;align-items:center;gap:0;background:var(--bg-secondary);border:1px solid var(--border);border-radius:8px;overflow:hidden;">
                        <span style="padding:0.65rem 0.75rem;color:var(--text-muted);font-size:0.82rem;white-space:nowrap;border-right:1px solid var(--border);">/{{ auth()->user()->username }}/page/</span>
                        <input type="text" name="slug" id="pageSlug" class="form-control"
                            style="border:none;background:transparent;border-radius:0;flex:1;"
                            value="{{ old('slug', $page?->slug ?? '') }}" placeholder="auto-generated">
                    </div>
                    <div style="font-size:0.75rem;color:var(--text-muted);margin-top:0.35rem;">Leave blank to auto-generate from title.</div>
                </div>

                <div class="form-group">
                    <label class="form-label" style="font-weight:700;">Page Content</label>
                    <div id="editor-container"></div>
                    <input type="hidden" name="content" id="pageContent">
                </div>
            </div>

            {{-- SEO --}}
            <div class="pf-card">
                <div class="pf-section-title"><i class="fas fa-search" style="margin-right:0.4rem;"></i> SEO Settings</div>
                <div class="form-group" style="margin-bottom:1rem;">
                    <label class="form-label">Meta Title</label>
                    <input type="text" name="meta_title" class="form-control" value="{{ old('meta_title', $page?->meta_title ?? '') }}" placeholder="Leave blank to use page title">
                </div>
                <div class="form-group">
                    <label class="form-label">Meta Description</label>
                    <textarea name="meta_desc" class="form-control" rows="3" placeholder="Brief description for search engines (150–160 chars)">{{ old('meta_desc', $page?->meta_desc ?? '') }}</textarea>
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div>
            <div class="pf-card">
                <div class="pf-section-title">Publish</div>
                <div class="form-group" style="margin-bottom:1rem;">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-control">
                        <option value="published" {{ old('status', $page?->status ?? 'draft') === 'published' ? 'selected' : '' }}>Published</option>
                        <option value="draft" {{ old('status', $page?->status ?? 'draft') === 'draft' ? 'selected' : '' }}>Draft</option>
                    </select>
                </div>
                <div class="form-group" style="margin-bottom:1.25rem;">
                    <label style="display:flex;align-items:center;gap:0.5rem;cursor:pointer;">
                        <input type="checkbox" name="show_in_menu" value="1" {{ old('show_in_menu', $page?->show_in_menu) ? 'checked' : '' }}
                            style="width:16px;height:16px;accent-color:var(--accent,#6366f1);">
                        <span style="font-size:0.875rem;font-weight:500;">Show in navigation menu</span>
                    </label>
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%;">
                    <i class="fas fa-{{ isset($page) && $page ? 'save' : 'plus' }}"></i>
                    {{ isset($page) && $page ? 'Update Page' : 'Create Page' }}
                </button>
                @if(isset($page) && $page)
                <a href="{{ route('admin.site.pages') }}" class="btn btn-outline" style="width:100%;margin-top:0.5rem;text-align:center;">Cancel</a>
                @endif
            </div>

            @if(isset($page) && $page)
            <div class="pf-card">
                <div class="pf-section-title">Page Info</div>
                <div style="font-size:0.8rem;color:var(--text-muted);">
                    <div style="margin-bottom:0.5rem;"><strong style="color:var(--text-secondary);">Created:</strong> {{ $page->created_at->format('d M Y') }}</div>
                    <div style="margin-bottom:0.5rem;"><strong style="color:var(--text-secondary);">Updated:</strong> {{ $page->updated_at->diffForHumans() }}</div>
                    <div><strong style="color:var(--text-secondary);">URL:</strong><br>
                        <a href="{{ $page->public_url }}" target="_blank" style="color:var(--accent,#6366f1);word-break:break-all;font-size:0.75rem;">{{ $page->public_url }}</a>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</form>

<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
<script>
const quill = new Quill('#editor-container', {
    theme: 'snow',
    placeholder: 'Write your page content here...',
    modules: {
        toolbar: [
            [{ 'header': [1, 2, 3, 4, false] }],
            ['bold', 'italic', 'underline', 'strike'],
            [{ 'color': [] }, { 'background': [] }],
            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
            [{ 'indent': '-1'}, { 'indent': '+1' }],
            ['blockquote', 'code-block'],
            ['link', 'image'],
            ['clean']
        ]
    }
});

// Load existing content
const existingContent = {!! json_encode(old('content', $page?->content ?? '')) !!};
if (existingContent) {
    quill.clipboard.dangerouslyPasteHTML(existingContent);
}

// Auto-generate slug from title
const titleInput = document.getElementById('pageTitle');
const slugInput = document.getElementById('pageSlug');
let slugManuallyEdited = {{ (isset($page) && $page) ? 'true' : 'false' }};

titleInput.addEventListener('input', function() {
    if (!slugManuallyEdited) {
        slugInput.value = this.value.toLowerCase().replace(/[^a-z0-9\s-]/g,'').trim().replace(/\s+/g,'-');
    }
});
slugInput.addEventListener('input', function() { slugManuallyEdited = true; });

// Submit: sync Quill to hidden input
document.getElementById('pageForm').addEventListener('submit', function() {
    document.getElementById('pageContent').value = quill.root.innerHTML;
});
</script>
@endsection
