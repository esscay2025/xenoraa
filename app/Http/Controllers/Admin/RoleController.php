<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RoleController extends Controller
{
    private function tenantId(): int
    {
        return auth()->id();
    }

    /**
     * List all roles: system roles (tenant_owner_id = null) + tenant-created roles.
     */
    public function index()
    {
        $tid = $this->tenantId();
        $systemRoles = Role::whereNull('tenant_owner_id')
            ->whereNotIn('name', ['superadmin'])
            ->get();
        $customRoles = Role::where('tenant_owner_id', $tid)->get();
        $availableModules = Role::availableModules();
        return view('admin.roles.index', compact('systemRoles', 'customRoles', 'availableModules'));
    }

    /**
     * Update modules for a system role (admin, staff, admin_staff).
     * Tenants can configure what modules their staff roles get by default.
     */
    public function updateSystemRole(Request $request, Role $role)
    {
        // Only allow updating admin_staff default modules (not admin/superadmin)
        if (in_array($role->name, ['admin', 'superadmin'])) {
            return back()->with('error', 'Cannot modify admin or superadmin role modules.');
        }
        $modules = $request->input('modules', []);
        $role->update(['modules' => $modules]);
        return back()->with('success', "Default modules for '{$role->display_name}' updated.");
    }

    /**
     * Store a new tenant-created custom role.
     */
    public function store(Request $request)
    {
        $request->validate([
            'display_name' => 'required|string|max:100',
            'description'  => 'nullable|string|max:255',
            'modules'      => 'nullable|array',
        ]);
        $tid  = $this->tenantId();
        $name = 'custom_' . $tid . '_' . Str::slug($request->display_name);
        // Ensure unique name
        $base = $name;
        $i    = 1;
        while (Role::where('name', $name)->exists()) {
            $name = $base . '_' . $i++;
        }
        Role::create([
            'name'            => $name,
            'display_name'    => $request->display_name,
            'description'     => $request->description,
            'modules'         => $request->input('modules', []),
            'tenant_owner_id' => $tid,
        ]);
        return back()->with('success', "Role '{$request->display_name}' created.");
    }

    /**
     * Update a tenant-created custom role.
     */
    public function update(Request $request, Role $role)
    {
        abort_if($role->tenant_owner_id !== $this->tenantId(), 403);
        $request->validate([
            'display_name' => 'required|string|max:100',
            'description'  => 'nullable|string|max:255',
            'modules'      => 'nullable|array',
        ]);
        $role->update([
            'display_name' => $request->display_name,
            'description'  => $request->description,
            'modules'      => $request->input('modules', []),
        ]);
        return back()->with('success', "Role '{$role->display_name}' updated.");
    }

    /**
     * Delete a tenant-created custom role.
     */
    public function destroy(Role $role)
    {
        abort_if($role->tenant_owner_id !== $this->tenantId(), 403);
        if ($role->users()->count() > 0) {
            return back()->with('error', 'Cannot delete a role that has users assigned to it.');
        }
        $role->delete();
        return back()->with('success', 'Role deleted.');
    }
}
