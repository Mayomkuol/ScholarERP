@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- PAGE HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="fw-bold mb-1">
                Edit Class Teacher Assignment
            </h4>

            <p class="text-muted mb-0">
                Update the teacher's class, stream, academic year and term assignment.
            </p>
        </div>

        <a href="{{ route('class-teacher-assignments.index') }}"
           class="btn btn-secondary">

            <i class="bi bi-arrow-left"></i>
            Back to Assignments

        </a>

    </div>


    {{-- VALIDATION ERRORS --}}
    @if($errors->any())

        <div class="alert alert-danger">

            <strong>Please correct the following errors:</strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- SUCCESS MESSAGE --}}
    @if(session('success'))

        <div class="alert alert-success">

            {{ session('success') }}

        </div>

    @endif


    {{-- EDIT FORM --}}
    <form method="POST"
          action="{{ route('class-teacher-assignments.update', $classTeacherAssignment) }}">

        @csrf

        @method('PUT')


        <div class="card shadow-sm">

            <div class="card-header bg-primary text-white">

                <h5 class="mb-0">

                    <i class="bi bi-pencil-square"></i>

                    Assignment Information

                </h5>

            </div>


            <div class="card-body">

                {{-- TEACHER --}}
                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label class="form-label fw-semibold">
                            Teacher
                        </label>

                        <select name="teacher_id"
                                class="form-select @error('teacher_id') is-invalid @enderror"
                                required>

                            <option value="">
                                Select Teacher
                            </option>

                            @foreach($teachers as $teacher)

                                <option value="{{ $teacher->id }}"
                                    {{ old(
                                        'teacher_id',
                                        $classTeacherAssignment->teacher_id
                                    ) == $teacher->id ? 'selected' : '' }}>

                                    {{ $teacher->first_name }}
                                    {{ $teacher->middle_name }}
                                    {{ $teacher->last_name }}

                                    — {{ $teacher->employee_no }}

                                </option>

                            @endforeach

                        </select>

                        @error('teacher_id')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- SCHOOL CLASS --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label fw-semibold">
                            Class
                        </label>

                        <select name="school_class_id"
                                id="school_class_id"
                                class="form-select @error('school_class_id') is-invalid @enderror"
                                required>

                            <option value="">
                                Select Class
                            </option>

                            @foreach($schoolClasses as $schoolClass)

                                <option value="{{ $schoolClass->id }}"
                                    {{ old(
                                        'school_class_id',
                                        $classTeacherAssignment->school_class_id
                                    ) == $schoolClass->id ? 'selected' : '' }}>

                                    {{ $schoolClass->name }}

                                </option>

                            @endforeach

                        </select>

                        @error('school_class_id')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>


                {{-- STREAM --}}
                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label class="form-label fw-semibold">
                            Stream
                        </label>

                        <select name="stream_id"
                                id="stream_id"
                                class="form-select @error('stream_id') is-invalid @enderror">

                            <option value="">
                                Whole Class / No Specific Stream
                            </option>

                            @foreach($streams as $stream)

                                <option value="{{ $stream->id }}"
                                    {{ old(
                                        'stream_id',
                                        $classTeacherAssignment->stream_id
                                    ) == $stream->id ? 'selected' : '' }}>

                                    {{ $stream->name }}

                                </option>

                            @endforeach

                        </select>

                        <small class="text-muted">

                            Select a stream if this teacher is responsible
                            for a specific stream.

                        </small>

                        @error('stream_id')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- ACADEMIC YEAR --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label fw-semibold">
                            Academic Year
                        </label>

                        <select name="academic_year_id"
                                class="form-select @error('academic_year_id') is-invalid @enderror"
                                required>

                            <option value="">
                                Select Academic Year
                            </option>

                            @foreach($academicYears as $academicYear)

                                <option value="{{ $academicYear->id }}"
                                    {{ old(
                                        'academic_year_id',
                                        $classTeacherAssignment->academic_year_id
                                    ) == $academicYear->id ? 'selected' : '' }}>

                                    {{ $academicYear->name }}

                                </option>

                            @endforeach

                        </select>

                        @error('academic_year_id')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>


                {{-- TERM --}}
                <div class="row">

                    <div class="col-md-4 mb-3">

                        <label class="form-label fw-semibold">
                            Term
                        </label>

                        <select name="term_id"
                                class="form-select @error('term_id') is-invalid @enderror"
                                required>

                            <option value="">
                                Select Term
                            </option>

                            @foreach($terms as $term)

                                <option value="{{ $term->id }}"
                                    {{ old(
                                        'term_id',
                                        $classTeacherAssignment->term_id
                                    ) == $term->id ? 'selected' : '' }}>

                                    {{ $term->name }}

                                    @if($term->academicYear)

                                        — {{ $term->academicYear->name }}

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


                    {{-- ASSIGNED DATE --}}
                    <div class="col-md-4 mb-3">

                        <label class="form-label fw-semibold">
                            Assigned Date
                        </label>

                        <input type="date"
                               name="assigned_at"
                               class="form-control @error('assigned_at') is-invalid @enderror"
                               value="{{ old(
                                   'assigned_at',
                                   optional($classTeacherAssignment->assigned_at)->format('Y-m-d')
                               ) }}">

                        @error('assigned_at')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- STATUS --}}
                    <div class="col-md-4 mb-3">

                        <label class="form-label fw-semibold">
                            Status
                        </label>

                        <select name="status"
                                class="form-select @error('status') is-invalid @enderror"
                                required>

                            <option value="Active"
                                {{ old(
                                    'status',
                                    $classTeacherAssignment->status
                                ) == 'Active' ? 'selected' : '' }}>

                                Active

                            </option>

                            <option value="Inactive"
                                {{ old(
                                    'status',
                                    $classTeacherAssignment->status
                                ) == 'Inactive' ? 'selected' : '' }}>

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


            {{-- FOOTER --}}
            <div class="card-footer d-flex justify-content-between">

                <a href="{{ route('class-teacher-assignments.index') }}"
                   class="btn btn-secondary">

                    <i class="bi bi-x-circle"></i>

                    Cancel

                </a>


                <button type="submit"
                        class="btn btn-primary">

                    <i class="bi bi-save"></i>

                    Update Assignment

                </button>

            </div>

        </div>

    </form>

</div>

@endsection


{{-- ============================================================
     STREAM FILTERING
============================================================ --}}

@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function () {

    const classSelect = document.getElementById('school_class_id');
    const streamSelect = document.getElementById('stream_id');

    if (!classSelect || !streamSelect) {
        return;
    }


    classSelect.addEventListener('change', function () {

        const classId = this.value;

        streamSelect.innerHTML =
            '<option value="">Loading streams...</option>';


        if (!classId) {

            streamSelect.innerHTML =
                '<option value="">Whole Class / No Specific Stream</option>';

            return;
        }


        fetch(
            "{{ url('class-teacher-assignments/streams') }}/" + classId
        )
        .then(response => response.json())
        .then(data => {

            streamSelect.innerHTML =
                '<option value="">Whole Class / No Specific Stream</option>';


            data.forEach(stream => {

                const option = document.createElement('option');

                option.value = stream.id;

                option.textContent = stream.name;

                streamSelect.appendChild(option);

            });

        })
        .catch(error => {

            console.error(error);

            streamSelect.innerHTML =
                '<option value="">Unable to load streams</option>';

        });

    });

});

</script>

@endpush