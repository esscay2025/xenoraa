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
    private function tenantId(): int
    {
        return auth()->id();
    }

    public function index(Request $request)
    {
        $tenantId = $this->tenantId();
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
        // Show all non-admin/superadmin roles: system + tenant-created
        $roles = Role::where(function($q) use ($tenantId) {
            $q->whereNull('tenant_owner_id')
              ->whereNotIn('name', ['admin', 'superadmin']);
        })->orWhere('tenant_owner_id', $tenantId)->get();
        $availableModules = Role::availableModules();
        return view('admin.users.index', compact('users', 'roles', 'availableModules'));
    }

    public function create()
    {
        $tenantId = $this->tenantId();
        $roles = Role::where(function($q) use ($tenantId) {
            $q->whereNull('tenant_owner_id')
              ->whereNotIn('name', ['admin', 'superadmin']);
        })->orWhere('tenant_owner_id', $tenantId)->get();
        $availableModules = Role::availableModules();
        return view('admin.users.create', compact('roles', 'availableModules'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role_id'  => ['required', 'exists:roles,id'],
        ]);
        $role = Role::findOrFail($request->role_id);
        if (in_array($role->name, ['admin', 'superadmin'])) {
            return back()->withErrors(['role_id' => 'You cannot create admin-level users.']);
        }
        // Module permissions: if explicitly set, save them; otherwise null (use role defaults)
        $modulePermissions = null;
        if ($request->has('override_modules') && $request->boolean('override_modules')) {
            $modulePermissions = $request->input('module_permissions', []);
        }
        User::create([
            'name'               => $request->name,
            'email'              => $request->email,
            'password'           => Hash::make($request->password),
            'role_id'            => $request->role_id,
            'tenant_owner_id'    => auth()->id(),
            'status'             => 'active',
            'module_permissions' => $modulePermissions,
        ]);
        return redirect()->route('admin.users.index')->with('success', 'Staff user created successfully.');
    }

    public function edit(User $user)
    {
        abort_if($user->tenant_owner_id !== auth()->id(), 403);
        $tenantId = $this->tenantId();
        $roles = Role::where(function($q) use ($tenantId) {
            $q->whereNull('tenant_owner_id')
              ->whereNotIn('name', ['admin', 'superadmin']);
        })->orWhere('tenant_owner_id', $tenantId)->get();
        $availableModules = Role::availableModules();
        return view('admin.users.edit', compact('user', 'roles', 'availableModules'));
    }

    public function update(Request $request, User $user)
    {
        abort_if($user->tenant_owner_id !== auth()->id(), 403);
        $request->validate([
            'name'    => ['required', 'string', 'max:255'],
            'email'   => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'role_id' => ['required', 'exists:roles,id'],
            'status'  => ['required', 'in:active,inactive'],
        ]);
        // Module permissions
        $modulePermissions = null;
        if ($request->has('override_modules') && $request->boolean('override_modules')) {
            $modulePermissions = $request->input('module_permissions', []);
        }
        $user->update([
            'name'               => $request->name,
            'email'              => $request->email,
            'role_id'            => $request->role_id,
            'status'             => $request->status,
            'module_permissions' => $modulePermissions,
        ]);
        if ($request->filled('password')) {
            $request->validate(['password' => ['confirmed', Rules\Password::defaults()]]);
            $user->update(['password' => Hash::make($request->password)]);
        }
        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

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
