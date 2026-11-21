@extends('layouts.app')

@section('content')

<div class="container-fluid py-4">

    {{-- ================================================================
         HEADER
    ================================================================= --}}
    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">

        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <a
                    href="{{ route('timetables.blocks.index') }}"
                    class="btn btn-sm btn-light border"
                >
                    <i class="bi bi-arrow-left"></i>
                    Back
                </a>

                <span class="badge bg-light text-dark border">
                    Timetable Block #{{ $timetableBlock->id }}
                </span>
            </div>

            <h3 class="fw-bold mb-1">
                {{ $timetableBlock->name }}
            </h3>

            <div class="text-muted">
                Build and manage the weekly timetable for this block.
            </div>
        </div>

        <div class="d-flex gap-2">

            @if(
                auth()->user()->can('timetables.edit') &&
                $timetableBlock->isDraft()
            )
                <a
                    href="{{ route('timetables.blocks.edit', $timetableBlock) }}"
                    class="btn btn-outline-primary"
                >
                    <i class="bi bi-pencil me-1"></i>
                    Edit Block
                </a>
            @endif

            <button
                type="button"
                class="btn btn-primary"
                data-bs-toggle="modal"
                data-bs-target="#addLessonModal"
                @disabled(!$timetableBlock->isDraft())
            >
                <i class="bi bi-plus-lg me-1"></i>
                Add Lesson
            </button>

        </div>

    </div>


    {{-- ================================================================
         FLASH MESSAGES
    ================================================================= --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">

            <div class="fw-bold mb-1">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                Please correct the following:
            </div>

            <ul class="mb-0 ps-4">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>
        </div>
    @endif


    {{-- ================================================================
         BLOCK INFORMATION
    ================================================================= --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-3">
                    <div class="small text-muted mb-1">
                        Academic Year
                    </div>

                    <div class="fw-semibold">
                        {{ $timetableBlock->academicYear->name ?? '—' }}
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="small text-muted mb-1">
                        Term
                    </div>

                    <div class="fw-semibold">
                        {{ $timetableBlock->term->name ?? '—' }}
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="small text-muted mb-1">
                        Class
                    </div>

                    <div class="fw-semibold">
                        {{ $timetableBlock->schoolClass->name ?? '—' }}
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="small text-muted mb-1">
                        Stream
                    </div>

                    <div class="fw-semibold">
                        {{ $timetableBlock->stream->name ?? 'All Streams' }}
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="small text-muted mb-1">
                        Effective From
                    </div>

                    <div class="fw-semibold">
                        {{ optional($timetableBlock->effective_from)->format('d M Y') }}
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="small text-muted mb-1">
                        Effective To
                    </div>

                    <div class="fw-semibold">
                        {{ optional($timetableBlock->effective_to)->format('d M Y') }}
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="small text-muted mb-1">
                        Created By
                    </div>

                    <div class="fw-semibold">
                        {{ $timetableBlock->creator->name ?? 'System' }}
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="small text-muted mb-1">
                        Status
                    </div>

                    @if($timetableBlock->isPublished())

                        <span class="badge bg-success-subtle text-success">
                            <i class="bi bi-check-circle me-1"></i>
                            Published
                        </span>

                    @elseif($timetableBlock->isArchived())

                        <span class="badge bg-secondary-subtle text-secondary">
                            <i class="bi bi-archive me-1"></i>
                            Archived
                        </span>

                    @else

                        <span class="badge bg-warning-subtle text-warning-emphasis">
                            <i class="bi bi-pencil-square me-1"></i>
                            Draft
                        </span>

                    @endif
                </div>

            </div>

        </div>

    </div>


    {{-- ================================================================
         STATISTICS
    ================================================================= --}}
    @php
        $lessons = $timetableBlock->timetables;

        $lessonCount = $lessons->count();

        $teacherCount = $lessons
            ->pluck('teacher_id')
            ->filter()
            ->unique()
            ->count();

        $subjectCount = $lessons
            ->pluck('subject_id')
            ->filter()
            ->unique()
            ->count();

        $roomCount = $lessons
            ->pluck('room')
            ->filter()
            ->unique()
            ->count();

        $clashCount = 0;
    @endphp

    <div class="row g-3 mb-4">

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-primary-subtle p-3">
                        <i class="bi bi-calendar-week fs-4 text-primary"></i>
                    </div>

                    <div>
                        <div class="small text-muted">
                            Total Lessons
                        </div>

                        <div class="fs-4 fw-bold">
                            {{ $lessonCount }}
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-success-subtle p-3">
                        <i class="bi bi-person-video3 fs-4 text-success"></i>
                    </div>

                    <div>
                        <div class="small text-muted">
                            Teachers
                        </div>

                        <div class="fs-4 fw-bold">
                            {{ $teacherCount }}
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-info-subtle p-3">
                        <i class="bi bi-book fs-4 text-info"></i>
                    </div>

                    <div>
                        <div class="small text-muted">
                            Subjects
                        </div>

                        <div class="fs-4 fw-bold">
                            {{ $subjectCount }}
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-warning-subtle p-3">
                        <i class="bi bi-door-open fs-4 text-warning"></i>
                    </div>

                    <div>
                        <div class="small text-muted">
                            Rooms
                        </div>

                        <div class="fs-4 fw-bold">
                            {{ $roomCount }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>


    {{-- ================================================================
         WEEKLY TIMETABLE
    ================================================================= --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white border-0 py-3">

            <div class="d-flex justify-content-between align-items-center">

                <div>
                    <h5 class="fw-bold mb-1">
                        <i class="bi bi-grid-3x3-gap me-2"></i>
                        Weekly Timetable
                    </h5>

                    <div class="small text-muted">
                        Monday to Saturday
                    </div>
                </div>

                <span class="badge bg-light text-dark border">
                    {{ $lessonCount }} lesson{{ $lessonCount == 1 ? '' : 's' }}
                </span>

            </div>

        </div>


        <div class="card-body p-0">

            @php
                $days = [
                    'Monday',
                    'Tuesday',
                    'Wednesday',
                    'Thursday',
                    'Friday',
                    'Saturday',
                ];
            @endphp

            <div class="table-responsive">

                <table class="table table-bordered align-middle mb-0 timetable-builder-table">

                    <thead class="table-light">

                        <tr>
                            <th
                                style="width: 130px;"
                                class="text-center"
                            >
                                Period
                            </th>

                            @foreach($days as $day)

                                <th
                                    class="text-center"
                                    style="min-width: 210px;"
                                >
                                    {{ $day }}
                                </th>

                            @endforeach

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($periods as $period)

                            <tr>

                                <td class="text-center bg-light">

                                    <div class="fw-bold">
                                        {{ $period->name }}
                                    </div>

                                    <div class="small text-muted">
                                        {{ \Carbon\Carbon::parse($period->start_time)->format('H:i') }}
                                        -
                                        {{ \Carbon\Carbon::parse($period->end_time)->format('H:i') }}
                                    </div>

                                </td>


                                @foreach($days as $day)

                                    @php
                                        $lesson = $lessons->first(
                                            fn ($item) =>
                                                $item->day === $day &&
                                                $item->period_id === $period->id
                                        );
                                    @endphp

                                    <td class="timetable-slot">

                                        @if($lesson)

                                            <div class="lesson-card">

                                                <div class="fw-bold text-primary mb-1">
                                                    {{ $lesson->subject->name ?? 'Unknown Subject' }}
                                                </div>

                                                <div class="small text-dark">
                                                    <i class="bi bi-person me-1"></i>

                                                    {{ $lesson->teacher->full_name ?? 'Unknown Teacher' }}
                                                </div>

                                                @if($lesson->room)

                                                    <div class="small text-muted mt-1">
                                                        <i class="bi bi-door-open me-1"></i>

                                                        {{ $lesson->room }}
                                                    </div>

                                                @endif


                                                <div class="mt-2 d-flex justify-content-between align-items-center">

                                                    <span class="badge bg-light text-dark border">
                                                        {{ ucfirst($lesson->lesson_type) }}
                                                    </span>


                                                    @if(
                                                        auth()->user()->can('timetables.delete') &&
                                                        $timetableBlock->isDraft()
                                                    )

                                                        <form
                                                            action="{{ route(
                                                                'timetables.blocks.lessons.destroy',
                                                                [
                                                                    'timetableBlock' => $timetableBlock,
                                                                    'timetable' => $lesson
                                                                ]
                                                            ) }}"
                                                            method="POST"
                                                            class="d-inline"
                                                            onsubmit="return confirm('Remove this lesson from the timetable?');"
                                                        >

                                                            @csrf
                                                            @method('DELETE')

                                                            <button
                                                                type="submit"
                                                                class="btn btn-sm btn-outline-danger"
                                                                title="Remove Lesson"
                                                            >
                                                                <i class="bi bi-trash"></i>
                                                            </button>

                                                        </form>

                                                    @endif

                                                </div>

                                            </div>

                                        @else

                                            <div class="empty-slot">

                                                @if($timetableBlock->isDraft())

                                                    <button
                                                        type="button"
                                                        class="btn btn-sm btn-outline-primary add-slot-btn"
                                                        data-day="{{ $day }}"
                                                        data-period="{{ $period->id }}"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#addLessonModal"
                                                    >
                                                        <i class="bi bi-plus"></i>
                                                        Add
                                                    </button>

                                                @else

                                                    <span class="text-muted small">
                                                        —
                                                    </span>

                                                @endif

                                            </div>

                                        @endif

                                    </td>

                                @endforeach

                            </tr>

                        @empty

                            <tr>
                                <td
                                    colspan="7"
                                    class="text-center py-5"
                                >
                                    <i class="bi bi-clock-history fs-1 text-muted"></i>

                                    <h5 class="mt-3">
                                        No lesson periods configured
                                    </h5>

                                    <p class="text-muted mb-0">
                                        Add active lesson periods under
                                        Timetable → Periods first.
                                    </p>
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>


{{-- ====================================================================
     ADD LESSON MODAL
===================================================================== --}}
<div
    class="modal fade"
    id="addLessonModal"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content border-0 shadow">

            <div class="modal-header">

                <div>

                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-plus-circle me-2"></i>
                        Add Timetable Lesson
                    </h5>

                    <div class="small text-muted">
                        {{ $timetableBlock->schoolClass->name ?? '' }}
                        •
                        {{ $timetableBlock->stream->name ?? 'All Streams' }}
                    </div>

                </div>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                ></button>

            </div>


            <form
                method="POST"
                action="{{ route(
                    'timetables.blocks.lessons.store',
                    $timetableBlock
                ) }}"
                id="addLessonForm"
            >

                @csrf

                <div class="modal-body">

                    <div class="alert alert-info border-0">

                        <i class="bi bi-info-circle me-2"></i>

                        Academic Year, Term, Class and Stream are inherited
                        automatically from this timetable block.

                    </div>


                    <div class="row g-3">

                        {{-- Day --}}
                        <div class="col-md-6">

                            <label class="form-label fw-semibold">
                                Day <span class="text-danger">*</span>
                            </label>

                            <select
                                name="day"
                                id="lesson_day"
                                class="form-select"
                                required
                            >

                                <option value="">
                                    Select Day
                                </option>

                                @foreach($days as $day)

                                    <option value="{{ $day }}">
                                        {{ $day }}
                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- Period --}}
                        <div class="col-md-6">

                            <label class="form-label fw-semibold">
                                Period <span class="text-danger">*</span>
                            </label>

                            <select
                                name="period_id"
                                id="lesson_period"
                                class="form-select"
                                required
                            >

                                <option value="">
                                    Select Period
                                </option>

                                @foreach($periods as $period)

                                    <option
                                        value="{{ $period->id }}"
                                    >
                                        {{ $period->name }}
                                        —
                                        {{ \Carbon\Carbon::parse($period->start_time)->format('H:i') }}
                                        -
                                        {{ \Carbon\Carbon::parse($period->end_time)->format('H:i') }}
                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- Subject --}}
                        <div class="col-md-6">

                            <label class="form-label fw-semibold">
                                Subject <span class="text-danger">*</span>
                            </label>

                            <select
                                name="subject_id"
                                id="lesson_subject"
                                class="form-select"
                                required
                            >

                                <option value="">
                                    Select Subject
                                </option>

                                @foreach($subjects as $subject)

                                    <option value="{{ $subject->id }}">
                                        {{ $subject->name }}

                                        @if(!empty($subject->code))
                                            ({{ $subject->code }})
                                        @endif

                                    </option>

                                @endforeach

                            </select>

                            <div class="form-text">
                                Only subjects allocated to this class/stream
                                are displayed.
                            </div>

                        </div>


                        {{-- Teacher --}}
                        <div class="col-md-6">

                            <label class="form-label fw-semibold">
                                Teacher <span class="text-danger">*</span>
                            </label>

                            <select
                                name="teacher_id"
                                id="lesson_teacher"
                                class="form-select"
                                required
                            >

                                <option value="">
                                    Select a subject first
                                </option>

                            </select>

                            <div class="form-text">
                                Teachers are filtered by the selected
                                subject and allocation.
                            </div>

                        </div>


                        {{-- Room --}}
                        <div class="col-md-6">

                            <label class="form-label fw-semibold">
                                Room
                            </label>

                            <input
                                type="text"
                                name="room"
                                class="form-control"
                                maxlength="100"
                                placeholder="e.g. Room 1, Lab, Library"
                            >

                        </div>


                        {{-- Lesson Type --}}
                        <div class="col-md-6">

                            <label class="form-label fw-semibold">
                                Lesson Type
                                <span class="text-danger">*</span>
                            </label>

                            <select
                                name="lesson_type"
                                class="form-select"
                                required
                            >

                                <option value="regular">
                                    Regular
                                </option>

                                <option value="double">
                                    Double
                                </option>

                                <option value="practical">
                                    Practical
                                </option>

                                <option value="laboratory">
                                    Laboratory
                                </option>

                                <option value="activity">
                                    Activity
                                </option>

                            </select>

                        </div>


                        {{-- Notes --}}
                        <div class="col-12">

                            <label class="form-label fw-semibold">
                                Notes
                            </label>

                            <textarea
                                name="notes"
                                class="form-control"
                                rows="3"
                                maxlength="1000"
                                placeholder="Optional lesson notes..."
                            ></textarea>

                        </div>

                    </div>

                </div>


                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-light border"
                        data-bs-dismiss="modal"
                    >
                        Cancel
                    </button>

                    <button
                        type="submit"
                        class="btn btn-primary"
                        id="saveLessonBtn"
                    >
                        <i class="bi bi-check-lg me-1"></i>
                        Add Lesson
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


