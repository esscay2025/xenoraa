@extends('layouts.admin')
@section('title', 'New Blog Post')

@push('styles')
<style>
    /* ── Editor Toolbar ── */
    .editor-toolbar {
        display: flex;
        flex-wrap: wrap;
        gap: 0.25rem;
        padding: 0.75rem;
        background: var(--bg-secondary);
        border: 1px solid var(--border-light);
        border-bottom: none;
        border-radius: 8px 8px 0 0;
    }
    .editor-toolbar button, .editor-toolbar select {
        background: var(--bg-card);
        border: 1px solid var(--border);
        color: var(--text-primary);
        padding: 0.3rem 0.6rem;
        border-radius: 4px;
        cursor: pointer;
        font-size: 0.8rem;
        font-weight: 600;
        transition: all 0.15s;
        min-width: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .editor-toolbar button:hover, .editor-toolbar select:hover { background: var(--bg-hover); border-color: #555; }
    .editor-toolbar button.active { background: white; color: black; }
    .editor-toolbar .toolbar-sep { width: 1px; background: var(--border); margin: 0 0.25rem; align-self: stretch; }
    .editor-toolbar select { padding: 0.3rem 0.5rem; min-width: 80px; }

    /* ── Editor Content Area ── */
    #editor-content {
        min-height: 400px;
        padding: 1.25rem;
        background: var(--bg-secondary);
        border: 1px solid var(--border-light);
        border-radius: 0 0 8px 8px;
        color: var(--text-primary);
        font-size: 0.95rem;
        line-height: 1.7;
        outline: none;
        overflow-y: auto;
    }
    #editor-content:focus { border-color: #555; }
    #editor-content h1 { font-size: 2rem; font-weight: 800; margin: 1.5rem 0 0.75rem; }
    #editor-content h2 { font-size: 1.6rem; font-weight: 700; margin: 1.5rem 0 0.75rem; }
    #editor-content h3 { font-size: 1.3rem; font-weight: 600; margin: 1.25rem 0 0.5rem; }
    #editor-content h4 { font-size: 1.1rem; font-weight: 600; margin: 1rem 0 0.5rem; }
    #editor-content h5 { font-size: 1rem; font-weight: 600; margin: 0.75rem 0 0.5rem; }
    #editor-content h6 { font-size: 0.9rem; font-weight: 600; margin: 0.75rem 0 0.5rem; color: var(--text-secondary); }
    #editor-content p { margin: 0 0 1rem; }
    #editor-content ul, #editor-content ol { margin: 0 0 1rem 1.5rem; }
    #editor-content li { margin-bottom: 0.25rem; }
    #editor-content blockquote { border-left: 3px solid #555; padding-left: 1rem; margin: 1rem 0; color: var(--text-secondary); font-style: italic; }
    #editor-content code { background: #1a1a1a; border: 1px solid var(--border); padding: 0.15rem 0.4rem; border-radius: 4px; font-family: monospace; font-size: 0.875rem; }
    #editor-content pre { background: #111; border: 1px solid var(--border); padding: 1rem; border-radius: 8px; overflow-x: auto; margin: 1rem 0; }
    #editor-content pre code { background: none; border: none; padding: 0; }
    #editor-content table { border-collapse: collapse; width: 100%; margin: 1rem 0; }
    #editor-content table th, #editor-content table td { border: 1px solid var(--border); padding: 0.5rem 0.75rem; }
    #editor-content table th { background: var(--bg-card); font-weight: 600; }
    #editor-content img { max-width: 100%; border-radius: 8px; margin: 0.5rem 0; }
    #editor-content img.align-left { float: left; margin-right: 1.5rem; margin-bottom: 0.5rem; max-width: 50%; }
    #editor-content img.align-right { float: right; margin-left: 1.5rem; margin-bottom: 0.5rem; max-width: 50%; }
    #editor-content img.align-center { display: block; margin: 0.5rem auto; }
    #editor-content a { color: #93c5fd; text-decoration: underline; }
    #editor-content hr { border: none; border-top: 1px solid var(--border); margin: 1.5rem 0; }

    /* ── Image Upload Modal ── */
    .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.7); z-index: 9999; align-items: center; justify-content: center; }
    .modal-overlay.open { display: flex; }
    .modal-box { background: var(--bg-card); border: 1px solid var(--border); border-radius: 12px; padding: 2rem; width: 500px; max-width: 95vw; }
    .modal-box h3 { margin: 0 0 1.5rem; font-size: 1.1rem; }
    .modal-tabs { display: flex; gap: 0.5rem; margin-bottom: 1.5rem; }
    .modal-tab { padding: 0.4rem 1rem; border-radius: 6px; cursor: pointer; font-size: 0.875rem; border: 1px solid var(--border); background: transparent; color: var(--text-secondary); }
    .modal-tab.active { background: white; color: black; border-color: white; }
    .modal-tab-content { display: none; }
    .modal-tab-content.active { display: block; }
