@extends('layouts.app')

@section('title', 'Edit Role')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="fw-bold mb-1">
                Edit Role
            </h3>

            <p class="text-muted mb-0">
                Update the role name and manage its permissions.
            </p>
        </div>

        <a href="{{ route('roles.index') }}"
           class="btn btn-outline-secondary">

            <i class="bi bi-arrow-left me-1"></i>
            Back to Roles

        </a>

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
        VALIDATION ERRORS
    ========================================================== --}}

    @if($errors->any())

        <div class="alert alert-danger">

            <strong>
                Please correct the following:
            </strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    <form method="POST"
          action="{{ route('roles.update', $role) }}">

        @csrf
        @method('PUT')


        {{-- =====================================================
            ROLE INFORMATION
        ====================================================== --}}

        <div class="card shadow-sm border-0 mb-4">

            <div class="card-header bg-white py-3">

                <h5 class="fw-bold mb-0">

                    <i class="bi bi-person-badge me-2 text-primary"></i>

                    Role Information

                </h5>

            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-8">

                        <label class="form-label fw-semibold">
                            Role Name
                        </label>

                        <input type="text"
                               name="name"
                               class="form-control"
                               value="{{ old('name', $role->name) }}"
                               placeholder="e.g. Accountant"
                               required>

                        <small class="text-muted">
                            Enter a unique name for this role.
                        </small>

                    </div>

                    <div class="col-md-4">

                        <label class="form-label fw-semibold">
                            Users Assigned
                        </label>

                        <div class="form-control bg-light">

                            <i class="bi bi-people me-1"></i>

                            {{ $role->users()->count() }} user(s)

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
            PERMISSIONS
        ====================================================== --}}

        <div class="card shadow-sm border-0 mb-4">

            <div class="card-header bg-white py-3">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <h5 class="fw-bold mb-1">

                            <i class="bi bi-shield-check me-2 text-success"></i>

                            Permissions

                        </h5>

                        <small class="text-muted">
                            Select what this role is allowed to access.
                        </small>

                    </div>

                    <div>

                        <button type="button"
                                class="btn btn-sm btn-outline-primary me-1"
                                id="selectAll">

                            <i class="bi bi-check-all"></i>
                            Select All

                        </button>

                        <button type="button"
                                class="btn btn-sm btn-outline-secondary"
                                id="clearAll">

                            <i class="bi bi-x-circle"></i>
                            Clear All

                        </button>

                    </div>

                </div>

            </div>


            <div class="card-body">

                @php

                    /*
                    |--------------------------------------------------------------------------
                    | Group permissions
                    |--------------------------------------------------------------------------
                    */

                    $permissionGroups = $permissions->groupBy(function ($permission) {

                        return explode('.', $permission->name)[0];

                    });

                    $assignedPermissions = $role->permissions
                        ->pluck('name')
                        ->toArray();

                @endphp


                <div class="row">

                    @forelse($permissionGroups as $group => $groupPermissions)

                        <div class="col-lg-4 col-md-6 mb-4">

                            <div class="border rounded p-3 h-100">

                                {{-- Group Header --}}

                                <div class="d-flex justify-content-between align-items-center mb-3">

                                    <h6 class="fw-bold text-capitalize mb-0">

                                        <i class="bi bi-folder2-open me-1 text-primary"></i>

                                        {{ str_replace('_', ' ', $group) }}

                                    </h6>

                                    <button type="button"
                                            class="btn btn-sm btn-link text-decoration-none group-select"
                                            data-group="{{ $group }}">

                                        Select

                                    </button>

                                </div>


                                {{-- Permissions --}}

                                @foreach($groupPermissions as $permission)

                                    @php

                                        $permissionName = $permission->name;

                                        $permissionAction = explode('.', $permissionName)[1] ?? '';

                                        $isChecked = in_array(
                                            $permissionName,
                                            old(
                                                'permissions',
                                                $assignedPermissions
                                            )
                                        );

                                    @endphp

                                    <div class="form-check mb-2">

                                        <input
                                            class="form-check-input permission-checkbox group-{{ $group }}"
                                            type="checkbox"
                                            name="permissions[]"
                                            value="{{ $permissionName }}"
                                            id="permission_{{ $permission->id }}"
                                            {{ $isChecked ? 'checked' : '' }}
                                        >

                                        <label
                                            class="form-check-label"
                                            for="permission_{{ $permission->id }}">

                                            {{ ucwords(str_replace('_', ' ', $permissionAction)) }}

                                            <small class="text-muted d-block">

                                                {{ $permissionName }}

                                            </small>

                                        </label>

                                    </div>

                                @endforeach

                            </div>

                        </div>

                    @empty

                        <div class="col-12">

                            <div class="alert alert-warning mb-0">

                                <i class="bi bi-exclamation-triangle me-1"></i>

                                No permissions have been created yet.

                            </div>

                        </div>

                    @endforelse

                </div>

            </div>

        </div>


        {{-- =====================================================
            SUPER ADMIN NOTICE
        ====================================================== --}}

        @if($role->name === 'Super Admin')

            <div class="alert alert-warning border-0 shadow-sm">

                <div class="d-flex">

                    <i class="bi bi-shield-lock fs-4 me-3"></i>

                    <div>

                        <strong>Super Admin Role</strong>

                        <p class="mb-0 mt-1">

                            Super Admin has full system permissions.
                            Any permissions selected here will be restored
                            to all available permissions when the role is saved.

                        </p>

                    </div>

                </div>

            </div>

        @endif


        {{-- =====================================================
            ACTIONS
        ====================================================== --}}

        <div class="card shadow-sm border-0">

            <div class="card-body">

                <div class="d-flex justify-content-end gap-2">

                    <a href="{{ route('roles.index') }}"
                       class="btn btn-outline-secondary">

                        <i class="bi bi-x-circle me-1"></i>

                        Cancel

                    </a>

                    <button type="submit"
                            class="btn btn-primary">

                        <i class="bi bi-save me-1"></i>

                        Update Role

                    </button>

                </div>

            </div>

        </div>

    </form>

</div>


{{-- =============================================================
    PERMISSION SELECTION JAVASCRIPT
============================================================= --}}

@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | Select All
    |--------------------------------------------------------------------------
    */

    const selectAllButton = document.getElementById('selectAll');

    if (selectAllButton) {

        selectAllButton.addEventListener('click', function () {

            document
                .querySelectorAll('.permission-checkbox')
                .forEach(function (checkbox) {

                    checkbox.checked = true;

                });

        });

    }


    /*
    |--------------------------------------------------------------------------
    | Clear All
    |--------------------------------------------------------------------------
    */

    const clearAllButton = document.getElementById('clearAll');

    if (clearAllButton) {

        clearAllButton.addEventListener('click', function () {

            document
                .querySelectorAll('.permission-checkbox')
                .forEach(function (checkbox) {

                    checkbox.checked = false;

                });

        });

    }


    /*
    |--------------------------------------------------------------------------
    | Select Individual Permission Group
    |--------------------------------------------------------------------------
    */

    document
        .querySelectorAll('.group-select')
        .forEach(function (button) {

            button.addEventListener('click', function () {

                const group = this.dataset.group;

                const checkboxes = document.querySelectorAll(
                    '.group-' + group
                );

                let allChecked = true;

                checkboxes.forEach(function (checkbox) {

                    if (!checkbox.checked) {

                        allChecked = false;

                    }

                });


                checkboxes.forEach(function (checkbox) {

                    checkbox.checked = !allChecked;

                });


                this.textContent = allChecked
                    ? 'Select'
                    : 'Clear';

            });

        });

});

</script>

@endpush

@endsection