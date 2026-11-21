@extends('layouts.app')

@section('content')

<div class="container-fluid py-4">

    {{-- ========================================================= --}}
    {{-- PAGE HEADER --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="fw-bold mb-1">
                <i class="bi bi-pencil-square me-2"></i>
                Enter Examination Marks
            </h3>

            <p class="text-muted mb-0">
                Select examination, class, stream and subject to load students.
            </p>
        </div>

        <a href="{{ route('exam-marks.index') }}"
           class="btn btn-outline-secondary">

            <i class="bi bi-arrow-left me-1"></i>
            Back to Results

        </a>

    </div>


    {{-- ========================================================= --}}
    {{-- SUCCESS MESSAGE --}}
    {{-- ========================================================= --}}

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <i class="bi bi-check-circle me-2"></i>

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- VALIDATION ERRORS --}}
    {{-- ========================================================= --}}

    @if($errors->any())

        <div class="alert alert-danger">

            <div class="fw-bold mb-2">

                <i class="bi bi-exclamation-triangle me-2"></i>

                Please correct the following:

            </div>

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- STEP 1 --}}
    {{-- ========================================================= --}}

    <div class="card shadow-sm border-0 mb-4">

        <div class="card-header bg-white py-3">

            <h5 class="fw-bold mb-0">

                <span class="badge bg-primary me-2">
                    1
                </span>

                Select Examination Details

            </h5>

        </div>


        <div class="card-body">

            <form method="POST"
                  action="{{ route('exam-marks.load') }}"
                  id="loadMarksForm">

                @csrf


                <div class="row g-4">


                    {{-- ================================================= --}}
                    {{-- EXAMINATION --}}
                    {{-- ================================================= --}}

                    <div class="col-md-3">

                        <label for="exam_id"
                               class="form-label fw-semibold">

                            Examination
                            <span class="text-danger">*</span>

                        </label>

                        <select name="exam_id"
                                id="exam_id"
                                class="form-select"
                                required>

                            <option value="">
                                Select Examination
                            </option>

                            @foreach($exams as $exam)

                                <option value="{{ $exam->id }}"
                                    @selected(
                                        old('exam_id', $selectedExam?->id) == $exam->id
                                    )>

                                    {{ $exam->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- ================================================= --}}
                    {{-- SCHOOL CLASS --}}
                    {{-- ================================================= --}}

                    <div class="col-md-3">

                        <label for="school_class_id"
                               class="form-label fw-semibold">

                            School Class
                            <span class="text-danger">*</span>

                        </label>

                        <select name="school_class_id"
                                id="school_class_id"
                                class="form-select"
                                required>

                            <option value="">
                                Select Class
                            </option>

                            @foreach($schoolClasses as $schoolClass)

                                <option value="{{ $schoolClass->id }}"
                                    @selected(
                                        old(
                                            'school_class_id',
                                            $selectedClass?->id
                                        ) == $schoolClass->id
                                    )>

                                    {{ $schoolClass->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- ================================================= --}}
                    {{-- STREAM --}}
                    {{-- ================================================= --}}

                    <div class="col-md-3">

                        <label for="stream_id"
                               class="form-label fw-semibold">

                            Stream
                            <span class="text-danger">*</span>

                        </label>

                        <select name="stream_id"
                                id="stream_id"
                                class="form-select"
                                required>

                            <option value="">
                                Select Stream
                            </option>

                            @foreach($streams as $stream)

                                <option value="{{ $stream->id }}"
                                        data-class-id="{{ $stream->school_class_id }}"
                                    @selected(
                                        old(
                                            'stream_id',
                                            $selectedStream?->id
                                        ) == $stream->id
                                    )>

                                    {{ $stream->name }}

                                </option>

                            @endforeach

                        </select>

                        <div id="streamHelp"
                             class="form-text">

                            Select a class to view its streams.

                        </div>

                    </div>


                    {{-- ================================================= --}}
                    {{-- SUBJECT --}}
                    {{-- ================================================= --}}

                    <div class="col-md-3">

                        <label for="subject_id"
                               class="form-label fw-semibold">

                            Subject
                            <span class="text-danger">*</span>

                        </label>

                        <select name="subject_id"
                                id="subject_id"
                                class="form-select"
                                required>

                            <option value="">
                                Select Subject
                            </option>

                            @foreach($subjects as $subject)

                                <option value="{{ $subject->id }}"
                                    @selected(
                                        old(
                                            'subject_id',
                                            $selectedSubject?->id
                                        ) == $subject->id
                                    )>

                                    {{ $subject->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>


                {{-- ========================================================= --}}
                {{-- LOAD STUDENTS BUTTON --}}
                {{-- ========================================================= --}}

                <div class="mt-4">

                    <button type="submit"
                            class="btn btn-primary">

                        <i class="bi bi-people me-1"></i>

                        Load Students

                    </button>

                </div>

            </form>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- STEP 2: STUDENTS --}}
    {{-- ========================================================= --}}

    @if(
        $selectedExam &&
        $selectedClass &&
        $selectedStream &&
        $selectedSubject
    )

        <div class="card shadow-sm border-0">

            {{-- ===================================================== --}}
            {{-- CARD HEADER --}}
            {{-- ===================================================== --}}

            <div class="card-header bg-white py-3">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <h5 class="fw-bold mb-1">

                            <span class="badge bg-success me-2">
                                2
                            </span>

                            Enter Examination Marks

                        </h5>

                        <small class="text-muted">

                            {{ $selectedClass->name }}

                            <span class="mx-1">•</span>

                            {{ $selectedStream->name }}

                            <span class="mx-1">•</span>

                            {{ $selectedExam->name }}

                            <span class="mx-1">•</span>

                            {{ $selectedSubject->name }}

                        </small>

                    </div>


                    <span class="badge bg-primary fs-6">

                        {{ $students->count() }}

                        Student{{ $students->count() == 1 ? '' : 's' }}

                    </span>

                </div>

            </div>


            {{-- ===================================================== --}}
            {{-- CARD BODY --}}
            {{-- ===================================================== --}}

            <div class="card-body">


                {{-- ================================================= --}}
                {{-- SELECTED DETAILS --}}
                {{-- ================================================= --}}

                <div class="row g-3 mb-4">


                    {{-- EXAM --}}
                    <div class="col-md-3">

                        <div class="border rounded p-3 h-100">

                            <small class="text-muted d-block mb-1">

                                Examination

                            </small>

                            <div class="fw-bold">

                                {{ $selectedExam->name }}

                            </div>

                        </div>

                    </div>


                    {{-- CLASS --}}
                    <div class="col-md-3">

                        <div class="border rounded p-3 h-100">

                            <small class="text-muted d-block mb-1">

                                Class

                            </small>

                            <div class="fw-bold">

                                {{ $selectedClass->name }}

                            </div>

                        </div>

                    </div>


                    {{-- STREAM --}}
                    <div class="col-md-3">

                        <div class="border rounded p-3 h-100">

                            <small class="text-muted d-block mb-1">

                                Stream

                            </small>

                            <div class="fw-bold">

                                {{ $selectedStream->name }}

                            </div>

                        </div>

                    </div>


                    {{-- SUBJECT --}}
                    <div class="col-md-3">

                        <div class="border rounded p-3 h-100">

                            <small class="text-muted d-block mb-1">

                                Subject

                            </small>

                            <div class="fw-bold">

                                {{ $selectedSubject->name }}

                            </div>

                        </div>

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- STUDENTS EXIST --}}
                {{-- ================================================= --}}

                @if($students->count())


                    <form method="POST"
                          action="{{ route('exam-marks.store') }}">

                        @csrf


                        {{-- ================================================= --}}
                        {{-- HIDDEN VALUES --}}
                        {{-- ================================================= --}}

                        <input type="hidden"
                               name="exam_id"
                               value="{{ $selectedExam->id }}">


                        <input type="hidden"
                               name="school_class_id"
                               value="{{ $selectedClass->id }}">


                        <input type="hidden"
                               name="stream_id"
                               value="{{ $selectedStream->id }}">


                        <input type="hidden"
                               name="subject_id"
                               value="{{ $selectedSubject->id }}">


                        {{-- ================================================= --}}
                        {{-- STUDENT TABLE --}}
                        {{-- ================================================= --}}

                        <div class="table-responsive">

                            <table class="table table-bordered table-hover align-middle mb-0">

                                <thead class="table-light">

                                    <tr>

                                        <th style="width:60px;">
                                            #
                                        </th>

                                        <th style="width:180px;">
                                            Admission No.
                                        </th>

                                        <th>
                                            Student Name
                                        </th>

                                        <th style="width:220px;">
                                            Marks
                                        </th>

                                    </tr>

                                </thead>


                                <tbody>

                                    @foreach($students as $index => $student)

                                        @php

                                            $existingMark =
                                                $existingMarks->get($student->id);

                                        @endphp


                                        <tr>

                                            {{-- NUMBER --}}
                                            <td>

                                                {{ $index + 1 }}

                                            </td>


                                            {{-- ADMISSION NUMBER --}}
                                            <td class="fw-semibold">

                                                {{ $student->admission_number
                                                    ?: $student->admission_no
                                                    ?: '—'
                                                }}

                                            </td>


                                            {{-- STUDENT --}}
                                            <td>

                                                <div class="fw-semibold">

                                                    {{ $student->first_name }}

                                                    @if($student->middle_name)

                                                        {{ $student->middle_name }}

                                                    @endif

                                                    {{ $student->last_name }}

                                                </div>

                                            </td>


                                            {{-- MARK --}}
                                            <td>

                                                <input type="number"
                                                       name="marks[{{ $student->id }}]"
                                                       class="form-control marks-input"
                                                       min="0"
                                                       max="100"
                                                       step="0.01"
                                                       value="{{ old(
                                                           'marks.' . $student->id,
                                                           $existingMark?->marks
                                                       ) }}"
                                                       placeholder="Enter marks">

                                            </td>

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>


                        {{-- ================================================= --}}
                        {{-- SAVE BUTTON --}}
                        {{-- ================================================= --}}

                        <div class="d-flex justify-content-between align-items-center mt-4">

                            <div class="text-muted">

                                <i class="bi bi-info-circle me-1"></i>

                                Enter marks between 0 and 100.

                            </div>


                            <button type="submit"
                                    class="btn btn-success btn-lg">

                                <i class="bi bi-save me-1"></i>

                                Save Examination Marks

                            </button>

                        </div>

                    </form>


                @else


                    {{-- ================================================= --}}
                    {{-- NO STUDENTS --}}
                    {{-- ================================================= --}}

                    <div class="alert alert-warning mb-0">

                        <i class="bi bi-exclamation-triangle me-2"></i>

                        No students were found in

                        <strong>
                            {{ $selectedClass->name }}
                        </strong>

                        -

                        <strong>
                            {{ $selectedStream->name }}
                        </strong>.

                    </div>


                @endif

            </div>

        </div>

    @endif

