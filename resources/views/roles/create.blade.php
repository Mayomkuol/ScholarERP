@extends('layouts.app')

@section('title', 'Create Role')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3 class="fw-bold mb-1">
                Create Role
            </h3>

            <p class="text-muted mb-0">
                Create a system role and assign the permissions it should have.
            </p>

        </div>

        <a href="{{ route('roles.index') }}"
           class="btn btn-outline-secondary">

            <i class="bi bi-arrow-left me-1"></i>

            Back to Roles

        </a>

    </div>


    {{-- =========================================================
        VALIDATION ERRORS
    ========================================================== --}}

    @if($errors->any())

        <div class="alert alert-danger shadow-sm">

            <div class="fw-bold mb-2">

                <i class="bi bi-exclamation-triangle me-2"></i>

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
        CREATE ROLE FORM
    ========================================================== --}}

    <form action="{{ route('roles.store') }}"
          method="POST">

        @csrf


        <div class="row g-4">


            {{-- =================================================
                ROLE INFORMATION
            ================================================== --}}

            <div class="col-lg-4">

                <div class="card border-0 shadow-sm">

                    <div class="card-header bg-white py-3">

                        <h5 class="fw-bold mb-1">

                            <i class="bi bi-shield-lock text-primary me-2"></i>

                            Role Information

                        </h5>

                        <small class="text-muted">

                            Define the name of the new system role.

                        </small>

                    </div>


                    <div class="card-body">

                        <div class="mb-4">

                            <label for="name"
                                   class="form-label fw-semibold">

                                Role Name
                                <span class="text-danger">*</span>

                            </label>

                            <input type="text"
                                   name="name"
                                   id="name"
                                   value="{{ old('name') }}"
                                   class="form-control form-control-lg @error('name') is-invalid @enderror"
                                   placeholder="e.g. Librarian"
                                   required>

                            @error('name')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                            <div class="form-text">

                                Use a clear name that describes the user's
                                responsibility.

                            </div>

                        </div>


                        {{-- Examples --}}
                        <div class="mb-3">

                            <label class="form-label fw-semibold">

                                Examples

                            </label>

                            <div class="d-flex flex-wrap gap-2">

                                <span class="badge bg-light text-dark border">
                                    Librarian
                                </span>

                                <span class="badge bg-light text-dark border">
                                    Bursar
                                </span>

                                <span class="badge bg-light text-dark border">
                                    Principal
                                </span>

                                <span class="badge bg-light text-dark border">
                                    Deputy Principal
                                </span>

                                <span class="badge bg-light text-dark border">
                                    Receptionist
                                </span>

                            </div>

                        </div>


                        <hr>


                        {{-- Information --}}
                        <div class="alert alert-info mb-0">

                            <div class="d-flex">

                                <i class="bi bi-info-circle fs-5 me-2"></i>

                                <div>

                                    <strong>Important</strong>

                                    <p class="mb-0 mt-1 small">

                                        The permissions selected on this page
                                        determine which parts of ScholarERP
                                        the role can access.

                                    </p>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================================
                PERMISSIONS
            ================================================== --}}

            <div class="col-lg-8">

                <div class="card border-0 shadow-sm">

                    <div class="card-header bg-white py-3">

                        <div class="d-flex justify-content-between
                                    align-items-center">

                            <div>

                                <h5 class="fw-bold mb-1">

                                    <i class="bi bi-key text-primary me-2"></i>

                                    Permissions

                                </h5>

                                <small class="text-muted">

                                    Select the functions this role can access.

                                </small>

                            </div>


                            <div class="d-flex gap-2">

                                <button type="button"
                                        class="btn btn-sm btn-outline-primary"
                                        id="selectAllPermissions">

                                    <i class="bi bi-check2-square me-1"></i>

                                    Select All

                                </button>

                                <button type="button"
                                        class="btn btn-sm btn-outline-secondary"
                                        id="clearAllPermissions">

                                    <i class="bi bi-x-square me-1"></i>

                                    Clear All

                                </button>

                            </div>

                        </div>

                    </div>


                    <div class="card-body">


                        {{-- Permission counter --}}
                        <div class="alert alert-light border
                                    d-flex justify-content-between
                                    align-items-center mb-4">

                            <span>

                                <i class="bi bi-check-circle text-success me-1"></i>

                                Selected Permissions

                            </span>

                            <strong>

                                <span id="selectedCount">0</span>
                                /
                                {{ $permissions->count() }}

                            </strong>

                        </div>


                        {{-- Search --}}
                        <div class="mb-4">

                            <div class="input-group">

                                <span class="input-group-text bg-white">

                                    <i class="bi bi-search"></i>

                                </span>

                                <input type="text"
                                       id="permissionSearch"
                                       class="form-control"
                                       placeholder="Search permissions...">

                            </div>

                        </div>


                        {{-- =================================================
                            PERMISSION GROUPS
                        ================================================== --}}

                        @php

                            $permissionGroups = $permissions->groupBy(function ($permission) {

                                $parts = explode('.', $permission->name);

                                return ucwords(
                                    str_replace(
                                        ['-', '_'],
                                        ' ',
                                        $parts[0]
                                    )
                                );

                            });

                        @endphp


                        <div id="permissionContainer">

                            @forelse($permissionGroups as $group => $groupPermissions)

                                <div class="permission-group mb-4"
                                     data-group="{{ strtolower($group) }}">

                                    {{-- Group Header --}}
                                    <div class="d-flex justify-content-between
                                                align-items-center
                                                bg-light
                                                border rounded-top px-3 py-2">

                                        <div>

                                            <span class="fw-bold">

                                                @if(strtolower($group) === 'dashboard')

                                                    <i class="bi bi-speedometer2 text-primary me-2"></i>

                                                @elseif(strtolower($group) === 'students')

                                                    <i class="bi bi-people text-primary me-2"></i>

                                                @elseif(strtolower($group) === 'teachers')

                                                    <i class="bi bi-person-workspace text-primary me-2"></i>

                                                @elseif(strtolower($group) === 'finance')

                                                    <i class="bi bi-cash-stack text-primary me-2"></i>

                                                @elseif(strtolower($group) === 'reports')

                                                    <i class="bi bi-bar-chart text-primary me-2"></i>

                                                @elseif(strtolower($group) === 'users')

                                                    <i class="bi bi-person-gear text-primary me-2"></i>

                                                @elseif(strtolower($group) === 'roles')

                                                    <i class="bi bi-shield-lock text-primary me-2"></i>

                                                @elseif(strtolower($group) === 'schools')

                                                    <i class="bi bi-building text-primary me-2"></i>

                                                @else

                                                    <i class="bi bi-folder text-primary me-2"></i>

                                                @endif

                                                {{ $group }}

                                            </span>

                                            <small class="text-muted ms-2">

                                                {{ $groupPermissions->count() }}
                                                permissions

                                            </small>

                                        </div>


                                        <button type="button"
                                                class="btn btn-sm btn-outline-primary group-select"
                                                data-group="{{ strtolower($group) }}">

                                            Select Group

                                        </button>

                                    </div>


                                    {{-- Permission Items --}}
                                    <div class="border border-top-0
                                                rounded-bottom p-3">

                                        <div class="row g-2">

                                            @foreach($groupPermissions as $permission)

                                                <div class="col-md-6
                                                            permission-item"
                                                     data-permission="{{ strtolower($permission->name) }}">

                                                    <div class="form-check
                                                                border rounded
                                                                p-2 px-3
                                                                permission-card">

                                                        <input
                                                            class="form-check-input permission-checkbox"
                                                            type="checkbox"
                                                            name="permissions[]"
                                                            value="{{ $permission->name }}"
                                                            id="permission_{{ $permission->id }}"
                                                            data-group="{{ strtolower($group) }}"
                                                            {{ in_array(
                                                                $permission->name,
                                                                old('permissions', [])
                                                            ) ? 'checked' : '' }}
                                                        >

                                                        <label
                                                            class="form-check-label w-100 ms-1"
                                                            for="permission_{{ $permission->id }}">

                                                            <span class="fw-semibold">

                                                                {{ ucwords(
                                                                    str_replace(
                                                                        ['.', '_', '-'],
                                                                        ' ',
                                                                        $permission->name
                                                                    )
                                                                ) }}

                                                            </span>

                                                            <small class="text-muted d-block">

                                                                {{ $permission->name }}

                                                            </small>

                                                        </label>

                                                    </div>

                                                </div>

                                            @endforeach

                                        </div>

                                    </div>

                                </div>

                            @empty

                                <div class="text-center py-5">

                                    <i class="bi bi-key fs-1 text-muted"></i>

                                    <h5 class="mt-3">
                                        No Permissions Found
                                    </h5>

                                    <p class="text-muted">
                                        There are currently no permissions
                                        configured in the system.
                                    </p>

                                </div>

                            @endforelse

                        </div>

                    </div>


                    {{-- =================================================
                        FORM FOOTER
                    ================================================== --}}

                    <div class="card-footer bg-white border-0 py-3">

                        <div class="d-flex justify-content-between
                                    align-items-center">

                            <a href="{{ route('roles.index') }}"
                               class="btn btn-outline-secondary">

                                <i class="bi bi-x-circle me-1"></i>

                                Cancel

                            </a>


                            <button type="submit"
                                    class="btn btn-primary px-4">

                                <i class="bi bi-check-circle me-1"></i>

                                Create Role

                            </button>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </form>

