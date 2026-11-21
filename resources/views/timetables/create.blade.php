@extends('layouts.app')

@section('title', 'Create Timetable')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">

        <div>
            <h3 class="fw-bold mb-1">
                <i class="bi bi-calendar-plus me-2 text-primary"></i>
                Create Timetable
            </h3>

            <p class="text-muted mb-0">
                Create a lesson using your school's class, subject and teacher allocations.
            </p>
        </div>

        <a
            href="{{ route('timetables.index') }}"
            class="btn btn-outline-secondary"
        >
            <i class="bi bi-arrow-left me-1"></i>
            Back to Timetable
        </a>

    </div>


    {{-- =========================================================
        VALIDATION ERRORS
    ========================================================== --}}
    @if ($errors->any())

        <div class="alert alert-danger alert-dismissible fade show shadow-sm">

            <div class="d-flex align-items-start">

                <i class="bi bi-exclamation-triangle-fill fs-4 me-3"></i>

                <div>

                    <strong>Please correct the following:</strong>

                    <ul class="mb-0 mt-2">

                        @foreach ($errors->all() as $error)

                            <li>{{ $error }}</li>

                        @endforeach

                    </ul>

                </div>

            </div>

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    {{-- =========================================================
        SUCCESS MESSAGE
    ========================================================== --}}
    @if (session('success'))

        <div class="alert alert-success alert-dismissible fade show shadow-sm">

            <i class="bi bi-check-circle-fill me-2"></i>

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    {{-- =========================================================
        MAIN CARD
    ========================================================== --}}
    <div class="card border-0 shadow-sm">

        {{-- =====================================================
            CARD HEADER
        ====================================================== --}}
        <div class="card-header bg-primary text-white py-3">

            <div class="d-flex align-items-center">

                <div
                    class="bg-white bg-opacity-25 rounded-circle
                           d-flex align-items-center justify-content-center me-3"
                    style="width:42px;height:42px;"
                >
                    <i class="bi bi-calendar-week fs-5"></i>
                </div>

                <div>

                    <h5 class="mb-0 fw-bold">
                        Timetable Details
                    </h5>

                    <small class="opacity-75">
                        Select the academic context, class, allocated subject,
                        teacher and lesson period.
                    </small>

                </div>

            </div>

        </div>


        {{-- =====================================================
            FORM
        ====================================================== --}}
        <form
            action="{{ route('timetables.store') }}"
            method="POST"
            id="timetableForm"
        >

            @csrf


            <div class="card-body p-4">


                {{-- =================================================
                    ACADEMIC INFORMATION
                ================================================== --}}
                <div class="mb-4">

                    <div class="d-flex align-items-center mb-3">

                        <div
                            class="bg-primary bg-opacity-10 text-primary
                                   rounded-circle d-flex align-items-center
                                   justify-content-center me-2"
                            style="width:34px;height:34px;"
                        >
                            <i class="bi bi-mortarboard"></i>
                        </div>

                        <div>

                            <h6 class="fw-bold mb-0">
                                Academic Information
                            </h6>

                            <small class="text-muted">
                                Select the academic year and term for this timetable.
                            </small>

                        </div>

                    </div>


                    <div class="row">

                        {{-- Academic Year --}}
                        <div class="col-md-6 mb-3">

                            <label
                                for="academic_year_id"
                                class="form-label fw-semibold"
                            >
                                Academic Year
                                <span class="text-danger">*</span>
                            </label>

                            <select
                                name="academic_year_id"
                                id="academic_year_id"
                                class="form-select @error('academic_year_id') is-invalid @enderror"
                                required
                            >

                                <option value="">
                                    Select Academic Year
                                </option>

                                @foreach($academicYears as $year)

                                    <option
                                        value="{{ $year->id }}"
                                        @selected(old('academic_year_id') == $year->id)
                                    >
                                        {{ $year->name }}
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

                            <label
                                for="term_id"
                                class="form-label fw-semibold"
                            >
                                Term
                                <span class="text-danger">*</span>
                            </label>

                            <select
                                name="term_id"
                                id="term_id"
                                class="form-select @error('term_id') is-invalid @enderror"
                                required
                            >

                                <option value="">
                                    Select Term
                                </option>

                                @foreach($terms as $term)

                                    <option
                                        value="{{ $term->id }}"
                                        @selected(old('term_id') == $term->id)
                                    >
                                        {{ $term->name }}
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


                <hr class="my-4">


                {{-- =================================================
                    CLASS INFORMATION
                ================================================== --}}
                <div class="mb-4">

                    <div class="d-flex align-items-center mb-3">

                        <div
                            class="bg-success bg-opacity-10 text-success
                                   rounded-circle d-flex align-items-center
                                   justify-content-center me-2"
                            style="width:34px;height:34px;"
                        >
                            <i class="bi bi-people"></i>
                        </div>

                        <div>

                            <h6 class="fw-bold mb-0">
                                Class Assignment
                            </h6>

                            <small class="text-muted">
                                Select a class and its stream.
                            </small>

                        </div>

                    </div>


                    <div class="row">

                        {{-- Class --}}
                        <div class="col-md-6 mb-3">

                            <label
                                for="school_class_id"
                                class="form-label fw-semibold"
                            >
                                Class
                                <span class="text-danger">*</span>
                            </label>

                            <select
                                name="school_class_id"
                                id="school_class_id"
                                class="form-select @error('school_class_id') is-invalid @enderror"
                                required
                            >

                                <option value="">
                                    Select Class
                                </option>

                                @foreach($classes as $class)

                                    <option
                                        value="{{ $class->id }}"
                                        @selected(old('school_class_id') == $class->id)
                                    >
                                        {{ $class->name }}
                                    </option>

                                @endforeach

                            </select>

                            <div class="form-text">
                                Select the class before selecting a stream.
                            </div>

                            @error('school_class_id')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- Stream --}}
                        <div class="col-md-6 mb-3">

                            <label
                                for="stream_id"
                                class="form-label fw-semibold"
                            >
                                Stream
                                <span class="text-muted fw-normal">
                                    (Optional)
                                </span>
                            </label>

                            <select
                                name="stream_id"
                                id="stream_id"
                                class="form-select @error('stream_id') is-invalid @enderror"
                                disabled
                            >

                                <option value="">
                                    Select Class First
                                </option>

                            </select>

                            <div class="form-text">
                                Select a stream for stream-specific lessons,
                                or use Whole Class where applicable.
                            </div>

                            @error('stream_id')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>

                    </div>


                    {{-- CLASS/STREAM STATUS --}}
                    <div
                        id="classSelectionStatus"
                        class="alert alert-light border d-none mb-0"
                    >

                        <div class="d-flex align-items-center">

                            <i class="bi bi-info-circle text-primary fs-5 me-2"></i>

                            <span id="classSelectionStatusText"></span>

                        </div>

                    </div>

                </div>


                <hr class="my-4">


                {{-- =================================================
                    LESSON INFORMATION
                ================================================== --}}
                <div class="mb-4">

                    <div class="d-flex align-items-center mb-3">

                        <div
                            class="bg-warning bg-opacity-10 text-warning
                                   rounded-circle d-flex align-items-center
                                   justify-content-center me-2"
                            style="width:34px;height:34px;"
                        >
                            <i class="bi bi-book"></i>
                        </div>

                        <div>

                            <h6 class="fw-bold mb-0">
                                Lesson Information
                            </h6>

                            <small class="text-muted">
                                ScholarERP will show only subjects and teachers
                                already allocated to this class and stream.
                            </small>

                        </div>

                    </div>


                    <div class="row">

                        {{-- Subject --}}
                        <div class="col-md-6 mb-3">

                            <label
                                for="subject_id"
                                class="form-label fw-semibold"
                            >
                                Subject
                                <span class="text-danger">*</span>
                            </label>

                            <select
                                name="subject_id"
                                id="subject_id"
                                class="form-select @error('subject_id') is-invalid @enderror"
                                disabled
                                required
                            >

                                <option value="">
                                    Select Class and Stream First
                                </option>

                            </select>

                            <div class="form-text">
                                Only subjects allocated to the selected
                                class and stream will be available.
                            </div>

                            @error('subject_id')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- Teacher --}}
                        <div class="col-md-6 mb-3">

                            <label
                                for="teacher_id"
                                class="form-label fw-semibold"
                            >
                                Teacher
                                <span class="text-danger">*</span>
                            </label>

                            <select
                                name="teacher_id"
                                id="teacher_id"
                                class="form-select @error('teacher_id') is-invalid @enderror"
                                disabled
                                required
                            >

                                <option value="">
                                    Select Subject First
                                </option>

                            </select>

                            <div class="form-text">
                                Only teachers allocated to this subject,
                                class, stream, academic year and term will appear.
                            </div>

                            @error('teacher_id')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>

                    </div>


                    {{-- ALLOCATION STATUS --}}
                    <div
                        id="allocationStatus"
                        class="alert alert-light border d-none mb-0"
                    >

                        <div class="d-flex align-items-center">

                            <i
                                id="allocationStatusIcon"
                                class="bi bi-info-circle text-primary fs-5 me-2"
                            ></i>

                            <span id="allocationStatusText"></span>

                        </div>

                    </div>

                </div>


                <hr class="my-4">


                {{-- =================================================
                    SCHEDULE
                ================================================== --}}
                <div class="mb-4">

                    <div class="d-flex align-items-center mb-3">

                        <div
                            class="bg-info bg-opacity-10 text-info
                                   rounded-circle d-flex align-items-center
                                   justify-content-center me-2"
                            style="width:34px;height:34px;"
                        >
                            <i class="bi bi-clock"></i>
                        </div>

                        <div>

                            <h6 class="fw-bold mb-0">
                                Schedule
                            </h6>

                            <small class="text-muted">
                                Select the day and an existing lesson period.
                            </small>

                        </div>

                    </div>


                    <div class="row">

                        {{-- Day --}}
                        <div class="col-md-6 mb-3">

                            <label
                                for="day"
                                class="form-label fw-semibold"
                            >
                                Day
                                <span class="text-danger">*</span>
                            </label>

                            <select
                                name="day"
                                id="day"
                                class="form-select @error('day') is-invalid @enderror"
                                required
                            >

                                <option value="">
                                    Select Day
                                </option>

                                @foreach([
                                    'Monday',
                                    'Tuesday',
                                    'Wednesday',
                                    'Thursday',
                                    'Friday',
                                    'Saturday'
                                ] as $day)

                                    <option
                                        value="{{ $day }}"
                                        @selected(old('day') == $day)
                                    >
                                        {{ $day }}
                                    </option>

                                @endforeach

                            </select>

                            @error('day')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- Period --}}
                        <div class="col-md-6 mb-3">

                            <label
                                for="period_id"
                                class="form-label fw-semibold"
                            >
                                Timetable Period
                                <span class="text-danger">*</span>
                            </label>

                            <select
                                name="period_id"
                                id="period_id"
                                class="form-select @error('period_id') is-invalid @enderror"
                                required
                            >

                                <option value="">
                                    Select Lesson Period
                                </option>

                                @forelse($periods as $period)

                                    <option
                                        value="{{ $period->id }}"
                                        data-start="{{ substr($period->start_time, 0, 5) }}"
                                        data-end="{{ substr($period->end_time, 0, 5) }}"
                                        data-type="{{ $period->type }}"
                                        @selected(old('period_id') == $period->id)
                                    >

                                        @if($period->period_number)

                                            Period {{ $period->period_number }}

                                        @else

                                            {{ $period->name }}

                                        @endif

                                        —
                                        {{ \Carbon\Carbon::parse($period->start_time)->format('h:i A') }}
                                        -
                                        {{ \Carbon\Carbon::parse($period->end_time)->format('h:i A') }}

                                    </option>

                                @empty

                                    <option value="" disabled>
                                        No active lesson periods configured
                                    </option>

                                @endforelse

                            </select>

                            <div class="form-text">
                                Period times are managed from Timetable Periods.
                            </div>

                            @error('period_id')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>

                    </div>


                    {{-- PERIOD PREVIEW --}}
                    <div
                        id="periodPreview"
                        class="alert alert-info d-none mt-2 mb-0"
                    >

                        <div class="d-flex align-items-center">

                            <i class="bi bi-clock-history fs-4 me-3"></i>

                            <div>

                                <div class="fw-bold">
                                    Selected Period
                                </div>

                                <div id="periodPreviewText">
                                    —
                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                <hr class="my-4">


                {{-- =================================================
                    LOCATION
                ================================================== --}}
                <div class="mb-3">

                    <div class="d-flex align-items-center mb-3">

                        <div
                            class="bg-secondary bg-opacity-10 text-secondary
                                   rounded-circle d-flex align-items-center
                                   justify-content-center me-2"
                            style="width:34px;height:34px;"
                        >
                            <i class="bi bi-door-open"></i>
                        </div>

                        <div>

                            <h6 class="fw-bold mb-0">
                                Location
                            </h6>

                            <small class="text-muted">
                                Specify where the lesson will take place.
                            </small>

                        </div>

                    </div>


                    <div class="row">

                        <div class="col-md-6">

                            <label
                                for="room"
                                class="form-label fw-semibold"
                            >
                                Room
                                <span class="text-muted fw-normal">
                                    (Optional)
                                </span>
                            </label>

                            <input
                                type="text"
                                name="room"
                                id="room"
                                value="{{ old('room') }}"
                                class="form-control @error('room') is-invalid @enderror"
                                placeholder="e.g. Room 4, Computer Lab, Science Lab"
                            >

                            <div class="form-text">
                                ScholarERP can later use the room to detect room conflicts.
                            </div>

                            @error('room')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>

                    </div>

                </div>


                {{-- =================================================
                    INFORMATION NOTICE
                ================================================== --}}
                <div class="alert alert-light border mt-4 mb-0">

                    <div class="d-flex">

                        <i class="bi bi-shield-check text-primary fs-5 me-3"></i>

                        <div>

                            <strong>Smart timetable validation</strong>

                            <p class="mb-0 mt-1 text-muted small">

                                ScholarERP automatically uses the selected
                                period's start and end times. It also verifies
                                that the subject and teacher are properly allocated
                                to the selected class and stream and checks for
                                timetable conflicts before saving.

                            </p>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =====================================================
                FOOTER
            ====================================================== --}}
            <div class="card-footer bg-light border-top p-3">

                <div class="d-flex justify-content-between align-items-center">

                    <a
                        href="{{ route('timetables.index') }}"
                        class="btn btn-outline-secondary"
                    >
                        <i class="bi bi-x-circle me-1"></i>
                        Cancel
                    </a>


                    <button
                        type="submit"
                        class="btn btn-primary px-4"
                        id="saveTimetableBtn"
                    >
                        <i class="bi bi-save me-1"></i>
                        Save Timetable
                    </button>

                </div>

            </div>

        </form>

    </div>

