@extends('layouts.app')

@section('title', 'Edit User')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
         PAGE HEADER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="fw-bold mb-1">
                Edit User
            </h3>

            <small class="text-muted">
                Update user account details, role and access permissions.
            </small>
        </div>

        <div class="d-flex gap-2">

            <a href="{{ route('users.show', $user) }}"
               class="btn btn-outline-secondary">

                <i class="bi bi-eye me-1"></i>
                View User

            </a>

            <a href="{{ route('users.index') }}"
               class="btn btn-light border">

                <i class="bi bi-arrow-left me-1"></i>
                Back to Users

            </a>

        </div>

    </div>


    {{-- =========================================================
         VALIDATION ERRORS
    ========================================================== --}}

    @if($errors->any())

        <div class="alert alert-danger">

            <div class="fw-bold mb-2">
                <i class="bi bi-exclamation-triangle me-1"></i>
                Please correct the following errors:
            </div>

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- =========================================================
         USER FORM
    ========================================================== --}}

    <form action="{{ route('users.update', $user) }}"
          method="POST">

        @csrf
        @method('PUT')


        <div class="row g-4">


            {{-- =================================================
                 LEFT COLUMN
            ================================================== --}}

            <div class="col-lg-8">

                <div class="card border-0 shadow-sm">

                    <div class="card-header bg-white py-3">

                        <h5 class="fw-bold mb-0">

                            <i class="bi bi-person-gear me-2 text-primary"></i>

                            Account Information

                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="row g-3">


                            {{-- Name --}}

                            <div class="col-md-6">

                                <label class="form-label fw-semibold">

                                    Full Name
                                    <span class="text-danger">*</span>

                                </label>

                                <input
                                    type="text"
                                    name="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $user->name) }}"
                                    placeholder="Enter full name"
                                    required
                                >

                                @error('name')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- Email --}}

                            <div class="col-md-6">

                                <label class="form-label fw-semibold">

                                    Email Address
                                    <span class="text-danger">*</span>

                                </label>

                                <input
                                    type="email"
                                    name="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email', $user->email) }}"
                                    placeholder="user@example.com"
                                    required
                                >

                                @error('email')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- Password --}}

                            <div class="col-md-6">

                                <label class="form-label fw-semibold">

                                    New Password

                                </label>

                                <input
                                    type="password"
                                    name="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    placeholder="Leave blank to keep current password"
                                >

                                <small class="text-muted">

                                    Leave blank if you do not want to change the password.

                                </small>

                                @error('password')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- Confirm Password --}}

                            <div class="col-md-6">

                                <label class="form-label fw-semibold">

                                    Confirm New Password

                                </label>

                                <input
                                    type="password"
                                    name="password_confirmation"
                                    class="form-control"
                                    placeholder="Repeat new password"
                                >

                            </div>


                        </div>

                    </div>

                </div>


                {{-- =================================================
                     ROLE & ACCESS
                ================================================== --}}

                <div class="card border-0 shadow-sm mt-4">

                    <div class="card-header bg-white py-3">

                        <h5 class="fw-bold mb-0">

                            <i class="bi bi-shield-check me-2 text-primary"></i>

                            Role & Access

                        </h5>

                    </div>


                    <div class="card-body">

                        <label class="form-label fw-semibold">

                            User Role
                            <span class="text-danger">*</span>

                        </label>

                        <select
                            name="role"
                            class="form-select @error('role') is-invalid @enderror"
                            required
                        >

                            <option value="">
                                Select user role
                            </option>

                            @foreach($roles as $role)

                                <option
                                    value="{{ $role->name }}"
                                    {{ old(
                                        'role',
                                        $user->roles->first()?->name
                                    ) === $role->name ? 'selected' : '' }}
                                >

                                    {{ $role->name }}

                                </option>

                            @endforeach

                        </select>

                        @error('role')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror


                        <div class="alert alert-info mt-3 mb-0">

                            <i class="bi bi-info-circle me-1"></i>

                            The user's role determines the modules and
                            actions they can access within ScholarERP.

                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================================
                 RIGHT COLUMN
            ================================================== --}}

            <div class="col-lg-4">

                {{-- User Summary --}}

                <div class="card border-0 shadow-sm">

                    <div class="card-header bg-white py-3">

                        <h5 class="fw-bold mb-0">

                            <i class="bi bi-person-circle me-2 text-primary"></i>

                            User Summary

                        </h5>

                    </div>


                    <div class="card-body text-center">

                        <div
                            class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto mb-3"
                            style="width:90px;height:90px;font-size:32px;"
                        >

                            {{ strtoupper(substr($user->name, 0, 1)) }}

                        </div>


                        <h5 class="fw-bold mb-1">

                            {{ $user->name }}

                        </h5>


                        <p class="text-muted mb-3">

                            {{ $user->email }}

                        </p>


                        @forelse($user->roles as $role)

                            <span class="badge bg-primary mb-1">

                                <i class="bi bi-shield-check me-1"></i>

                                {{ $role->name }}

                            </span>

                        @empty

                            <span class="badge bg-secondary">

                                No Role Assigned

                            </span>

                        @endforelse

                    </div>

                </div>


                {{-- Account Information --}}

                <div class="card border-0 shadow-sm mt-4">

                    <div class="card-header bg-white py-3">

                        <h6 class="fw-bold mb-0">

                            <i class="bi bi-clock-history me-2"></i>

                            Account Information

                        </h6>

                    </div>


                    <div class="card-body">

                        <div class="d-flex justify-content-between mb-3">

                            <span class="text-muted">
                                User ID
                            </span>

                            <strong>
                                #{{ $user->id }}
                            </strong>

                        </div>


                        <div class="d-flex justify-content-between mb-3">

                            <span class="text-muted">
                                Created
                            </span>

                            <strong>

                                {{ $user->created_at?->format('d M Y') }}

                            </strong>

                        </div>


                        <div class="d-flex justify-content-between">

                            <span class="text-muted">
                                Last Updated
                            </span>

                            <strong>

                                {{ $user->updated_at?->format('d M Y') }}

                            </strong>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- =================================================
             FORM ACTIONS
        ================================================== --}}

        <div class="card border-0 shadow-sm mt-4">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                    <a
                        href="{{ route('users.index') }}"
                        class="btn btn-light border"
                    >

                        <i class="bi bi-x-circle me-1"></i>

                        Cancel

                    </a>


                    <button
                        type="submit"
                        class="btn btn-primary px-4"
                    >

                        <i class="bi bi-check-circle me-1"></i>

                        Update User

                    </button>

                </div>

            </div>

        </div>

    </form>

</div>

@endsection