</div>


{{-- ============================================================= --}}
{{-- STREAM FILTERING --}}
{{-- ============================================================= --}}

<script>

document.addEventListener('DOMContentLoaded', function () {

    const classSelect = document.getElementById('school_class_id');

    const streamSelect = document.getElementById('stream_id');

    const streamHelp = document.getElementById('streamHelp');

    if (!classSelect || !streamSelect) {
        return;
    }


    /*
    |--------------------------------------------------------------------------
    | Store all stream options
    |--------------------------------------------------------------------------
    */

    const allStreams = Array.from(
        streamSelect.querySelectorAll('option[data-class-id]')
    );


    /*
    |--------------------------------------------------------------------------
    | Filter streams by selected class
    |--------------------------------------------------------------------------
    */

    function filterStreams() {

        const classId = classSelect.value;

        const currentStreamId = streamSelect.value;


        // Reset dropdown

        streamSelect.innerHTML = '';


        // Default option

        const defaultOption = document.createElement('option');

        defaultOption.value = '';

        defaultOption.textContent = classId
            ? 'Select Stream'
            : 'Select Class First';

        streamSelect.appendChild(defaultOption);


        /*
        |--------------------------------------------------------------------------
        | No class selected
        |--------------------------------------------------------------------------
        */

        if (!classId) {

            streamSelect.disabled = true;

            streamHelp.textContent =
                'Select a class to view its streams.';

            streamHelp.classList.remove('text-success');

            return;

        }


        /*
        |--------------------------------------------------------------------------
        | Find streams belonging to selected class
        |--------------------------------------------------------------------------
        */

        const matchingStreams = allStreams.filter(function (option) {

            return option.dataset.classId === classId;

        });


        /*
        |--------------------------------------------------------------------------
        | No streams found
        |--------------------------------------------------------------------------
        */

        if (matchingStreams.length === 0) {

            streamSelect.disabled = true;

            streamHelp.textContent =
                'No streams have been assigned to this class.';

            streamHelp.classList.remove('text-success');

            return;

        }


        /*
        |--------------------------------------------------------------------------
        | Add matching streams
        |--------------------------------------------------------------------------
        */

        matchingStreams.forEach(function (originalOption) {

            const option = originalOption.cloneNode(true);

            streamSelect.appendChild(option);

        });


        /*
        |--------------------------------------------------------------------------
        | Restore previously selected stream
        |--------------------------------------------------------------------------
        */

        if (
            currentStreamId &&
            matchingStreams.some(function (option) {
                return option.value === currentStreamId;
            })
        ) {

            streamSelect.value = currentStreamId;

        }


        /*
        |--------------------------------------------------------------------------
        | Enable dropdown
        |--------------------------------------------------------------------------
        */

        streamSelect.disabled = false;

        streamHelp.textContent =
            matchingStreams.length +
            ' stream' +
            (matchingStreams.length === 1 ? '' : 's') +
            ' available.';

        streamHelp.classList.add('text-success');

    }


    /*
    |--------------------------------------------------------------------------
    | When class changes
    |--------------------------------------------------------------------------
    */

    classSelect.addEventListener('change', function () {

        filterStreams();

    });


    /*
    |--------------------------------------------------------------------------
    | Run on page load
    |--------------------------------------------------------------------------
    */

    filterStreams();

});

</script>

@endsection