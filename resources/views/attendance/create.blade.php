@extends('layouts.app')

@section('title', 'Student Attendance')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="fw-bold mb-1">
                Student Attendance
            </h4>

            <p class="text-muted mb-0">
                Record daily attendance for students by class and stream.
            </p>

        </div>

        <a href="{{ route('attendance.index') }}"
           class="btn btn-outline-primary">

            <i class="bi bi-calendar-check me-1"></i>

            Attendance History

        </a>

    </div>


    {{-- =========================================================
        ALERTS
    ========================================================== --}}

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <i class="bi bi-check-circle me-2"></i>

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            <i class="bi bi-exclamation-triangle me-2"></i>

            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    @if($errors->any())

        <div class="alert alert-danger">

            <strong>Please correct the following:</strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- =========================================================
        ATTENDANCE SELECTION
    ========================================================== --}}

    <div class="card shadow-sm border-0 mb-4">

        <div class="card-header bg-primary text-white">

            <h5 class="mb-0">

                <i class="bi bi-search me-2"></i>

                Select Attendance Details

            </h5>

        </div>


        <div class="card-body">

            <form
                method="GET"
                action="{{ route('attendance.create') }}">

                <div class="row g-3">

                    {{-- Academic Year --}}

                    <div class="col-md-3">

                        <label class="form-label fw-semibold">

                            Academic Year

                        </label>

                        <select
                            name="academic_year_id"
                            class="form-select"
                            required>

                            <option value="">
                                Select Academic Year
                            </option>

                            @foreach($years as $year)

                                <option
                                    value="{{ $year->id }}"
                                    {{ request('academic_year_id') == $year->id ? 'selected' : '' }}>

                                    {{ $year->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Term --}}

                    <div class="col-md-3">

                        <label class="form-label fw-semibold">

                            Term

                        </label>

                        <select
                            name="term_id"
                            class="form-select"
                            required>

                            <option value="">
                                Select Term
                            </option>

                            @foreach($terms as $term)

                                <option
                                    value="{{ $term->id }}"
                                    {{ request('term_id') == $term->id ? 'selected' : '' }}>

                                    {{ $term->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Class --}}

                    <div class="col-md-3">

                        <label class="form-label fw-semibold">

                            Class

                        </label>

                        <select
                            name="school_class_id"
                            class="form-select"
                            required>

                            <option value="">
                                Select Class
                            </option>

                            @foreach($classes as $class)

                                <option
                                    value="{{ $class->id }}"
                                    {{ request('school_class_id') == $class->id ? 'selected' : '' }}>

                                    {{ $class->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Stream --}}

                    <div class="col-md-3">

                        <label class="form-label fw-semibold">

                            Stream

                        </label>

                        <select
                            name="stream_id"
                            class="form-select">

                            <option value="">
                                All Streams
                            </option>

                            @foreach($streams as $stream)

                                <option
                                    value="{{ $stream->id }}"
                                    {{ request('stream_id') == $stream->id ? 'selected' : '' }}>

                                    {{ $stream->display_name }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Attendance Date --}}

                    <div class="col-md-4">

                        <label class="form-label fw-semibold">

                            Attendance Date

                        </label>

                        <input
                            type="date"
                            name="attendance_date"
                            class="form-control"
                            value="{{ request('attendance_date', date('Y-m-d')) }}"
                            required>

                    </div>


                    {{-- Load Students --}}

                    <div class="col-md-3 d-flex align-items-end">

                        <button
                            type="submit"
                            class="btn btn-primary w-100">

                            <i class="bi bi-search me-1"></i>

                            Load Students

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- =========================================================
        STUDENTS
    ========================================================== --}}

    @if($students->count())

        <form
            method="POST"
            action="{{ route('attendance.store') }}">

            @csrf


            {{-- Hidden Selection Values --}}

            <input
                type="hidden"
                name="academic_year_id"
                value="{{ request('academic_year_id') }}">

            <input
                type="hidden"
                name="term_id"
                value="{{ request('term_id') }}">

            <input
                type="hidden"
                name="school_class_id"
                value="{{ request('school_class_id') }}">

            <input
                type="hidden"
                name="stream_id"
                value="{{ request('stream_id') }}">

            <input
                type="hidden"
                name="attendance_date"
                value="{{ request('attendance_date') }}">


            {{-- =====================================================
                ATTENDANCE SUMMARY
            ====================================================== --}}

            <div class="row g-3 mb-4">

                {{-- Total --}}

                <div class="col-md-3">

                    <div class="card shadow-sm border-0 h-100">

                        <div class="card-body">

                            <small class="text-muted">
                                Total Students
                            </small>

                            <h3 class="fw-bold mb-0">
                                {{ $students->count() }}
                            </h3>

                        </div>

                    </div>

                </div>


                {{-- Present --}}

                <div class="col-md-3">

                    <div class="card shadow-sm border-0 border-start border-success border-4 h-100">

                        <div class="card-body">

                            <small class="text-muted">
                                Present
                            </small>

                            <h3
                                class="fw-bold text-success mb-0"
                                id="presentCount">

                                0

                            </h3>

                        </div>

                    </div>

                </div>


                {{-- Absent --}}

                <div class="col-md-3">

                    <div class="card shadow-sm border-0 border-start border-danger border-4 h-100">

                        <div class="card-body">

                            <small class="text-muted">
                                Absent
                            </small>

                            <h3
                                class="fw-bold text-danger mb-0"
                                id="absentCount">

                                0

                            </h3>

                        </div>

                    </div>

                </div>


                {{-- Late / Other --}}

                <div class="col-md-3">

                    <div class="card shadow-sm border-0 border-start border-warning border-4 h-100">

                        <div class="card-body">

                            <small class="text-muted">
                                Late / Other
                            </small>

                            <h3
                                class="fw-bold text-warning mb-0"
                                id="otherCount">

                                0

                            </h3>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =====================================================
                STUDENT ATTENDANCE TABLE
            ====================================================== --}}

            <div class="card shadow-sm border-0">

                <div class="card-header">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <h5 class="mb-1 fw-bold">

                                Student Attendance Register

                            </h5>

                            <small class="text-muted">

                                {{ $students->count() }} student(s) loaded

                            </small>

                        </div>

                        <div>

                            <strong>
                                {{ request('attendance_date') }}
                            </strong>

                        </div>

                    </div>

                </div>


                <div class="card-body p-0">

                    <div class="table-responsive">

                        <table
                            class="table table-hover table-bordered align-middle mb-0">

                            <thead class="table-dark">

                                <tr>

                                    <th width="50">
                                        #
                                    </th>

                                    <th>
                                        Admission No
                                    </th>

                                    <th>
                                        Student
                                    </th>

                                    <th>
                                        Class
                                    </th>

                                    <th>
                                        Stream
                                    </th>

                                    <th width="42%">
                                        Attendance Status
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @foreach($students as $student)

                                    <tr>

                                        {{-- Number --}}

                                        <td>

                                            {{ $loop->iteration }}

                                        </td>


                                        {{-- Admission Number --}}

                                        <td class="fw-semibold">

                                            {{ $student->admission_no }}

                                        </td>


                                        {{-- Student --}}

                                        <td>

                                            <div class="fw-semibold">

                                                {{ $student->first_name }}

                                                {{ $student->middle_name }}

                                                {{ $student->last_name }}

                                            </div>

                                            <input
                                                type="hidden"
                                                name="student_id[]"
                                                value="{{ $student->id }}">

                                        </td>


                                        {{-- Class --}}

                                        <td>

                                            {{ $student->schoolClass->name ?? '-' }}

                                        </td>


                                        {{-- Stream --}}

                                        <td>

                                            @if($student->stream)

                                                <span class="badge bg-primary">

                                                    {{ $student->stream->display_name }}

                                                </span>

                                            @else

                                                <span class="text-muted">
                                                    No Stream
                                                </span>

                                            @endif

                                        </td>


                                        {{-- Attendance --}}

                                        <td>

                                            <div class="d-flex flex-wrap gap-3">

                                                @foreach([
                                                    'Present',
                                                    'Absent',
                                                    'Late',
                                                    'Excused',
                                                    'Sick'
                                                ] as $status)

                                                    <div class="form-check">

                                                        <input
                                                            class="form-check-input attendance-radio"
                                                            type="radio"
                                                            name="status[{{ $student->id }}]"
                                                            value="{{ $status }}"
                                                            {{ $status === 'Present' ? 'checked' : '' }}>

                                                        <label class="form-check-label">

                                                            {{ $status }}

                                                        </label>

                                                    </div>

                                                @endforeach

                                            </div>

                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                </div>


                {{-- =================================================
                    ACTIONS
                ================================================== --}}

                <div class="card-footer text-end">

                    <a
                        href="{{ route('attendance.index') }}"
                        class="btn btn-secondary me-2">

                        <i class="bi bi-x-circle me-1"></i>

                        Cancel

                    </a>


                    <button
                        type="submit"
                        class="btn btn-success">

                        <i class="bi bi-check-circle me-1"></i>

                        Save Attendance

                    </button>

                </div>

            </div>

        </form>


    @else

        {{-- =========================================================
            NO STUDENTS
        ========================================================== --}}

        @if(
            request('school_class_id')
        )

            <div class="alert alert-warning">

                <i class="bi bi-exclamation-triangle me-2"></i>

                No students were found for the selected class and stream.

            </div>

        @else

            <div class="alert alert-info">

                <i class="bi bi-info-circle me-2"></i>

                Select an academic year, term, class, stream and attendance
                date, then click <strong>Load Students</strong>.

            </div>

        @endif

    @endif

</div>


{{-- =========================================================
    ATTENDANCE COUNTER SCRIPT
========================================================== --}}

@if($students->count())

<script>

document.addEventListener('DOMContentLoaded', function () {

    function updateAttendanceCounts() {

        let present = 0;
        let absent = 0;
        let late = 0;
        let other = 0;

        document
            .querySelectorAll('.attendance-radio:checked')
            .forEach(function (radio) {

                switch (radio.value) {

                    case 'Present':
                        present++;
                        break;

                    case 'Absent':
                        absent++;
                        break;

                    case 'Late':
                        late++;
                        break;

                    case 'Excused':
                    case 'Sick':
                        other++;
                        break;

                }

            });


        document.getElementById('presentCount').textContent = present;

        document.getElementById('absentCount').textContent = absent;

        document.getElementById('otherCount').textContent =
            late + other;

    }


    document
        .querySelectorAll('.attendance-radio')
        .forEach(function (radio) {

            radio.addEventListener(
                'change',
                updateAttendanceCounts
            );

        });


    updateAttendanceCounts();

});

</script>

@endif

@endsection