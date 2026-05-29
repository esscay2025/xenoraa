<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class JobController extends Controller
{
    /**
     * Display a listing of jobs.
     */
    public function index(Request $request)
    {
        $query = Job::withCount('applications');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $jobs = $query->orderBy('created_at', 'desc')->paginate(15);
        return view('admin.jobs.index', compact('jobs'));
    }

    /**
     * Show the form for creating a new job.
     */
    public function create()
    {
        return view('admin.jobs.create');
    }

    /**
     * Store a newly created job.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required'],
            'location' => ['required', 'string'],
            'type' => ['required', 'string'],
            'status' => ['required', 'in:active,filled,inactive'],
        ]);

        Job::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'slug' => Str::slug($request->title) . '-' . time(),
            'description' => $request->description,
            'requirements' => $request->requirements,
            'location' => $request->location,
            'type' => $request->type,
            'salary_range' => $request->salary_range,
            'status' => $request->status,
            'expires_at' => $request->expires_at,
        ]);

        return redirect()->route('admin.jobs.index')->with('success', 'Job posted successfully.');
    }

    /**
     * Show the form for editing the specified job.
     */
    public function edit(Job $job)
    {
        return view('admin.jobs.edit', compact('job'));
    }

    /**
     * Update the specified job.
     */
    public function update(Request $request, Job $job)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required'],
            'location' => ['required', 'string'],
            'type' => ['required', 'string'],
            'status' => ['required', 'in:active,filled,inactive'],
        ]);

        $job->update($request->only(['title', 'description', 'requirements', 'location', 'type', 'salary_range', 'status', 'expires_at']));

        return redirect()->route('admin.jobs.index')->with('success', 'Job updated successfully.');
    }

    /**
     * Remove the specified job.
     */
    public function destroy(Job $job)
    {
        $job->delete();
        return redirect()->route('admin.jobs.index')->with('success', 'Job deleted successfully.');
    }

    /**
     * View all applications for a specific job.
     */
    public function applications(Job $job)
    {
        $applications = $job->applications()->with('user')->orderBy('created_at', 'desc')->paginate(20);
        return view('admin.jobs.applications', compact('job', 'applications'));
    }

    /**
     * Update application status.
     */
    public function updateApplicationStatus(Request $request, JobApplication $application)
    {
        $request->validate(['status' => ['required', 'in:applied,reviewing,interviewing,offered,rejected']]);
        $application->update(['status' => $request->status]);
        return back()->with('success', 'Application status updated.');
    }
}
