@extends('layouts.admin')
@section('title', 'Product Categories')
@php
    $contentActive = false; $recruitmentActive = false; $financeActive = false;
    $administrationActive = false; $communityActive = false; $crmActive = false;
    $ecommerceActive = true; $siteActive = false;
@endphp
@section('content')
<div style="padding:2rem;">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:2rem;flex-wrap:wrap;gap:1rem;">
        <div>
            <a href="{{ route('admin.ecommerce.dashboard') }}" style="color:#6366f1;text-decoration:none;font-size:0.875rem;display:block;margin-bottom:0.5rem;"><i class="fas fa-arrow-left"></i> E-commerce</a>
            <h1 style="font-size:1.75rem;font-weight:700;color:#fff;margin:0;">Product Categories</h1>
        </div>
    </div>

    @if(session('success'))
        <div style="background:#064e3b;border:1px solid #10b981;color:#6ee7b7;padding:0.75rem 1rem;border-radius:8px;margin-bottom:1.5rem;">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div style="background:#450a0a;border:1px solid #ef4444;color:#fca5a5;padding:0.75rem 1rem;border-radius:8px;margin-bottom:1.5rem;">{{ session('error') }}</div>
    @endif

    <div style="display:grid;grid-template-columns:1fr 360px;gap:1.5rem;align-items:start;">

        {{-- Categories List --}}
        <div style="background:#1e293b;border:1px solid #334155;border-radius:12px;overflow:hidden;">
            <table style="width:100%;border-collapse:collapse;">
                <thead>
                    <tr style="background:#0f172a;border-bottom:1px solid #334155;">
                        <th style="padding:0.875rem 1rem;text-align:left;font-size:0.75rem;color:#9ca3af;font-weight:600;text-transform:uppercase;">Category</th>
                        <th style="padding:0.875rem 1rem;text-align:left;font-size:0.75rem;color:#9ca3af;font-weight:600;text-transform:uppercase;">Subcategories</th>
                        <th style="padding:0.875rem 1rem;text-align:left;font-size:0.75rem;color:#9ca3af;font-weight:600;text-transform:uppercase;">Products</th>
                        <th style="padding:0.875rem 1rem;text-align:left;font-size:0.75rem;color:#9ca3af;font-weight:600;text-transform:uppercase;">Status</th>
                        <th style="padding:0.875rem 1rem;text-align:center;font-size:0.75rem;color:#9ca3af;font-weight:600;text-transform:uppercase;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $cat)
                    <tr style="border-bottom:1px solid #1e293b;" onmouseover="this.style.background='#0f172a'" onmouseout="this.style.background='transparent'">
                        <td style="padding:0.875rem 1rem;">
                            <div style="display:flex;align-items:center;gap:0.75rem;">
                                <div style="width:36px;height:36px;border-radius:8px;background:#0f172a;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <i class="fas {{ $cat->icon ?? 'fa-tag' }}" style="color:#6366f1;"></i>
                                </div>
                                <div>
                                    <div style="color:#fff;font-weight:600;font-size:0.875rem;">{{ $cat->name }}</div>
                                    <div style="color:#9ca3af;font-size:0.75rem;">/{{ $cat->slug }}</div>
                                </div>
                            </div>
                            {{-- Subcategories --}}
                            @if($cat->children->count() > 0)
                            <div style="margin-top:0.5rem;padding-left:3rem;">
                                @foreach($cat->children as $sub)
                                <div style="display:flex;align-items:center;gap:0.5rem;padding:0.25rem 0;">
                                    <i class="fas fa-level-up-alt fa-rotate-90" style="color:#6366f1;font-size:0.65rem;"></i>
                                    <span style="color:#9ca3af;font-size:0.8rem;">{{ $sub->name }}</span>
                                    <span style="background:#1e3a5f;color:#60a5fa;padding:0.1rem 0.4rem;border-radius:10px;font-size:0.7rem;">{{ $sub->products_count ?? 0 }}</span>
                                    <form method="POST" action="{{ route('admin.ecommerce.categories.destroy', $sub) }}" onsubmit="return confirm('Delete subcategory?')" style="margin-left:auto;">
                                        @csrf @method('DELETE')
                                        <button type="submit" style="background:transparent;color:#ef4444;border:none;cursor:pointer;font-size:0.7rem;padding:0;"><i class="fas fa-times"></i></button>
                                    </form>
                                </div>
                                @endforeach
                            </div>
                            @endif
                        </td>
                        <td style="padding:0.875rem 1rem;color:#9ca3af;font-size:0.875rem;">{{ $cat->children->count() }}</td>
                        <td style="padding:0.875rem 1rem;"><span style="background:#1e3a5f;color:#60a5fa;padding:0.2rem 0.6rem;border-radius:20px;font-size:0.8rem;">{{ $cat->products_count }}</span></td>
                        <td style="padding:0.875rem 1rem;">
                            <span style="background:{{ $cat->is_active?'#064e3b':'#450a0a' }};color:{{ $cat->is_active?'#10b981':'#ef4444' }};padding:0.2rem 0.6rem;border-radius:20px;font-size:0.75rem;">
                                {{ $cat->is_active?'Active':'Inactive' }}
                            </span>
                        </td>
                        <td style="padding:0.875rem 1rem;text-align:center;">
                            <form method="POST" action="{{ route('admin.ecommerce.categories.destroy', $cat) }}" onsubmit="return confirm('Delete this category?')">
                                @csrf @method('DELETE')
                                <button type="submit" style="background:#450a0a;color:#ef4444;border:none;padding:0.35rem 0.65rem;border-radius:6px;cursor:pointer;font-size:0.75rem;"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" style="padding:3rem;text-align:center;color:#9ca3af;">No categories yet. Add your first category using the form.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Add Category Form --}}
        <div style="position:sticky;top:1.5rem;">
            <div style="background:#1e293b;border:1px solid #334155;border-radius:12px;padding:1.5rem;">
                <h3 style="color:#fff;font-size:1rem;font-weight:600;margin:0 0 1.25rem;"><i class="fas fa-plus-circle" style="color:#6366f1;margin-right:0.5rem;"></i>Add Category</h3>
                <form method="POST" action="{{ route('admin.ecommerce.categories.store') }}">
                    @csrf
                    <div style="margin-bottom:1rem;">
                        <label style="display:block;font-size:0.75rem;color:#9ca3af;margin-bottom:0.4rem;">Category Name *</label>
                        <input type="text" name="name" required placeholder="e.g. Digital Products" style="width:100%;background:#0f172a;border:1px solid #334155;color:#fff;padding:0.6rem 0.75rem;border-radius:8px;font-size:0.875rem;box-sizing:border-box;">
                    </div>
                    <div style="margin-bottom:1rem;">
                        <label style="display:block;font-size:0.75rem;color:#9ca3af;margin-bottom:0.4rem;">Parent Category (for subcategory)</label>
                        <select name="parent_id" style="width:100%;background:#0f172a;border:1px solid #334155;color:#fff;padding:0.6rem 0.75rem;border-radius:8px;font-size:0.875rem;">
                            <option value="">— Top Level Category —</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div style="margin-bottom:1rem;">
                        <label style="display:block;font-size:0.75rem;color:#9ca3af;margin-bottom:0.4rem;">Icon (FontAwesome class)</label>
                        <input type="text" name="icon" placeholder="fa-tag" value="fa-tag" style="width:100%;background:#0f172a;border:1px solid #334155;color:#fff;padding:0.6rem 0.75rem;border-radius:8px;font-size:0.875rem;box-sizing:border-box;">
                    </div>
                    <div style="margin-bottom:1rem;">
                        <label style="display:block;font-size:0.75rem;color:#9ca3af;margin-bottom:0.4rem;">Description</label>
                        <textarea name="description" rows="2" style="width:100%;background:#0f172a;border:1px solid #334155;color:#fff;padding:0.6rem 0.75rem;border-radius:8px;font-size:0.875rem;resize:vertical;box-sizing:border-box;"></textarea>
                    </div>
                    <div style="margin-bottom:1.25rem;">
                        <label style="display:block;font-size:0.75rem;color:#9ca3af;margin-bottom:0.4rem;">Sort Order</label>
                        <input type="number" name="sort_order" value="0" style="width:100%;background:#0f172a;border:1px solid #334155;color:#fff;padding:0.6rem 0.75rem;border-radius:8px;font-size:0.875rem;box-sizing:border-box;">
                    </div>
                    <button type="submit" style="width:100%;background:#6366f1;color:#fff;padding:0.6rem;border-radius:8px;border:none;cursor:pointer;font-weight:600;"><i class="fas fa-plus"></i> Create Category</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
