<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    private function tenantId()
    {
        return auth()->user()->getTenantId();
    }

    public function index()
    {
        $documents = Document::where('user_id', $this->tenantId())
            ->orderByDesc('created_at')
            ->get();
        $stats = [
            'total' => $documents->count(),
            'public' => $documents->where('is_public', true)->count(),
            'private' => $documents->where('is_public', false)->count(),
            'downloads' => $documents->sum('download_count'),
        ];
        return view('admin.documents.index', compact('documents', 'stats'));
    }

    public function create()
    {
        return view('admin.documents.form', ['document' => null]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'category' => 'required|in:brochure,company_profile,resume,product_catalog,certificate,other',
            'is_public' => 'required|in:0,1',
            'file' => 'required|file|max:20480', // 20MB max
        ]);

        $file = $request->file('file');
        $path = $file->store('documents', 'public');

        Document::create([
            'user_id' => $this->tenantId(),
            'title' => $validated['title'],
            'description' => $validated['description'],
            'category' => $validated['category'],
            'is_public' => (bool) $validated['is_public'],
            'file_path' => '/storage/' . $path,
            'file_name' => $file->getClientOriginalName(),
            'file_size' => $file->getSize(),
            'file_type' => $file->getClientMimeType(),
        ]);

        return redirect()->route('admin.documents.index')->with('success', 'Document uploaded successfully.');
    }

    public function edit(Document $document)
    {
        abort_if($document->user_id !== $this->tenantId(), 403);
        return view('admin.documents.form', compact('document'));
    }

    public function update(Request $request, Document $document)
    {
        abort_if($document->user_id !== $this->tenantId(), 403);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'category' => 'required|in:brochure,company_profile,resume,product_catalog,certificate,other',
            'is_public' => 'required|in:0,1',
            'file' => 'nullable|file|max:20480',
        ]);

        $data = [
            'title' => $validated['title'],
            'description' => $validated['description'],
            'category' => $validated['category'],
            'is_public' => (bool) $validated['is_public'],
        ];

        if ($request->hasFile('file')) {
            // Delete old file
            $oldPath = str_replace('/storage/', '', $document->file_path);
            Storage::disk('public')->delete($oldPath);

            $file = $request->file('file');
            $path = $file->store('documents', 'public');
            $data['file_path'] = '/storage/' . $path;
            $data['file_name'] = $file->getClientOriginalName();
            $data['file_size'] = $file->getSize();
            $data['file_type'] = $file->getClientMimeType();
        }

        $document->update($data);
        return redirect()->route('admin.documents.index')->with('success', 'Document updated.');
    }

    public function destroy(Document $document)
    {
        abort_if($document->user_id !== $this->tenantId(), 403);
        $oldPath = str_replace('/storage/', '', $document->file_path);
        Storage::disk('public')->delete($oldPath);
        $document->delete();
        return back()->with('success', 'Document deleted.');
    }
}
