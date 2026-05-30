@extends('layouts.admin')
@section('title', 'Edit Blog Post')

@push('styles')
<style>
    .editor-toolbar {
        display: flex; flex-wrap: wrap; gap: 0.25rem; padding: 0.75rem;
        background: var(--bg-secondary); border: 1px solid var(--border-light);
        border-bottom: none; border-radius: 8px 8px 0 0;
    }
    .editor-toolbar button, .editor-toolbar select {
        background: var(--bg-card); border: 1px solid var(--border); color: var(--text-primary);
        padding: 0.3rem 0.6rem; border-radius: 4px; cursor: pointer; font-size: 0.8rem;
        font-weight: 600; transition: all 0.15s; min-width: 30px;
        display: flex; align-items: center; justify-content: center;
    }
    .editor-toolbar button:hover, .editor-toolbar select:hover { background: var(--bg-hover); border-color: #555; }
    .editor-toolbar .toolbar-sep { width: 1px; background: var(--border); margin: 0 0.25rem; align-self: stretch; }
    .editor-toolbar select { padding: 0.3rem 0.5rem; min-width: 80px; }
    #editor-content {
        min-height: 400px; padding: 1.25rem; background: var(--bg-secondary);
        border: 1px solid var(--border-light); border-radius: 0 0 8px 8px;
        color: var(--text-primary); font-size: 0.95rem; line-height: 1.7; outline: none;
    }
    #editor-content h1 { font-size: 2rem; font-weight: 800; margin: 1.5rem 0 0.75rem; }
    #editor-content h2 { font-size: 1.6rem; font-weight: 700; margin: 1.5rem 0 0.75rem; }
    #editor-content h3 { font-size: 1.3rem; font-weight: 600; margin: 1.25rem 0 0.5rem; }
    #editor-content h4 { font-size: 1.1rem; font-weight: 600; margin: 1rem 0 0.5rem; }
    #editor-content p { margin: 0 0 1rem; }
    #editor-content ul, #editor-content ol { margin: 0 0 1rem 1.5rem; }
    #editor-content blockquote { border-left: 3px solid #555; padding-left: 1rem; margin: 1rem 0; color: var(--text-secondary); font-style: italic; }
    #editor-content code { background: #1a1a1a; border: 1px solid var(--border); padding: 0.15rem 0.4rem; border-radius: 4px; font-family: monospace; font-size: 0.875rem; }
    #editor-content pre { background: #111; border: 1px solid var(--border); padding: 1rem; border-radius: 8px; overflow-x: auto; margin: 1rem 0; }
    #editor-content pre code { background: none; border: none; padding: 0; }
    #editor-content table { border-collapse: collapse; width: 100%; margin: 1rem 0; }
    #editor-content table th, #editor-content table td { border: 1px solid var(--border); padding: 0.5rem 0.75rem; }
    #editor-content table th { background: var(--bg-card); font-weight: 600; }
    #editor-content img { max-width: 100%; border-radius: 8px; margin: 0.5rem 0; }
    #editor-content img.align-left { float: left; margin-right: 1.5rem; max-width: 50%; }
    #editor-content img.align-right { float: right; margin-left: 1.5rem; max-width: 50%; }
    #editor-content img.align-center { display: block; margin: 0.5rem auto; }
    #editor-content a { color: #93c5fd; text-decoration: underline; }
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
    <form method="POST" action="{{ route('admin.blog.update', $blog) }}" enctype="multipart/form-data" id="blogForm">
        @csrf @method('PUT')
        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
            <div class="grid-2" style="gap: 2rem;">
                <div class="form-group">
                    <label class="form-label">Post Title *</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $blog->title) }}" required style="font-size: 1.1rem; padding: 0.75rem 1rem;">
                </div>
                <div class="form-group">
                    <label class="form-label">Excerpt / Summary</label>
                    <textarea name="summary" class="form-control" rows="2">{{ old('summary', $blog->summary) }}</textarea>
                </div>
            </div>

            <div>
                <label class="form-label">Content *</label>
                <div class="editor-toolbar">
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
                    <button type="button" data-cmd="bold"><b>B</b></button>
                    <button type="button" data-cmd="italic"><i>I</i></button>
                    <button type="button" data-cmd="underline"><u>U</u></button>
                    <button type="button" data-cmd="strikeThrough"><s>S</s></button>
                    <div class="toolbar-sep"></div>
                    <button type="button" data-cmd="insertUnorderedList"><i class="fas fa-list-ul"></i></button>
                    <button type="button" data-cmd="insertOrderedList"><i class="fas fa-list-ol"></i></button>
                    <div class="toolbar-sep"></div>
                    <button type="button" data-cmd="justifyLeft"><i class="fas fa-align-left"></i></button>
                    <button type="button" data-cmd="justifyCenter"><i class="fas fa-align-center"></i></button>
                    <button type="button" data-cmd="justifyRight"><i class="fas fa-align-right"></i></button>
                    <div class="toolbar-sep"></div>
                    <button type="button" id="btnBlockquote"><i class="fas fa-quote-left"></i></button>
                    <button type="button" id="btnCode"><i class="fas fa-code"></i></button>
                    <button type="button" id="btnCodeBlock"><i class="fas fa-terminal"></i></button>
                    <button type="button" id="btnHR"><i class="fas fa-minus"></i></button>
                    <div class="toolbar-sep"></div>
                    <button type="button" id="btnLink"><i class="fas fa-link"></i></button>
                    <button type="button" id="btnImage"><i class="fas fa-image"></i></button>
                    <button type="button" id="btnTable"><i class="fas fa-table"></i></button>
                    <button type="button" id="btnEmbed"><i class="fas fa-film"></i></button>
                    <div class="toolbar-sep"></div>
                    <button type="button" data-cmd="undo"><i class="fas fa-undo"></i></button>
                    <button type="button" data-cmd="redo"><i class="fas fa-redo"></i></button>
                    <div class="toolbar-sep"></div>
                    <button type="button" id="btnHtmlSource"><i class="fas fa-code"></i> HTML</button>
                </div>
                <div id="editor-content" contenteditable="true">{!! old('content', $blog->content) !!}</div>
                <textarea name="content" id="hiddenContent" style="display:none;" required>{{ old('content', $blog->content) }}</textarea>
            </div>

            <div class="grid-2" style="align-items: start;">
                <div class="card">
                    <h3 style="font-size: 0.875rem; font-weight: 600; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 1rem;">Publish Settings</h3>
                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-control" required>
                            <option value="draft" {{ old('status', $blog->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ old('status', $blog->status) === 'published' ? 'selected' : '' }}>Published</option>
                            <option value="archived" {{ old('status', $blog->status) === 'archived' ? 'selected' : '' }}>Archived</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Category</label>
                        <select name="category_id" class="form-control">
                            <option value="">No Category</option>
                            @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $blog->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Featured Image</label>
                        @if($blog->featured_image)
                        <img src="{{ asset('storage/' . $blog->featured_image) }}" style="width: 100%; border-radius: 8px; margin-bottom: 0.5rem; max-height: 150px; object-fit: cover;">
                        @endif
                        <input type="file" name="featured_image" class="form-control" accept="image/*">
                    </div>
                </div>
                <div style="display: flex; flex-direction: column; gap: 0.75rem; padding-top: 0.5rem;">
                    <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; padding: 0.75rem;"><i class="fas fa-save"></i> Update Post</button>
                    <a href="{{ route('admin.blog.index') }}" class="btn btn-outline" style="width: 100%; justify-content: center;">Cancel</a>
                    <form method="POST" action="{{ route('admin.blog.destroy', $blog) }}" onsubmit="return confirm('Delete this post?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn" style="width: 100%; justify-content: center; background: rgba(239,68,68,0.1); color: #ef4444; border-color: rgba(239,68,68,0.3);">
                            <i class="fas fa-trash"></i> Delete Post
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </form>
</div>

<div class="modal-overlay" id="imageModal">
    <div class="modal-box">
        <h3><i class="fas fa-image"></i> Insert Image</h3>
        <div class="modal-tabs">
            <button class="modal-tab active" data-tab="upload">Upload File</button>
            <button class="modal-tab" data-tab="url">Image URL</button>
        </div>
        <div class="modal-tab-content active" id="tab-upload">
            <div class="form-group"><label class="form-label">Select Image</label><input type="file" id="inlineImageFile" class="form-control" accept="image/*"></div>
            <div class="form-group"><label class="form-label">Alt Text</label><input type="text" id="inlineImageAlt" class="form-control" placeholder="Describe the image..."></div>
            <div class="form-group"><label class="form-label">Alignment</label><select id="inlineImageAlign" class="form-control"><option value="">Default</option><option value="align-left">Float Left</option><option value="align-right">Float Right</option><option value="align-center">Center</option></select></div>
            <button type="button" class="btn btn-primary" id="insertUploadedImage">Insert</button>
            <button type="button" class="btn btn-outline" onclick="closeModal('imageModal')" style="margin-left:0.5rem;">Cancel</button>
        </div>
        <div class="modal-tab-content" id="tab-url">
            <div class="form-group"><label class="form-label">Image URL</label><input type="url" id="inlineImageUrl" class="form-control" placeholder="https://..."></div>
            <div class="form-group"><label class="form-label">Alt Text</label><input type="text" id="inlineImageUrlAlt" class="form-control"></div>
            <div class="form-group"><label class="form-label">Alignment</label><select id="inlineImageUrlAlign" class="form-control"><option value="">Default</option><option value="align-left">Float Left</option><option value="align-right">Float Right</option><option value="align-center">Center</option></select></div>
            <button type="button" class="btn btn-primary" id="insertUrlImage">Insert</button>
            <button type="button" class="btn btn-outline" onclick="closeModal('imageModal')" style="margin-left:0.5rem;">Cancel</button>
        </div>
    </div>
</div>

<div class="modal-overlay" id="embedModal">
    <div class="modal-box">
        <h3><i class="fas fa-film"></i> Embed Media</h3>
        <div class="form-group"><label class="form-label">YouTube URL or Embed Code</label><textarea id="embedCode" class="form-control" rows="4" placeholder="Paste YouTube URL or iframe embed code..."></textarea></div>
        <button type="button" class="btn btn-primary" id="insertEmbed">Insert</button>
        <button type="button" class="btn btn-outline" onclick="closeModal('embedModal')" style="margin-left:0.5rem;">Cancel</button>
    </div>
</div>

<div class="modal-overlay" id="htmlModal">
    <div class="modal-box" style="width:700px;">
        <h3><i class="fas fa-code"></i> HTML Source</h3>
        <textarea id="htmlSource" class="form-control" rows="15" style="font-family:monospace;font-size:0.85rem;"></textarea>
        <div style="margin-top:1rem;display:flex;gap:0.5rem;">
            <button type="button" class="btn btn-primary" id="applyHtmlSource">Apply</button>
            <button type="button" class="btn btn-outline" onclick="closeModal('htmlModal')">Cancel</button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const editor = document.getElementById('editor-content');
    const hiddenContent = document.getElementById('hiddenContent');
    let savedRange = null;
    function saveSelection() { const s = window.getSelection(); if(s.rangeCount>0) savedRange = s.getRangeAt(0).cloneRange(); }
    function restoreSelection() { if(savedRange) { const s=window.getSelection(); s.removeAllRanges(); s.addRange(savedRange); } }
    document.getElementById('blogForm').addEventListener('submit', function() { hiddenContent.value = editor.innerHTML; });
    document.querySelectorAll('[data-cmd]').forEach(btn => btn.addEventListener('click', function() { editor.focus(); document.execCommand(this.dataset.cmd, false, null); }));
    document.getElementById('headingSelect').addEventListener('change', function() { editor.focus(); document.execCommand('formatBlock', false, this.value === 'p' ? 'p' : this.value); });
    document.getElementById('btnBlockquote').addEventListener('click', function() { editor.focus(); document.execCommand('formatBlock', false, 'blockquote'); });
    document.getElementById('btnCode').addEventListener('click', function() { editor.focus(); const sel=window.getSelection(); if(sel.rangeCount>0){ const r=sel.getRangeAt(0),c=document.createElement('code'); try{r.surroundContents(c);}catch(e){c.textContent=sel.toString();r.deleteContents();r.insertNode(c);} } });
    document.getElementById('btnCodeBlock').addEventListener('click', function() { editor.focus(); const pre=document.createElement('pre'),code=document.createElement('code'); code.textContent=window.getSelection().toString()||'code here'; pre.appendChild(code); const sel=window.getSelection(); if(sel.rangeCount>0){sel.getRangeAt(0).deleteContents();sel.getRangeAt(0).insertNode(pre);} });
    document.getElementById('btnHR').addEventListener('click', function() { editor.focus(); document.execCommand('insertHorizontalRule', false, null); });
    document.getElementById('btnLink').addEventListener('click', function() { editor.focus(); const url=prompt('Enter URL:','https://'); if(url) document.execCommand('createLink', false, url); });
    document.getElementById('btnTable').addEventListener('click', function() { editor.focus(); const r=parseInt(prompt('Rows:','3'))||3,c=parseInt(prompt('Columns:','3'))||3; let h='<table><thead><tr>'; for(let i=0;i<c;i++) h+=`<th>Header ${i+1}</th>`; h+='</tr></thead><tbody>'; for(let i=0;i<r-1;i++){h+='<tr>';for(let j=0;j<c;j++) h+='<td>Cell</td>';h+='</tr>';} h+='</tbody></table><p></p>'; document.execCommand('insertHTML', false, h); });
    document.getElementById('btnImage').addEventListener('click', function() { saveSelection(); openModal('imageModal'); });
    document.getElementById('btnEmbed').addEventListener('click', function() { saveSelection(); openModal('embedModal'); });
    document.getElementById('btnHtmlSource').addEventListener('click', function() { document.getElementById('htmlSource').value=editor.innerHTML; openModal('htmlModal'); });
    document.getElementById('applyHtmlSource').addEventListener('click', function() { editor.innerHTML=document.getElementById('htmlSource').value; closeModal('htmlModal'); });
    document.querySelectorAll('.modal-tab').forEach(tab => tab.addEventListener('click', function() { const m=this.closest('.modal-box'); m.querySelectorAll('.modal-tab').forEach(t=>t.classList.remove('active')); m.querySelectorAll('.modal-tab-content').forEach(c=>c.classList.remove('active')); this.classList.add('active'); m.querySelector('#tab-'+this.dataset.tab).classList.add('active'); }));
    document.getElementById('insertUrlImage').addEventListener('click', function() { const url=document.getElementById('inlineImageUrl').value,alt=document.getElementById('inlineImageUrlAlt').value,align=document.getElementById('inlineImageUrlAlign').value; if(!url) return; restoreSelection(); document.execCommand('insertHTML', false, `<img src="${url}" alt="${alt}" class="${align}" style="max-width:100%;">`); closeModal('imageModal'); });
    document.getElementById('insertUploadedImage').addEventListener('click', function() { const file=document.getElementById('inlineImageFile').files[0],alt=document.getElementById('inlineImageAlt').value,align=document.getElementById('inlineImageAlign').value; if(!file) return; const r=new FileReader(); r.onload=function(e){restoreSelection();document.execCommand('insertHTML',false,`<img src="${e.target.result}" alt="${alt}" class="${align}" style="max-width:100%;">`);closeModal('imageModal');}; r.readAsDataURL(file); });
    document.getElementById('insertEmbed').addEventListener('click', function() { let code=document.getElementById('embedCode').value.trim(); if(!code) return; const yt=code.match(/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/); if(yt) code=`<div style="position:relative;padding-bottom:56.25%;height:0;overflow:hidden;margin:1rem 0;"><iframe src="https://www.youtube.com/embed/${yt[1]}" style="position:absolute;top:0;left:0;width:100%;height:100%;border:0;" allowfullscreen></iframe></div>`; restoreSelection(); document.execCommand('insertHTML', false, code+'<p></p>'); closeModal('embedModal'); });
    function openModal(id){document.getElementById(id).classList.add('open');}
    function closeModal(id){document.getElementById(id).classList.remove('open');}
    window.closeModal=closeModal;
    document.querySelectorAll('.modal-overlay').forEach(o=>o.addEventListener('click',function(e){if(e.target===this)closeModal(this.id);}));
</script>
@endpush
@endsection
