@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="fw-bold mb-1">
                Register New Teacher
            </h4>

            <p class="text-muted mb-0">
                Create a teacher profile and user account.
            </p>
        </div>

        <a href="{{ route('teachers.index') }}"
           class="btn btn-secondary">

            <i class="bi bi-arrow-left"></i>
            Back to Teachers

        </a>

    </div>


    {{-- =========================================================
        VALIDATION ERRORS
    ========================================================== --}}

    @if($errors->any())

        <div class="alert alert-danger">

            <div class="fw-bold mb-2">
                Please correct the following errors:
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


    {{-- =========================================================
        SUCCESS MESSAGE
    ========================================================== --}}

    @if(session('success'))

        <div class="alert alert-success">

            {{ session('success') }}

        </div>

    @endif


    {{-- =========================================================
        TEACHER FORM
    ========================================================== --}}

    <form action="{{ route('teachers.store') }}"
          method="POST"
          enctype="multipart/form-data">

        @csrf


        {{-- =====================================================
            ACCOUNT INFORMATION
        ====================================================== --}}

        <div class="card shadow-sm mb-4">

            <div class="card-header bg-primary text-white">

                <h5 class="mb-0">

                    <i class="bi bi-person-lock me-2"></i>

                    User Account

                </h5>

            </div>

            <div class="card-body">

                <div class="row">

                    {{-- ACCOUNT MODE --}}

                    <div class="col-md-4 mb-3">

                        <label for="account_mode"
                               class="form-label fw-semibold">

                            Account Option
                            <span class="text-danger">*</span>

                        </label>

                        <select name="account_mode"
                                id="account_mode"
                                class="form-select @error('account_mode') is-invalid @enderror"
                                required>

                            <option value="">
                                Select Account Option
                            </option>

                            <option value="new"
                                {{ old('account_mode', 'new') === 'new' ? 'selected' : '' }}>

                                Create New User Account

                            </option>

                            <option value="existing"
                                {{ old('account_mode') === 'existing' ? 'selected' : '' }}>

                                Link Existing Teacher User

                            </option>

                        </select>

                        @error('account_mode')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>


                {{-- =================================================
                    EXISTING USER
                ================================================== --}}

                <div id="existing-user-section"
                     class="row"
                     style="display: none;">

                    <div class="col-md-8 mb-3">

                        <label for="user_id"
                               class="form-label fw-semibold">

                            Existing Teacher User

                            <span class="text-danger">*</span>

                        </label>

                        <select name="user_id"
                                id="user_id"
                                class="form-select @error('user_id') is-invalid @enderror">

                            <option value="">
                                Select Teacher User
                            </option>

                            @foreach($teacherUsers as $teacherUser)

                                <option value="{{ $teacherUser->id }}"
                                    {{ old('user_id') == $teacherUser->id ? 'selected' : '' }}>

                                    {{ $teacherUser->name }}
                                    —
                                    {{ $teacherUser->email }}

                                </option>

                            @endforeach

                        </select>

                        <small class="text-muted">

                            Only users with the
                            <strong>Teacher</strong>
                            role who do not yet have a teacher profile
                            are shown.

                        </small>

                        @error('user_id')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>


                {{-- =================================================
                    NEW USER ACCOUNT
                ================================================== --}}

                <div id="new-user-section"
                     class="row">

                    <div class="col-md-6 mb-3">

                        <label class="form-label fw-semibold">

                            Login Email

                            <span class="text-danger">*</span>

                        </label>

                        <div class="form-text mb-2">

                            Use the same email entered under
                            Contact Information below.

                        </div>

                    </div>

                    <div class="col-md-3 mb-3">

                        <label for="password"
                               class="form-label fw-semibold">

                            Password
                            <span class="text-danger">*</span>

                        </label>

                        <input type="password"
                               name="password"
                               id="password"
                               class="form-control @error('password') is-invalid @enderror"
                               minlength="8"
                               autocomplete="new-password">

                        <small class="text-muted">
                            Minimum 8 characters.
                        </small>

                        @error('password')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                    <div class="col-md-3 mb-3">

                        <label for="password_confirmation"
                               class="form-label fw-semibold">

                            Confirm Password
                            <span class="text-danger">*</span>

                        </label>

                        <input type="password"
                               name="password_confirmation"
                               id="password_confirmation"
                               class="form-control"
                               minlength="8"
                               autocomplete="new-password">

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
            PERSONAL INFORMATION
        ====================================================== --}}

        <div class="card shadow-sm mb-4">

            <div class="card-header bg-light">

                <h5 class="mb-0 fw-semibold">

                    <i class="bi bi-person me-2"></i>

                    Personal Information

                </h5>

            </div>

            <div class="card-body">

                <div class="row">

                    {{-- FIRST NAME --}}

                    <div class="col-md-4 mb-3">

                        <label for="first_name"
                               class="form-label fw-semibold">

                            First Name
                            <span class="text-danger">*</span>

                        </label>

                        <input type="text"
                               name="first_name"
                               id="first_name"
                               class="form-control @error('first_name') is-invalid @enderror"
                               value="{{ old('first_name') }}"
                               required>

                        @error('first_name')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- MIDDLE NAME --}}

                    <div class="col-md-4 mb-3">

                        <label for="middle_name"
                               class="form-label fw-semibold">

                            Middle Name

                        </label>

                        <input type="text"
                               name="middle_name"
                               id="middle_name"
                               class="form-control @error('middle_name') is-invalid @enderror"
                               value="{{ old('middle_name') }}">

                        @error('middle_name')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- LAST NAME --}}

                    <div class="col-md-4 mb-3">

                        <label for="last_name"
                               class="form-label fw-semibold">

                            Last Name
                            <span class="text-danger">*</span>

                        </label>

                        <input type="text"
                               name="last_name"
                               id="last_name"
                               class="form-control @error('last_name') is-invalid @enderror"
                               value="{{ old('last_name') }}"
                               required>

                        @error('last_name')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>


                <div class="row">

                    {{-- GENDER --}}

                    <div class="col-md-3 mb-3">

                        <label for="gender"
                               class="form-label fw-semibold">

                            Gender
                            <span class="text-danger">*</span>

                        </label>

                        <select name="gender"
                                id="gender"
                                class="form-select @error('gender') is-invalid @enderror"
                                required>

                            <option value="">
                                Select Gender
                            </option>

                            <option value="Male"
                                {{ old('gender') === 'Male' ? 'selected' : '' }}>

                                Male

                            </option>

                            <option value="Female"
                                {{ old('gender') === 'Female' ? 'selected' : '' }}>

                                Female

                            </option>

                        </select>

                        @error('gender')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- DATE OF BIRTH --}}

                    <div class="col-md-3 mb-3">

                        <label for="date_of_birth"
                               class="form-label fw-semibold">

                            Date of Birth

                        </label>

                        <input type="date"
                               name="date_of_birth"
                               id="date_of_birth"
                               class="form-control @error('date_of_birth') is-invalid @enderror"
                               value="{{ old('date_of_birth') }}">

                        @error('date_of_birth')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- NATIONAL ID --}}

                    <div class="col-md-3 mb-3">

                        <label for="national_id"
                               class="form-label fw-semibold">

                            National ID

                        </label>

                        <input type="text"
                               name="national_id"
                               id="national_id"
                               class="form-control @error('national_id') is-invalid @enderror"
                               value="{{ old('national_id') }}">

                        @error('national_id')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- PHOTO --}}

                    <div class="col-md-3 mb-3">

                        <label for="photo"
                               class="form-label fw-semibold">

                            Passport Photo

                        </label>

                        <input type="file"
                               name="photo"
                               id="photo"
                               class="form-control @error('photo') is-invalid @enderror"
                               accept="image/*">

                        <small class="text-muted">
                            Maximum 2MB.
                        </small>

                        @error('photo')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
            CONTACT INFORMATION
        ====================================================== --}}

        <div class="card shadow-sm mb-4">

            <div class="card-header bg-light">

                <h5 class="mb-0 fw-semibold">

                    <i class="bi bi-telephone me-2"></i>

                    Contact Information

                </h5>

            </div>

            <div class="card-body">

                <div class="row">

                    {{-- PHONE --}}

                    <div class="col-md-4 mb-3">

                        <label for="phone"
                               class="form-label fw-semibold">

                            Phone

                        </label>

                        <input type="text"
                               name="phone"
                               id="phone"
                               class="form-control @error('phone') is-invalid @enderror"
                               value="{{ old('phone') }}">

                        @error('phone')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- EMAIL --}}

                    <div class="col-md-4 mb-3">

                        <label for="email"
                               class="form-label fw-semibold">

                            Email
                            <span class="text-danger">*</span>

                        </label>

                        <input type="email"
                               name="email"
                               id="email"
                               class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email') }}"
                               required>

                        <small class="text-muted">

                            For new accounts, this will also be
                            the teacher's login email.

                        </small>

                        @error('email')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- ADDRESS --}}

                    <div class="col-md-4 mb-3">

                        <label for="address"
                               class="form-label fw-semibold">

                            Address

                        </label>

                        <input type="text"
                               name="address"
                               id="address"
                               class="form-control @error('address') is-invalid @enderror"
                               value="{{ old('address') }}">

                        @error('address')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
            EMPLOYMENT INFORMATION
        ====================================================== --}}

        <div class="card shadow-sm mb-4">

            <div class="card-header bg-light">

                <h5 class="mb-0 fw-semibold">

                    <i class="bi bi-briefcase me-2"></i>

                    Employment Information

                </h5>

            </div>

            <div class="card-body">

                <div class="row">

                    {{-- DEPARTMENT --}}

                    <div class="col-md-4 mb-3">

                        <label for="department_id"
                               class="form-label fw-semibold">

                            Department

                        </label>

                        <select name="department_id"
                                id="department_id"
                                class="form-select @error('department_id') is-invalid @enderror">

                            <option value="">
                                Select Department
                            </option>

                            @foreach($departments as $department)

                                <option value="{{ $department->id }}"
                                    {{ old('department_id') == $department->id ? 'selected' : '' }}>

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


                    {{-- DESIGNATION --}}

                    <div class="col-md-4 mb-3">

                        <label for="designation"
                               class="form-label fw-semibold">

                            Designation
                            <span class="text-danger">*</span>

                        </label>

                        <select name="designation"
                                id="designation"
                                class="form-select @error('designation') is-invalid @enderror"
                                required>

                            <option value="">
                                Select Designation
                            </option>

                            <option value="Teacher"
                                {{ old('designation', 'Teacher') === 'Teacher' ? 'selected' : '' }}>

                                Teacher

                            </option>

                            <option value="Senior Teacher"
                                {{ old('designation') === 'Senior Teacher' ? 'selected' : '' }}>

                                Senior Teacher

                            </option>

                            <option value="Head of Department"
                                {{ old('designation') === 'Head of Department' ? 'selected' : '' }}>

                                Head of Department

                            </option>

                            <option value="Deputy Principal"
                                {{ old('designation') === 'Deputy Principal' ? 'selected' : '' }}>

                                Deputy Principal

                            </option>

                            <option value="Principal"
                                {{ old('designation') === 'Principal' ? 'selected' : '' }}>

                                Principal

                            </option>

                        </select>

                        @error('designation')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- EMPLOYMENT TYPE --}}

                    <div class="col-md-4 mb-3">

                        <label for="employment_type"
                               class="form-label fw-semibold">

                            Employment Type
                            <span class="text-danger">*</span>

                        </label>

                        <select name="employment_type"
                                id="employment_type"
                                class="form-select @error('employment_type') is-invalid @enderror"
                                required>

                            <option value="">
                                Select Employment Type
                            </option>

                            <option value="Permanent"
                                {{ old('employment_type', 'Permanent') === 'Permanent' ? 'selected' : '' }}>

                                Permanent

                            </option>

                            <option value="Contract"
                                {{ old('employment_type') === 'Contract' ? 'selected' : '' }}>

                                Contract

                            </option>

                            <option value="Part Time"
                                {{ old('employment_type') === 'Part Time' ? 'selected' : '' }}>

                                Part Time

                            </option>

                        </select>

                        @error('employment_type')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>


                <div class="row">

                    {{-- JOINING DATE --}}

                    <div class="col-md-3 mb-3">

                        <label for="joining_date"
                               class="form-label fw-semibold">

                            Joining Date
                            <span class="text-danger">*</span>

                        </label>

                        <input type="date"
                               name="joining_date"
                               id="joining_date"
                               class="form-control @error('joining_date') is-invalid @enderror"
                               value="{{ old('joining_date', date('Y-m-d')) }}"
                               required>

                        @error('joining_date')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- QUALIFICATION --}}

                    <div class="col-md-3 mb-3">

                        <label for="qualification"
                               class="form-label fw-semibold">

                            Qualification

                        </label>

                        <input type="text"
                               name="qualification"
                               id="qualification"
                               class="form-control @error('qualification') is-invalid @enderror"
                               value="{{ old('qualification') }}"
                               placeholder="e.g. B.Ed, BSc, Diploma">

                        @error('qualification')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- SPECIALIZATION --}}

                    <div class="col-md-3 mb-3">

                        <label for="specialization"
                               class="form-label fw-semibold">

                            Specialization

                        </label>

                        <input type="text"
                               name="specialization"
                               id="specialization"
                               class="form-control @error('specialization') is-invalid @enderror"
                               value="{{ old('specialization') }}"
                               placeholder="e.g. Mathematics">

                        @error('specialization')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- SALARY --}}

                    <div class="col-md-3 mb-3">

                        <label for="salary"
                               class="form-label fw-semibold">

                            Salary

                        </label>

                        <input type="number"
                               name="salary"
                               id="salary"
                               class="form-control @error('salary') is-invalid @enderror"
                               value="{{ old('salary') }}"
                               step="0.01"
                               min="0"
                               placeholder="0.00">

                        @error('salary')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>


                {{-- STATUS --}}

                <div class="row">

                    <div class="col-md-3 mb-3">

                        <label for="status"
                               class="form-label fw-semibold">

                            Status
                            <span class="text-danger">*</span>

                        </label>

                        <select name="status"
                                id="status"
                                class="form-select @error('status') is-invalid @enderror"
                                required>

                            <option value="1"
                                {{ old('status', '1') == '1' ? 'selected' : '' }}>

                                Active

                            </option>

                            <option value="0"
                                {{ old('status') == '0' ? 'selected' : '' }}>

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


        {{-- =====================================================
            FORM ACTIONS
        ====================================================== --}}

        <div class="card shadow-sm mb-4">

            <div class="card-body">

                <div class="d-flex justify-content-end gap-2">

                    <a href="{{ route('teachers.index') }}"
                       class="btn btn-secondary">

                        <i class="bi bi-x-circle me-1"></i>

                        Cancel

                    </a>

                    <button type="submit"
                            class="btn btn-success">

                        <i class="bi bi-check-circle me-1"></i>

                        Save Teacher

                    </button>

                </div>

            </div>

        </div>

    </form>