</style>
@endpush

@section('content')
<div style="max-width: 1100px;">
    <form method="POST" action="{{ route('admin.blog.store') }}" enctype="multipart/form-data" id="blogForm">
        @csrf
        <div class="grid-2" style="align-items: start; gap: 2rem;">
            <div style="grid-column: 1 / -1;">
                <div class="form-group">
                    <label class="form-label">Post Title *</label>
                    <input type="text" name="title" class="form-control" placeholder="Enter a compelling title..." value="{{ old('title') }}" required style="font-size: 1.1rem; padding: 0.75rem 1rem;">
                    @error('title')<p style="color: var(--danger); font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</p>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Excerpt / Summary</label>
                    <textarea name="summary" class="form-control" rows="2" placeholder="Brief description shown in blog listing cards...">{{ old('summary') }}</textarea>
                </div>
            </div>

            {{-- Rich Text Editor --}}
            <div style="grid-column: 1 / -1;">
                <label class="form-label">Content *</label>

                {{-- Toolbar --}}
                <div class="editor-toolbar" id="editorToolbar">
                    {{-- Headings --}}
                    <select id="headingSelect" title="Heading">
                        <option value="p">Paragraph</option>
                        <option value="h1">Heading 1</option>
                        <option value="h2">Heading 2</option>
                        <option value="h3">Heading 3</option>
                        <option value="h4">Heading 4</option>
                        <option value="h5">Heading 5</option>
                        <option value="h6">Heading 6</option>
                    </select>
                    <div class="toolbar-sep"></div>
                    {{-- Text Formatting --}}
                    <button type="button" data-cmd="bold" title="Bold"><b>B</b></button>
                    <button type="button" data-cmd="italic" title="Italic"><i>I</i></button>
                    <button type="button" data-cmd="underline" title="Underline"><u>U</u></button>
                    <button type="button" data-cmd="strikeThrough" title="Strikethrough"><s>S</s></button>
                    <div class="toolbar-sep"></div>
                    {{-- Lists --}}
                    <button type="button" data-cmd="insertUnorderedList" title="Bullet List"><i class="fas fa-list-ul"></i></button>
                    <button type="button" data-cmd="insertOrderedList" title="Numbered List"><i class="fas fa-list-ol"></i></button>
                    <div class="toolbar-sep"></div>
                    {{-- Alignment --}}
                    <button type="button" data-cmd="justifyLeft" title="Align Left"><i class="fas fa-align-left"></i></button>
                    <button type="button" data-cmd="justifyCenter" title="Align Center"><i class="fas fa-align-center"></i></button>
                    <button type="button" data-cmd="justifyRight" title="Align Right"><i class="fas fa-align-right"></i></button>
                    <div class="toolbar-sep"></div>
                    {{-- Blocks --}}
                    <button type="button" id="btnBlockquote" title="Blockquote"><i class="fas fa-quote-left"></i></button>
                    <button type="button" id="btnCode" title="Inline Code"><i class="fas fa-code"></i></button>
                    <button type="button" id="btnCodeBlock" title="Code Block"><i class="fas fa-terminal"></i></button>
                    <button type="button" id="btnHR" title="Horizontal Rule"><i class="fas fa-minus"></i></button>
                    <div class="toolbar-sep"></div>
                    {{-- Links & Media --}}
                    <button type="button" id="btnLink" title="Insert Link"><i class="fas fa-link"></i></button>
                    <button type="button" id="btnImage" title="Insert Image"><i class="fas fa-image"></i></button>
                    <button type="button" id="btnTable" title="Insert Table"><i class="fas fa-table"></i></button>
                    <button type="button" id="btnEmbed" title="Embed Media (YouTube/Video)"><i class="fas fa-film"></i></button>
                    <div class="toolbar-sep"></div>
                    {{-- Undo/Redo --}}
                    <button type="button" data-cmd="undo" title="Undo"><i class="fas fa-undo"></i></button>
                    <button type="button" data-cmd="redo" title="Redo"><i class="fas fa-redo"></i></button>
                    <div class="toolbar-sep"></div>
                    {{-- HTML Source --}}
                    <button type="button" id="btnHtmlSource" title="View/Edit HTML Source"><i class="fas fa-code"></i> HTML</button>
                </div>

                {{-- Editor Content Area --}}
                <div id="editor-content" contenteditable="true">
                    {!! old('content', '<p>Start writing your blog post here...</p>') !!}
                </div>
                <textarea name="content" id="hiddenContent" style="display:none;" required>{{ old('content') }}</textarea>
                @error('content')<p style="color: var(--danger); font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</p>@enderror
            </div>

            {{-- Sidebar Settings --}}
            <div style="grid-column: 1 / -1;">
                <div class="grid-2" style="align-items: start;">
                    <div class="card">
                        <h3 style="font-size: 0.875rem; font-weight: 600; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 1rem;">Publish Settings</h3>
                        <div class="form-group">
                            <label class="form-label">Status *</label>
                            <select name="status" class="form-control" required>
                                <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="published" {{ old('status', 'published') === 'published' ? 'selected' : '' }}>Published</option>
                                <option value="archived" {{ old('status') === 'archived' ? 'selected' : '' }}>Archived</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Category</label>
                            <select name="category_id" class="form-control">
                                <option value="">No Category</option>
                                @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">Featured Image</label>
                            <input type="file" name="featured_image" class="form-control" accept="image/*" id="featuredImageInput">
                            <div id="featuredImagePreview" style="margin-top: 0.5rem; display: none;">
                                <img id="featuredImagePreviewImg" src="" alt="Preview" style="max-height: 150px; border-radius: 6px; border: 1px solid var(--border);">
                            </div>
                        </div>
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 0.75rem; padding-top: 0.5rem;">
                        <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; padding: 0.75rem;"><i class="fas fa-save"></i> Save & Publish Post</button>
                        <a href="{{ route('admin.blog.index') }}" class="btn btn-outline" style="width: 100%; justify-content: center;">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

