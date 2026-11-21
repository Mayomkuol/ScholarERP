@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="fw-bold mb-1">
                Assign Class Teacher
            </h4>

            <p class="text-muted mb-0">
                Assign a teacher to a class or specific stream.
            </p>
        </div>

        <div>

            <a href="{{ route('class-teacher-assignments.index') }}"
               class="btn btn-secondary">

                <i class="bi bi-arrow-left"></i>

                Back to Assignments

            </a>

        </div>

    </div>


    {{-- =========================================================
        VALIDATION ERRORS
    ========================================================== --}}

    @if($errors->any())

        <div class="alert alert-danger">

            <div class="fw-semibold mb-2">

                <i class="bi bi-exclamation-triangle"></i>

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
        ASSIGNMENT FORM
    ========================================================== --}}

    <form action="{{ route('class-teacher-assignments.store') }}"
          method="POST">

        @csrf


        <div class="card shadow-sm">

            <div class="card-header bg-primary text-white">

                <h5 class="mb-0">

                    <i class="bi bi-person-workspace"></i>

                    Class Teacher Assignment

                </h5>

            </div>


            <div class="card-body">


                {{-- =================================================
                    TEACHER INFORMATION
                ================================================== --}}

                <div class="card mb-4">

                    <div class="card-header bg-light">

                        <strong>
                            Teacher Information
                        </strong>

                    </div>


                    <div class="card-body">

                        <div class="row">

                            {{-- Teacher --}}

                            <div class="col-md-6 mb-3">

                                <label for="teacher_id"
                                       class="form-label">

                                    Teacher
                                    <span class="text-danger">*</span>

                                </label>

                                <select name="teacher_id"
                                        id="teacher_id"
                                        class="form-select @error('teacher_id') is-invalid @enderror"
                                        required>

                                    <option value="">
                                        Select Teacher
                                    </option>

                                    @foreach($teachers as $teacher)

                                        <option value="{{ $teacher->id }}"
                                            {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>

                                            {{ $teacher->employee_no }}
                                            —
                                            {{ $teacher->first_name }}

                                            @if($teacher->middle_name)
                                                {{ $teacher->middle_name }}
                                            @endif

                                            {{ $teacher->last_name }}

                                        </option>

                                    @endforeach

                                </select>

                                @error('teacher_id')

                                    <div class="invalid-feedback">

                                        {{ $message }}

                                    </div>

                                @enderror

                            </div>


                            {{-- Teacher Designation --}}

                            <div class="col-md-6 mb-3">

                                <label class="form-label">

                                    Assignment Type

                                </label>

                                <input type="text"
                                       class="form-control"
                                       value="Class Teacher"
                                       readonly>

                                <small class="text-muted">

                                    The selected teacher will become the class teacher
                                    for the selected class/stream.

                                </small>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                    ACADEMIC PERIOD
                ================================================== --}}

                <div class="card mb-4">

                    <div class="card-header bg-light">

                        <strong>
                            Academic Period
                        </strong>

                    </div>


                    <div class="card-body">

                        <div class="row">

                            {{-- Academic Year --}}

                            <div class="col-md-6 mb-3">

                                <label for="academic_year_id"
                                       class="form-label">

                                    Academic Year
                                    <span class="text-danger">*</span>

                                </label>

                                <select name="academic_year_id"
                                        id="academic_year_id"
                                        class="form-select @error('academic_year_id') is-invalid @enderror"
                                        required>

                                    <option value="">
                                        Select Academic Year
                                    </option>

                                    @foreach($academicYears as $academicYear)

                                        <option value="{{ $academicYear->id }}"
                                            {{ old('academic_year_id', $currentAcademicYearId ?? '') == $academicYear->id ? 'selected' : '' }}>

                                            {{ $academicYear->name }}

                                            @if($academicYear->is_current)

                                                — Current

                                            @endif

                                        </option>

                                    @endforeach

                                </select>

                                @error('academic_year_id')

                                    <div class="invalid-feedback">

                                        {{ $message }}

                                    </div>

                                @enderror

                            </div>


                            {{-- Term --}}

                            <div class="col-md-6 mb-3">

                                <label for="term_id"
                                       class="form-label">

                                    Term
                                    <span class="text-danger">*</span>

                                </label>

                                <select name="term_id"
                                        id="term_id"
                                        class="form-select @error('term_id') is-invalid @enderror"
                                        required>

                                    <option value="">
                                        Select Term
                                    </option>

                                    @foreach($terms as $term)

                                        <option value="{{ $term->id }}"
                                            {{ old('term_id', $currentTermId ?? '') == $term->id ? 'selected' : '' }}>

                                            {{ $term->name }}

                                            @if($term->is_current)

                                                — Current

                                            @endif

                                        </option>

                                    @endforeach

                                </select>

                                @error('term_id')

                                    <div class="invalid-feedback">

                                        {{ $message }}

                                    </div>

                                @enderror

                            </div>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                    CLASS ASSIGNMENT
                ================================================== --}}

                <div class="card mb-4">

                    <div class="card-header bg-light">

                        <strong>
                            Class Assignment
                        </strong>

                    </div>


                    <div class="card-body">

                        <div class="row">

                            {{-- School Class --}}

                            <div class="col-md-6 mb-3">

                                <label for="school_class_id"
                                       class="form-label">

                                    School Class
                                    <span class="text-danger">*</span>

                                </label>

                                <select name="school_class_id"
                                        id="school_class_id"
                                        class="form-select @error('school_class_id') is-invalid @enderror"
                                        required>

                                    <option value="">
                                        Select School Class
                                    </option>

                                    @foreach($schoolClasses as $schoolClass)

                                        <option value="{{ $schoolClass->id }}"
                                            {{ old('school_class_id') == $schoolClass->id ? 'selected' : '' }}>

                                            {{ $schoolClass->name }}

                                            @if($schoolClass->code)

                                                ({{ $schoolClass->code }})

                                            @endif

                                        </option>

                                    @endforeach

                                </select>

                                @error('school_class_id')

                                    <div class="invalid-feedback">

                                        {{ $message }}

                                    </div>

                                @enderror

                            </div>


                            {{-- Stream --}}

                            <div class="col-md-6 mb-3">

                                <label for="stream_id"
                                       class="form-label">

                                    Stream

                                    <span class="text-muted">
                                        (Optional)
                                    </span>

                                </label>

                                <select name="stream_id"
                                        id="stream_id"
                                        class="form-select @error('stream_id') is-invalid @enderror">

                                    <option value="">

                                        Whole Class / No Stream

                                    </option>

                                </select>

                                <div class="form-text">

                                    Leave as <strong>Whole Class</strong>
                                    if the teacher is responsible for the
                                    entire class.

                                </div>

                                @error('stream_id')

                                    <div class="invalid-feedback">

                                        {{ $message }}

                                    </div>

                                @enderror

                            </div>

                        </div>


                        {{-- Stream Loading Message --}}

                        <div id="stream-loading"
                             class="text-muted small d-none">

                            <i class="bi bi-arrow-repeat"></i>

                            Loading streams...

                        </div>


                        {{-- Stream Error Message --}}

                        <div id="stream-error"
                             class="alert alert-danger mt-2 d-none">

                        </div>

                    </div>

                </div>


                {{-- =================================================
                    ASSIGNMENT STATUS
                ================================================== --}}

                <div class="card mb-4">

                    <div class="card-header bg-light">

                        <strong>
                            Assignment Details
                        </strong>

                    </div>


                    <div class="card-body">

                        <div class="row">

                            {{-- Status --}}

                            <div class="col-md-6 mb-3">

                                <label for="status"
                                       class="form-label">

                                    Status
                                    <span class="text-danger">*</span>

                                </label>

                                <select name="status"
                                        id="status"
                                        class="form-select @error('status') is-invalid @enderror"
                                        required>

                                    <option value="Active"
                                        {{ old('status', 'Active') === 'Active' ? 'selected' : '' }}>

                                        Active

                                    </option>

                                    <option value="Inactive"
                                        {{ old('status') === 'Inactive' ? 'selected' : '' }}>

                                        Inactive

                                    </option>

                                </select>

                                @error('status')

                                    <div class="invalid-feedback">

                                        {{ $message }}

                                    </div>

                                @enderror

                            </div>


                            {{-- Assigned Date --}}

                            <div class="col-md-6 mb-3">

                                <label for="assigned_at"
                                       class="form-label">

                                    Assignment Date

                                </label>

                                <input type="date"
                                       name="assigned_at"
                                       id="assigned_at"
                                       class="form-control @error('assigned_at') is-invalid @enderror"
                                       value="{{ old('assigned_at', date('Y-m-d')) }}">

                                @error('assigned_at')

                                    <div class="invalid-feedback">

                                        {{ $message }}

                                    </div>

                                @enderror

                            </div>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                    INFORMATION NOTICE
                ================================================== --}}

                <div class="alert alert-info">

                    <div class="d-flex">

                        <div class="me-3">

                            <i class="bi bi-info-circle fs-4"></i>

                        </div>

                        <div>

                            <strong>
                                Assignment Information
                            </strong>

                            <p class="mb-0 mt-1">

                                A teacher can be assigned to a specific stream
                                or to the entire class. An active teacher
                                assignment determines which students appear
                                on the teacher's Class Teacher Dashboard.

                            </p>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                    FORM ACTIONS
                ================================================== --}}

                <div class="d-flex justify-content-end gap-2">

                    <a href="{{ route('class-teacher-assignments.index') }}"
                       class="btn btn-secondary">

                        <i class="bi bi-x-circle"></i>

                        Cancel

                    </a>


                    <button type="submit"
                            class="btn btn-success">

                        <i class="bi bi-check-circle"></i>

                        Save Assignment

                    </button>

                </div>

            </div>

        </div>

    </form>

