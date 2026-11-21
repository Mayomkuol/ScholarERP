@extends('layouts.app')

@section('title', 'Student Promotion')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="fw-bold mb-1">
                Student Promotion
            </h4>

            <p class="text-muted mb-0">
                Review examination performance and promote eligible students.
            </p>
        </div>

        @if(Route::has('student-promotions.history'))
            <a
                href="{{ route('student-promotions.history') }}"
                class="btn btn-outline-secondary">

                <i class="bi bi-clock-history me-1"></i>

                Promotion History

            </a>
        @endif

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

        <div class="alert alert-danger alert-dismissible fade show">

            <div class="fw-bold mb-2">

                <i class="bi bi-exclamation-triangle me-2"></i>

                Please correct the following:

            </div>

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- =========================================================
        SELECT EXAMINATION
    ========================================================== --}}

    <div class="card shadow-sm border-0 mb-4">

        <div class="card-header bg-primary text-white">

            <h5 class="mb-0">

                <i class="bi bi-search me-2"></i>

                Select Examination

            </h5>

        </div>


        <div class="card-body">

            <form
                method="GET"
                action="{{ route('student-promotions.index') }}">

                <div class="row g-3 align-items-end">

                    {{-- Exam --}}

                    <div class="col-md-5">

                        <label class="form-label fw-semibold">
                            Examination
                        </label>

                        <select
                            name="exam_id"
                            class="form-select"
                            required>

                            <option value="">
                                Select Examination
                            </option>

                            @foreach($exams as $exam)

                                <option
                                    value="{{ $exam->id }}"
                                    {{ request('exam_id') == $exam->id ? 'selected' : '' }}>

                                    {{ $exam->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Current Class & Stream --}}

