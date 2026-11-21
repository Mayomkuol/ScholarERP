@extends('layouts.app')

@section('title', 'Roles & Permissions')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="fw-bold mb-1">
                Roles & Permissions
            </h3>

            <p class="text-muted mb-0">
                Manage system roles and control what each role can access.
            </p>
        </div>

        <div>

            <a href="{{ route('roles.create') }}"
               class="btn btn-primary">

                <i class="bi bi-plus-circle me-1"></i>

                Create Role

            </a>

        </div>

    </div>


    {{-- =========================================================
        ALERTS
    ========================================================== --}}

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show shadow-sm"
             role="alert">

            <i class="bi bi-check-circle me-2"></i>

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show shadow-sm"
             role="alert">

            <i class="bi bi-exclamation-triangle me-2"></i>

            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- =========================================================
        SUMMARY CARDS
    ========================================================== --}}

    <div class="row g-4 mb-4">

        {{-- Total Roles --}}
        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body d-flex align-items-center">

                    <div class="rounded-circle bg-primary bg-opacity-10
                                text-primary d-flex align-items-center
                                justify-content-center me-3"
                         style="width:52px;height:52px;">

                        <i class="bi bi-shield-lock fs-4"></i>

                    </div>

                    <div>

                        <small class="text-muted d-block">
                            Total Roles
                        </small>

                        <h3 class="fw-bold mb-0">
                            {{ $roles->count() }}
                        </h3>

                    </div>

                </div>

            </div>

        </div>


        {{-- Total Permissions --}}
        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body d-flex align-items-center">

                    <div class="rounded-circle bg-success bg-opacity-10
                                text-success d-flex align-items-center
                                justify-content-center me-3"
                         style="width:52px;height:52px;">

                        <i class="bi bi-key fs-4"></i>

                    </div>

                    <div>

                        <small class="text-muted d-block">
                            System Permissions
                        </small>

                        <h3 class="fw-bold mb-0">

                           {{ $totalPermissions }}

                        </h3>

                    </div>

                </div>

            </div>

        </div>


        {{-- Assigned Users --}}
        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body d-flex align-items-center">

                    <div class="rounded-circle bg-warning bg-opacity-10
                                text-warning d-flex align-items-center
                                justify-content-center me-3"
                         style="width:52px;height:52px;">

                        <i class="bi bi-people fs-4"></i>

                    </div>

                    <div>

                        <small class="text-muted d-block">
                            Assigned Users
                        </small>

                        <h3 class="fw-bold mb-0">

                            {{ $roles->sum('users_count') }}

                        </h3>

                    </div>

                </div>

            </div>

        </div>


        {{-- Super Admin --}}
        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body d-flex align-items-center">

                    <div class="rounded-circle bg-danger bg-opacity-10
                                text-danger d-flex align-items-center
                                justify-content-center me-3"
                         style="width:52px;height:52px;">

                        <i class="bi bi-person-badge fs-4"></i>

                    </div>

                    <div>

                        <small class="text-muted d-block">
                            Administrator
                        </small>

                        <h6 class="fw-bold mb-0">
                            Super Admin
                        </h6>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        ROLES TABLE
    ========================================================== --}}

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white border-0 py-3">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <h5 class="fw-bold mb-1">
                        System Roles
                    </h5>

                    <small class="text-muted">
                        Each role determines the functions a user can access.
                    </small>

                </div>

                <span class="badge bg-primary">
                    {{ $roles->count() }} Roles
                </span>

            </div>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th class="ps-4" style="width:70px;">
                                #
                            </th>

                            <th>
                                Role
                            </th>

                            <th>
                                Users
                            </th>

                            <th>
                                Permissions
                            </th>

                            <th>
                                Access Level
                            </th>

                            <th class="text-center pe-4">
                                Actions
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                    @forelse($roles as $role)

                        <tr>

                            {{-- Number --}}
                            <td class="ps-4">

                                <span class="text-muted">

                                    {{ $loop->iteration }}

                                </span>

                            </td>


                            {{-- Role --}}
                            <td>

                                <div class="d-flex align-items-center">

                                    <div class="rounded-circle
                                                bg-primary bg-opacity-10
                                                text-primary
                                                d-flex align-items-center
                                                justify-content-center me-3"
                                         style="width:42px;height:42px;">

                                        @if($role->name === 'Super Admin')

                                            <i class="bi bi-shield-fill-check"></i>

                                        @elseif($role->name === 'Accountant')

                                            <i class="bi bi-cash-stack"></i>

                                        @elseif($role->name === 'Teacher')

                                            <i class="bi bi-person-workspace"></i>

                                        @elseif($role->name === 'Registrar')

                                            <i class="bi bi-person-vcard"></i>

                                        @elseif($role->name === 'HR/Admin')

                                            <i class="bi bi-people"></i>

                                        @else

                                            <i class="bi bi-person-badge"></i>

                                        @endif

                                    </div>


                                    <div>

                                        <div class="fw-semibold">

                                            {{ $role->name }}

                                        </div>

                                        <small class="text-muted">

                                            {{ $role->name === 'Super Admin'
                                                ? 'Full system access'
                                                : 'System role'
                                            }}

                                        </small>

                                    </div>

                                </div>

                            </td>


                            {{-- Users --}}
                            <td>

                                <span class="badge bg-light text-dark border">

                                    <i class="bi bi-people me-1"></i>

                                    {{ $role->users_count }}

                                    {{ $role->users_count == 1 ? 'User' : 'Users' }}

                                </span>

                            </td>


                            {{-- Permissions --}}
                            <td>

                                <span class="badge bg-success bg-opacity-10
                                             text-success border border-success">

                                    <i class="bi bi-key me-1"></i>

                                    {{ $role->permissions_count }}

                                </span>

                            </td>


                            {{-- Access Level --}}
                            <td>

                                @if($role->name === 'Super Admin')

                                    <span class="badge bg-danger">

                                        <i class="bi bi-shield-fill-check me-1"></i>

                                        Full Access

                                    </span>

                                @elseif($role->permissions_count >= 15)

                                    <span class="badge bg-primary">

                                        High Access

                                    </span>

                                @elseif($role->permissions_count >= 5)

                                    <span class="badge bg-warning text-dark">

                                        Moderate Access

                                    </span>

                                @else

                                    <span class="badge bg-secondary">

                                        Limited Access

                                    </span>

                                @endif

                            </td>


                            {{-- Actions --}}
                            <td class="text-center pe-4">

                                <div class="btn-group">

                                    {{-- View --}}
                                    <a href="{{ route('roles.show', $role) }}"
                                       class="btn btn-sm btn-outline-primary"
                                       title="View Role">

                                        <i class="bi bi-eye"></i>

                                    </a>


                                    {{-- Edit --}}
                                    <a href="{{ route('roles.edit', $role) }}"
                                       class="btn btn-sm btn-outline-warning"
                                       title="Edit Role">

                                        <i class="bi bi-pencil"></i>

                                    </a>


                                    {{-- Delete --}}
                                    @if($role->name !== 'Super Admin'
                                        && $role->users_count === 0)

                                        <form action="{{ route('roles.destroy', $role) }}"
                                              method="POST"
                                              class="d-inline">

                                            @csrf

                                            @method('DELETE')

                                            <button type="submit"
                                                    class="btn btn-sm btn-outline-danger"
                                                    title="Delete Role"
                                                    onclick="return confirm(
                                                        'Are you sure you want to delete the {{ $role->name }} role?'
                                                    )">

                                                <i class="bi bi-trash"></i>

                                            </button>

                                        </form>

                                    @else

                                        <button type="button"
                                                class="btn btn-sm btn-outline-secondary"
                                                disabled
                                                title="This role cannot be deleted">

                                            <i class="bi bi-lock"></i>

                                        </button>

                                    @endif

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="6"
                                class="text-center py-5">

                                <div class="mb-3">

                                    <i class="bi bi-shield-lock fs-1 text-muted"></i>

                                </div>

                                <h5 class="fw-semibold">
                                    No Roles Found
                                </h5>

                                <p class="text-muted mb-3">
                                    There are currently no system roles.
                                </p>

                                <a href="{{ route('roles.create') }}"
                                   class="btn btn-primary">

                                    <i class="bi bi-plus-circle me-1"></i>

                                    Create First Role

                                </a>

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- Footer --}}
        @if($roles->count() > 0)

            <div class="card-footer bg-white border-0">

                <div class="d-flex justify-content-between align-items-center">

                    <small class="text-muted">

                        <i class="bi bi-info-circle me-1"></i>

                        Roles control access to ScholarERP modules.

                    </small>

                    <small class="text-muted">

                        {{ $roles->count() }}
                        {{ $roles->count() == 1 ? 'role' : 'roles' }}
                        configured

                    </small>

                </div>

            </div>

        @endif

    </div>

</div>

@endsection