@extends('layouts.app')

@section('title', 'Create User')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="fw-bold mb-1">
                Create User
            </h3>

            <small class="text-muted">
                Create a new system account and assign an appropriate role.
            </small>
        </div>

        <a href="{{ route('users.index') }}"
           class="btn btn-outline-secondary">

            <i class="bi bi-arrow-left me-1"></i>

            Back to Users

        </a>

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


    <div class="row justify-content-center">

        <div class="col-xl-9 col-lg-10">


            {{-- =================================================
                 USER ACCOUNT CARD
            ================================================== --}}

            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white py-3">

                    <h5 class="fw-bold mb-0">

                        <i class="bi bi-person-plus me-2"></i>

                        User Account

                    </h5>

                    <small class="text-muted">

                        Enter the user's login information.

                    </small>

                </div>


                <div class="card-body">

                    <form action="{{ route('users.store') }}"
                          method="POST">

                        @csrf


                        {{-- =================================================
                             PERSONAL INFORMATION
                        ================================================== --}}

                        <div class="row g-3">


                            {{-- Name --}}
                            <div class="col-md-6">

                                <label for="name"
                                       class="form-label fw-semibold">

                                    Full Name
                                    <span class="text-danger">*</span>

                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">

                                        <i class="bi bi-person"></i>

                                    </span>

                                    <input
                                        type="text"
                                        name="name"
                                        id="name"
                                        class="form-control @error('name') is-invalid @enderror"
                                        value="{{ old('name') }}"
                                        placeholder="Enter full name"
                                        required
                                        autofocus
                                    >

                                </div>

                                @error('name')

                                    <div class="text-danger small mt-1">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- Email --}}
                            <div class="col-md-6">

                                <label for="email"
                                       class="form-label fw-semibold">

                                    Email Address
                                    <span class="text-danger">*</span>

                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">

                                        <i class="bi bi-envelope"></i>

                                    </span>

                                    <input
                                        type="email"
                                        name="email"
                                        id="email"
                                        class="form-control @error('email') is-invalid @enderror"
                                        value="{{ old('email') }}"
                                        placeholder="example@school.com"
                                        required
                                    >

                                </div>

                                @error('email')

                                    <div class="text-danger small mt-1">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- Password --}}
                            <div class="col-md-6">

                                <label for="password"
                                       class="form-label fw-semibold">

                                    Password
                                    <span class="text-danger">*</span>

                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">

                                        <i class="bi bi-lock"></i>

                                    </span>

                                    <input
                                        type="password"
                                        name="password"
                                        id="password"
                                        class="form-control @error('password') is-invalid @enderror"
                                        placeholder="Minimum 8 characters"
                                        required
                                    >

                                    <button
                                        type="button"
                                        class="btn btn-outline-secondary"
                                        onclick="togglePassword('password', this)"
                                        title="Show/Hide Password">

                                        <i class="bi bi-eye"></i>

                                    </button>

                                </div>

                                <small class="text-muted">
                                    Password must contain at least 8 characters.
                                </small>

                                @error('password')

                                    <div class="text-danger small mt-1">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- Confirm Password --}}
                            <div class="col-md-6">

                                <label for="password_confirmation"
                                       class="form-label fw-semibold">

                                    Confirm Password
                                    <span class="text-danger">*</span>

                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">

                                        <i class="bi bi-shield-lock"></i>

                                    </span>

                                    <input
                                        type="password"
                                        name="password_confirmation"
                                        id="password_confirmation"
                                        class="form-control"
                                        placeholder="Confirm password"
                                        required
                                    >

                                    <button
                                        type="button"
                                        class="btn btn-outline-secondary"
                                        onclick="togglePassword('password_confirmation', this)"
                                        title="Show/Hide Password">

                                        <i class="bi bi-eye"></i>

                                    </button>

                                </div>

                            </div>


                        </div>


                        <hr class="my-4">


                        {{-- =================================================
                             ROLE ASSIGNMENT
                        ================================================== --}}

                        <div class="mb-3">

                            <h5 class="fw-bold mb-1">

                                <i class="bi bi-shield-check me-2"></i>

                                Role Assignment

                            </h5>

                            <small class="text-muted">

                                Select the role that determines what this user
                                can access in ScholarERP.

                            </small>

                        </div>


                        <div class="row">


                            <div class="col-md-8">

                                <label for="role"
                                       class="form-label fw-semibold">

                                    System Role
                                    <span class="text-danger">*</span>

                                </label>

                                <select
                                    name="role"
                                    id="role"
                                    class="form-select @error('role') is-invalid @enderror"
                                    required>

                                    <option value="">
                                        -- Select Role --
                                    </option>

                                    @foreach($roles as $role)

                                        <option
                                            value="{{ $role->name }}"
                                            {{ old('role') === $role->name ? 'selected' : '' }}>

                                            {{ $role->name }}

                                        </option>

                                    @endforeach

                                </select>

                                @error('role')

                                    <div class="text-danger small mt-1">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>

                        </div>


                        {{-- Role Information --}}
                        <div class="alert alert-light border mt-3">

                            <div class="d-flex">

                                <i class="bi bi-info-circle text-primary me-2 mt-1"></i>

                                <div>

                                    <strong>
                                        Role-based access
                                    </strong>

                                    <p class="mb-0 small text-muted">

                                        The selected role determines the
                                        permissions assigned to this user.
                                        Permissions can be managed from
                                        <strong>Roles & Permissions</strong>.

                                    </p>

                                </div>

                            </div>

                        </div>


                        {{-- =================================================
                             FORM ACTIONS
                        ================================================== --}}

                        <div class="d-flex justify-content-end gap-2 mt-4">

                            <a href="{{ route('users.index') }}"
                               class="btn btn-light border">

                                Cancel

                            </a>

                            <button type="submit"
                                    class="btn btn-primary">

                                <i class="bi bi-person-plus me-1"></i>

                                Create User

                            </button>

                        </div>


                    </form>

                </div>

            </div>


            {{-- =================================================
                 AVAILABLE ROLES
            ================================================== --}}

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white py-3">

                    <h5 class="fw-bold mb-0">

                        <i class="bi bi-shield me-2"></i>

                        Available Roles

                    </h5>

                </div>

                <div class="card-body">

                    <div class="row g-3">

                        @forelse($roles as $role)

                            <div class="col-md-6">

                                <div class="border rounded p-3 h-100">

                                    <div class="d-flex align-items-center">

                                        @if($role->name === 'Super Admin')

                                            <div class="text-danger me-3">

                                                <i class="bi bi-shield-fill-check fs-3"></i>

                                            </div>

                                        @elseif($role->name === 'Accountant')

                                            <div class="text-success me-3">

                                                <i class="bi bi-calculator fs-3"></i>

                                            </div>

                                        @elseif($role->name === 'Registrar')

                                            <div class="text-primary me-3">

                                                <i class="bi bi-person-vcard fs-3"></i>

                                            </div>

                                        @elseif($role->name === 'Teacher')

                                            <div class="text-info me-3">

                                                <i class="bi bi-mortarboard fs-3"></i>

                                            </div>

                                        @else

                                            <div class="text-secondary me-3">

                                                <i class="bi bi-person-badge fs-3"></i>

                                            </div>

                                        @endif


                                        <div>

                                            <div class="fw-semibold">

                                                {{ $role->name }}

                                            </div>

                                            <small class="text-muted">

                                                {{ $role->permissions()->count() }}
                                                permissions

                                            </small>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        @empty

                            <div class="col-12">

                                <div class="alert alert-warning mb-0">

                                    No roles have been created yet.

                                    <a href="{{ route('roles.create') }}"
                                       class="alert-link">

                                        Create a role

                                    </a>

                                    before creating a user.

                                </div>

                            </div>

                        @endforelse

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


{{-- =============================================================
     PASSWORD TOGGLE
============================================================== --}}

@push('scripts')

<script>

function togglePassword(fieldId, button)
{
    const field = document.getElementById(fieldId);

    const icon = button.querySelector('i');

    if (field.type === 'password') {

        field.type = 'text';

        icon.classList.remove('bi-eye');

        icon.classList.add('bi-eye-slash');

    } else {

        field.type = 'password';

        icon.classList.remove('bi-eye-slash');

        icon.classList.add('bi-eye');

    }
}

</script>

@endpush

@endsection