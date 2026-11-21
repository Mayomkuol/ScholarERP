@extends('layouts.app')

@section('content')

<div class="container-fluid py-4">

    {{-- Page Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">

        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <a href="{{ route('timetables.blocks.show', $timetableBlock) }}"
                   class="btn btn-light border btn-sm">
                    <i class="bi bi-arrow-left"></i>
                </a>

                <span class="text-muted small">
                    Timetable
                </span>
                <i class="bi bi-chevron-right text-muted small"></i>
                <span class="text-muted small">
                    Blocks
                </span>
                <i class="bi bi-chevron-right text-muted small"></i>
                <span class="small">
                    Edit
                </span>
            </div>

            <h2 class="fw-bold mb-1">
                Edit Timetable Block
            </h2>

            <p class="text-muted mb-0">
                Update the timetable block configuration and term coverage.
            </p>
        </div>

        <div class="d-flex gap-2">

            <a href="{{ route('timetables.blocks.show', $timetableBlock) }}"
               class="btn btn-outline-secondary">
                <i class="bi bi-calendar-week me-1"></i>
                View Timetable
            </a>

        </div>

    </div>


    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="alert alert-danger border-0 shadow-sm mb-4">
            <div class="d-flex align-items-start">

                <i class="bi bi-exclamation-triangle-fill fs-5 me-3"></i>

                <div>
                    <strong>Please correct the following errors:</strong>

                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>

            </div>
        </div>
    @endif


    {{-- Edit Form --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white border-bottom py-3">

            <div class="d-flex align-items-center">

                <div class="rounded-circle bg-primary bg-opacity-10 text-primary p-2 me-3">
                    <i class="bi bi-pencil-square fs-5"></i>
                </div>

                <div>
                    <h5 class="mb-0 fw-bold">
                        Timetable Block Details
                    </h5>

                    <small class="text-muted">
                        Block #{{ $timetableBlock->id }}
                    </small>
                </div>

            </div>

        </div>


        <form method="POST"
              action="{{ route('timetables.blocks.update', $timetableBlock) }}">

            @csrf
            @method('PUT')


            <div class="card-body p-4">

                <div class="row g-4">

                    {{-- Academic Year --}}
                    <div class="col-md-6">

                        <label for="academic_year_id"
                               class="form-label fw-semibold">
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

                            @foreach ($academicYears as $academicYear)

                                <option value="{{ $academicYear->id }}"
                                    {{ old(
                                        'academic_year_id',
                                        $timetableBlock->academic_year_id
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


                    {{-- Term --}}
                    <div class="col-md-6">

                        <label for="term_id"
                               class="form-label fw-semibold">

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

                            @foreach ($terms as $term)

                                <option value="{{ $term->id }}"
                                    {{ old(
                                        'term_id',
                                        $timetableBlock->term_id
                                    ) == $term->id ? 'selected' : '' }}>

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

                        <label for="school_class_id"
                               class="form-label fw-semibold">

                            School Class
                            <span class="text-danger">*</span>

                        </label>

                        <select name="school_class_id"
                                id="school_class_id"
                                class="form-select @error('school_class_id') is-invalid @enderror"
                                required>

                            <option value="">
                                Select Class
                            </option>

                            @foreach ($classes as $class)

                                <option value="{{ $class->id }}"
                                    {{ old(
                                        'school_class_id',
                                        $timetableBlock->school_class_id
                                    ) == $class->id ? 'selected' : '' }}>

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

                        <label for="stream_id"
                               class="form-label fw-semibold">

                            Stream

                            <span class="text-muted fw-normal">
                                (Optional)
                            </span>

                        </label>

                        <select name="stream_id"
                                id="stream_id"
                                class="form-select @error('stream_id') is-invalid @enderror">

                            <option value="">
                                All Streams
                            </option>

                            @foreach ($streams as $stream)

                                <option value="{{ $stream->id }}"
                                    {{ old(
                                        'stream_id',
                                        $timetableBlock->stream_id
                                    ) == $stream->id ? 'selected' : '' }}>

                                    {{ $stream->name }}

                                </option>

                            @endforeach

                        </select>

                        <div class="form-text">
                            Leave as <strong>All Streams</strong> if this timetable
                            applies to the entire class.
                        </div>

                        @error('stream_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Block Name --}}
                    <div class="col-12">

                        <label for="name"
                               class="form-label fw-semibold">

                            Timetable Block Name
                            <span class="text-danger">*</span>

                        </label>

                        <input type="text"
                               name="name"
                               id="name"
                               value="{{ old('name', $timetableBlock->name) }}"
                               class="form-control @error('name') is-invalid @enderror"
                               placeholder="e.g. Term 1 timetable"
                               maxlength="150"
                               required>

                        @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Effective From --}}
                    <div class="col-md-6">

                        <label for="effective_from"
                               class="form-label fw-semibold">

                            Effective From
                            <span class="text-danger">*</span>

                        </label>

                        <input type="date"
                               name="effective_from"
                               id="effective_from"
                               value="{{ old(
                                   'effective_from',
                                   optional($timetableBlock->effective_from)->format('Y-m-d')
                               ) }}"
                               class="form-control @error('effective_from') is-invalid @enderror"
                               required>

                        @error('effective_from')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Effective To --}}
                    <div class="col-md-6">

                        <label for="effective_to"
                               class="form-label fw-semibold">

                            Effective To
                            <span class="text-danger">*</span>

                        </label>

                        <input type="date"
                               name="effective_to"
                               id="effective_to"
                               value="{{ old(
                                   'effective_to',
                                   optional($timetableBlock->effective_to)->format('Y-m-d')
                               ) }}"
                               class="form-control @error('effective_to') is-invalid @enderror"
                               required>

                        @error('effective_to')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Status --}}
                    <div class="col-md-6">

                        <label for="status"
                               class="form-label fw-semibold">

                            Status
                            <span class="text-danger">*</span>

                        </label>

                        <select name="status"
                                id="status"
                                class="form-select @error('status') is-invalid @enderror"
                                required>

                            <option value="draft"
                                {{ old(
                                    'status',
                                    $timetableBlock->status
                                ) === 'draft' ? 'selected' : '' }}>
                                Draft
                            </option>

                            <option value="published"
                                {{ old(
                                    'status',
                                    $timetableBlock->status
                                ) === 'published' ? 'selected' : '' }}>
                                Published
                            </option>

                            <option value="archived"
                                {{ old(
                                    'status',
                                    $timetableBlock->status
                                ) === 'archived' ? 'selected' : '' }}>
                                Archived
                            </option>

                        </select>

                        @error('status')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                        <div class="form-text">
                            Published timetables should only be used after
                            all lessons have been reviewed.
                        </div>

                    </div>


                    {{-- Notes --}}
                    <div class="col-md-6">

                        <label for="notes"
                               class="form-label fw-semibold">

                            Notes
                            <span class="text-muted fw-normal">
                                (Optional)
                            </span>

                        </label>

                        <textarea name="notes"
                                  id="notes"
                                  rows="4"
                                  class="form-control @error('notes') is-invalid @enderror"
                                  maxlength="2000"
                                  placeholder="Add any additional notes about this timetable block...">{{ old('notes', $timetableBlock->notes) }}</textarea>

                        @error('notes')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>


                {{-- Warning --}}
                @if ($timetableBlock->timetables()->exists())

                    <div class="alert alert-warning border-0 mt-4 mb-0">

                        <div class="d-flex align-items-start">

                            <i class="bi bi-exclamation-triangle-fill fs-5 me-3"></i>

                            <div>

                                <strong>This timetable already contains lessons.</strong>

                                <div class="small mt-1">
                                    Changing the class, stream, academic year or term
                                    may affect the meaning of the existing lessons.
                                    Review the timetable carefully after saving.
                                </div>

                            </div>

                        </div>

                    </div>

                @endif

            </div>


            {{-- Footer --}}
            <div class="card-footer bg-white border-top p-4">

                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">

                    <a href="{{ route('timetables.blocks.show', $timetableBlock) }}"
                       class="btn btn-light border">

                        <i class="bi bi-x-lg me-1"></i>
                        Cancel

                    </a>


                    <button type="submit"
                            class="btn btn-primary px-4">

                        <i class="bi bi-check2-circle me-1"></i>
                        Update Timetable Block

                    </button>

                </div>

            </div>

        </form>

    </div>

