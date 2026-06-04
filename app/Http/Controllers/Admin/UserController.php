<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    /**
     * Display a listing of users — only sub-users belonging to this tenant.
     * Excludes: super admin, other tenant admins, and users from other tenants.
     */
    public function index(Request $request)
    {
        $tenantId = auth()->user()->getTenantId();

        // Only show sub-users created by this tenant admin
        // (tenant_owner_id = current admin's ID)
        $query = User::with('role')
            ->where('tenant_owner_id', $tenantId);

        if ($request->filled('role')) {
            $query->whereHas('role', fn($q) => $q->where('name', $request->role));
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(15);

        // Only show staff/visitor roles (not admin/superadmin) for sub-user creation
        $roles = Role::whereNotIn('name', ['admin', 'superadmin'])->get();

        return view('admin.users.index', compact('users', 'roles'));
    }

    /**
     * Show the form for creating a new sub-user.
     */
    public function create()
    {
        $roles = Role::whereNotIn('name', ['admin', 'superadmin'])->get();
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created sub-user — automatically linked to this tenant.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role_id'  => ['required', 'exists:roles,id'],
        ]);

        // Ensure the selected role is not admin or superadmin
        $role = Role::findOrFail($request->role_id);
        if (in_array($role->name, ['admin', 'superadmin'])) {
            return back()->withErrors(['role_id' => 'You cannot create admin-level users.']);
        }

        User::create([
            'name'            => $request->name,
            'email'           => $request->email,
            'password'        => Hash::make($request->password),
            'role_id'         => $request->role_id,
            'tenant_owner_id' => auth()->id(), // Link to current tenant
            'status'          => 'active',
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    /**
     * Show the form for editing the specified user — only if owned by this tenant.
     */
    public function edit(User $user)
    {
        abort_if($user->tenant_owner_id !== auth()->id(), 403);
        $roles = Role::whereNotIn('name', ['admin', 'superadmin'])->get();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified user.
     */
    public function update(Request $request, User $user)
    {
        abort_if($user->tenant_owner_id !== auth()->id(), 403);

        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'role_id'  => ['required', 'exists:roles,id'],
            'status'   => ['required', 'in:active,inactive'],
        ]);

        $user->update([
            'name'    => $request->name,
            'email'   => $request->email,
            'role_id' => $request->role_id,
            'status'  => $request->status,
        ]);

        if ($request->filled('password')) {
            $request->validate(['password' => ['confirmed', Rules\Password::defaults()]]);
            $user->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user.
     */
    public function destroy(User $user)
    {
        abort_if($user->tenant_owner_id !== auth()->id(), 403);

        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }
}
