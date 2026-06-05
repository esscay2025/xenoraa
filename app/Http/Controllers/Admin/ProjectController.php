<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    private function tenantId()
    {
        return auth()->user()->getTenantId();
    }

    public function index()
    {
        $projects = Project::where('user_id', $this->tenantId())
            ->orderBy('sort_order')
            ->orderByDesc('created_at')
            ->get();
        return view('admin.projects.index', compact('projects'));
    }

    public function create()
    {
        return view('admin.projects.form', ['project' => null]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'client_name' => 'nullable|string|max:255',
            'project_url' => 'nullable|url|max:500',
            'technology_used' => 'nullable|string|max:500',
            'category' => 'nullable|string|max:100',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'status' => 'required|in:completed,in_progress,planned',
            'is_featured' => 'nullable|boolean',
            'featured_image' => 'nullable|image|max:5120',
        ]);

        $validated['user_id'] = $this->tenantId();
        $validated['slug'] = Str::slug($validated['title']);
        $validated['is_featured'] = $request->has('is_featured');

        if ($request->hasFile('featured_image')) {
            $path = $request->file('featured_image')->store('projects', 'public');
            $validated['featured_image'] = '/storage/' . $path;
        }

        // Handle multiple images
        if ($request->hasFile('images')) {
            $images = [];
            foreach ($request->file('images') as $img) {
                $p = $img->store('projects/gallery', 'public');
                $images[] = '/storage/' . $p;
            }
            $validated['images'] = $images;
        }

        Project::create($validated);

        return redirect()->route('admin.projects.index')
            ->with('success', 'Project created successfully.');
    }

    public function edit(Project $project)
    {
        abort_if($project->user_id !== $this->tenantId(), 403);
        return view('admin.projects.form', compact('project'));
    }

    public function update(Request $request, Project $project)
    {
        abort_if($project->user_id !== $this->tenantId(), 403);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'client_name' => 'nullable|string|max:255',
            'project_url' => 'nullable|url|max:500',
            'technology_used' => 'nullable|string|max:500',
            'category' => 'nullable|string|max:100',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'status' => 'required|in:completed,in_progress,planned',
            'is_featured' => 'nullable|boolean',
            'featured_image' => 'nullable|image|max:5120',
        ]);

        $validated['is_featured'] = $request->has('is_featured');

        if ($request->hasFile('featured_image')) {
            $path = $request->file('featured_image')->store('projects', 'public');
            $validated['featured_image'] = '/storage/' . $path;
        }

        if ($request->hasFile('images')) {
            $images = $project->images ?? [];
            foreach ($request->file('images') as $img) {
                $p = $img->store('projects/gallery', 'public');
                $images[] = '/storage/' . $p;
            }
            $validated['images'] = $images;
        }

        $project->update($validated);

        return redirect()->route('admin.projects.index')
            ->with('success', 'Project updated successfully.');
    }

    public function destroy(Project $project)
    {
        abort_if($project->user_id !== $this->tenantId(), 403);
        $project->delete();
        return redirect()->route('admin.projects.index')
            ->with('success', 'Project deleted successfully.');
    }
}
