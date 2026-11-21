@extends('layouts.app')

@section('title', 'Role Details')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
         PAGE HEADER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3 class="fw-bold mb-1">
                Role Details
            </h3>

            <small class="text-muted">
                View role information, assigned permissions and users.
            </small>

        </div>

        <div class="d-flex gap-2">

            <a href="{{ route('roles.edit', $role) }}"
               class="btn btn-warning">

                <i class="bi bi-pencil me-1"></i>
                Edit Role

            </a>

            <a href="{{ route('roles.index') }}"
               class="btn btn-light border">

                <i class="bi bi-arrow-left me-1"></i>
                Back to Roles

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
             ROLE INFORMATION
        ====================================================== --}}

        <div class="col-lg-4">

            <div class="card border-0 shadow-sm">

                <div class="card-body text-center py-4">

                    <div
                        class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto mb-3"
                        style="width:90px;height:90px;font-size:36px;"
                    >

                        <i class="bi bi-shield-lock"></i>

                    </div>


                    <h4 class="fw-bold mb-2">

                        {{ $role->name }}

                    </h4>


                    @if($role->name === 'Super Admin')

                        <span class="badge bg-danger px-3 py-2">

                            <i class="bi bi-star-fill me-1"></i>
                            System Administrator

                        </span>

                    @else

                        <span class="badge bg-primary px-3 py-2">

                            System Role

                        </span>

                    @endif


                    <hr class="my-4">


                    <div class="row">

                        <div class="col-6">

                            <h4 class="fw-bold text-primary">

                                {{ $role->permissions->count() }}

                            </h4>

                            <small class="text-muted">
                                Permissions
                            </small>

                        </div>


                        <div class="col-6">

                            <h4 class="fw-bold text-success">

                                {{ $role->users->count() }}

                            </h4>

                            <small class="text-muted">
                                Users
                            </small>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Role metadata --}}

            <div class="card border-0 shadow-sm mt-4">

                <div class="card-header bg-white">

                    <h6 class="fw-bold mb-0">

                        <i class="bi bi-info-circle me-2"></i>
                        Role Information

                    </h6>

                </div>


                <div class="card-body">

                    <div class="d-flex justify-content-between mb-3">

                        <span class="text-muted">
                            Role ID
                        </span>

                        <strong>
                            #{{ $role->id }}
                        </strong>

                    </div>


                    <div class="d-flex justify-content-between mb-3">

                        <span class="text-muted">
                            Guard
                        </span>

                        <strong>
                            {{ $role->guard_name }}
                        </strong>

                    </div>


                    <div class="d-flex justify-content-between mb-3">

                        <span class="text-muted">
                            Created
                        </span>

                        <strong>

                            {{ $role->created_at?->format('d M Y') }}

                        </strong>

                    </div>


                    <div class="d-flex justify-content-between">

                        <span class="text-muted">
                            Updated
                        </span>

                        <strong>

                            {{ $role->updated_at?->format('d M Y') }}

                        </strong>

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
             PERMISSIONS
        ====================================================== --}}

        <div class="col-lg-8">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white py-3">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <h5 class="fw-bold mb-1">

                                <i class="bi bi-key me-2 text-primary"></i>

                                Assigned Permissions

                            </h5>

                            <small class="text-muted">

                                Permissions currently assigned to this role.

                            </small>

                        </div>


                        <span class="badge bg-primary">

                            {{ $role->permissions->count() }}

                        </span>

                    </div>

                </div>


                <div class="card-body">

                    @if($role->permissions->count())

                        <div class="row g-2">

                            @foreach($role->permissions->sortBy('name') as $permission)

                                <div class="col-md-6">

                                    <div class="border rounded p-3 h-100 bg-light">

                                        <div class="d-flex align-items-center">

                                            <i class="bi bi-check-circle-fill text-success me-2"></i>

                                            <span class="fw-semibold">

                                                {{ $permission->name }}

                                            </span>

                                        </div>

                                    </div>

                                </div>

                            @endforeach

                        </div>

                    @else

                        <div class="text-center py-5">

                            <i class="bi bi-shield-x fs-1 text-muted"></i>

                            <h5 class="mt-3">

                                No Permissions Assigned

                            </h5>

                            <p class="text-muted mb-0">

                                This role currently has no permissions.

                            </p>

                        </div>

                    @endif

                </div>

            </div>


            {{-- =================================================
                 USERS ASSIGNED TO ROLE
            ================================================== --}}

            <div class="card border-0 shadow-sm mt-4">

                <div class="card-header bg-white py-3">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <h5 class="fw-bold mb-1">

                                <i class="bi bi-people me-2 text-primary"></i>

                                Users With This Role

                            </h5>

                            <small class="text-muted">

                                System users currently assigned to
                                <strong>{{ $role->name }}</strong>.

                            </small>

                        </div>


                        <span class="badge bg-success">

                            {{ $role->users->count() }}

                        </span>

                    </div>

                </div>


                <div class="card-body p-0">

                    @if($role->users->count())

                        <div class="table-responsive">

                            <table class="table table-hover align-middle mb-0">

                                <thead class="table-light">

                                    <tr>

                                        <th class="ps-4">
                                            User
                                        </th>

                                        <th>
                                            Email
                                        </th>

                                        <th>
                                            Assigned
                                        </th>

                                    </tr>

                                </thead>


                                <tbody>

                                    @foreach($role->users as $user)

                                        <tr>

                                            <td class="ps-4">

                                                <div class="d-flex align-items-center">

                                                    <div
                                                        class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2"
                                                        style="width:38px;height:38px;"
                                                    >

                                                        {{ strtoupper(substr($user->name, 0, 1)) }}

                                                    </div>


                                                    <div>

                                                        <div class="fw-semibold">

                                                            {{ $user->name }}

                                                        </div>

                                                        <small class="text-muted">

                                                            User #{{ $user->id }}

                                                        </small>

                                                    </div>

                                                </div>

                                            </td>


                                            <td>

                                                {{ $user->email }}

                                            </td>


                                            <td>

                                                <small class="text-muted">

                                                    {{ $user->created_at?->format('d M Y') }}

                                                </small>

                                            </td>

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    @else

                        <div class="text-center py-5">

                            <i class="bi bi-person-x fs-1 text-muted"></i>

                            <h5 class="mt-3">

                                No Users Assigned

                            </h5>

                            <p class="text-muted mb-0">

                                No system users currently have this role.

                            </p>

                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         BOTTOM ACTIONS
    ========================================================== --}}

    <div class="card border-0 shadow-sm mt-4">

        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center">

                <a href="{{ route('roles.index') }}"
                   class="btn btn-light border">

                    <i class="bi bi-arrow-left me-1"></i>

                    Back to Roles

                </a>


                <div class="d-flex gap-2">

                    <a href="{{ route('roles.edit', $role) }}"
                       class="btn btn-warning">

                        <i class="bi bi-pencil me-1"></i>

                        Edit Role

                    </a>


                    @if($role->name !== 'Super Admin' && $role->users->count() === 0)

                        <form
                            action="{{ route('roles.destroy', $role) }}"
                            method="POST"
                            class="d-inline"
                            onsubmit="return confirm('Are you sure you want to delete this role?')"
                        >

                            @csrf
                            @method('DELETE')

                            <button
                                type="submit"
                                class="btn btn-danger"
                            >

                                <i class="bi bi-trash me-1"></i>

                                Delete Role

                            </button>

                        </form>

                    @endif

                </div>

            </div>

        </div>

    </div>

</div>

@endsection