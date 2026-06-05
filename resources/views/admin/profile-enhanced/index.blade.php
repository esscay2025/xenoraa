@extends('layouts.admin')
@section('title', 'Profile Enhancements')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1">Profile Enhancements</h4>
        <p class="text-muted mb-0">Manage your skills, education, certifications, and languages</p>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle"></i> {{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

<ul class="nav nav-tabs mb-4" role="tablist">
    <li class="nav-item"><a class="nav-link {{ $tab === 'skills' ? 'active' : '' }}" href="{{ route('admin.profile-enhanced.index', ['tab' => 'skills']) }}"><i class="bi bi-lightning"></i> Skills <span class="badge bg-primary">{{ $skills->count() }}</span></a></li>
    <li class="nav-item"><a class="nav-link {{ $tab === 'education' ? 'active' : '' }}" href="{{ route('admin.profile-enhanced.index', ['tab' => 'education']) }}"><i class="bi bi-mortarboard"></i> Education <span class="badge bg-primary">{{ $education->count() }}</span></a></li>
    <li class="nav-item"><a class="nav-link {{ $tab === 'certifications' ? 'active' : '' }}" href="{{ route('admin.profile-enhanced.index', ['tab' => 'certifications']) }}"><i class="bi bi-award"></i> Certifications <span class="badge bg-primary">{{ $certifications->count() }}</span></a></li>
    <li class="nav-item"><a class="nav-link {{ $tab === 'languages' ? 'active' : '' }}" href="{{ route('admin.profile-enhanced.index', ['tab' => 'languages']) }}"><i class="bi bi-translate"></i> Languages <span class="badge bg-primary">{{ $languages->count() }}</span></a></li>
</ul>

{{-- Skills Tab --}}
@if($tab === 'skills')
<div class="row g-4">
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent"><h6 class="mb-0"><i class="bi bi-plus-circle"></i> Add Skill</h6></div>
            <div class="card-body">
                <form action="{{ route('admin.profile.skills.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Skill Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="e.g., Laravel, Python, UI Design" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Category</label>
                        <input type="text" name="category" class="form-control" placeholder="e.g., Backend, Frontend, Design">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Proficiency (1-100) <span class="text-danger">*</span></label>
                        <input type="range" name="proficiency" class="form-range" min="1" max="100" value="75" oninput="this.nextElementSibling.textContent=this.value+'%'">
                        <small class="text-muted">75%</small>
                    </div>
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-plus-lg"></i> Add Skill</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        @if($skills->isEmpty())
        <div class="text-center py-5"><i class="bi bi-lightning display-1 text-muted"></i><p class="text-muted mt-3">No skills added yet.</p></div>
        @else
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                @php $grouped = $skills->groupBy('category'); @endphp
                @foreach($grouped as $category => $items)
                <h6 class="text-primary mb-3">{{ $category ?: 'General' }}</h6>
                @foreach($items as $skill)
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between mb-1">
                            <span>{{ $skill->name }}</span>
                            <small class="text-muted">{{ $skill->proficiency }}%</small>
                        </div>
                        <div class="progress" style="height:6px;">
                            <div class="progress-bar" style="width:{{ $skill->proficiency }}%"></div>
                        </div>
                    </div>
                    <form action="{{ route('admin.profile.skills.destroy', $skill) }}" method="POST" onsubmit="return confirm('Remove?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="bi bi-x"></i></button></form>
                </div>
                @endforeach
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endif

{{-- Education Tab --}}
@if($tab === 'education')
<div class="row g-4">
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent"><h6 class="mb-0"><i class="bi bi-plus-circle"></i> Add Education</h6></div>
            <div class="card-body">
                <form action="{{ route('admin.profile.education.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Institution <span class="text-danger">*</span></label>
                        <input type="text" name="institution" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Degree <span class="text-danger">*</span></label>
                        <input type="text" name="degree" class="form-control" placeholder="e.g., B.Tech, MBA, PhD" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Field of Study</label>
                        <input type="text" name="field_of_study" class="form-control" placeholder="e.g., Computer Science">
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label">Start Date <span class="text-danger">*</span></label>
                            <input type="date" name="start_date" class="form-control" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">End Date</label>
                            <input type="date" name="end_date" class="form-control">
                        </div>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" name="is_current" value="1" class="form-check-input" id="isCurrentEdu">
                        <label class="form-check-label" for="isCurrentEdu">Currently studying here</label>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Grade / GPA</label>
                        <input type="text" name="grade" class="form-control" placeholder="e.g., 8.5 CGPA, First Class">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="2"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-plus-lg"></i> Add Education</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        @if($education->isEmpty())
        <div class="text-center py-5"><i class="bi bi-mortarboard display-1 text-muted"></i><p class="text-muted mt-3">No education records yet.</p></div>
        @else
        <div class="card border-0 shadow-sm">
            <div class="list-group list-group-flush">
                @foreach($education as $edu)
                <div class="list-group-item d-flex gap-3">
                    <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center" style="width:48px;height:48px;min-width:48px;"><i class="bi bi-mortarboard text-primary"></i></div>
                    <div class="flex-grow-1">
                        <h6 class="mb-0">{{ $edu->degree }}{{ $edu->field_of_study ? ' in ' . $edu->field_of_study : '' }}</h6>
                        <p class="mb-0 text-muted">{{ $edu->institution }}</p>
                        <small class="text-muted">
                            {{ $edu->start_date ? \Carbon\Carbon::parse($edu->start_date)->format('M Y') : '' }}
                            -
                            {{ $edu->is_current ? 'Present' : ($edu->end_date ? \Carbon\Carbon::parse($edu->end_date)->format('M Y') : 'N/A') }}
                        </small>
                        @if($edu->grade)<br><small class="text-muted">Grade: {{ $edu->grade }}</small>@endif
                        @if($edu->description)<p class="small mt-1 mb-0">{{ $edu->description }}</p>@endif
                    </div>
                    <form action="{{ route('admin.profile.education.destroy', $edu) }}" method="POST" onsubmit="return confirm('Remove?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button></form>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endif