</div>


{{-- Dynamic Stream Loading --}}
<script>

document.addEventListener('DOMContentLoaded', function () {

    const classSelect = document.getElementById('school_class_id');
    const streamSelect = document.getElementById('stream_id');

    const currentStreamId = @json(
        old('stream_id', $timetableBlock->stream_id)
    );

    if (!classSelect || !streamSelect) {
        return;
    }


    function loadStreams(classId, selectedStreamId = null) {

        streamSelect.innerHTML = `
            <option value="">
                Loading streams...
            </option>
        `;

        streamSelect.disabled = true;


        if (!classId) {

            streamSelect.innerHTML = `
                <option value="">
                    All Streams
                </option>
            `;

            streamSelect.disabled = false;

            return;
        }


        const url =
            '{{ route('timetables.data.streams') }}'
            + '?school_class_id='
            + encodeURIComponent(classId);


        fetch(url, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })

        .then(response => {

            if (!response.ok) {
                throw new Error('Unable to load streams.');
            }

            return response.json();

        })

        .then(streams => {

            streamSelect.innerHTML = `
                <option value="">
                    All Streams
                </option>
            `;


            streams.forEach(stream => {

                const option =
                    document.createElement('option');

                option.value = stream.id;
                option.textContent = stream.name;

                if (
                    selectedStreamId !== null &&
                    String(selectedStreamId) === String(stream.id)
                ) {
                    option.selected = true;
                }

                streamSelect.appendChild(option);

            });

            streamSelect.disabled = false;

        })

        .catch(error => {

            console.error(error);

            streamSelect.innerHTML = `
                <option value="">
                    Unable to load streams
                </option>
            `;

            streamSelect.disabled = false;

        });

    }


    classSelect.addEventListener('change', function () {

        loadStreams(this.value, null);

    });


    /*
     * Load streams immediately so the currently
     * selected stream is preserved.
     */
    loadStreams(
        classSelect.value,
        currentStreamId
    );

});

</script>


<style>

.card {
    border-radius: 14px;
}

.form-control,
.form-select {
    min-height: 44px;
    border-radius: 8px;
}

.form-control:focus,
.form-select:focus {
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.08);
}

.btn {
    border-radius: 8px;
}

.card-header,
.card-footer {
    border-radius: 14px;
}

.alert {
    border-radius: 10px;
}

</style>

@endsection