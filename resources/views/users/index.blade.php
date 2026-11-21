@extends('layouts.app')

@section('title', 'User Management')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="fw-bold mb-1">
                User Management
            </h3>

            <small class="text-muted">
                Manage system users, accounts and assigned roles.
            </small>
        </div>

        <div>

            <a href="{{ route('users.create') }}"
               class="btn btn-primary">

                <i class="bi bi-person-plus me-1"></i>

                Add User

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


    {{-- =========================================================
        SUMMARY CARDS
    ========================================================== --}}

    <div class="row g-3 mb-4">

        {{-- Total Users --}}
        <div class="col-md-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <small class="text-muted">
                                Total Users
                            </small>

                            <h3 class="fw-bold mb-0 mt-1">
                                {{ $users->total() }}
                            </h3>

                        </div>

                        <div class="bg-primary bg-opacity-10
                                    text-primary rounded-circle
                                    p-3">

                            <i class="bi bi-people fs-4"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Roles --}}
        <div class="col-md-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <small class="text-muted">
                                System Roles
                            </small>

                            <h3 class="fw-bold mb-0 mt-1">

                                {{ \Spatie\Permission\Models\Role::count() }}

                            </h3>

                        </div>

                        <div class="bg-success bg-opacity-10
                                    text-success rounded-circle
                                    p-3">

                            <i class="bi bi-shield-check fs-4"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Current User --}}
        <div class="col-md-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <small class="text-muted">
                                Current User
                            </small>

                            <h5 class="fw-bold mb-0 mt-1">

                                {{ auth()->user()->name }}

                            </h5>

                            <small class="text-muted">

                                {{ auth()->user()->getRoleNames()->implode(', ') }}

                            </small>

                        </div>

                        <div class="bg-warning bg-opacity-10
                                    text-warning rounded-circle
                                    p-3">

                            <i class="bi bi-person-badge fs-4"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        USER TABLE
    ========================================================== --}}

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white py-3">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <h5 class="fw-bold mb-0">

                        <i class="bi bi-people me-2"></i>

                        System Users

                    </h5>

                    <small class="text-muted">

                        Users who have access to ScholarERP.

                    </small>

                </div>

            </div>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th class="ps-4">
                                #
                            </th>

                            <th>
                                User
                            </th>

                            <th>
                                Email
                            </th>

                            <th>
                                Role
                            </th>

                            <th>
                                Status
                            </th>

                            <th>
                                Created
                            </th>

                            <th class="text-center">
                                Actions
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                    @forelse($users as $user)

                        <tr>

                            {{-- Number --}}
                            <td class="ps-4">

                                {{ $users->firstItem() + $loop->index }}

                            </td>


                            {{-- User --}}
                            <td>

                                <div class="d-flex align-items-center">

                                    <div class="rounded-circle
                                                bg-primary
                                                bg-opacity-10
                                                text-primary
                                                d-flex
                                                align-items-center
                                                justify-content-center
                                                me-3"
                                         style="width:42px;height:42px;">

                                        <strong>

                                            {{ strtoupper(substr($user->name, 0, 1)) }}

                                        </strong>

                                    </div>


                                    <div>

                                        <div class="fw-semibold">

                                            {{ $user->name }}

                                        </div>

                                        @if($user->id === auth()->id())

                                            <small class="text-primary">

                                                <i class="bi bi-person-check"></i>

                                                You

                                            </small>

                                        @endif

                                    </div>

                                </div>

                            </td>


                            {{-- Email --}}
                            <td>

                                <span class="text-muted">

                                    {{ $user->email }}

                                </span>

                            </td>


                            {{-- Role --}}
                            <td>

                                @forelse($user->roles as $role)

                                    @if($role->name === 'Super Admin')

                                        <span class="badge bg-danger">

                                            <i class="bi bi-shield-fill-check me-1"></i>

                                            {{ $role->name }}

                                        </span>

                                    @elseif($role->name === 'Accountant')

                                        <span class="badge bg-success">

                                            <i class="bi bi-calculator me-1"></i>

                                            {{ $role->name }}

                                        </span>

                                    @elseif($role->name === 'Registrar')

                                        <span class="badge bg-primary">

                                            <i class="bi bi-person-vcard me-1"></i>

                                            {{ $role->name }}

                                        </span>

                                    @elseif($role->name === 'Teacher')

                                        <span class="badge bg-info">

                                            <i class="bi bi-mortarboard me-1"></i>

                                            {{ $role->name }}

                                        </span>

                                    @else

                                        <span class="badge bg-secondary">

                                            {{ $role->name }}

                                        </span>

                                    @endif

                                @empty

                                    <span class="badge bg-light text-dark">

                                        No Role

                                    </span>

                                @endforelse

                            </td>


                            {{-- Status --}}
                            <td>

                                <span class="badge bg-success-subtle text-success">

                                    <i class="bi bi-check-circle me-1"></i>

                                    Active

                                </span>

                            </td>


                            {{-- Created --}}
                            <td>

                                <span class="text-muted">

                                    {{ $user->created_at?->format('d M Y') }}

                                </span>

                            </td>


                            {{-- Actions --}}
                            <td class="text-center">

                                {{-- View --}}
                                <a href="{{ route('users.show', $user) }}"
                                   class="btn btn-sm btn-outline-primary"
                                   title="View User">

                                    <i class="bi bi-eye"></i>

                                </a>


                                {{-- Edit --}}
                                <a href="{{ route('users.edit', $user) }}"
                                   class="btn btn-sm btn-outline-warning"
                                   title="Edit User">

                                    <i class="bi bi-pencil"></i>

                                </a>


                                {{-- Delete --}}
                                @if($user->id !== auth()->id() &&
                                    !$user->hasRole('Super Admin'))

                                    <form action="{{ route('users.destroy', $user) }}"
                                          method="POST"
                                          class="d-inline">

                                        @csrf

                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn btn-sm btn-outline-danger"
                                                title="Delete User"
                                                onclick="return confirm('Are you sure you want to delete this user account?')">

                                            <i class="bi bi-trash"></i>

                                        </button>

                                    </form>

                                @else

                                    <button type="button"
                                            class="btn btn-sm btn-outline-secondary"
                                            disabled
                                            title="This account cannot be deleted">

                                        <i class="bi bi-lock"></i>

                                    </button>

                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="7"
                                class="text-center py-5">

                                <div class="text-muted">

                                    <i class="bi bi-people fs-1"></i>

                                    <h5 class="mt-3">
                                        No Users Found
                                    </h5>

                                    <p class="mb-3">
                                        There are currently no system users.
                                    </p>

                                    <a href="{{ route('users.create') }}"
                                       class="btn btn-primary">

                                        <i class="bi bi-person-plus me-1"></i>

                                        Add First User

                                    </a>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- Pagination --}}
        @if($users->hasPages())

            <div class="card-footer bg-white">

                {{ $users->links() }}

            </div>

        @endif

    </div>

</div>

@endsection