<div class="col-md-5">

    <label class="form-label fw-semibold">
        Current Class & Stream
    </label>

    <select
        name="stream_id"
        class="form-select"
        required>

        <option value="">
            Select Class & Stream
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


                    {{-- Load Students --}}

                    <div class="col-md-2 d-grid">

                        <button
                            type="submit"
                            class="btn btn-primary">

                            <i class="bi bi-search me-1"></i>

                            Load Students

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- =========================================================
        PROMOTION CONTENT
    ========================================================== --}}

    @if(
    request('exam_id') &&
    request('stream_id')
)

        {{-- =====================================================
            GENERATE RESULTS
        ====================================================== --}}

        <div class="card shadow-sm border-0 mb-4">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <h6 class="fw-bold mb-1">

                            Examination Results

                        </h6>

                        <small class="text-muted">

                            Generate or update examination results
                            before promoting students.

                        </small>

                    </div>


                    <form
                        action="{{ route('student-promotions.generate-results') }}"
                        method="POST">

                        @csrf

                        <input
                            type="hidden"
                            name="exam_id"
                            value="{{ request('exam_id') }}">

                        <input
    type="hidden"
    name="stream_id"
    value="{{ request('stream_id') }}">

                        <button
                            type="submit"
                            class="btn btn-primary">

                            <i class="bi bi-calculator me-1"></i>

                            Generate Results

                        </button>

                    </form>

                </div>

            </div>

        </div>


        {{-- =====================================================
            STUDENTS EXIST
        ====================================================== --}}

        @if($students->count())


            {{-- =================================================
                CALCULATE SUMMARY
            ================================================== --}}

            @php

                $eligibleCount = $students
                    ->filter(function ($student) {

                        return $student->result &&
                               $student->result->mean_score >= 50;

                    })
                    ->count();


                $repeatCount = $students
                    ->filter(function ($student) {

                        return $student->result &&
                               $student->result->mean_score < 50;

                    })
                    ->count();


                $noResultCount = $students
                    ->filter(function ($student) {

                        return !$student->result;

                    })
                    ->count();

            @endphp


            {{-- =================================================
                PROMOTION FORM
            ================================================== --}}

            <form
                action="{{ route('student-promotions.store') }}"
                method="POST"
                id="promotionForm">

                @csrf

                <input
                    type="hidden"
                    name="exam_id"
                    value="{{ request('exam_id') }}">


                {{-- =================================================
                    PROMOTION DETAILS
                ================================================== --}}

                <div class="card shadow-sm border-0 mb-4">

                    <div class="card-header">

                        <h5 class="mb-0 fw-bold">

                            <i class="bi bi-arrow-up-circle me-2"></i>

                            Promotion Details

                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="row g-3">

                            {{-- Academic Year --}}

                            <div class="col-md-3">

                                <label
                                    for="to_academic_year_id"
                                    class="form-label fw-semibold">

                                    Promote To Academic Year

                                </label>

                                <select
                                    name="to_academic_year_id"
                                    id="to_academic_year_id"
                                    class="form-select"
                                    required>

                                    <option value="">
                                        Select Academic Year
                                    </option>

                                    @foreach($years as $year)

                                        <option
                                            value="{{ $year->id }}"
                                            {{ old('to_academic_year_id') == $year->id ? 'selected' : '' }}>

                                            {{ $year->name }}

                                        </option>

                                    @endforeach

                                </select>

                            </div>


                            {{-- Destination Class --}}

                            <div class="col-md-3">

                                <label
                                    for="to_class_id"
                                    class="form-label fw-semibold">

                                    Promote To Class

                                </label>

                                <select
                                    name="to_class_id"
                                    id="to_class_id"
                                    class="form-select"
                                    required>

                                    <option value="">
                                        Select Class
                                    </option>

                                    @foreach($classes as $class)

                                        <option
                                            value="{{ $class->id }}"
                                            {{ old('to_class_id') == $class->id ? 'selected' : '' }}>

                                            {{ $class->name }}

                                        </option>

                                    @endforeach

                                </select>

                            </div>


                            {{-- Destination Stream --}}

                            <div class="col-md-3">

                                <label
                                    for="to_stream_id"
                                    class="form-label fw-semibold">

                                    Promote To Stream

                                </label>

                                <select
                                    name="to_stream_id"
                                    id="to_stream_id"
                                    class="form-select">

                                    <option value="">
                                        Select Stream
                                    </option>

                                    @foreach($streams as $stream)

                                        <option
                                            value="{{ $stream->id }}"
                                            data-class="{{ $stream->school_class_id }}"
                                            {{ old('to_stream_id') == $stream->id ? 'selected' : '' }}>

                                            {{ $stream->display_name }}

                                        </option>

                                    @endforeach

                                </select>

                                <small class="text-muted">

                                    Select a class first to view its streams.

                                </small>

                            </div>


                            {{-- Promotion Date --}}

                            <div class="col-md-3">

                                <label
                                    for="promotion_date"
                                    class="form-label fw-semibold">

                                    Promotion Date

                                </label>

                                <input
                                    type="date"
                                    name="promotion_date"
                                    id="promotion_date"
                                    class="form-control"
                                    value="{{ old('promotion_date', date('Y-m-d')) }}"
                                    required>

                            </div>

                        </div>


                        {{-- Remarks --}}

                        <div class="mt-3">

                            <label
                                for="remarks"
                                class="form-label fw-semibold">

                                Remarks

                            </label>

                            <textarea
                                name="remarks"
                                id="remarks"
                                rows="2"
                                class="form-control"
                                maxlength="500"
                                placeholder="Optional promotion remarks...">{{ old('remarks') }}</textarea>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                    STUDENT SUMMARY
                ================================================== --}}

                <div class="row g-3 mb-4">

                    {{-- Total --}}

                    <div class="col-md-3">

                        <div class="card shadow-sm border-0 h-100">

                            <div class="card-body">

                                <small class="text-muted">
                                    Students
                                </small>

                                <h3 class="fw-bold mb-0">
                                    {{ $students->count() }}
                                </h3>

                            </div>

                        </div>

                    </div>


                    {{-- Eligible --}}

                    <div class="col-md-3">

                        <div class="card shadow-sm border-0 h-100">

                            <div class="card-body">

                                <small class="text-muted">
                                    Eligible for Promotion
                                </small>

                                <h3 class="fw-bold text-success mb-0">
                                    {{ $eligibleCount }}
                                </h3>

                            </div>

                        </div>

                    </div>


                    {{-- Repeat --}}

                    <div class="col-md-3">

                        <div class="card shadow-sm border-0 h-100">

                            <div class="card-body">

                                <small class="text-muted">
                                    Repeat
                                </small>

                                <h3 class="fw-bold text-danger mb-0">
                                    {{ $repeatCount }}
                                </h3>

                            </div>

                        </div>

                    </div>


                    {{-- No Results --}}

                    <div class="col-md-3">

                        <div class="card shadow-sm border-0 h-100">

                            <div class="card-body">

                                <small class="text-muted">
                                    No Results
                                </small>

                                <h3 class="fw-bold text-secondary mb-0">
                                    {{ $noResultCount }}
                                </h3>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                    STUDENT RESULTS
                ================================================== --}}

                <div class="card shadow-sm border-0">

                    <div class="card-header">

                        <div class="d-flex justify-content-between align-items-center">

                            <div>

                                <h5 class="mb-1 fw-bold">
                                    Student Results
                                </h5>

                                <small class="text-muted">

                                    Students with a mean score of
                                    <strong>50 or above</strong>
                                    are eligible for promotion.

                                </small>

                            </div>


                            <div>

                                <span class="badge bg-success">

                                    Eligible:
                                    {{ $eligibleCount }}

                                </span>

                            </div>

                        </div>

                    </div>


                    <div class="card-body p-0">

                        <div class="table-responsive">

                            <table
                                class="table table-hover table-bordered align-middle mb-0">

                                <thead class="table-dark">

                                    <tr>

                                        <th
                                            width="45"
                                            class="text-center">

                                            <input
                                                type="checkbox"
                                                id="checkAll"
                                                title="Select all eligible students">

                                        </th>

                                        <th>
                                            #
                                        </th>

                                        <th>
                                            Admission No
                                        </th>

                                        <th>
                                            Student
                                        </th>

                                        <th>
                                            Current Class
                                        </th>

                                        <th>
                                            Current Stream
                                        </th>

                                        <th>
                                            Total
                                        </th>

                                        <th>
                                            Mean
                                        </th>

                                        <th>
                                            Grade
                                        </th>

                                        <th>
                                            Points
                                        </th>

                                        <th>
                                            Position
                                        </th>

                                        <th>
                                            Decision
                                        </th>

                                    </tr>

                                </thead>


                                <tbody>

                                    @foreach($students as $student)

                                        @php

                                            $hasResult =
                                                $student->result !== null;

                                            $eligible =
                                                $hasResult &&
                                                $student->result->mean_score >= 50;

                                        @endphp


                                        <tr>

                                            {{-- Checkbox --}}

                                            <td class="text-center">

                                                @if($eligible)

                                                    <input
                                                        type="checkbox"
                                                        name="students[]"
                                                        value="{{ $student->id }}"
                                                        class="student-checkbox">

                                                @else

                                                    <input
                                                        type="checkbox"
                                                        disabled>

                                                @endif

                                            </td>


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

                                            </td>


                                            {{-- Current Class --}}

                                            <td>

                                                {{ $student->schoolClass->name ?? '-' }}

                                            </td>


                                            {{-- Current Stream --}}

                                            <td>

                                                @if($student->stream)

                                                    <span class="badge bg-light text-dark border">

                                                        {{ $student->stream->display_name }}

                                                    </span>

                                                @else

                                                    <span class="text-muted">
                                                        -
                                                    </span>

                                                @endif

                                            </td>


                                            {{-- Total --}}

                                            <td>

                                                @if($hasResult)

                                                    {{ number_format(
                                                        $student->result->total_marks,
                                                        2
                                                    ) }}

                                                @else

                                                    <span class="text-muted">
                                                        -
                                                    </span>

                                                @endif

                                            </td>


                                            {{-- Mean --}}

                                            <td>

                                                @if($hasResult)

                                                    <strong>

                                                        {{ number_format(
                                                            $student->result->mean_score,
                                                            2
                                                        ) }}

                                                    </strong>

                                                @else

                                                    <span class="text-muted">
                                                        -
                                                    </span>

                                                @endif

                                            </td>


                                            {{-- Grade --}}

                                            <td>

                                                @if($hasResult)

                                                    <span class="badge bg-primary">

                                                        {{ $student->result->mean_grade }}

                                                    </span>

                                                @else

                                                    <span class="text-muted">
                                                        -
                                                    </span>

                                                @endif

                                            </td>


                                            {{-- Points --}}

                                            <td>

                                                @if($hasResult)

                                                    {{ number_format(
                                                        $student->result->points,
                                                        2
                                                    ) }}

                                                @else

                                                    <span class="text-muted">
                                                        -
                                                    </span>

                                                @endif

                                            </td>


                                            {{-- Position --}}

                                            <td>

                                                @if($hasResult)

                                                    <span class="badge bg-info text-dark">

                                                        {{ $student->result->position }}

                                                    </span>

                                                @else

                                                    <span class="text-muted">
                                                        -
                                                    </span>

                                                @endif

                                            </td>


                                            {{-- Decision --}}

                                            <td>

                                                @if(!$hasResult)

                                                    <span class="badge bg-secondary">

                                                        No Results

                                                    </span>

                                                @elseif($eligible)

                                                    <span class="badge bg-success">

                                                        Promote

                                                    </span>

                                                @else

                                                    <span class="badge bg-danger">

                                                        Repeat

                                                    </span>

                                                @endif

                                            </td>

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    </div>


                    {{-- =================================================
                        PROMOTE FOOTER
                    ================================================== --}}

                    <div class="card-footer d-flex justify-content-between align-items-center">

                        <div class="text-muted small">

                            <i class="bi bi-info-circle me-1"></i>

                            Only selected eligible students will be promoted.

                        </div>


                        <button
                            type="submit"
                            class="btn btn-success"
                            id="promoteButton">

                            <i class="bi bi-arrow-up-circle me-1"></i>

                            Promote Selected Students

                        </button>

                    </div>

                </div>

            </form>


        @else

            {{-- =====================================================
                NO STUDENTS
            ====================================================== --}}

            <div class="alert alert-info">

                <i class="bi bi-info-circle me-2"></i>

                No students were found in the selected class.

            </div>

        @endif

    @endif

