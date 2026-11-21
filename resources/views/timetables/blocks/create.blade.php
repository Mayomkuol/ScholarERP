@extends('layouts.app')

@section('content')

<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">

        <div>
            <h4 class="fw-bold mb-1">
                <i class="bi bi-calendar-plus me-2"></i>
                Create Timetable Block
            </h4>

            <p class="text-muted mb-0">
                Create a timetable schedule that applies across a defined term period.
            </p>
        </div>

        <a
            href="{{ route('timetables.blocks.index') }}"
            class="btn btn-light border"
        >
            <i class="bi bi-arrow-left me-1"></i>
            Back to Blocks
        </a>

    </div>


    {{-- Validation --}}
    @if($errors->any())

        <div class="alert alert-danger">

            <div class="fw-semibold mb-2">
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


    <form
        method="POST"
        action="{{ route('timetables.blocks.store') }}"
        id="blockForm"
    >

        @csrf


        <div class="row g-4">

            {{-- Main Information --}}
            <div class="col-xl-8">

                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white border-0 py-3">

                        <h6 class="fw-bold mb-1">
                            <i class="bi bi-info-circle me-2 text-primary"></i>
                            Block Information
                        </h6>

                        <small class="text-muted">
                            Define the academic period and class this timetable block belongs to.
                        </small>

                    </div>


                    <div class="card-body">

                        <div class="row g-3">

                            {{-- Academic Year --}}
                            <div class="col-md-6">

                                <label class="form-label fw-semibold">
                                    Academic Year
                                    <span class="text-danger">*</span>
                                </label>

                                <select
                                    name="academic_year_id"
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
                            <div class="col-md-6">

                                <label class="form-label fw-semibold">
                                    Term
                                    <span class="text-danger">*</span>
                                </label>

                                <select
                                    name="term_id"
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


                            {{-- Class --}}
                            <div class="col-md-6">

                                <label class="form-label fw-semibold">
                                    School Class
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

                                @error('school_class_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- Stream --}}
                            <div class="col-md-6">

                                <label class="form-label fw-semibold">
                                    Stream
                                </label>

                                <select
                                    name="stream_id"
                                    id="stream_id"
                                    class="form-select @error('stream_id') is-invalid @enderror"
                                    disabled
                                >

                                    <option value="">
                                        All Streams
                                    </option>

                                </select>

                                <div class="form-text">
                                    Leave as All Streams for a class-wide timetable.
                                </div>

                                @error('stream_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- Name --}}
                            <div class="col-12">

                                <label class="form-label fw-semibold">
                                    Block Name
                                    <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="text"
                                    name="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name') }}"
                                    placeholder="e.g. Term 1 Class 7 Green Timetable"
                                    required
                                >

                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                        </div>

                    </div>

                </div>


                {{-- Effective Period --}}
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white border-0 py-3">

                        <h6 class="fw-bold mb-1">
                            <i class="bi bi-calendar-range me-2 text-primary"></i>
                            Effective Period
                        </h6>

                        <small class="text-muted">
                            Define when this timetable block applies.
                        </small>

                    </div>


                    <div class="card-body">

                        <div class="row g-3">

                            <div class="col-md-6">

                                <label class="form-label fw-semibold">
                                    Effective From
                                    <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="date"
                                    name="effective_from"
                                    class="form-control @error('effective_from') is-invalid @enderror"
                                    value="{{ old('effective_from') }}"
                                    required
                                >

                                @error('effective_from')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            <div class="col-md-6">

                                <label class="form-label fw-semibold">
                                    Effective To
                                    <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="date"
                                    name="effective_to"
                                    class="form-control @error('effective_to') is-invalid @enderror"
                                    value="{{ old('effective_to') }}"
                                    required
                                >

                                @error('effective_to')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                        </div>

                    </div>

                </div>


                {{-- Notes --}}
                <div class="card border-0 shadow-sm">

                    <div class="card-header bg-white border-0 py-3">

                        <h6 class="fw-bold mb-1">
                            <i class="bi bi-journal-text me-2 text-primary"></i>
                            Notes
                        </h6>

                    </div>


                    <div class="card-body">

                        <textarea
                            name="notes"
                            rows="4"
                            class="form-control @error('notes') is-invalid @enderror"
                            placeholder="Optional notes about this timetable block..."
                        >{{ old('notes') }}</textarea>

                        @error('notes')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>

            </div>


            {{-- Side Panel --}}
            <div class="col-xl-4">

                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white border-0 py-3">

                        <h6 class="fw-bold mb-1">
                            <i class="bi bi-sliders me-2 text-primary"></i>
                            Block Status
                        </h6>

                    </div>


                    <div class="card-body">

                        <label class="form-label fw-semibold">
                            Status
                        </label>

                        <select
                            name="status"
                            id="status"
                            class="form-select"
                        >

                            <option
                                value="draft"
                                @selected(old('status', 'draft') === 'draft')
                            >
                                Draft
                            </option>

                            <option
                                value="published"
                                @selected(old('status') === 'published')
                            >
                                Published
                            </option>

                        </select>


                        <div class="alert alert-light border mt-3 mb-0">

                            <div class="fw-semibold mb-1">
                                <i class="bi bi-lightbulb me-1"></i>
                                Recommended
                            </div>

                            <small class="text-muted">
                                Create the block as Draft while building the timetable.
                                Publish it only after all lessons and clash checks are complete.
                            </small>

                        </div>

                    </div>

                </div>


                {{-- Summary --}}
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white border-0 py-3">

                        <h6 class="fw-bold mb-0">
                            Block Summary
                        </h6>

                    </div>

                    <div class="card-body">

                        <div class="d-flex justify-content-between mb-3">

                            <span class="text-muted">
                                Academic Year
                            </span>

                            <span
                                class="fw-semibold"
                                id="summaryYear"
                            >
                                —
                            </span>

                        </div>


                        <div class="d-flex justify-content-between mb-3">

                            <span class="text-muted">
                                Term
                            </span>

                            <span
                                class="fw-semibold"
                                id="summaryTerm"
                            >
                                —
                            </span>

                        </div>


                        <div class="d-flex justify-content-between mb-3">

                            <span class="text-muted">
                                Class
                            </span>

                            <span
                                class="fw-semibold"
                                id="summaryClass"
                            >
                                —
                            </span>

                        </div>


                        <div class="d-flex justify-content-between">

                            <span class="text-muted">
                                Stream
                            </span>

                            <span
                                class="fw-semibold"
                                id="summaryStream"
                            >
                                All Streams
                            </span>

                        </div>

                    </div>

                </div>


                {{-- Actions --}}
                <div class="card border-0 shadow-sm">

                    <div class="card-body">

                        <button
                            type="submit"
                            id="saveBlockBtn"
                            class="btn btn-primary w-100 mb-2"
                        >
                            <i class="bi bi-check-lg me-1"></i>
                            Create Timetable Block
                        </button>


                        <a
                            href="{{ route('timetables.blocks.index') }}"
                            class="btn btn-light border w-100"
                        >
                            Cancel
                        </a>

                    </div>

                </div>

            </div>

        </div>

    </form>

</div>


<script>

document.addEventListener('DOMContentLoaded', function () {

    const classSelect = document.getElementById('school_class_id');
    const streamSelect = document.getElementById('stream_id');

    const yearSelect = document.querySelector(
        '[name="academic_year_id"]'
    );

    const termSelect = document.querySelector(
        '[name="term_id"]'
    );

    const summaryYear = document.getElementById('summaryYear');
    const summaryTerm = document.getElementById('summaryTerm');
    const summaryClass = document.getElementById('summaryClass');
    const summaryStream = document.getElementById('summaryStream');

    const oldStreamId = @json(old('stream_id'));


    function updateSummary() {

        summaryYear.textContent =
            yearSelect.options[yearSelect.selectedIndex]?.text || '—';

        summaryTerm.textContent =
            termSelect.options[termSelect.selectedIndex]?.text || '—';

        summaryClass.textContent =
            classSelect.options[classSelect.selectedIndex]?.text || '—';

        summaryStream.textContent =
            streamSelect.options[streamSelect.selectedIndex]?.text ||
            'All Streams';
    }


    async function loadStreams() {

        const classId = classSelect.value;

        streamSelect.innerHTML = `
            <option value="">
                All Streams
            </option>
        `;

        streamSelect.disabled = true;

        updateSummary();

        if (!classId) {
            return;
        }


        try {

            const response = await fetch(
                `{{ route('timetables.data.streams') }}?school_class_id=${classId}`,
                {
                    headers: {
                        'Accept': 'application/json'
                    }
                }
            );

            if (!response.ok) {
                throw new Error('Unable to load streams.');
            }

            const streams = await response.json();


            streams.forEach(stream => {

                const option = document.createElement('option');

                option.value = stream.id;
                option.textContent = stream.name;

                if (
                    oldStreamId &&
                    String(oldStreamId) === String(stream.id)
                ) {
                    option.selected = true;
                }

                streamSelect.appendChild(option);

            });


            streamSelect.disabled = false;

            updateSummary();

        } catch (error) {

            console.error(error);

            streamSelect.innerHTML = `
                <option value="">
                    Unable to load streams
                </option>
            `;

            streamSelect.disabled = true;
        }
    }


    classSelect.addEventListener(
        'change',
        loadStreams
    );

    yearSelect.addEventListener(
        'change',
        updateSummary
    );

    termSelect.addEventListener(
        'change',
        updateSummary
    );

    streamSelect.addEventListener(
        'change',
        updateSummary
    );


    if (classSelect.value) {
        loadStreams();
    }

    updateSummary();


    document.getElementById('blockForm')
        .addEventListener('submit', function () {

            const button =
                document.getElementById('saveBlockBtn');

            button.disabled = true;

            button.innerHTML = `
                <span
                    class="spinner-border spinner-border-sm me-2"
                ></span>
                Creating Block...
            `;

        });

});

</script>

@endsection