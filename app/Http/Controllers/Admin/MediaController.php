<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MediaItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    private function tenantId()
    {
        return auth()->user()->getTenantId();
    }

    public function index(Request $request)
    {
        $query = MediaItem::where('user_id', $this->tenantId());

        if ($request->filled('type') && in_array($request->type, ['image', 'video', 'youtube'])) {
            $query->where('type', $request->type);
        }
        if ($request->filled('album')) {
            $query->where('album', $request->album);
        }

        $media = $query->orderByDesc('created_at')->get();
        $albums = MediaItem::where('user_id', $this->tenantId())
            ->whereNotNull('album')
            ->distinct()
            ->pluck('album');

        $stats = [
            'total' => $media->count(),
            'images' => MediaItem::where('user_id', $this->tenantId())->where('type', 'image')->count(),
            'videos' => MediaItem::where('user_id', $this->tenantId())->whereIn('type', ['video', 'youtube'])->count(),
        ];

        return view('admin.media.index', compact('media', 'albums', 'stats'));
    }

    public function create()
    {
        $albums = MediaItem::where('user_id', $this->tenantId())
            ->whereNotNull('album')
            ->distinct()
            ->pluck('album');
        return view('admin.media.form', ['item' => null, 'albums' => $albums]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'type' => 'required|in:image,video,youtube',
            'file' => 'required_if:type,image,video|file|max:51200', // 50MB
            'video_url' => 'required_if:type,youtube|nullable|url',
            'album' => 'nullable|string|max:100',
            'is_public' => 'required|in:0,1',
        ]);

        $data = [
            'user_id' => $this->tenantId(),
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'type' => $validated['type'],
            'album' => $validated['album'] ?? null,
            'is_public' => (bool) $validated['is_public'],
        ];

        if ($validated['type'] === 'youtube') {
            $data['video_url'] = $validated['video_url'];
            // Generate YouTube thumbnail
            preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/', $validated['video_url'], $matches);
            if (isset($matches[1])) {
                $data['thumbnail'] = "https://img.youtube.com/vi/{$matches[1]}/hqdefault.jpg";
            }
        } else {
            $file = $request->file('file');
            $path = $file->store('media', 'public');
            $data['file_path'] = '/storage/' . $path;

            // Generate thumbnail for images
            if ($validated['type'] === 'image') {
                $data['thumbnail'] = $data['file_path'];
            }
        }

        MediaItem::create($data);
        return redirect()->route('admin.media.index')->with('success', 'Media uploaded successfully.');
    }

    public function edit($id)
    {
        $item = MediaItem::where('user_id', $this->tenantId())->findOrFail($id);
        $albums = MediaItem::where('user_id', $this->tenantId())
            ->whereNotNull('album')
            ->distinct()
            ->pluck('album');
        return view('admin.media.form', compact('item', 'albums'));
    }

    public function update(Request $request, $id)
    {
        $item = MediaItem::where('user_id', $this->tenantId())->findOrFail($id);

        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'album' => 'nullable|string|max:100',
            'is_public' => 'required|in:0,1',
            'video_url' => 'nullable|url',
        ]);

        $data = [
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'album' => $validated['album'] ?? null,
            'is_public' => (bool) $validated['is_public'],
        ];

        if ($item->type === 'youtube' && !empty($validated['video_url'])) {
            $data['video_url'] = $validated['video_url'];
            preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/', $validated['video_url'], $matches);
            if (isset($matches[1])) {
                $data['thumbnail'] = "https://img.youtube.com/vi/{$matches[1]}/hqdefault.jpg";
            }
        }

        $item->update($data);
        return redirect()->route('admin.media.index')->with('success', 'Media updated.');
    }

    public function destroy($id)
    {
        $item = MediaItem::where('user_id', $this->tenantId())->findOrFail($id);
        if ($item->file_path) {
            $path = str_replace('/storage/', '', $item->file_path);
            Storage::disk('public')->delete($path);
        }
        $item->delete();
        return back()->with('success', 'Media deleted.');
    }
}