</div>


{{-- =========================================================
    JAVASCRIPT
========================================================== --}}

<script>

document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | SELECT ALL ELIGIBLE STUDENTS
    |--------------------------------------------------------------------------
    */

    const checkAll =
        document.getElementById('checkAll');

    const studentCheckboxes =
        document.querySelectorAll('.student-checkbox');


    if (checkAll) {

        checkAll.addEventListener('change', function () {

            studentCheckboxes.forEach(function (checkbox) {

                checkbox.checked =
                    checkAll.checked;

            });

        });

    }


    /*
    |--------------------------------------------------------------------------
    | KEEP SELECT-ALL STATE ACCURATE
    |--------------------------------------------------------------------------
    */

    studentCheckboxes.forEach(function (checkbox) {

        checkbox.addEventListener('change', function () {

            if (!checkAll) {
                return;
            }

            const total =
                studentCheckboxes.length;

            const checked =
                document.querySelectorAll(
                    '.student-checkbox:checked'
                ).length;

            checkAll.checked =
                total > 0 && checked === total;

        });

    });


    /*
    |--------------------------------------------------------------------------
    | CLASS → STREAM FILTERING
    |--------------------------------------------------------------------------
    */

    const classSelect =
        document.getElementById('to_class_id');

    const streamSelect =
        document.getElementById('to_stream_id');


    if (classSelect && streamSelect) {

        const streamOptions =
            Array.from(
                streamSelect.querySelectorAll(
                    'option[data-class]'
                )
            );


        function filterStreams() {

            const selectedClass =
                classSelect.value;

            const oldStream =
                "{{ old('to_stream_id') }}";


            /*
            |--------------------------------------------------------------------------
            | Reset stream
            |--------------------------------------------------------------------------
            */

            streamSelect.value = '';


            /*
            |--------------------------------------------------------------------------
            | Show only streams belonging to selected class
            |--------------------------------------------------------------------------
            */

            streamOptions.forEach(function (option) {

                const belongsToClass =
                    option.dataset.class === selectedClass;

                option.hidden =
                    !belongsToClass;

            });


            /*
            |--------------------------------------------------------------------------
            | Restore old stream after validation failure
            |--------------------------------------------------------------------------
            */

            if (oldStream && selectedClass) {

                const oldOption =
                    streamOptions.find(function (option) {

                        return (
                            option.value === oldStream &&
                            option.dataset.class === selectedClass
                        );

                    });


                if (oldOption) {

                    streamSelect.value =
                        oldStream;

                }

            }

        }


        /*
        |--------------------------------------------------------------------------
        | When destination class changes
        |--------------------------------------------------------------------------
        */

        classSelect.addEventListener(
            'change',
            filterStreams
        );


        /*
        |--------------------------------------------------------------------------
        | Run when page loads
        |--------------------------------------------------------------------------
        */

        filterStreams();

    }


    /*
    |--------------------------------------------------------------------------
    | PREVENT EMPTY PROMOTION SUBMISSION
    |--------------------------------------------------------------------------
    */

    const promotionForm =
        document.getElementById('promotionForm');

    const promoteButton =
        document.getElementById('promoteButton');


    if (promotionForm && promoteButton) {

        promotionForm.addEventListener(
            'submit',
            function (event) {

                const selectedStudents =
                    document.querySelectorAll(
                        '.student-checkbox:checked'
                    );


                if (selectedStudents.length === 0) {

                    event.preventDefault();

                    alert(
                        'Please select at least one eligible student to promote.'
                    );

                    return;

                }


                /*
                |--------------------------------------------------------------------------
                | Confirm promotion
                |--------------------------------------------------------------------------
                */

                const confirmed =
                    confirm(
                        'Are you sure you want to promote the selected student(s)?'
                    );


                if (!confirmed) {

                    event.preventDefault();

                    return;

                }


                /*
                |--------------------------------------------------------------------------
                | Prevent double submission
                |--------------------------------------------------------------------------
                */

                promoteButton.disabled =
                    true;

                promoteButton.innerHTML =
                    '<span class="spinner-border spinner-border-sm me-1"></span> Promoting...';

            }
        );

    }

});

</script>

@endsection