</div>


{{-- =============================================================
    JAVASCRIPT
============================================================= --}}
@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | ELEMENTS
    |--------------------------------------------------------------------------
    */

    const academicYearSelect =
        document.getElementById('academic_year_id');

    const termSelect =
        document.getElementById('term_id');

    const classSelect =
        document.getElementById('school_class_id');

    const streamSelect =
        document.getElementById('stream_id');

    const subjectSelect =
        document.getElementById('subject_id');

    const teacherSelect =
        document.getElementById('teacher_id');

    const periodSelect =
        document.getElementById('period_id');

    const periodPreview =
        document.getElementById('periodPreview');

    const periodPreviewText =
        document.getElementById('periodPreviewText');

    const classSelectionStatus =
        document.getElementById('classSelectionStatus');

    const classSelectionStatusText =
        document.getElementById('classSelectionStatusText');

    const allocationStatus =
        document.getElementById('allocationStatus');

    const allocationStatusText =
        document.getElementById('allocationStatusText');

    const allocationStatusIcon =
        document.getElementById('allocationStatusIcon');

    const form =
        document.getElementById('timetableForm');

    const saveButton =
        document.getElementById('saveTimetableBtn');


    /*
    |--------------------------------------------------------------------------
    | OLD VALUES
    |--------------------------------------------------------------------------
    */

    const oldStreamId =
        @json(old('stream_id'));

    const oldSubjectId =
        @json(old('subject_id'));

    const oldTeacherId =
        @json(old('teacher_id'));


    /*
    |--------------------------------------------------------------------------
    | HELPER FUNCTIONS
    |--------------------------------------------------------------------------
    */

    function resetSelect(select, message)
    {
        if (!select) {
            return;
        }

        select.innerHTML = '';

        const option =
            document.createElement('option');

        option.value = '';

        option.textContent = message;

        select.appendChild(option);

        select.disabled = true;
    }


    function enableSelect(select)
    {
        if (select) {
            select.disabled = false;
        }
    }


    function setLoading(select, message)
    {
        if (!select) {
            return;
        }

        select.innerHTML = '';

        const option =
            document.createElement('option');

        option.value = '';

        option.textContent = message;

        select.appendChild(option);

        select.disabled = true;
    }


    function showClassStatus(message)
    {
        if (!classSelectionStatus) {
            return;
        }

        classSelectionStatusText.textContent =
            message;

        classSelectionStatus.classList.remove(
            'd-none'
        );
    }


    function hideClassStatus()
    {
        if (!classSelectionStatus) {
            return;
        }

        classSelectionStatus.classList.add(
            'd-none'
        );
    }


    function showAllocationStatus(
        message,
        type = 'info'
    )
    {
        if (!allocationStatus) {
            return;
        }

        allocationStatus.className =
            'alert border mb-0';

        allocationStatus.classList.add(
            type === 'success'
                ? 'alert-success'
                : type === 'danger'
                    ? 'alert-danger'
                    : 'alert-light'
        );

        allocationStatusIcon.className =
            type === 'success'
                ? 'bi bi-check-circle-fill text-success fs-5 me-2'
                : type === 'danger'
                    ? 'bi bi-exclamation-circle-fill text-danger fs-5 me-2'
                    : 'bi bi-info-circle text-primary fs-5 me-2';

        allocationStatusText.textContent =
            message;

        allocationStatus.classList.remove(
            'd-none'
        );
    }


    function hideAllocationStatus()
    {
        if (!allocationStatus) {
            return;
        }

        allocationStatus.classList.add(
            'd-none'
        );
    }


    function formatTime(time)
    {
        if (!time) {
            return '';
        }

        const parts =
            time.split(':');

        let hour =
            parseInt(parts[0], 10);

        const minute =
            parts[1];

        const suffix =
            hour >= 12
                ? 'PM'
                : 'AM';

        hour =
            hour % 12;

        if (hour === 0) {
            hour = 12;
        }

        return `${hour}:${minute} ${suffix}`;
    }


    /*
    |--------------------------------------------------------------------------
    | LOAD STREAMS
    |--------------------------------------------------------------------------
    */

    async function loadStreams()
    {
        const classId =
            classSelect.value;

        resetSelect(
            streamSelect,
            'Loading streams...'
        );

        resetSelect(
            subjectSelect,
            'Select Stream First'
        );

        resetSelect(
            teacherSelect,
            'Select Subject First'
        );

        hideClassStatus();
        hideAllocationStatus();


        if (!classId) {

            resetSelect(
                streamSelect,
                'Select Class First'
            );

            return;
        }


        try {

            const params =
                new URLSearchParams({
                    school_class_id: classId
                });


            const response =
                await fetch(
                    `{{ route('timetables.data.streams') }}?${params.toString()}`,
                    {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    }
                );


            if (!response.ok) {

                throw new Error(
                    'Unable to load streams.'
                );

            }


            const streams =
                await response.json();


            streamSelect.innerHTML = '';


            /*
            |--------------------------------------------------------------------------
            | WHOLE CLASS OPTION
            |--------------------------------------------------------------------------
            */

            const wholeClassOption =
                document.createElement('option');

            wholeClassOption.value = '';

            wholeClassOption.textContent =
                'Whole Class / All Streams';

            streamSelect.appendChild(
                wholeClassOption
            );


            /*
            |--------------------------------------------------------------------------
            | STREAM OPTIONS
            |--------------------------------------------------------------------------
            */

            streams.forEach(function (stream) {

                const option =
                    document.createElement('option');

                option.value =
                    stream.id;

                option.textContent =
                    stream.name;

                streamSelect.appendChild(
                    option
                );

            });


            enableSelect(streamSelect);


            /*
            |--------------------------------------------------------------------------
            | RESTORE OLD STREAM
            |--------------------------------------------------------------------------
            */

            if (
                oldStreamId &&
                streams.some(
                    stream =>
                        String(stream.id) ===
                        String(oldStreamId)
                )
            ) {

                streamSelect.value =
                    oldStreamId;

            }


            showClassStatus(
                streams.length
                    ? 'Streams loaded. Select a stream to load its allocated subjects.'
                    : 'This class has no streams configured. Whole Class lessons can still be created.'
            );


            /*
            |--------------------------------------------------------------------------
            | LOAD SUBJECTS
            |--------------------------------------------------------------------------
            */

            await loadSubjects();


        } catch (error) {

            console.error(error);

            resetSelect(
                streamSelect,
                'Unable to load streams'
            );

            showClassStatus(
                'Unable to load streams. Please try again.'
            );

        }

    }


    /*
    |--------------------------------------------------------------------------
    | LOAD SUBJECTS
    |--------------------------------------------------------------------------
    */

    async function loadSubjects()
    {
        const classId =
            classSelect.value;

        const streamId =
            streamSelect.value;

        const academicYearId =
            academicYearSelect.value;

        const termId =
            termSelect.value;


        resetSelect(
            subjectSelect,
            'Loading allocated subjects...'
        );

        resetSelect(
            teacherSelect,
            'Select Subject First'
        );

        hideAllocationStatus();


        if (
            !classId ||
            !academicYearId ||
            !termId
        ) {

            resetSelect(
                subjectSelect,
                'Select Academic Year, Term and Class'
            );

            return;

        }


        try {

            const params =
                new URLSearchParams({

                    school_class_id:
                        classId,

                    stream_id:
                        streamId,

                    academic_year_id:
                        academicYearId,

                    term_id:
                        termId

                });


            const response =
                await fetch(
                    `{{ route('timetables.data.subjects') }}?${params.toString()}`,
                    {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    }
                );


            if (!response.ok) {

                let message =
                    'Unable to load allocated subjects.';

                try {

                    const errorData =
                        await response.json();

                    if (errorData.message) {
                        message =
                            errorData.message;
                    }

                } catch (e) {
                    // Ignore invalid JSON.
                }

                throw new Error(message);

            }


            const subjects =
                await response.json();


            subjectSelect.innerHTML = '';


            const firstOption =
                document.createElement('option');

            firstOption.value = '';

            firstOption.textContent =
                subjects.length
                    ? 'Select Subject'
                    : 'No subjects allocated';

            subjectSelect.appendChild(
                firstOption
            );


            subjects.forEach(function (subject) {

                const option =
                    document.createElement('option');

                option.value =
                    subject.id;

                option.textContent =
                    subject.code
                        ? `${subject.name} (${subject.code})`
                        : subject.name;

                subjectSelect.appendChild(
                    option
                );

            });


            enableSelect(subjectSelect);


            /*
            |--------------------------------------------------------------------------
            | RESTORE OLD SUBJECT
            |--------------------------------------------------------------------------
            */

            if (
                oldSubjectId &&
                subjects.some(
                    subject =>
                        String(subject.id) ===
                        String(oldSubjectId)
                )
            ) {

                subjectSelect.value =
                    oldSubjectId;

                await loadTeachers();

            }


            if (subjects.length) {

                showAllocationStatus(
                    `${subjects.length} allocated subject${subjects.length === 1 ? '' : 's'} available for this class and stream.`,
                    'success'
                );

            } else {

                showAllocationStatus(
                    'No subjects are allocated to this class and stream for the selected academic year and term.',
                    'danger'
                );

            }


        } catch (error) {

            console.error(error);

            resetSelect(
                subjectSelect,
                'Unable to load subjects'
            );

            showAllocationStatus(
                error.message ||
                'Unable to load allocated subjects.',
                'danger'
            );

        }

    }


    /*
    |--------------------------------------------------------------------------
    | LOAD TEACHERS
    |--------------------------------------------------------------------------
    */

    async function loadTeachers()
    {
        const classId =
            classSelect.value;

        const streamId =
            streamSelect.value;

        const subjectId =
            subjectSelect.value;

        const academicYearId =
            academicYearSelect.value;

        const termId =
            termSelect.value;


        resetSelect(
            teacherSelect,
            'Loading allocated teachers...'
        );


        if (
            !classId ||
            !subjectId ||
            !academicYearId ||
            !termId
        ) {

            resetSelect(
                teacherSelect,
                'Select Subject First'
            );

            return;

        }


        try {

            const params =
                new URLSearchParams({

                    school_class_id:
                        classId,

                    stream_id:
                        streamId,

                    subject_id:
                        subjectId,

                    academic_year_id:
                        academicYearId,

                    term_id:
                        termId

                });


            const response =
                await fetch(
                    `{{ route('timetables.data.teachers') }}?${params.toString()}`,
                    {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    }
                );


            if (!response.ok) {

                let message =
                    'Unable to load allocated teachers.';

                try {

                    const errorData =
                        await response.json();

                    if (errorData.message) {
                        message =
                            errorData.message;
                    }

                } catch (e) {
                    // Ignore invalid JSON.
                }

                throw new Error(message);

            }


            const teachers =
                await response.json();


            teacherSelect.innerHTML = '';


            const firstOption =
                document.createElement('option');

            firstOption.value = '';

            firstOption.textContent =
                teachers.length
                    ? 'Select Teacher'
                    : 'No teacher allocated';

            teacherSelect.appendChild(
                firstOption
            );


            teachers.forEach(function (teacher) {

                const option =
                    document.createElement('option');

                option.value =
                    teacher.id;

                option.textContent =
                    teacher.name;

                teacherSelect.appendChild(
                    option
                );

            });


            enableSelect(teacherSelect);


            /*
            |--------------------------------------------------------------------------
            | RESTORE OLD TEACHER
            |--------------------------------------------------------------------------
            */

            if (
                oldTeacherId &&
                teachers.some(
                    teacher =>
                        String(teacher.id) ===
                        String(oldTeacherId)
                )
            ) {

                teacherSelect.value =
                    oldTeacherId;

            }


            if (teachers.length) {

                showAllocationStatus(
                    `${teachers.length} allocated teacher${teachers.length === 1 ? '' : 's'} available for this subject.`,
                    'success'
                );

            } else {

                showAllocationStatus(
                    'No teacher is allocated to this subject for the selected class, stream, academic year and term.',
                    'danger'
                );

            }


        } catch (error) {

            console.error(error);

            resetSelect(
                teacherSelect,
                'Unable to load teachers'
            );

            showAllocationStatus(
                error.message ||
                'Unable to load allocated teachers.',
                'danger'
            );

        }

    }


    /*
    |--------------------------------------------------------------------------
    | CLASS CHANGE
    |--------------------------------------------------------------------------
    */

    classSelect.addEventListener(
        'change',
        loadStreams
    );


    /*
    |--------------------------------------------------------------------------
    | STREAM CHANGE
    |--------------------------------------------------------------------------
    */

    streamSelect.addEventListener(
        'change',
        async function () {

            await loadSubjects();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | ACADEMIC YEAR CHANGE
    |--------------------------------------------------------------------------
    */

    academicYearSelect.addEventListener(
        'change',
        async function () {

            if (classSelect.value) {

                await loadSubjects();

            }

        }
    );


    /*
    |--------------------------------------------------------------------------
    | TERM CHANGE
    |--------------------------------------------------------------------------
    */

    termSelect.addEventListener(
        'change',
        async function () {

            if (classSelect.value) {

                await loadSubjects();

            }

        }
    );


    /*
    |--------------------------------------------------------------------------
    | SUBJECT CHANGE
    |--------------------------------------------------------------------------
    */

    subjectSelect.addEventListener(
        'change',
        loadTeachers
    );


    /*
    |--------------------------------------------------------------------------
    | PERIOD PREVIEW
    |--------------------------------------------------------------------------
    */

    function updatePeriodPreview()
    {
        if (!periodSelect) {
            return;
        }

        const selectedOption =
            periodSelect.options[
                periodSelect.selectedIndex
            ];


        if (
            !selectedOption ||
            !selectedOption.value
        ) {

            periodPreview.classList.add(
                'd-none'
            );

            return;

        }


        const start =
            selectedOption.dataset.start;

        const end =
            selectedOption.dataset.end;


        if (!start || !end) {

            periodPreview.classList.add(
                'd-none'
            );

            return;

        }


        periodPreviewText.textContent =
            `${formatTime(start)} - ${formatTime(end)}`;


        periodPreview.classList.remove(
            'd-none'
        );

    }


    periodSelect.addEventListener(
        'change',
        updatePeriodPreview
    );


    updatePeriodPreview();


    /*
    |--------------------------------------------------------------------------
    | FORM SUBMISSION
    |--------------------------------------------------------------------------
    */

    if (form && saveButton) {

        form.addEventListener(
            'submit',
            function () {

                saveButton.disabled = true;

                saveButton.innerHTML =
                    '<span class="spinner-border spinner-border-sm me-2"></span>' +
                    'Saving...';

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | INITIAL LOAD
    |--------------------------------------------------------------------------
    */

    if (classSelect.value) {

        loadStreams();

    }

});

</script>

@endpush

@endsection