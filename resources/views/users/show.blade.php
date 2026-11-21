@extends('layouts.app')

@section('title', 'User Details')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3 class="fw-bold mb-1">
                User Details
            </h3>

            <small class="text-muted">
                View account information, assigned role and permissions.
            </small>

        </div>

        <div class="d-flex gap-2">

            <a href="{{ route('users.index') }}"
               class="btn btn-outline-secondary">

                <i class="bi bi-arrow-left me-1"></i>

                Back to Users

            </a>

            <a href="{{ route('users.edit', $user) }}"
               class="btn btn-primary">

                <i class="bi bi-pencil me-1"></i>

                Edit User

            </a>

        </div>

    </div>


    {{-- =========================================================
        ALERTS
    ========================================================== --}}

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <i class="bi bi-check-circle me-1"></i>

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            <i class="bi bi-exclamation-triangle me-1"></i>

            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    <div class="row g-4">


        {{-- =====================================================
             LEFT COLUMN — USER PROFILE
        ====================================================== --}}

        <div class="col-xl-4 col-lg-5">

            <div class="card border-0 shadow-sm">

                <div class="card-body text-center p-4">


                    {{-- Avatar --}}
                    <div class="rounded-circle
                                bg-primary
                                bg-opacity-10
                                text-primary
                                d-flex
                                align-items-center
                                justify-content-center
                                mx-auto
                                mb-3"
                         style="width:100px;height:100px;">

                        <span class="fw-bold"
                              style="font-size:42px;">

                            {{ strtoupper(substr($user->name, 0, 1)) }}

                        </span>

                    </div>


                    {{-- Name --}}
                    <h4 class="fw-bold mb-1">

                        {{ $user->name }}

                    </h4>


                    {{-- Email --}}
                    <p class="text-muted mb-3">

                        <i class="bi bi-envelope me-1"></i>

                        {{ $user->email }}

                    </p>


                    {{-- Role --}}
                    @forelse($user->roles as $role)

                        @if($role->name === 'Super Admin')

                            <span class="badge bg-danger fs-6">

                                <i class="bi bi-shield-fill-check me-1"></i>

                                {{ $role->name }}

                            </span>

                        @elseif($role->name === 'Accountant')

                            <span class="badge bg-success fs-6">

                                <i class="bi bi-calculator me-1"></i>

                                {{ $role->name }}

                            </span>

                        @elseif($role->name === 'Registrar')

                            <span class="badge bg-primary fs-6">

                                <i class="bi bi-person-vcard me-1"></i>

                                {{ $role->name }}

                            </span>

                        @elseif($role->name === 'Teacher')

                            <span class="badge bg-info fs-6">

                                <i class="bi bi-mortarboard me-1"></i>

                                {{ $role->name }}

                            </span>

                        @else

                            <span class="badge bg-secondary fs-6">

                                {{ $role->name }}

                            </span>

                        @endif

                    @empty

                        <span class="badge bg-secondary fs-6">

                            No Role Assigned

                        </span>

                    @endforelse


                    <hr class="my-4">


                    {{-- Account Status --}}
                    <div class="d-flex justify-content-between align-items-center mb-3">

                        <span class="text-muted">
                            Account Status
                        </span>

                        <span class="badge bg-success-subtle text-success">

                            <i class="bi bi-check-circle me-1"></i>

                            Active

                        </span>

                    </div>


                    {{-- User ID --}}
                    <div class="d-flex justify-content-between align-items-center mb-3">

                        <span class="text-muted">
                            User ID
                        </span>

                        <strong>
                            #{{ $user->id }}
                        </strong>

                    </div>


                    {{-- Created --}}
                    <div class="d-flex justify-content-between align-items-center mb-3">

                        <span class="text-muted">
                            Created
                        </span>

                        <strong>
                            {{ $user->created_at?->format('d M Y') }}
                        </strong>

                    </div>


                    {{-- Updated --}}
                    <div class="d-flex justify-content-between align-items-center">

                        <span class="text-muted">
                            Last Updated
                        </span>

                        <strong>
                            {{ $user->updated_at?->format('d M Y') }}
                        </strong>

                    </div>


                </div>

            </div>


            {{-- =================================================
                 SECURITY CARD
            ================================================== --}}

            <div class="card border-0 shadow-sm mt-4">

                <div class="card-header bg-white py-3">

                    <h6 class="fw-bold mb-0">

                        <i class="bi bi-shield-lock me-2"></i>

                        Account Security

                    </h6>

                </div>

                <div class="card-body">

                    <div class="d-flex align-items-center">

                        <div class="bg-success bg-opacity-10
                                    text-success
                                    rounded-circle
                                    p-2 me-3">

                            <i class="bi bi-lock"></i>

                        </div>

                        <div>

                            <div class="fw-semibold">
                                Password Protected
                            </div>

                            <small class="text-muted">
                                Account password is securely encrypted.
                            </small>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
             RIGHT COLUMN
        ====================================================== --}}

        <div class="col-xl-8 col-lg-7">


            {{-- =================================================
                 ROLE CARD
            ================================================== --}}

            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white py-3">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <h5 class="fw-bold mb-0">

                                <i class="bi bi-shield-check me-2"></i>

                                Assigned Role

                            </h5>

                            <small class="text-muted">

                                Role assigned to this user.

                            </small>

                        </div>

                        <span class="badge bg-primary">

                            {{ $user->roles->count() }}

                            {{ Str::plural('Role', $user->roles->count()) }}

                        </span>

                    </div>

                </div>


                <div class="card-body">

                    @forelse($user->roles as $role)

                        <div class="border rounded p-3 mb-3">

                            <div class="d-flex justify-content-between align-items-center">

                                <div class="d-flex align-items-center">

                                    <div class="bg-primary bg-opacity-10
                                                text-primary
                                                rounded-circle
                                                p-3 me-3">

                                        <i class="bi bi-shield-check fs-4"></i>

                                    </div>

                                    <div>

                                        <h5 class="fw-bold mb-1">

                                            {{ $role->name }}

                                        </h5>

                                        <small class="text-muted">

                                            {{ $role->permissions->count() }}

                                            permissions assigned

                                        </small>

                                    </div>

                                </div>

                                @if($role->name === 'Super Admin')

                                    <span class="badge bg-danger">

                                        Full Access

                                    </span>

                                @endif

                            </div>

                        </div>

                    @empty

                        <div class="alert alert-warning mb-0">

                            <i class="bi bi-exclamation-triangle me-1"></i>

                            This user currently has no role assigned.

                        </div>

                    @endforelse

                </div>

            </div>


            {{-- =================================================
                 PERMISSIONS CARD
            ================================================== --}}

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white py-3">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <h5 class="fw-bold mb-0">

                                <i class="bi bi-key me-2"></i>

                                Permissions

                            </h5>

                            <small class="text-muted">

                                Permissions inherited from the assigned role.

                            </small>

                        </div>


                        <span class="badge bg-success">

                            {{ $user->getAllPermissions()->count() }}

                            Total

                        </span>

                    </div>

                </div>


                <div class="card-body">


                    @php

                        $permissions = $user->getAllPermissions()
                            ->sortBy('name')
                            ->groupBy(function ($permission) {

                                return explode('.', $permission->name)[0];

                            });

                    @endphp


                    @forelse($permissions as $module => $modulePermissions)

                        <div class="mb-4">


                            <div class="d-flex align-items-center mb-2">

                                <span class="fw-bold text-capitalize">

                                    {{ str_replace('_', ' ', $module) }}

                                </span>

                                <span class="badge bg-light text-dark ms-2">

                                    {{ $modulePermissions->count() }}

                                </span>

                            </div>


                            <div class="row g-2">

                                @foreach($modulePermissions as $permission)

                                    <div class="col-md-6 col-xl-4">

                                        <div class="border rounded p-2">

                                            <i class="bi bi-check-circle-fill
                                                      text-success me-1"></i>

                                            <small>

                                                {{ str_replace(
                                                    '.',
                                                    ' → ',
                                                    $permission->name
                                                ) }}

                                            </small>

                                        </div>

                                    </div>

                                @endforeach

                            </div>

                        </div>

                    @empty

                        <div class="text-center py-4 text-muted">

                            <i class="bi bi-key fs-1"></i>

                            <p class="mt-2 mb-0">

                                No permissions assigned.

                            </p>

                        </div>

                    @endforelse

                </div>

            </div>


            {{-- =================================================
                 DANGER ZONE
            ================================================== --}}

            @if($user->id !== auth()->id() &&
                !$user->hasRole('Super Admin'))

                <div class="card border-danger shadow-sm mt-4">

                    <div class="card-header bg-danger bg-opacity-10">

                        <h6 class="fw-bold text-danger mb-0">

                            <i class="bi bi-exclamation-triangle me-2"></i>

                            Danger Zone

                        </h6>

                    </div>

                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-center">

                            <div>

                                <strong>
                                    Delete User Account
                                </strong>

                                <p class="text-muted small mb-0">

                                    Permanently remove this user's
                                    access to ScholarERP.

                                </p>

                            </div>


                            <form action="{{ route('users.destroy', $user) }}"
                                  method="POST">

                                @csrf

                                @method('DELETE')

                                <button type="submit"
                                        class="btn btn-outline-danger"
                                        onclick="return confirm('Are you sure you want to permanently delete this user account?')">

                                    <i class="bi bi-trash me-1"></i>

                                    Delete User

                                </button>

                            </form>

                        </div>

                    </div>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection