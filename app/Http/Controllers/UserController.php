<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display all users.
     */
    public function index()
    {
        $this->ensurePermission('users.view');

        $users = User::with('roles')
            ->latest()
            ->paginate(15);

        return view('users.index', compact('users'));
    }

    /**
     * Show create user form.
     */
    public function create()
    {
        $this->ensurePermission('users.create');

        /*
        |--------------------------------------------------------------------------
        | Super Admin protection
        |--------------------------------------------------------------------------
        |
        | Only an existing Super Admin can create/assign another Super Admin.
        |
        */

        if (auth()->user()->hasRole('Super Admin')) {
            $roles = Role::where('guard_name', 'web')
                ->orderBy('name')
                ->get();
        } else {
            $roles = Role::where('guard_name', 'web')
                ->where('name', '!=', 'Super Admin')
                ->orderBy('name')
                ->get();
        }

        return view('users.create', compact('roles'));
    }

    /**
     * Store new user.
     */
    public function store(Request $request)
    {
        $this->ensurePermission('users.create');

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users,email',
            ],

            'password' => [
                'required',
                'string',
                'confirmed',
                Password::min(10)
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(),
            ],

            'role' => [
                'required',
                'string',
                Rule::exists('roles', 'name')
                    ->where(function ($query) {
                        $query->where('guard_name', 'web');
                    }),
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Prevent Super Admin privilege escalation
        |--------------------------------------------------------------------------
        */

        if (
            $validated['role'] === 'Super Admin'
            && !auth()->user()->hasRole('Super Admin')
        ) {
            abort(403, 'Only a Super Admin can create another Super Admin account.');
        }

        DB::transaction(function () use ($validated) {

            /*
            |--------------------------------------------------------------------------
            | User model has the "hashed" password cast.
            |--------------------------------------------------------------------------
            |
            | Therefore we pass the plain validated password here and Laravel
            | hashes it automatically through User::$casts.
            |
            */

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => $validated['password'],
            ]);

            $user->assignRole($validated['role']);
        });

        return redirect()
            ->route('users.index')
            ->with(
                'success',
                'User account created successfully.'
            );
    }

    /**
     * Display user details.
     */
    public function show(User $user)
    {
        $this->ensurePermission('users.view');

        $user->load('roles.permissions');

        return view(
            'users.show',
            compact('user')
        );
    }

    /**
     * Show edit user form.
     */
    public function edit(User $user)
    {
        $this->ensurePermission('users.edit');

        /*
        |--------------------------------------------------------------------------
        | Protect Super Admin account
        |--------------------------------------------------------------------------
        |
        | Only a Super Admin may edit a Super Admin account.
        |
        */

        if (
            $user->hasRole('Super Admin')
            && !auth()->user()->hasRole('Super Admin')
        ) {
            abort(
                403,
                'Only a Super Admin can modify a Super Admin account.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Super Admin role visibility
        |--------------------------------------------------------------------------
        */

        if (auth()->user()->hasRole('Super Admin')) {
            $roles = Role::where('guard_name', 'web')
                ->orderBy('name')
                ->get();
        } else {
            $roles = Role::where('guard_name', 'web')
                ->where('name', '!=', 'Super Admin')
                ->orderBy('name')
                ->get();
        }

        $user->load('roles');

        return view(
            'users.edit',
            compact(
                'user',
                'roles'
            )
        );
    }

    /**
     * Update user.
     */
    public function update(
        Request $request,
        User $user
    ) {
        $this->ensurePermission('users.edit');

        /*
        |--------------------------------------------------------------------------
        | Protect Super Admin account
        |--------------------------------------------------------------------------
        */

        if (
            $user->hasRole('Super Admin')
            && !auth()->user()->hasRole('Super Admin')
        ) {
            abort(
                403,
                'Only a Super Admin can modify a Super Admin account.'
            );
        }

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')
                    ->ignore($user->id),
            ],

            'password' => [
                'nullable',
                'string',
                'confirmed',
                Password::min(10)
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(),
            ],

            'role' => [
                'required',
                'string',
                Rule::exists('roles', 'name')
                    ->where(function ($query) {
                        $query->where('guard_name', 'web');
                    }),
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Prevent unauthorized Super Admin assignment
        |--------------------------------------------------------------------------
        */

        if (
            $validated['role'] === 'Super Admin'
            && !auth()->user()->hasRole('Super Admin')
        ) {
            abort(
                403,
                'Only a Super Admin can assign the Super Admin role.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Prevent non-Super Admin from modifying Super Admin status
        |--------------------------------------------------------------------------
        */

        if (
            $user->hasRole('Super Admin')
            && $validated['role'] !== 'Super Admin'
        ) {
            abort(
                403,
                'The Super Admin role cannot be removed by this account.'
            );
        }

        DB::transaction(function () use ($validated, $user) {

            $user->name = $validated['name'];
            $user->email = $validated['email'];

            /*
            |--------------------------------------------------------------------------
            | Password
            |--------------------------------------------------------------------------
            |
            | User::$casts contains:
            |
            | 'password' => 'hashed'
            |
            | Therefore Laravel hashes the new password automatically.
            |
            */

            if (!empty($validated['password'])) {
                $user->password = $validated['password'];
            }

            $user->save();

            $user->syncRoles([
                $validated['role'],
            ]);
        });

        return redirect()
            ->route('users.index')
            ->with(
                'success',
                'User account updated successfully.'
            );
    }

    /**
     * Delete user.
     */
    public function destroy(User $user)
    {
        $this->ensurePermission('users.delete');

        /*
        |--------------------------------------------------------------------------
        | Prevent deleting yourself
        |--------------------------------------------------------------------------
        */

        if ($user->id === auth()->id()) {
            return back()->with(
                'error',
                'You cannot delete your own account.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Protect Super Admin
        |--------------------------------------------------------------------------
        */

        if ($user->hasRole('Super Admin')) {
            return back()->with(
                'error',
                'A Super Admin account cannot be deleted.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Delete user safely
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use ($user) {

            /*
            |--------------------------------------------------------------------------
            | Remove role assignments first
            |--------------------------------------------------------------------------
            |
            | This prevents stale role relationships where possible.
            |
            */

            $user->syncRoles([]);

            $user->delete();
        });

        return redirect()
            ->route('users.index')
            ->with(
                'success',
                'User account deleted successfully.'
            );
    }

    /**
     * Ensure the authenticated user has the required permission.
     *
     * This provides controller-level defense in addition to route middleware.
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
