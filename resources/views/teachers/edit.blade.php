@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ============================================================
        PAGE HEADER
    ============================================================ --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3 class="mb-1">

                <i class="bi bi-pencil-square"></i>

                Edit Teacher

            </h3>

            <p class="text-muted mb-0">

                Update teacher personal, employment and account information.

            </p>

        </div>


        <a href="{{ route('teachers.show', $teacher) }}"
           class="btn btn-secondary">

            <i class="bi bi-arrow-left"></i>

            Back to Profile

        </a>

    </div>


    {{-- ============================================================
        SUCCESS MESSAGE
    ============================================================ --}}

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <i class="bi bi-check-circle"></i>

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- ============================================================
        ERROR MESSAGES
    ============================================================ --}}

    @if($errors->any())

        <div class="alert alert-danger">

            <div class="fw-bold mb-2">

                <i class="bi bi-exclamation-triangle"></i>

                Please fix the following errors:

            </div>


            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- ============================================================
        FORM
    ============================================================ --}}

    <form action="{{ route('teachers.update', $teacher) }}"
          method="POST"
          enctype="multipart/form-data">

        @csrf

        @method('PUT')


        {{-- ========================================================
            SYSTEM ACCOUNT
        ======================================================== --}}

        <div class="card shadow-sm mb-4">

            <div class="card-header">

                <h5 class="mb-0">

                    <i class="bi bi-person-lock"></i>

                    System Account

                </h5>

            </div>


            <div class="card-body">

                <div class="row">


                    {{-- Account Name --}}

                    <div class="col-md-6 mb-3">

                        <label class="form-label">

                            Account Name

                        </label>


                        <input type="text"
                               class="form-control"
                               value="{{ $teacher->user->name ?? '' }}"
                               readonly>


                        <small class="text-muted">

                            Automatically synchronized with the teacher's name.

                        </small>

                    </div>


                    {{-- Account Email --}}

                    <div class="col-md-6 mb-3">

                        <label class="form-label">

                            Account Email

                            <span class="text-danger">*</span>

                        </label>


                        <input type="email"
                               name="email"
                               class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email', $teacher->user->email ?? $teacher->email) }}"
                               required>


                        @error('email')

                            <div class="invalid-feedback">

                                {{ $message }}

                            </div>

                        @enderror

                    </div>


                    {{-- New Password --}}

                    <div class="col-md-6 mb-3">

                        <label class="form-label">

                            New Password

                        </label>


                        <input type="password"
                               name="password"
                               class="form-control @error('password') is-invalid @enderror"
                               autocomplete="new-password">


                        <small class="text-muted">

                            Leave blank to keep the current password.

                        </small>


                        @error('password')

                            <div class="invalid-feedback">

                                {{ $message }}

                            </div>

                        @enderror

                    </div>


                    {{-- Confirm Password --}}

                    <div class="col-md-6 mb-3">

                        <label class="form-label">

                            Confirm New Password

                        </label>


                        <input type="password"
                               name="password_confirmation"
                               class="form-control"
                               autocomplete="new-password">

                    </div>


                    {{-- Account Status --}}

                    <div class="col-md-6 mb-3">

                        <label class="form-label">

                            Account Status

                            <span class="text-danger">*</span>

                        </label>


                        <select name="status"
                                class="form-select @error('status') is-invalid @enderror"
                                required>

                            <option value="1"
                                {{ old('status', $teacher->status) == 1 ? 'selected' : '' }}>

                                Active

                            </option>


                            <option value="0"
                                {{ old('status', $teacher->status) == 0 ? 'selected' : '' }}>

                                Inactive

                            </option>

                        </select>


                        @error('status')

                            <div class="invalid-feedback">

                                {{ $message }}

                            </div>

                        @enderror

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================
            PERSONAL INFORMATION
        ======================================================== --}}

        <div class="card shadow-sm mb-4">

            <div class="card-header">

                <h5 class="mb-0">

                    <i class="bi bi-person"></i>

                    Personal Information

                </h5>

            </div>


            <div class="card-body">

                <div class="row">


                    {{-- First Name --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">

                            First Name

                            <span class="text-danger">*</span>

                        </label>


                        <input type="text"
                               name="first_name"
                               class="form-control @error('first_name') is-invalid @enderror"
                               value="{{ old('first_name', $teacher->first_name) }}"
                               required>


                        @error('first_name')

                            <div class="invalid-feedback">

                                {{ $message }}

                            </div>

                        @enderror

                    </div>


                    {{-- Middle Name --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">

                            Middle Name

                        </label>


                        <input type="text"
                               name="middle_name"
                               class="form-control @error('middle_name') is-invalid @enderror"
                               value="{{ old('middle_name', $teacher->middle_name) }}">


                        @error('middle_name')

                            <div class="invalid-feedback">

                                {{ $message }}

                            </div>

                        @enderror

                    </div>


                    {{-- Last Name --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">

                            Last Name

                            <span class="text-danger">*</span>

                        </label>


                        <input type="text"
                               name="last_name"
                               class="form-control @error('last_name') is-invalid @enderror"
                               value="{{ old('last_name', $teacher->last_name) }}"
                               required>


                        @error('last_name')

                            <div class="invalid-feedback">

                                {{ $message }}

                            </div>

                        @enderror

                    </div>


                    {{-- Gender --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">

                            Gender

                            <span class="text-danger">*</span>

                        </label>


                        <select name="gender"
                                class="form-select @error('gender') is-invalid @enderror"
                                required>

                            <option value="">
                                Select Gender
                            </option>


                            <option value="Male"
                                {{ old('gender', $teacher->gender) == 'Male' ? 'selected' : '' }}>

                                Male

                            </option>


                            <option value="Female"
                                {{ old('gender', $teacher->gender) == 'Female' ? 'selected' : '' }}>

                                Female

                            </option>


                            <option value="Other"
                                {{ old('gender', $teacher->gender) == 'Other' ? 'selected' : '' }}>

                                Other

                            </option>

                        </select>


                        @error('gender')

                            <div class="invalid-feedback">

                                {{ $message }}

                            </div>

                        @enderror

                    </div>


                    {{-- Date of Birth --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">

                            Date of Birth

                        </label>


                        <input type="date"
                               name="date_of_birth"
                               class="form-control @error('date_of_birth') is-invalid @enderror"
                               value="{{ old(
                                   'date_of_birth',
                                   $teacher->date_of_birth
                                       ? $teacher->date_of_birth->format('Y-m-d')
                                       : ''
                               ) }}">


                        @error('date_of_birth')

                            <div class="invalid-feedback">

                                {{ $message }}

                            </div>

                        @enderror

                    </div>


                    {{-- National ID --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">

                            National ID

                        </label>


                        <input type="text"
                               name="national_id"
                               class="form-control @error('national_id') is-invalid @enderror"
                               value="{{ old('national_id', $teacher->national_id) }}">


                        @error('national_id')

                            <div class="invalid-feedback">

                                {{ $message }}

                            </div>

                        @enderror

                    </div>


                    {{-- Phone --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">

                            Phone

                        </label>


                        <input type="text"
                               name="phone"
                               class="form-control @error('phone') is-invalid @enderror"
                               value="{{ old('phone', $teacher->phone) }}">


                        @error('phone')

                            <div class="invalid-feedback">

                                {{ $message }}

                            </div>

                        @enderror

                    </div>


                    {{-- Teacher Email --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">

                            Teacher Email

                        </label>


                        <input type="email"
                               class="form-control"
                               value="{{ old('email', $teacher->user->email ?? $teacher->email) }}"
                               disabled>


                        <small class="text-muted">

                            Synchronized with the system account email above.

                        </small>

                    </div>


                    {{-- Address --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">

                            Address

                        </label>


                        <input type="text"
                               name="address"
                               class="form-control @error('address') is-invalid @enderror"
                               value="{{ old('address', $teacher->address) }}">


                        @error('address')

                            <div class="invalid-feedback">

                                {{ $message }}

                            </div>

                        @enderror

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================
            EMPLOYMENT INFORMATION
        ======================================================== --}}

        <div class="card shadow-sm mb-4">

            <div class="card-header">

                <h5 class="mb-0">

                    <i class="bi bi-briefcase"></i>

                    Employment Information

                </h5>

            </div>


            <div class="card-body">

                <div class="row">


                    {{-- Employee Number --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">

                            Employee Number

                        </label>


                        <input type="text"
                               class="form-control"
                               value="{{ $teacher->employee_no }}"
                               readonly>


                        <small class="text-muted">

                            Employee number cannot be changed.

                        </small>

                    </div>


                    {{-- Department --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">

                            Department

                        </label>


                        <select name="department_id"
                                class="form-select @error('department_id') is-invalid @enderror">

                            <option value="">

                                Select Department

                            </option>


                            @foreach($departments as $department)

                                <option value="{{ $department->id }}"
                                    {{ old('department_id', $teacher->department_id) == $department->id ? 'selected' : '' }}>

                                    {{ $department->name }}

                                </option>

                            @endforeach

                        </select>


                        @error('department_id')

                            <div class="invalid-feedback">

                                {{ $message }}

                            </div>

                        @enderror

                    </div>


                    {{-- Designation --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">

                            Designation

                            <span class="text-danger">*</span>

                        </label>


                        <input type="text"
                               name="designation"
                               class="form-control @error('designation') is-invalid @enderror"
                               value="{{ old('designation', $teacher->designation) }}"
                               required>


                        @error('designation')

                            <div class="invalid-feedback">

                                {{ $message }}

                            </div>

                        @enderror

                    </div>


                    {{-- Employment Type --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">

                            Employment Type

                            <span class="text-danger">*</span>

                        </label>


                        <select name="employment_type"
                                class="form-select @error('employment_type') is-invalid @enderror"
                                required>

                            <option value="Permanent"
                                {{ old('employment_type', $teacher->employment_type) == 'Permanent' ? 'selected' : '' }}>

                                Permanent

                            </option>


                            <option value="Contract"
                                {{ old('employment_type', $teacher->employment_type) == 'Contract' ? 'selected' : '' }}>

                                Contract

                            </option>


                            <option value="Part Time"
                                {{ old('employment_type', $teacher->employment_type) == 'Part Time' ? 'selected' : '' }}>

                                Part Time

                            </option>


                            <option value="Temporary"
                                {{ old('employment_type', $teacher->employment_type) == 'Temporary' ? 'selected' : '' }}>

                                Temporary

                            </option>

                        </select>


                        @error('employment_type')

                            <div class="invalid-feedback">

                                {{ $message }}

                            </div>

                        @enderror

                    </div>


                    {{-- Joining Date --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">

                            Joining Date

                            <span class="text-danger">*</span>

                        </label>


                        <input type="date"
                               name="joining_date"
                               class="form-control @error('joining_date') is-invalid @enderror"
                               value="{{ old(
                                   'joining_date',
                                   $teacher->joining_date
                                       ? $teacher->joining_date->format('Y-m-d')
                                       : ''
                               ) }}"
                               required>


                        @error('joining_date')

                            <div class="invalid-feedback">

                                {{ $message }}

                            </div>

                        @enderror

                    </div>


                    {{-- Qualification --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">

                            Qualification

                        </label>


                        <input type="text"
                               name="qualification"
                               class="form-control @error('qualification') is-invalid @enderror"
                               value="{{ old('qualification', $teacher->qualification) }}">


                        @error('qualification')

                            <div class="invalid-feedback">

                                {{ $message }}

                            </div>

                        @enderror

                    </div>


                    {{-- Specialization --}}

                    <div class="col-md-6 mb-3">

                        <label class="form-label">

                            Specialization

                        </label>


                        <input type="text"
                               name="specialization"
                               class="form-control @error('specialization') is-invalid @enderror"
                               value="{{ old('specialization', $teacher->specialization) }}">


                        @error('specialization')

                            <div class="invalid-feedback">

                                {{ $message }}

                            </div>

                        @enderror

                    </div>


                    {{-- Salary --}}

                    <div class="col-md-6 mb-3">

                        <label class="form-label">

                            Salary

                        </label>


                        <input type="number"
                               name="salary"
                               class="form-control @error('salary') is-invalid @enderror"
                               value="{{ old('salary', $teacher->salary) }}"
                               min="0"
                               step="0.01">


                        @error('salary')

                            <div class="invalid-feedback">

                                {{ $message }}

                            </div>

                        @enderror

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================
            TEACHER PHOTO
        ======================================================== --}}

        <div class="card shadow-sm mb-4">

            <div class="card-header">

                <h5 class="mb-0">

                    <i class="bi bi-camera"></i>

                    Teacher Photo

                </h5>

            </div>


            <div class="card-body">

                <div class="row align-items-center">


                    {{-- Current Photo --}}

                    <div class="col-md-3 text-center mb-3">

                        @if($teacher->photo)

                            <img src="{{ asset('storage/' . $teacher->photo) }}"
                                 alt="{{ $teacher->full_name }}"
                                 class="img-thumbnail rounded"
                                 style="
                                    width: 150px;
                                    height: 150px;
                                    object-fit: cover;
                                 ">


                            <div class="small text-muted mt-2">

                                Current Photo

                            </div>

                        @else

                            <div class="border rounded p-4 text-muted">

                                <i class="bi bi-person fs-1"></i>

                                <div>

                                    No photo uploaded

                                </div>

                            </div>

                        @endif

                    </div>


                    {{-- New Photo --}}

                    <div class="col-md-9">

                        <label class="form-label">

                            Replace Photo

                        </label>


                        <input type="file"
                               name="photo"
                               class="form-control @error('photo') is-invalid @enderror"
                               accept="image/*">


                        <div class="form-text">

                            Maximum file size: 2MB.

                            Leave empty to keep the current photo.

                        </div>


                        @error('photo')

                            <div class="text-danger small mt-1">

                                {{ $message }}

                            </div>

                        @enderror

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================
            ACTIONS
        ======================================================== --}}

        <div class="card shadow-sm mb-4">

            <div class="card-body">

                <div class="d-flex justify-content-between">


                    <a href="{{ route('teachers.show', $teacher) }}"
                       class="btn btn-secondary">

                        <i class="bi bi-arrow-left"></i>

                        Cancel

                    </a>


                    <button type="submit"
                            class="btn btn-success">

                        <i class="bi bi-check-circle"></i>

                        Update Teacher

                    </button>

                </div>

            </div>

        </div>

    </form>

</div>

@endsection