{{-- Certifications Tab --}}
@if($tab === 'certifications')
<div class="row g-4">
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent"><h6 class="mb-0"><i class="bi bi-plus-circle"></i> Add Certification</h6></div>
            <div class="card-body">
                <form action="{{ route('admin.profile.certifications.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Certification Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Issuing Organization <span class="text-danger">*</span></label>
                        <input type="text" name="issuing_organization" class="form-control" placeholder="e.g., AWS, Google, Microsoft" required>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label">Issue Date</label>
                            <input type="date" name="issue_date" class="form-control">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Expiry Date</label>
                            <input type="date" name="expiry_date" class="form-control">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Credential ID</label>
                        <input type="text" name="credential_id" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Credential URL</label>
                        <input type="url" name="credential_url" class="form-control" placeholder="https://...">
                    </div>
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-plus-lg"></i> Add Certification</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        @if($certifications->isEmpty())
        <div class="text-center py-5"><i class="bi bi-award display-1 text-muted"></i><p class="text-muted mt-3">No certifications yet.</p></div>
        @else
        <div class="card border-0 shadow-sm">
            <div class="list-group list-group-flush">
                @foreach($certifications as $cert)
                <div class="list-group-item d-flex gap-3">
                    <div class="rounded-circle bg-warning bg-opacity-10 d-flex align-items-center justify-content-center" style="width:48px;height:48px;min-width:48px;"><i class="bi bi-award text-warning"></i></div>
                    <div class="flex-grow-1">
                        <h6 class="mb-0">{{ $cert->name }}</h6>
                        <p class="mb-0 text-muted">{{ $cert->issuing_organization }}</p>
                        <small class="text-muted">
                            @if($cert->issue_date) Issued {{ \Carbon\Carbon::parse($cert->issue_date)->format('M Y') }} @endif
                            @if($cert->expiry_date) &middot; Expires {{ \Carbon\Carbon::parse($cert->expiry_date)->format('M Y') }} @endif
                        </small>
                        @if($cert->credential_url)<br><a href="{{ $cert->credential_url }}" target="_blank" class="small"><i class="bi bi-box-arrow-up-right"></i> View Credential</a>@endif
                    </div>
                    <form action="{{ route('admin.profile.certifications.destroy', $cert) }}" method="POST" onsubmit="return confirm('Remove?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button></form>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endif

{{-- Languages Tab --}}
@if($tab === 'languages')
<div class="row g-4">
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent"><h6 class="mb-0"><i class="bi bi-plus-circle"></i> Add Language</h6></div>
            <div class="card-body">
                <form action="{{ route('admin.profile.languages.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Language <span class="text-danger">*</span></label>
                        <input type="text" name="language" class="form-control" placeholder="e.g., English, Hindi, Tamil" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Proficiency <span class="text-danger">*</span></label>
                        <select name="proficiency" class="form-select" required>
                            @foreach(\App\Models\ProfileLanguage::PROFICIENCIES as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-plus-lg"></i> Add Language</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        @if($languages->isEmpty())
        <div class="text-center py-5"><i class="bi bi-translate display-1 text-muted"></i><p class="text-muted mt-3">No languages added yet.</p></div>
        @else
        <div class="card border-0 shadow-sm">
            <div class="list-group list-group-flush">
                @foreach($languages as $lang)
                <div class="list-group-item d-flex align-items-center gap-3">
                    <i class="bi bi-translate fs-4 text-info"></i>
                    <div class="flex-grow-1">
                        <strong>{{ $lang->language }}</strong>
                        @php
                            $profColors = ['native' => 'success', 'fluent' => 'primary', 'professional' => 'info', 'conversational' => 'warning', 'basic' => 'secondary'];
                        @endphp
                        <span class="badge bg-{{ $profColors[$lang->proficiency] ?? 'secondary' }} ms-2">{{ \App\Models\ProfileLanguage::PROFICIENCIES[$lang->proficiency] ?? ucfirst($lang->proficiency) }}</span>
                    </div>
                    <form action="{{ route('admin.profile.languages.destroy', $lang) }}" method="POST" onsubmit="return confirm('Remove?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button></form>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endif
@endsection
