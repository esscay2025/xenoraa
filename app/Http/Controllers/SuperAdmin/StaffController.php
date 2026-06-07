<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\SaRole;
use App\Models\SaPermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class StaffController extends Controller
{
    // ── List all staff ─────────────────────────────────────────────────────
    public function index()
    {
        $this->authorize_sa('staff.view');

        $staffRole = SaRole::where('name', 'staff')->first();
        $staff = User::where('sa_role_id', $staffRole?->id)
            ->with('saRole')
            ->latest()
            ->paginate(20);

        return view('superadmin.staff.index', compact('staff'));
    }

    // ── Create staff form ──────────────────────────────────────────────────
    public function create()
    {
        $this->authorize_sa('staff.create');
        $permissions = SaPermission::grouped();
        return view('superadmin.staff.create', compact('permissions'));
    }

    // ── Store new staff ────────────────────────────────────────────────────
    public function store(Request $request)
    {
        $this->authorize_sa('staff.create');

        $validated = $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'phone'    => 'nullable|string|max:20',
            'permissions' => 'nullable|array',
        ]);

        DB::beginTransaction();
        try {
            $staffRole = SaRole::where('name', 'staff')->first();

            $staff = User::create([
                'name'          => $validated['name'],
                'email'         => $validated['email'],
                'password'      => Hash::make($validated['password']),
                'phone'         => $validated['phone'] ?? null,
                'status'        => 'active',
                'sa_role_id'    => $staffRole?->id,
                'created_by_sa' => auth()->id(),
            ]);

            // Save user-level permission overrides
            $this->saveUserPermissions($staff->id, $validated['permissions'] ?? []);

            DB::commit();
            return redirect()->route('superadmin.staff.index')
                ->with('success', "Staff member {$staff->name} created successfully.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to create staff: ' . $e->getMessage());
        }
    }

    // ── Edit staff form ────────────────────────────────────────────────────
    public function edit($id)
    {
        $this->authorize_sa('staff.edit');
        $staffRole = SaRole::where('name', 'staff')->first();
        $staff = User::where('sa_role_id', $staffRole?->id)->findOrFail($id);

        $permissions = SaPermission::grouped();
        $userPermissions = DB::table('sa_user_permissions')
            ->where('user_id', $id)
            ->pluck('granted', 'sa_permission_id')
            ->toArray();

        // Also get role-level permissions
        $rolePermIds = DB::table('sa_role_permissions')
            ->where('sa_role_id', $staffRole?->id)
            ->pluck('sa_permission_id')
            ->toArray();

        return view('superadmin.staff.edit', compact('staff', 'permissions', 'userPermissions', 'rolePermIds'));
    }

    // ── Update staff ───────────────────────────────────────────────────────
    public function update(Request $request, $id)
    {
        $this->authorize_sa('staff.edit');
        $staffRole = SaRole::where('name', 'staff')->first();
        $staff = User::where('sa_role_id', $staffRole?->id)->findOrFail($id);

        $validated = $request->validate([
            'name'        => 'required|string|max:100',
            'email'       => 'required|email|unique:users,email,' . $id,
            'phone'       => 'nullable|string|max:20',
            'status'      => 'required|in:active,inactive',
            'permissions' => 'nullable|array',
        ]);

        $staff->update([
            'name'   => $validated['name'],
            'email'  => $validated['email'],
            'phone'  => $validated['phone'] ?? null,
            'status' => $validated['status'],
        ]);

        if ($request->has('permissions')) {
            $this->saveUserPermissions($staff->id, $validated['permissions'] ?? []);
        }

        return back()->with('success', 'Staff member updated successfully.');
    }

    // ── Delete staff ───────────────────────────────────────────────────────
    public function destroy($id)
    {
        $this->authorize_sa('staff.delete');
        $staffRole = SaRole::where('name', 'staff')->first();
        $staff = User::where('sa_role_id', $staffRole?->id)->findOrFail($id);
        $staff->delete();
        return redirect()->route('superadmin.staff.index')
            ->with('success', 'Staff member deleted.');
    }

    // ── Roles & Permissions manager ────────────────────────────────────────
    public function rolesIndex()
    {
        $this->authorize_sa('staff.edit');
        $roles = SaRole::with('saPermissions')->get();
        $permissions = SaPermission::grouped();
        return view('superadmin.staff.roles', compact('roles', 'permissions'));
    }

    // ── Update role permissions ────────────────────────────────────────────
    public function updateRolePermissions(Request $request, $roleId)
    {
        $this->authorize_sa('staff.edit');
        $role = SaRole::findOrFail($roleId);

        // Don't allow modifying superadmin role
        if ($role->name === 'superadmin') {
            return back()->with('error', 'Cannot modify superadmin permissions.');
        }

        $permissionIds = $request->input('permissions', []);

        DB::table('sa_role_permissions')->where('sa_role_id', $roleId)->delete();
        foreach ($permissionIds as $permId) {
            DB::table('sa_role_permissions')->insert([
                'sa_role_id'       => $roleId,
                'sa_permission_id' => (int) $permId,
            ]);
        }

        return back()->with('success', "Permissions for {$role->display_name} updated.");
    }

    // ── Helpers ────────────────────────────────────────────────────────────

    private function saveUserPermissions(int $userId, array $permissionKeys): void
    {
        DB::table('sa_user_permissions')->where('user_id', $userId)->delete();

        $allPerms = SaPermission::all()->keyBy('key');
        foreach ($permissionKeys as $key => $granted) {
            if (isset($allPerms[$key])) {
                DB::table('sa_user_permissions')->insert([
                    'user_id'           => $userId,
                    'sa_permission_id'  => $allPerms[$key]->id,
                    'granted'           => (bool) $granted,
                ]);
            }
        }
    }

    private function authorize_sa(string $permission): void
    {
        if (!auth()->user()->hasSaPermission($permission)) {
            abort(403, 'You do not have permission to perform this action.');
        }
    }
}