{{-- Image Insert Modal --}}
<div class="modal-overlay" id="imageModal">
    <div class="modal-box">
        <h3><i class="fas fa-image"></i> Insert Image</h3>
        <div class="modal-tabs">
            <button class="modal-tab active" data-tab="upload">Upload File</button>
            <button class="modal-tab" data-tab="url">Image URL</button>
        </div>
        <div class="modal-tab-content active" id="tab-upload">
            <div class="form-group">
                <label class="form-label">Select Image File</label>
                <input type="file" id="inlineImageFile" class="form-control" accept="image/*">
            </div>
            <div class="form-group">
                <label class="form-label">Alt Text</label>
                <input type="text" id="inlineImageAlt" class="form-control" placeholder="Describe the image...">
            </div>
            <div class="form-group">
                <label class="form-label">Alignment</label>
                <select id="inlineImageAlign" class="form-control">
                    <option value="">Default (inline)</option>
                    <option value="align-left">Float Left</option>
                    <option value="align-right">Float Right</option>
                    <option value="align-center">Center</option>
                </select>
            </div>
            <button type="button" class="btn btn-primary" id="insertUploadedImage">Insert Image</button>
            <button type="button" class="btn btn-outline" onclick="closeModal('imageModal')" style="margin-left: 0.5rem;">Cancel</button>
        </div>
        <div class="modal-tab-content" id="tab-url">
            <div class="form-group">
                <label class="form-label">Image URL</label>
                <input type="url" id="inlineImageUrl" class="form-control" placeholder="https://example.com/image.jpg">
            </div>
            <div class="form-group">
                <label class="form-label">Alt Text</label>
                <input type="text" id="inlineImageUrlAlt" class="form-control" placeholder="Describe the image...">
            </div>
            <div class="form-group">
                <label class="form-label">Alignment</label>
                <select id="inlineImageUrlAlign" class="form-control">
                    <option value="">Default (inline)</option>
                    <option value="align-left">Float Left</option>
                    <option value="align-right">Float Right</option>
                    <option value="align-center">Center</option>
                </select>
            </div>
            <button type="button" class="btn btn-primary" id="insertUrlImage">Insert Image</button>
            <button type="button" class="btn btn-outline" onclick="closeModal('imageModal')" style="margin-left: 0.5rem;">Cancel</button>
        </div>
    </div>
