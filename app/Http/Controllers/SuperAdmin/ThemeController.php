<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Theme;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ThemeController extends Controller
{
    public function index()
    {
        $themes = Theme::orderBy('sort_order')->get();
        return view('superadmin.themes.index', compact('themes'));
    }

    public function create()
    {
        return view('superadmin.themes.form', ['theme' => null]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'                => 'required|string|max:100',
            'slug'                => 'nullable|string|max:100|unique:themes,slug',
            'category'            => 'required|string|max:100',
            'description'         => 'nullable|string',
            'tags'                => 'nullable|string',
            'accent_color'        => 'nullable|string|max:20',
            'bg_color'            => 'nullable|string|max:20',
            'hero_title'          => 'nullable|string|max:200',
            'hero_sub'            => 'nullable|string|max:200',
            'best_for'            => 'nullable|string|max:200',
            'profession_key'      => 'nullable|string|max:50',
            'profession_keywords' => 'nullable|string',
            'is_premium'          => 'nullable|boolean',
            'is_active'           => 'nullable|boolean',
            'thumbnail'           => 'nullable|string|max:255',
            'demo_url'            => 'nullable|url|max:255',
            'sort_order'          => 'nullable|integer',
        ]);

        $data['slug']                = $data['slug'] ?? Str::slug($data['name']);
        $data['tags']                = $data['tags'] ? array_map('trim', explode(',', $data['tags'])) : [];
        $data['profession_keywords'] = $data['profession_keywords'] ? array_map('trim', explode(',', $data['profession_keywords'])) : [];
        $data['is_premium']          = $request->boolean('is_premium');
        $data['is_active']           = $request->boolean('is_active', true);

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail_file')) {
            $path = $request->file('thumbnail_file')->store('themes', 'public');
            $data['thumbnail'] = $path;
        }

        Theme::create($data);

        return redirect()->route('superadmin.themes.index')
            ->with('success', 'Theme created successfully.');
    }

    public function edit(Theme $theme)
    {
        return view('superadmin.themes.form', compact('theme'));
    }

    public function update(Request $request, Theme $theme)
    {
        $data = $request->validate([
            'name'                => 'required|string|max:100',
            'category'            => 'required|string|max:100',
            'description'         => 'nullable|string',
            'tags'                => 'nullable|string',
            'accent_color'        => 'nullable|string|max:20',
            'bg_color'            => 'nullable|string|max:20',
            'hero_title'          => 'nullable|string|max:200',
            'hero_sub'            => 'nullable|string|max:200',
            'best_for'            => 'nullable|string|max:200',
            'profession_key'      => 'nullable|string|max:50',
            'profession_keywords' => 'nullable|string',
            'is_premium'          => 'nullable|boolean',
            'is_active'           => 'nullable|boolean',
            'thumbnail'           => 'nullable|string|max:255',
            'demo_url'            => 'nullable|url|max:255',
            'sort_order'          => 'nullable|integer',
        ]);

        $data['tags']                = $data['tags'] ? array_map('trim', explode(',', $data['tags'])) : [];
        $data['profession_keywords'] = $data['profession_keywords'] ? array_map('trim', explode(',', $data['profession_keywords'])) : [];
        $data['is_premium']          = $request->boolean('is_premium');
        $data['is_active']           = $request->boolean('is_active', true);

        if ($request->hasFile('thumbnail_file')) {
            $path = $request->file('thumbnail_file')->store('themes', 'public');
            $data['thumbnail'] = $path;
        }

        $theme->update($data);

        return redirect()->route('superadmin.themes.index')
            ->with('success', 'Theme updated successfully.');
    }

    public function destroy(Theme $theme)
    {
        $theme->delete();
        return redirect()->route('superadmin.themes.index')
            ->with('success', 'Theme deleted.');
    }

    public function toggleActive(Theme $theme)
    {
        $theme->update(['is_active' => !$theme->is_active]);
        return back()->with('success', 'Theme status updated.');
    }
}
