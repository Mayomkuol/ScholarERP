@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- PAGE HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="fw-bold mb-1">
                Subject Allocation
            </h4>

            <p class="text-muted mb-0">
                Assign subjects to a class and stream.
                Subjects are automatically filtered by education level.
            </p>
        </div>

    </div>


    {{-- SUCCESS MESSAGE --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    {{-- ERROR MESSAGE --}}
    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    {{-- VALIDATION ERRORS --}}
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


    {{-- ALLOCATION FORM --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <h5 class="fw-bold mb-0">
                Allocate Subjects
            </h5>

        </div>


        <div class="card-body">

            {{-- CLASS + STREAM SELECTION --}}

            <div class="row g-3">

                {{-- CLASS --}}
                <div class="col-md-5">

                    <label class="form-label fw-semibold">
                        Class
                    </label>

                    <select
                        id="school_class_id"
                        class="form-select"
                    >

                        <option value="">
                            Select Class
                        </option>

                        @foreach($classes as $class)

                            <option
                                value="{{ $class->id }}"
                                data-level="{{ $class->level }}"
                            >

                                {{ $class->name }}

                                — {{ $class->level }}

                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- STREAM --}}
                <div class="col-md-5">

                    <label class="form-label fw-semibold">
                        Stream
                    </label>

                    <select
                        id="stream_id"
                        class="form-select"
                        disabled
                    >

                        <option value="">
                            Select Stream
                        </option>

                    </select>

                </div>


                {{-- LOAD BUTTON --}}
                <div class="col-md-2 d-flex align-items-end">

                    <button
                        type="button"
                        id="loadSubjectsBtn"
                        class="btn btn-primary w-100"
                        disabled
                    >

                        <i class="bi bi-arrow-repeat me-1"></i>

                        Load Subjects

                    </button>

                </div>

            </div>


            {{-- SELECTED CLASS INFORMATION --}}
            <div
                id="classInfo"
                class="alert alert-light border mt-4 d-none"
            >

                <div class="row">

                    <div class="col-md-4">

                        <strong>Class:</strong>

                        <span id="selectedClassName"></span>

                    </div>

                    <div class="col-md-4">

                        <strong>Level:</strong>

                        <span id="selectedLevel"></span>

                    </div>

                    <div class="col-md-4">

                        <strong>Stream:</strong>

                        <span id="selectedStreamName"></span>

                    </div>

                </div>

            </div>


            {{-- SUBJECT AREA --}}
            <div
                id="subjectsArea"
                class="mt-4 d-none"
            >

                <div class="d-flex justify-content-between align-items-center mb-3">

                    <div>

                        <h6 class="fw-bold mb-1">
                            Available Subjects
                        </h6>

                        <small class="text-muted">
                            Select the subjects offered to this class and stream.
                        </small>

                    </div>


                    <div>

                        <button
                            type="button"
                            id="selectAllBtn"
                            class="btn btn-outline-primary btn-sm"
                        >

                            Select All

                        </button>

                        <button
                            type="button"
                            id="clearAllBtn"
                            class="btn btn-outline-secondary btn-sm"
                        >

                            Clear All

                        </button>

                    </div>

                </div>


                {{-- SUBJECT LIST --}}
                <div
                    id="subjectsList"
                    class="row g-3"
                >
                </div>


                {{-- NO SUBJECTS --}}
                <div
                    id="noSubjects"
                    class="alert alert-warning d-none"
                >

                    No subjects are available for this education level.

                    Please add subjects under the
                    <strong>Subjects</strong> section first.

                </div>


                {{-- ALLOCATE BUTTON --}}
                <div class="mt-4">

                    <button
                        type="button"
                        id="allocateBtn"
                        class="btn btn-success"
                        disabled
                    >

                        <i class="bi bi-check-circle me-1"></i>

                        Allocate Selected Subjects

                    </button>

                </div>

            </div>

        </div>

    </div>


    {{-- CURRENT ALLOCATIONS --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <h5 class="fw-bold mb-0">
                        Current Subject Allocations
                    </h5>

                    <small class="text-muted">
                        Subjects currently assigned to classes and streams.
                    </small>

                </div>

            </div>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th width="60">
                                #
                            </th>

                            <th>
                                Class
                            </th>

                            <th>
                                Level
                            </th>

                            <th>
                                Stream
                            </th>

                            <th>
                                Subject
                            </th>

                            <th>
                                Category
                            </th>

                            <th width="100">
                                Action
                            </th>

                        </tr>

                    </thead>

<tbody>

@forelse($allocations as $allocation)

    <tr>

        {{-- # --}}
        <td class="text-center">
            {{ $loop->iteration }}
        </td>

        {{-- CLASS --}}
        <td>
            <strong>
                {{ $allocation->schoolClass?->name ?? 'Class Missing' }}
            </strong>
        </td>

        {{-- LEVEL --}}
        <td>
            @if($allocation->schoolClass)
                @if($allocation->schoolClass->level === 'Primary')
                    <span class="badge bg-success">
                        Primary
                    </span>
                @elseif($allocation->schoolClass->level === 'Secondary')
                    <span class="badge bg-primary">
                        Secondary
                    </span>
                @else
                    <span class="badge bg-secondary">
                        {{ $allocation->schoolClass->level }}
                    </span>
                @endif
            @else
                <span class="text-danger">
                    —
                </span>
            @endif
        </td>

        {{-- STREAM --}}
        <td>
            @if($allocation->stream)
                <span class="badge bg-info">
                    {{ $allocation->stream->name }}
                </span>
            @else
                <span class="badge bg-secondary">
                    All Streams
                </span>
            @endif
        </td>

        {{-- SUBJECT --}}
        <td>

            @if($allocation->subject)

                <div class="fw-semibold">
                    {{ $allocation->subject->name }}
                </div>

                @if($allocation->subject->code)
                    <small class="text-muted">
                        {{ $allocation->subject->code }}
                    </small>
                @endif

            @else

                <span class="text-danger">
                    <i class="bi bi-exclamation-triangle me-1"></i>
                    Subject Missing
                </span>

            @endif

        </td>

        {{-- CATEGORY --}}
        <td>

            @if($allocation->subject)

                @php
                    $category = $allocation->subject->category;
                @endphp

                @if($category)

                    <span class="badge bg-primary">
                        {{ $category }}
                    </span>

                @else

                    <span class="text-muted">
                        —
                    </span>

                @endif

            @else

                <span class="text-danger">
                    —
                </span>

            @endif

        </td>

        {{-- ACTION --}}
        <td class="text-center">

            <form
                action="{{ route(
                    'subject-allocations.destroy',
                    $allocation
                ) }}"
                method="POST"
                class="d-inline"
                onsubmit="return confirm(
                    'Are you sure you want to remove this subject allocation?'
                );"
            >

                @csrf
                @method('DELETE')

                <button
                    type="submit"
                    class="btn btn-sm btn-outline-danger"
                    title="Remove Allocation"
                >
                    <i class="bi bi-trash"></i>
                </button>

            </form>

        </td>

    </tr>

@empty

    <tr>

        <td
            colspan="7"
            class="text-center text-muted py-5"
        >
            <i class="bi bi-journal-x fs-1 d-block mb-3"></i>

            No subject allocations found.
        </td>

    </tr>

@endforelse

</tbody>

{{-- ================================================================ --}}
{{-- JAVASCRIPT --}}
{{-- ================================================================ --}}

<script>

document.addEventListener('DOMContentLoaded', function () {

    const classSelect =
        document.getElementById('school_class_id');

    const streamSelect =
        document.getElementById('stream_id');

    const loadSubjectsBtn =
        document.getElementById('loadSubjectsBtn');

    const subjectsArea =
        document.getElementById('subjectsArea');

    const subjectsList =
        document.getElementById('subjectsList');

    const noSubjects =
        document.getElementById('noSubjects');

    const allocateBtn =
        document.getElementById('allocateBtn');

    const selectAllBtn =
        document.getElementById('selectAllBtn');

    const clearAllBtn =
        document.getElementById('clearAllBtn');

    const classInfo =
        document.getElementById('classInfo');

    const selectedClassName =
        document.getElementById('selectedClassName');

    const selectedLevel =
        document.getElementById('selectedLevel');

    const selectedStreamName =
        document.getElementById('selectedStreamName');


    /*
    |--------------------------------------------------------------------------
    | ROUTES
    |--------------------------------------------------------------------------
    */

    const streamsUrl =
        "{{ url('/subject-allocations/streams') }}";

    const subjectsUrl =
        "{{ url('/subject-allocations/subjects') }}";

    const allocateUrl =
        "{{ route('subject-allocations.store') }}";


    /*
    |--------------------------------------------------------------------------
    | CLASS CHANGED
    |--------------------------------------------------------------------------
    */

    classSelect.addEventListener('change', function () {

        const classId = this.value;

        streamSelect.innerHTML =
            '<option value="">Select Stream</option>';

        streamSelect.disabled = true;

        loadSubjectsBtn.disabled = true;

        subjectsArea.classList.add('d-none');

        classInfo.classList.add('d-none');

        subjectsList.innerHTML = '';

        if (!classId) {

            return;

        }


        /*
        |----------------------------------------------------------------------
        | LOAD STREAMS
        |----------------------------------------------------------------------
        */

        fetch(`${streamsUrl}/${classId}`)

            .then(response => {

                if (!response.ok) {

                    throw new Error(
                        'Unable to load streams.'
                    );

                }

                return response.json();

            })

            .then(streams => {

                if (!streams.length) {

                    streamSelect.innerHTML =
                        '<option value="">No streams available</option>';

                    return;

                }


                streams.forEach(stream => {

                    const option =
                        document.createElement('option');

                    option.value =
                        stream.id;

                    option.textContent =
                        stream.name;

                    streamSelect.appendChild(option);

                });

                streamSelect.disabled = false;

            })

            .catch(error => {

                console.error(error);

                alert(
                    'Unable to load streams for this class.'
                );

            });

    });


    /*
    |--------------------------------------------------------------------------
    | STREAM CHANGED
    |--------------------------------------------------------------------------
    */

    streamSelect.addEventListener('change', function () {

        loadSubjectsBtn.disabled =
            !this.value;

        subjectsArea.classList.add('d-none');

    });


    /*
    |--------------------------------------------------------------------------
    | LOAD SUBJECTS
    |--------------------------------------------------------------------------
    */

    loadSubjectsBtn.addEventListener('click', function () {

        const classId =
            classSelect.value;

        const streamId =
            streamSelect.value;


        if (!classId || !streamId) {

            return;

        }


        /*
        |----------------------------------------------------------------------
        | SHOW CLASS INFORMATION
        |----------------------------------------------------------------------
        */

        const selectedOption =
            classSelect.options[
                classSelect.selectedIndex
            ];

        const streamOption =
            streamSelect.options[
                streamSelect.selectedIndex
            ];

        selectedClassName.textContent =
            selectedOption.textContent.trim();

        selectedLevel.textContent =
            selectedOption.dataset.level;

        selectedStreamName.textContent =
            streamOption.textContent;

        classInfo.classList.remove('d-none');


        /*
        |----------------------------------------------------------------------
        | RESET
        |----------------------------------------------------------------------
        */

        subjectsList.innerHTML = '';

        noSubjects.classList.add('d-none');

        allocateBtn.disabled = true;

        subjectsArea.classList.remove('d-none');


        /*
        |----------------------------------------------------------------------
        | LOAD SUBJECTS
        |----------------------------------------------------------------------
        */

        fetch(
            `${subjectsUrl}/${classId}/${streamId}`
        )

        .then(response => {

            if (!response.ok) {

                throw new Error(
                    'Unable to load subjects.'
                );

            }

            return response.json();

        })

        .then(data => {

            /*
            |------------------------------------------------------------------
            | NO SUBJECTS
            |------------------------------------------------------------------
            */

            if (!data.subjects.length) {

                noSubjects.classList.remove('d-none');

                return;

            }


            /*
            |------------------------------------------------------------------
            | CREATE SUBJECT CARDS
            |------------------------------------------------------------------
            */

            data.subjects.forEach(subject => {

                const col =
                    document.createElement('div');

                col.className =
                    'col-md-4 col-lg-3';


                const card =
                    document.createElement('div');

                card.className =
                    'card border h-100';


                const body =
                    document.createElement('div');

                body.className =
                    'card-body';


                /*
                |------------------------------------------------------------------
                | CHECKBOX
                |------------------------------------------------------------------
                */

                const checkbox =
                    document.createElement('input');

                checkbox.type =
                    'checkbox';

                checkbox.className =
                    'form-check-input subject-checkbox me-2';

                checkbox.value =
                    subject.id;

                checkbox.id =
                    `subject_${subject.id}`;

                checkbox.dataset.allocated =
                    subject.allocated ? '1' : '0';


                /*
                |------------------------------------------------------------------
                | LABEL
                |------------------------------------------------------------------
                */

                const label =
                    document.createElement('label');

                label.className =
                    'form-check-label fw-semibold';

                label.htmlFor =
                    checkbox.id;

                label.textContent =
                    subject.name;


                const wrapper =
                    document.createElement('div');

                wrapper.className =
                    'form-check';


                wrapper.appendChild(
                    checkbox
                );

                wrapper.appendChild(
                    label
                );


                /*
                |------------------------------------------------------------------
                | CODE
                |------------------------------------------------------------------
                */

                const code =
                    document.createElement('div');

                code.className =
                    'small text-muted mt-2';

                code.textContent =
                    subject.code;


                /*
                |------------------------------------------------------------------
                | CATEGORY
                |------------------------------------------------------------------
                */

                const category =
                    document.createElement('div');

                category.className =
                    'mt-2';


                const badge =
                    document.createElement('span');

                badge.className =
                    subject.category === 'Compulsory'
                        ? 'badge bg-primary'
                        : 'badge bg-secondary';

                badge.textContent =
                    subject.category;


                category.appendChild(
                    badge
                );


                /*
                |------------------------------------------------------------------
                | ALREADY ALLOCATED
                |------------------------------------------------------------------
                */

                if (subject.allocated) {

                    checkbox.disabled =
                        true;

                    checkbox.checked =
                        false;

                    const allocatedBadge =
                        document.createElement('span');

                    allocatedBadge.className =
                        'badge bg-success ms-1';

                    allocatedBadge.textContent =
                        'Allocated';

                    category.appendChild(
                        allocatedBadge
                    );

                }


                body.appendChild(
                    wrapper
                );

                body.appendChild(
                    code
                );

                body.appendChild(
                    category
                );


                card.appendChild(
                    body
                );

                col.appendChild(
                    card
                );

                subjectsList.appendChild(
                    col
                );

            });


            updateAllocateButton();

        })

        .catch(error => {

            console.error(error);

            alert(
                'Unable to load subjects.'
            );

        });

    });


    /*
    |--------------------------------------------------------------------------
    | SELECT ALL
    |--------------------------------------------------------------------------
    */

    selectAllBtn.addEventListener('click', function () {

        document
            .querySelectorAll(
                '.subject-checkbox:not(:disabled)'
            )
            .forEach(function (checkbox) {

                checkbox.checked = true;

            });

        updateAllocateButton();

    });


    /*
    |--------------------------------------------------------------------------
    | CLEAR ALL
    |--------------------------------------------------------------------------
    */

    clearAllBtn.addEventListener('click', function () {

        document
            .querySelectorAll(
                '.subject-checkbox:not(:disabled)'
            )
            .forEach(function (checkbox) {

                checkbox.checked = false;

            });

        updateAllocateButton();

    });


    /*
    |--------------------------------------------------------------------------
    | CHECKBOX CHANGE
    |--------------------------------------------------------------------------
    */

    subjectsList.addEventListener(
        'change',
        function (event) {

            if (
                event.target.classList.contains(
                    'subject-checkbox'
                )
            ) {

                updateAllocateButton();

            }

        }
    );


    /*
    |--------------------------------------------------------------------------
    | ENABLE / DISABLE ALLOCATE BUTTON
    |--------------------------------------------------------------------------
    */

    function updateAllocateButton() {

        const selected =
            document.querySelectorAll(
                '.subject-checkbox:checked'
            );

        allocateBtn.disabled =
            selected.length === 0;

    }


    /*
    |--------------------------------------------------------------------------
    | ALLOCATE SELECTED SUBJECTS
    |--------------------------------------------------------------------------
    */

    allocateBtn.addEventListener('click', function () {

        const classId =
            classSelect.value;

        const streamId =
            streamSelect.value;

        const selectedSubjects =
            Array.from(
                document.querySelectorAll(
                    '.subject-checkbox:checked'
                )
            ).map(
                checkbox => checkbox.value
            );


        if (
            !classId ||
            !streamId ||
            !selectedSubjects.length
        ) {

            return;

        }


        /*
        |----------------------------------------------------------------------
        | DISABLE BUTTON
        |----------------------------------------------------------------------
        */

        allocateBtn.disabled = true;

        allocateBtn.innerHTML =
            '<span class="spinner-border spinner-border-sm me-1"></span>' +
            'Allocating...';


        /*
        |----------------------------------------------------------------------
        | SEND REQUEST
        |----------------------------------------------------------------------
        */

        fetch(allocateUrl, {

            method: 'POST',

            headers: {

                'Content-Type':
                    'application/json',

                'Accept':
                    'application/json',

                'X-CSRF-TOKEN':
                    document
                        .querySelector(
                            'meta[name="csrf-token"]'
                        )
                        .getAttribute('content'),

            },

            body: JSON.stringify({

                school_class_id:
                    classId,

                stream_id:
                    streamId,

                subject_ids:
                    selectedSubjects,

            }),

        })

        .then(response => {

            return response.json()
                .then(data => ({

                    status:
                        response.status,

                    ok:
                        response.ok,

                    data:
                        data,

                }));

        })

        .then(result => {

            if (!result.ok) {

                throw new Error(
                    result.data.message ||
                    'Unable to allocate subjects.'
                );

            }


            /*
            |------------------------------------------------------------------
            | SUCCESS
            |------------------------------------------------------------------
            */

            window.location.reload();

        })

        .catch(error => {

            console.error(error);

            alert(error.message);

            allocateBtn.disabled =
                false;

            allocateBtn.innerHTML =
                '<i class="bi bi-check-circle me-1"></i>' +
                'Allocate Selected Subjects';

            updateAllocateButton();

        });

    });

});

</script>

@endsection