</div>

@endsection


{{-- =============================================================
    ACCOUNT MODE JAVASCRIPT
============================================================= --}}

@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function () {

    const accountMode =
        document.getElementById('account_mode');

    const existingSection =
        document.getElementById('existing-user-section');

    const newSection =
        document.getElementById('new-user-section');

    const userId =
        document.getElementById('user_id');

    const password =
        document.getElementById('password');

    const passwordConfirmation =
        document.getElementById('password_confirmation');


    function toggleAccountMode() {

        if (accountMode.value === 'existing') {

            /*
             * EXISTING USER
             */

            existingSection.style.display = 'flex';

            newSection.style.display = 'none';

            if (userId) {
                userId.required = true;
            }

            if (password) {
                password.required = false;
            }

            if (passwordConfirmation) {
                passwordConfirmation.required = false;
            }

        } else {

            /*
             * NEW USER
             */

            existingSection.style.display = 'none';

            newSection.style.display = 'flex';

            if (userId) {
                userId.required = false;
            }

            if (password) {
                password.required = true;
            }

            if (passwordConfirmation) {
                passwordConfirmation.required = true;
            }
        }
    }


    if (accountMode) {

        accountMode.addEventListener(
            'change',
            toggleAccountMode
        );

        toggleAccountMode();
    }

});

</script>

@endpush