{{-- ====================================================================
     STYLES
===================================================================== --}}
<style>

    .timetable-builder-table {
        min-width: 1450px;
    }

    .timetable-builder-table th {
        font-size: 0.85rem;
        font-weight: 700;
        white-space: nowrap;
    }

    .timetable-slot {
        height: 105px;
        vertical-align: middle;
        background: #fff;
    }

    .lesson-card {
        background: #f8faff;
        border: 1px solid #dbe5ff;
        border-left: 4px solid #0d6efd;
        border-radius: 8px;
        padding: 10px;
        min-height: 85px;
    }

    .empty-slot {
        min-height: 80px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .add-slot-btn {
        opacity: 0.55;
        transition: all 0.2s ease;
    }

    .add-slot-btn:hover {
        opacity: 1;
        transform: translateY(-1px);
    }

</style>


{{-- ====================================================================
     JAVASCRIPT
===================================================================== --}}
<script>

document.addEventListener('DOMContentLoaded', function () {

    const daySelect = document.getElementById('lesson_day');
    const periodSelect = document.getElementById('lesson_period');
    const subjectSelect = document.getElementById('lesson_subject');
    const teacherSelect = document.getElementById('lesson_teacher');
    const addLessonForm = document.getElementById('addLessonForm');
    const saveLessonBtn = document.getElementById('saveLessonBtn');


    /*
    |--------------------------------------------------------------------------
    | Add From Empty Slot
    |--------------------------------------------------------------------------
    */

    document.querySelectorAll('.add-slot-btn').forEach(function (button) {

        button.addEventListener('click', function () {

            const day = this.dataset.day;
            const period = this.dataset.period;

            daySelect.value = day;
            periodSelect.value = period;

            subjectSelect.value = '';
            teacherSelect.innerHTML = `
                <option value="">
                    Select a subject first
                </option>
            `;

        });

    });


    /*
    |--------------------------------------------------------------------------
    | Load Teachers
    |--------------------------------------------------------------------------
    */

    subjectSelect.addEventListener('change', function () {

        const subjectId = this.value;

        teacherSelect.innerHTML = `
            <option value="">
                Loading teachers...
            </option>
        `;

        teacherSelect.disabled = true;


        if (!subjectId) {

            teacherSelect.innerHTML = `
                <option value="">
                    Select a subject first
                </option>
            `;

            teacherSelect.disabled = false;

            return;
        }


        const url =
            "{{ route('timetables.data.teachers') }}" +
            "?school_class_id={{ $timetableBlock->school_class_id }}" +
            "&stream_id={{ $timetableBlock->stream_id ?? '' }}" +
            "&subject_id=" + encodeURIComponent(subjectId) +
            "&academic_year_id={{ $timetableBlock->academic_year_id }}" +
            "&term_id={{ $timetableBlock->term_id }}";


        fetch(url, {
            headers: {
                'Accept': 'application/json'
            }
        })
        .then(async response => {

            const data = await response.json();

            if (!response.ok) {
                throw new Error(
                    data.message ||
                    'Unable to load teachers.'
                );
            }

            return data;

        })
        .then(function (teachers) {

            teacherSelect.innerHTML = `
                <option value="">
                    Select Teacher
                </option>
            `;


            if (!teachers.length) {

                teacherSelect.innerHTML = `
                    <option value="">
                        No allocated teacher found
                    </option>
                `;

            } else {

                teachers.forEach(function (teacher) {

                    const option =
                        document.createElement('option');

                    option.value = teacher.id;
                    option.textContent = teacher.name;

                    teacherSelect.appendChild(option);

                });

            }

            teacherSelect.disabled = false;

        })
        .catch(function (error) {

            console.error(error);

            teacherSelect.innerHTML = `
                <option value="">
                    Unable to load teachers
                </option>
            `;

            teacherSelect.disabled = false;

        });

    });


    /*
    |--------------------------------------------------------------------------
    | Submit Button
    |--------------------------------------------------------------------------
    */

    addLessonForm.addEventListener('submit', function () {

        saveLessonBtn.disabled = true;

        saveLessonBtn.innerHTML = `
            <span
                class="spinner-border spinner-border-sm me-1"
            ></span>
            Adding Lesson...
        `;

    });

});

</script>

@endsection