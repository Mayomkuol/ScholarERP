<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Display all roles.
     */
    public function index()
    {
        $this->ensurePermission('roles.view');

        $roles = Role::withCount([
            'permissions',
            'users',
        ])
            ->where('guard_name', 'web')
            ->orderBy('name')
            ->get();

        $totalPermissions = Permission::where(
            'guard_name',
            'web'
        )->count();

        return view(
            'roles.index',
            compact(
                'roles',
                'totalPermissions'
            )
        );
    }

    /**
     * Show create role form.
     */
    public function create()
    {
        $this->ensurePermission('roles.create');

        /*
        |--------------------------------------------------------------------------
        | Only permissions from the web guard are available.
        |--------------------------------------------------------------------------
        */

        $permissions = Permission::where(
            'guard_name',
            'web'
        )
            ->orderBy('name')
            ->get();

        return view(
            'roles.create',
            compact('permissions')
        );
    }

    /**
     * Store a new role.
     */
    public function store(Request $request)
    {
        $this->ensurePermission('roles.create');

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                'unique:roles,name',
            ],

            'permissions' => [
                'nullable',
                'array',
            ],

            'permissions.*' => [
                'string',
                Rule::exists('permissions', 'name')
                    ->where(function ($query) {
                        $query->where('guard_name', 'web');
                    }),
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Super Admin role cannot be created manually.
        |--------------------------------------------------------------------------
        |
        | There must only be one protected Super Admin role.
        |
        */

        if (
            strcasecmp(
                trim($validated['name']),
                'Super Admin'
            ) === 0
        ) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'The Super Admin role is protected and cannot be created manually.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Prevent creation of a role with permission-management privileges.
        |--------------------------------------------------------------------------
        |
        | Only Super Admin should be able to create a role that can manage
        | permissions or users at the highest level.
        |
        */

        $permissions = $validated['permissions'] ?? [];

        $protectedPermissions = [
            'permissions.manage',
        ];

        if (
            !$this->isSuperAdmin()
            && !empty(
                array_intersect(
                    $permissions,
                    $protectedPermissions
                )
            )
        ) {
            abort(
                403,
                'Only a Super Admin can create a role with permission-management privileges.'
            );
        }

        DB::transaction(function () use (
            $validated,
            $permissions
        ) {
            $role = Role::create([
                'name' => trim($validated['name']),
                'guard_name' => 'web',
            ]);

            $role->syncPermissions($permissions);
        });

        return redirect()
            ->route('roles.index')
            ->with(
                'success',
                'Role created successfully.'
            );
    }

    /**
     * Show role.
     */
    public function show(Role $role)
    {
        $this->ensurePermission('roles.view');

        /*
        |--------------------------------------------------------------------------
        | Only web roles belong to ScholarERP's web authentication system.
        |--------------------------------------------------------------------------
        */

        abort_unless(
            $role->guard_name === 'web',
            404
        );

        $role->load([
            'permissions',
            'users',
        ]);

        return view(
            'roles.show',
            compact('role')
        );
    }

    /**
     * Show edit role form.
     */
    public function edit(Role $role)
    {
        $this->ensurePermission('roles.edit');

        abort_unless(
            $role->guard_name === 'web',
            404
        );

        /*
        |--------------------------------------------------------------------------
        | Super Admin role can only be edited by Super Admin.
        |--------------------------------------------------------------------------
        */

        if (
            $role->name === 'Super Admin'
            && !$this->isSuperAdmin()
        ) {
            abort(
                403,
                'Only a Super Admin can modify the Super Admin role.'
            );
        }

        $permissions = Permission::where(
            'guard_name',
            'web'
        )
            ->orderBy('name')
            ->get();

        $role->load('permissions');

        return view(
            'roles.edit',
            compact(
                'role',
                'permissions'
            )
        );
    }

    /**
     * Update role.
     */
    public function update(
        Request $request,
        Role $role
    ) {
        $this->ensurePermission('roles.edit');

        abort_unless(
            $role->guard_name === 'web',
            404
        );

        /*
        |--------------------------------------------------------------------------
        | Protect Super Admin
        |--------------------------------------------------------------------------
        */

        if (
            $role->name === 'Super Admin'
            && !$this->isSuperAdmin()
        ) {
            abort(
                403,
                'Only a Super Admin can modify the Super Admin role.'
            );
        }

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('roles', 'name')
                    ->ignore($role->id),
            ],

            'permissions' => [
                'nullable',
                'array',
            ],

            'permissions.*' => [
                'string',
                Rule::exists('permissions', 'name')
                    ->where(function ($query) {
                        $query->where('guard_name', 'web');
                    }),
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Super Admin role is immutable.
        |--------------------------------------------------------------------------
        |
        | Even a Super Admin should not accidentally rename or remove
        | permissions from the protected role through this form.
        |
        */

        if ($role->name === 'Super Admin') {
            $role->syncPermissions(
                Permission::where(
                    'guard_name',
                    'web'
                )->get()
            );

            return redirect()
                ->route('roles.index')
                ->with(
                    'success',
                    'Super Admin permissions remain fully protected.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Prevent renaming another role to Super Admin.
        |--------------------------------------------------------------------------
        */

        if (
            strcasecmp(
                trim($validated['name']),
                'Super Admin'
            ) === 0
        ) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'The Super Admin role name is protected and cannot be assigned to another role.'
                );
        }

        $permissions = $validated['permissions'] ?? [];

        /*
        |--------------------------------------------------------------------------
        | Protect permission-management privilege.
        |--------------------------------------------------------------------------
        */

        if (
            !$this->isSuperAdmin()
            && in_array(
                'permissions.manage',
                $permissions,
                true
            )
        ) {
            abort(
                403,
                'Only a Super Admin can grant permission-management privileges.'
            );
        }

        DB::transaction(function () use (
            $validated,
            $permissions,
            $role
        ) {
            $role->update([
                'name' => trim($validated['name']),
            ]);

            $role->syncPermissions($permissions);
        });

        return redirect()
            ->route('roles.index')
            ->with(
                'success',
                'Role updated successfully.'
            );
    }

    /**
     * Delete role.
     */
    public function destroy(Role $role)
    {
        $this->ensurePermission('roles.delete');

        abort_unless(
            $role->guard_name === 'web',
            404
        );

        /*
        |--------------------------------------------------------------------------
        | Protect Super Admin
        |--------------------------------------------------------------------------
        */

        if ($role->name === 'Super Admin') {
            return back()->with(
                'error',
                'The Super Admin role cannot be deleted.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Prevent deletion when users are assigned.
        |--------------------------------------------------------------------------
        */

        if ($role->users()->exists()) {
            return back()->with(
                'error',
                'This role cannot be deleted because users are assigned to it.'
            );
        }

        $role->delete();

        return redirect()
            ->route('roles.index')
            ->with(
                'success',
                'Role deleted successfully.'
            );
    }

    /**
     * Check whether the current user is Super Admin.
     */
    private function isSuperAdmin(): bool
    {
        return auth()->check()
            && auth()->user()->hasRole('Super Admin');
    }

    /**
     * Ensure the authenticated user has the required permission.
     */
    private function ensurePermission(string $permission): void
    {
        abort_unless(
            auth()->check()
            && auth()->user()->can($permission),
            403
        );
    }
}