</div>

{{-- Embed Modal --}}
<div class="modal-overlay" id="embedModal">
    <div class="modal-box">
        <h3><i class="fas fa-film"></i> Embed Media</h3>
        <div class="form-group">
            <label class="form-label">YouTube / Video URL or Embed Code</label>
            <textarea id="embedCode" class="form-control" rows="4" placeholder="Paste YouTube URL or full iframe embed code..."></textarea>
        </div>
        <button type="button" class="btn btn-primary" id="insertEmbed">Insert</button>
        <button type="button" class="btn btn-outline" onclick="closeModal('embedModal')" style="margin-left: 0.5rem;">Cancel</button>
    </div>
</div>

{{-- HTML Source Modal --}}
<div class="modal-overlay" id="htmlModal">
    <div class="modal-box" style="width: 700px;">
        <h3><i class="fas fa-code"></i> HTML Source</h3>
        <textarea id="htmlSource" class="form-control" rows="15" style="font-family: monospace; font-size: 0.85rem;"></textarea>
        <div style="margin-top: 1rem; display: flex; gap: 0.5rem;">
            <button type="button" class="btn btn-primary" id="applyHtmlSource">Apply Changes</button>
            <button type="button" class="btn btn-outline" onclick="closeModal('htmlModal')">Cancel</button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const editor = document.getElementById('editor-content');
    const hiddenContent = document.getElementById('hiddenContent');
    let savedRange = null;

    // Save selection before modal opens
    function saveSelection() {
        const sel = window.getSelection();
        if (sel.rangeCount > 0) {
            savedRange = sel.getRangeAt(0).cloneRange();
        }
    }

    function restoreSelection() {
        if (savedRange) {
            const sel = window.getSelection();
            sel.removeAllRanges();
            sel.addRange(savedRange);
        }
    }

    // Sync editor content to hidden textarea before submit
    document.getElementById('blogForm').addEventListener('submit', function() {
        hiddenContent.value = editor.innerHTML;
    });

    // Toolbar command buttons
    document.querySelectorAll('[data-cmd]').forEach(btn => {
        btn.addEventListener('click', function() {
            editor.focus();
            document.execCommand(this.dataset.cmd, false, null);
        });
    });

    // Heading select
    document.getElementById('headingSelect').addEventListener('change', function() {
        editor.focus();
        document.execCommand('formatBlock', false, this.value === 'p' ? 'p' : this.value);
    });

    // Blockquote
    document.getElementById('btnBlockquote').addEventListener('click', function() {
        editor.focus();
        document.execCommand('formatBlock', false, 'blockquote');
    });

    // Inline code
    document.getElementById('btnCode').addEventListener('click', function() {
        editor.focus();
        const sel = window.getSelection();
        if (sel.rangeCount > 0) {
            const range = sel.getRangeAt(0);
            const code = document.createElement('code');
            try {
                range.surroundContents(code);
            } catch(e) {
                code.textContent = sel.toString();
                range.deleteContents();
                range.insertNode(code);
            }
        }
    });

    // Code block
    document.getElementById('btnCodeBlock').addEventListener('click', function() {
        editor.focus();
        const pre = document.createElement('pre');
        const code = document.createElement('code');
        code.textContent = window.getSelection().toString() || 'code here';
        pre.appendChild(code);
        const sel = window.getSelection();
        if (sel.rangeCount > 0) {
            sel.getRangeAt(0).deleteContents();
            sel.getRangeAt(0).insertNode(pre);
        }
    });

    // Horizontal rule
    document.getElementById('btnHR').addEventListener('click', function() {
        editor.focus();
        document.execCommand('insertHorizontalRule', false, null);
    });

    // Link
    document.getElementById('btnLink').addEventListener('click', function() {
        editor.focus();
        const url = prompt('Enter URL:', 'https://');
        if (url) document.execCommand('createLink', false, url);
    });

    // Table
    document.getElementById('btnTable').addEventListener('click', function() {
        editor.focus();
        const rows = parseInt(prompt('Number of rows:', '3')) || 3;
        const cols = parseInt(prompt('Number of columns:', '3')) || 3;
        let html = '<table><thead><tr>';
        for (let c = 0; c < cols; c++) html += `<th>Header ${c+1}</th>`;
        html += '</tr></thead><tbody>';
        for (let r = 0; r < rows - 1; r++) {
            html += '<tr>';
            for (let c = 0; c < cols; c++) html += '<td>Cell</td>';
            html += '</tr>';
        }
        html += '</tbody></table><p></p>';
        document.execCommand('insertHTML', false, html);
    });

    // Image modal
    document.getElementById('btnImage').addEventListener('click', function() {
        saveSelection();
        openModal('imageModal');
    });

    // Embed modal
    document.getElementById('btnEmbed').addEventListener('click', function() {
        saveSelection();
        openModal('embedModal');
    });

    // HTML Source modal
    document.getElementById('btnHtmlSource').addEventListener('click', function() {
        document.getElementById('htmlSource').value = editor.innerHTML;
        openModal('htmlModal');
    });

    document.getElementById('applyHtmlSource').addEventListener('click', function() {
        editor.innerHTML = document.getElementById('htmlSource').value;
        closeModal('htmlModal');
    });

    // Modal tabs
    document.querySelectorAll('.modal-tab').forEach(tab => {
        tab.addEventListener('click', function() {
            const modal = this.closest('.modal-box');
            modal.querySelectorAll('.modal-tab').forEach(t => t.classList.remove('active'));
            modal.querySelectorAll('.modal-tab-content').forEach(c => c.classList.remove('active'));
            this.classList.add('active');
            modal.querySelector('#tab-' + this.dataset.tab).classList.add('active');
        });
    });

    // Insert image from URL
    document.getElementById('insertUrlImage').addEventListener('click', function() {
        const url = document.getElementById('inlineImageUrl').value;
        const alt = document.getElementById('inlineImageUrlAlt').value;
        const align = document.getElementById('inlineImageUrlAlign').value;
        if (!url) return;
        restoreSelection();
        const img = `<img src="${url}" alt="${alt}" class="${align}" style="max-width:100%;">`;
        document.execCommand('insertHTML', false, img);
        closeModal('imageModal');
    });

    // Insert uploaded image (convert to base64 for now)
    document.getElementById('insertUploadedImage').addEventListener('click', function() {
        const file = document.getElementById('inlineImageFile').files[0];
        const alt = document.getElementById('inlineImageAlt').value;
        const align = document.getElementById('inlineImageAlign').value;
        if (!file) return;
        const reader = new FileReader();
        reader.onload = function(e) {
            restoreSelection();
            const img = `<img src="${e.target.result}" alt="${alt}" class="${align}" style="max-width:100%;">`;
            document.execCommand('insertHTML', false, img);
            closeModal('imageModal');
        };
        reader.readAsDataURL(file);
    });

    // Insert embed
    document.getElementById('insertEmbed').addEventListener('click', function() {
        let code = document.getElementById('embedCode').value.trim();
        if (!code) return;
        // Convert YouTube URL to embed
        const ytMatch = code.match(/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/);
        if (ytMatch) {
            code = `<div style="position:relative;padding-bottom:56.25%;height:0;overflow:hidden;margin:1rem 0;"><iframe src="https://www.youtube.com/embed/${ytMatch[1]}" style="position:absolute;top:0;left:0;width:100%;height:100%;border:0;" allowfullscreen></iframe></div>`;
        }
        restoreSelection();
        document.execCommand('insertHTML', false, code + '<p></p>');
        closeModal('embedModal');
    });

    // Modal helpers
    function openModal(id) {
        document.getElementById(id).classList.add('open');
    }
    function closeModal(id) {
        document.getElementById(id).classList.remove('open');
    }
    window.closeModal = closeModal;

    // Close modal on overlay click
    document.querySelectorAll('.modal-overlay').forEach(overlay => {
        overlay.addEventListener('click', function(e) {
            if (e.target === this) closeModal(this.id);
        });
    });

    // Featured image preview
    document.getElementById('featuredImageInput').addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('featuredImagePreviewImg').src = e.target.result;
                document.getElementById('featuredImagePreview').style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush
@endsection
