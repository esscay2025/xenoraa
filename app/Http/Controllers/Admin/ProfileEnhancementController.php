<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProfileSkill;
use App\Models\ProfileEducation;
use App\Models\ProfileCertification;
use App\Models\ProfileLanguage;
use Illuminate\Http\Request;

class ProfileEnhancementController extends Controller
{
    private function tenantId()
    {
        return auth()->user()->getTenantId();
    }

    public function index(Request $request)
    {
        $tab = $request->get('tab', 'skills');
        $skills = ProfileSkill::where('user_id', $this->tenantId())->orderBy('sort_order')->get();
        $education = ProfileEducation::where('user_id', $this->tenantId())->orderByDesc('start_date')->get();
        $certifications = ProfileCertification::where('user_id', $this->tenantId())->orderByDesc('issue_date')->get();
        $languages = ProfileLanguage::where('user_id', $this->tenantId())->orderBy('sort_order')->get();

        return view('admin.profile-enhanced.index', compact('skills', 'education', 'certifications', 'languages', 'tab'));
    }

    // --- Skills ---
    public function storeSkill(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'category' => 'nullable|string|max:100',
            'proficiency' => 'required|integer|min:1|max:100',
        ]);
        $validated['user_id'] = $this->tenantId();
        $validated['sort_order'] = ProfileSkill::where('user_id', $this->tenantId())->max('sort_order') + 1;
        ProfileSkill::create($validated);
        return back()->with('success', 'Skill added.');
    }

    public function destroySkill(ProfileSkill $skill)
    {
        abort_if($skill->user_id !== $this->tenantId(), 403);
        $skill->delete();
        return back()->with('success', 'Skill removed.');
    }

    // --- Education ---
    public function storeEducation(Request $request)
    {
        $validated = $request->validate([
            'institution' => 'required|string|max:255',
            'degree' => 'required|string|max:255',
            'field_of_study' => 'nullable|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date',
            'is_current' => 'boolean',
            'description' => 'nullable|string|max:1000',
            'grade' => 'nullable|string|max:50',
        ]);
        $validated['user_id'] = $this->tenantId();
        $validated['is_current'] = $request->boolean('is_current');
        $validated['sort_order'] = ProfileEducation::where('user_id', $this->tenantId())->max('sort_order') + 1;
        ProfileEducation::create($validated);
        return back()->with('success', 'Education added.');
    }

    public function destroyEducation(ProfileEducation $education)
    {
        abort_if($education->user_id !== $this->tenantId(), 403);
        $education->delete();
        return back()->with('success', 'Education removed.');
    }

    // --- Certifications ---
    public function storeCertification(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'issuing_organization' => 'required|string|max:255',
            'issue_date' => 'nullable|date',
            'expiry_date' => 'nullable|date',
            'credential_id' => 'nullable|string|max:255',
            'credential_url' => 'nullable|url|max:255',
        ]);
        $validated['user_id'] = $this->tenantId();
        $validated['sort_order'] = ProfileCertification::where('user_id', $this->tenantId())->max('sort_order') + 1;
        ProfileCertification::create($validated);
        return back()->with('success', 'Certification added.');
    }

    public function destroyCertification(ProfileCertification $certification)
    {
        abort_if($certification->user_id !== $this->tenantId(), 403);
        $certification->delete();
        return back()->with('success', 'Certification removed.');
    }

    // --- Languages ---
    public function storeLanguage(Request $request)
    {
        $validated = $request->validate([
            'language' => 'required|string|max:100',
            'proficiency' => 'required|in:basic,conversational,professional,fluent,native',
        ]);
        $validated['user_id'] = $this->tenantId();
        $validated['sort_order'] = ProfileLanguage::where('user_id', $this->tenantId())->max('sort_order') + 1;
        ProfileLanguage::create($validated);
        return back()->with('success', 'Language added.');
    }

    public function destroyLanguage(ProfileLanguage $language)
    {
        abort_if($language->user_id !== $this->tenantId(), 403);
        $language->delete();
        return back()->with('success', 'Language removed.');
    }
}