</div>


{{-- =========================================================
    PAGE JAVASCRIPT
========================================================= --}}

@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function () {

    const checkboxes = document.querySelectorAll('.permission-checkbox');

    const selectedCount = document.getElementById('selectedCount');

    const selectAll = document.getElementById('selectAllPermissions');

    const clearAll = document.getElementById('clearAllPermissions');

    const search = document.getElementById('permissionSearch');

    /*
    |--------------------------------------------------------------------------
    | Update Selected Counter
    |--------------------------------------------------------------------------
    */

    function updateCounter() {

        const selected =
            document.querySelectorAll(
                '.permission-checkbox:checked'
            ).length;

        selectedCount.textContent = selected;

    }


    /*
    |--------------------------------------------------------------------------
    | Select All
    |--------------------------------------------------------------------------
    */

    selectAll.addEventListener('click', function () {

        checkboxes.forEach(function (checkbox) {

            const item =
                checkbox.closest('.permission-item');

            if (
                !item ||
                item.style.display !== 'none'
            ) {

                checkbox.checked = true;

            }

        });

        updateCounter();

    });


    /*
    |--------------------------------------------------------------------------
    | Clear All
    |--------------------------------------------------------------------------
    */

    clearAll.addEventListener('click', function () {

        checkboxes.forEach(function (checkbox) {

            checkbox.checked = false;

        });

        updateCounter();

    });


    /*
    |--------------------------------------------------------------------------
    | Individual Checkbox
    |--------------------------------------------------------------------------
    */

    checkboxes.forEach(function (checkbox) {

        checkbox.addEventListener(
            'change',
            updateCounter
        );

    });


    /*
    |--------------------------------------------------------------------------
    | Select Group
    |--------------------------------------------------------------------------
    */

    document
        .querySelectorAll('.group-select')
        .forEach(function (button) {

            button.addEventListener('click', function () {

                const group =
                    this.dataset.group;

                const groupCheckboxes =
                    document.querySelectorAll(
                        '.permission-checkbox[data-group="' +
                        group +
                        '"]'
                    );

                const allSelected =
                    Array.from(groupCheckboxes)
                        .every(function (checkbox) {
                            return checkbox.checked;
                        });

                groupCheckboxes.forEach(function (checkbox) {

                    checkbox.checked = !allSelected;

                });

                updateCounter();

                this.textContent =
                    allSelected
                        ? 'Select Group'
                        : 'Clear Group';

            });

        });


    /*
    |--------------------------------------------------------------------------
    | Permission Search
    |--------------------------------------------------------------------------
    */

    search.addEventListener('input', function () {

        const searchTerm =
            this.value.toLowerCase().trim();

        document
            .querySelectorAll('.permission-group')
            .forEach(function (group) {

                let visibleItems = 0;

                group
                    .querySelectorAll('.permission-item')
                    .forEach(function (item) {

                        const permission =
                            item.dataset.permission;

                        if (
                            permission.includes(searchTerm)
                        ) {

                            item.style.display = '';

                            visibleItems++;

                        } else {

                            item.style.display = 'none';

                        }

                    });

                group.style.display =
                    visibleItems > 0
                        ? ''
                        : 'none';

            });

    });


    /*
    |--------------------------------------------------------------------------
    | Initial Counter
    |--------------------------------------------------------------------------
    */

    updateCounter();

});

</script>

@endpush

@endsection