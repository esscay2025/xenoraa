<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    private function tenantId()
    {
        return auth()->user()->getTenantId();
    }

    public function index()
    {
        $testimonials = Testimonial::where('user_id', $this->tenantId())
            ->orderByDesc('created_at')
            ->get();
        $stats = [
            'total' => $testimonials->count(),
            'approved' => $testimonials->where('status', 'approved')->count(),
            'pending' => $testimonials->where('status', 'pending')->count(),
            'avg_rating' => $testimonials->where('status', 'approved')->avg('rating'),
        ];
        return view('admin.testimonials.index', compact('testimonials', 'stats'));
    }

    public function create()
    {
        return view('admin.testimonials.form', ['testimonial' => null]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
            'client_email' => 'nullable|email|max:255',
            'client_company' => 'nullable|string|max:255',
            'client_designation' => 'nullable|string|max:255',
            'review' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'video_url' => 'nullable|url|max:500',
            'status' => 'required|in:pending,approved,rejected',
            'is_featured' => 'nullable|boolean',
            'client_photo' => 'nullable|image|max:2048',
        ]);

        $validated['user_id'] = $this->tenantId();
        $validated['is_featured'] = $request->has('is_featured');

        if ($request->hasFile('client_photo')) {
            $path = $request->file('client_photo')->store('testimonials', 'public');
            $validated['client_photo'] = '/storage/' . $path;
        }

        Testimonial::create($validated);

        return redirect()->route('admin.testimonials.index')
            ->with('success', 'Testimonial added successfully.');
    }

    public function edit(Testimonial $testimonial)
    {
        abort_if($testimonial->user_id !== $this->tenantId(), 403);
        return view('admin.testimonials.form', compact('testimonial'));
    }

    public function update(Request $request, Testimonial $testimonial)
    {
        abort_if($testimonial->user_id !== $this->tenantId(), 403);

        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
            'client_email' => 'nullable|email|max:255',
            'client_company' => 'nullable|string|max:255',
            'client_designation' => 'nullable|string|max:255',
            'review' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'video_url' => 'nullable|url|max:500',
            'status' => 'required|in:pending,approved,rejected',
            'is_featured' => 'nullable|boolean',
            'client_photo' => 'nullable|image|max:2048',
        ]);

        $validated['is_featured'] = $request->has('is_featured');

        if ($request->hasFile('client_photo')) {
            $path = $request->file('client_photo')->store('testimonials', 'public');
            $validated['client_photo'] = '/storage/' . $path;
        }

        $testimonial->update($validated);

        return redirect()->route('admin.testimonials.index')
            ->with('success', 'Testimonial updated successfully.');
    }

    public function approve(Testimonial $testimonial)
    {
        abort_if($testimonial->user_id !== $this->tenantId(), 403);
        $testimonial->update(['status' => 'approved']);
        return back()->with('success', 'Testimonial approved.');
    }

    public function reject(Testimonial $testimonial)
    {
        abort_if($testimonial->user_id !== $this->tenantId(), 403);
        $testimonial->update(['status' => 'rejected']);
        return back()->with('success', 'Testimonial rejected.');
    }

    public function destroy(Testimonial $testimonial)
    {
        abort_if($testimonial->user_id !== $this->tenantId(), 403);
        $testimonial->delete();
        return redirect()->route('admin.testimonials.index')
            ->with('success', 'Testimonial deleted.');
    }
}
