<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * Roles allowed:
     *  - superadmin: full platform access
     *  - admin: tenant owner — full access to their own admin panel
     *  - admin_staff / staff: tenant sub-user — access to admin panel but limited to assigned modules
     *  - visitor: legacy role, redirected to user dashboard
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!$request->user()) {
            return redirect()->route('login');
        }
        $user = $request->user();
        $userRole = $user->role?->name;
        // admin_staff and staff are treated as "admin" for route access purposes.
        // Module-level restrictions are handled in the sidebar and controller layer.
        $effectiveRoles = $roles;
        if (in_array('admin', $roles)) {
            $effectiveRoles = array_merge($roles, ['admin_staff', 'staff']);
        }
        if (!in_array($userRole, $effectiveRoles)) {
            abort(403, 'Access denied. You do not have permission to access this resource.');
        }
        return $next($request);
    }
}