</div>


{{-- =============================================================
    STREAM AJAX
============================================================= --}}

<script>

document.addEventListener('DOMContentLoaded', function () {

    const classSelect = document.getElementById('school_class_id');

    const streamSelect = document.getElementById('stream_id');

    const loading = document.getElementById('stream-loading');

    const errorBox = document.getElementById('stream-error');


    /*
    |--------------------------------------------------------------------------
    | Existing old stream value
    |--------------------------------------------------------------------------
    */

    const oldStreamId = @json(old('stream_id'));


    /*
    |--------------------------------------------------------------------------
    | Load Streams
    |--------------------------------------------------------------------------
    */

    function loadStreams(classId, selectedStreamId = null) {

        streamSelect.innerHTML = `
            <option value="">
                Whole Class / No Stream
            </option>
        `;

        errorBox.classList.add('d-none');
        errorBox.innerHTML = '';


        if (!classId) {

            return;
        }


        loading.classList.remove('d-none');


        const url =
            "{{ url('class-teacher-assignments/streams') }}"
            + "/"
            + classId;


        fetch(url, {

            method: 'GET',

            headers: {

                'Accept': 'application/json',

                'X-Requested-With': 'XMLHttpRequest'

            }

        })

        .then(response => {

            if (!response.ok) {

                throw new Error(
                    'Unable to load streams.'
                );

            }

            return response.json();

        })

        .then(streams => {

            streams.forEach(stream => {

                const option =
                    document.createElement('option');

                option.value = stream.id;

                option.textContent =
                    stream.name
                    + (stream.code
                        ? ' (' + stream.code + ')'
                        : '');

                if (
                    selectedStreamId &&
                    selectedStreamId == stream.id
                ) {

                    option.selected = true;

                }

                streamSelect.appendChild(option);

            });

        })

        .catch(error => {

            console.error(error);

            errorBox.textContent =
                'Unable to load streams. Please try again.';

            errorBox.classList.remove('d-none');

        })

        .finally(() => {

            loading.classList.add('d-none');

        });

    }


    /*
    |--------------------------------------------------------------------------
    | Class Changed
    |--------------------------------------------------------------------------
    */

    classSelect.addEventListener('change', function () {

        loadStreams(
            this.value
        );

    });


    /*
    |--------------------------------------------------------------------------
    | Load Existing/Old Streams
    |--------------------------------------------------------------------------
    */

    if (classSelect.value) {

        loadStreams(
            classSelect.value,
            oldStreamId
        );

    }

});

</script>

@endsection