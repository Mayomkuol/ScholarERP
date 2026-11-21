@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="card shadow">

        <div class="card-header bg-warning">

            <h4 class="mb-0">
                Edit Teacher Allocation
            </h4>

        </div>

        <div class="card-body">

            @if ($errors->any())

                <div class="alert alert-danger">

                    <strong>Please fix the following errors:</strong>

                    <ul class="mb-0 mt-2">

                        @foreach($errors->all() as $error)

                            <li>{{ $error }}</li>

                        @endforeach

                    </ul>

                </div>

            @endif

            <form action="{{ route('teacher-allocations.update', $teacherAllocation->id) }}"
                  method="POST">

                @csrf
                @method('PUT')

                <div class="row">

                    <!-- Teacher -->

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Teacher <span class="text-danger">*</span>
                        </label>

                        <select name="teacher_id"
                                class="form-select"
                                required>

                            @foreach($teachers as $teacher)

                                <option value="{{ $teacher->id }}"
                                    {{ old('teacher_id', $teacherAllocation->teacher_id) == $teacher->id ? 'selected' : '' }}>

                                    {{ $teacher->employee_no }}
                                    -
                                    {{ $teacher->first_name }}
                                    {{ $teacher->last_name }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    <!-- Subject -->

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Subject <span class="text-danger">*</span>
                        </label>

                        <select name="subject_id"
                                class="form-select"
                                required>

                            @foreach($subjects as $subject)

                                <option value="{{ $subject->id }}"
                                    {{ old('subject_id', $teacherAllocation->subject_id) == $subject->id ? 'selected' : '' }}>

                                    {{ $subject->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    <!-- Academic Year -->

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Academic Year <span class="text-danger">*</span>
                        </label>

                        <select name="academic_year_id"
                                class="form-select"
                                required>

                            @foreach($years as $year)

                                <option value="{{ $year->id }}"
                                    {{ old('academic_year_id', $teacherAllocation->academic_year_id) == $year->id ? 'selected' : '' }}>

                                    {{ $year->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    <!-- Term -->

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Term <span class="text-danger">*</span>
                        </label>

                        <select name="term_id"
                                class="form-select"
                                required>

                            @foreach($terms as $term)

                                <option value="{{ $term->id }}"
                                    {{ old('term_id', $teacherAllocation->term_id) == $term->id ? 'selected' : '' }}>

                                    {{ $term->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    <!-- Class -->

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Class <span class="text-danger">*</span>
                        </label>

                        <select name="school_class_id"
                                id="school_class_id"
                                class="form-select"
                                required>

                            <option value="">
                                Select Class
                            </option>

                            @foreach($classes as $class)

                                <option value="{{ $class->id }}"
                                    {{ old('school_class_id', $teacherAllocation->school_class_id) == $class->id ? 'selected' : '' }}>

                                    {{ $class->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    <!-- Stream -->

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Stream
                        </label>

                        <select name="stream_id"
                                id="stream_id"
                                class="form-select">

                            <option value="">
                                No Stream
                            </option>

                            @foreach($streams as $stream)

                                <option value="{{ $stream->id }}"
                                        data-class="{{ $stream->school_class_id }}"
                                    {{ old('stream_id', $teacherAllocation->stream_id) == $stream->id ? 'selected' : '' }}>

                                    {{ $stream->name }}

                                </option>

                            @endforeach

                        </select>

                        <small class="text-muted">
                            Only streams belonging to the selected class are available.
                        </small>

                    </div>

                </div>

                <hr>

                <div class="text-end">

                    <a href="{{ route('teacher-allocations.index') }}"
                       class="btn btn-secondary">

                        Cancel

                    </a>

                    <button type="submit"
                            class="btn btn-success">

                        <i class="bi bi-check-circle"></i>

                        Update Allocation

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

<script>

document.addEventListener('DOMContentLoaded', function () {

    const classSelect = document.getElementById('school_class_id');

    const streamSelect = document.getElementById('stream_id');

    function filterStreams() {

        const selectedClass = classSelect.value;

        Array.from(streamSelect.options).forEach(function (option) {

            if (option.value === '') {

                option.hidden = false;

                return;
            }

            const streamClass = option.dataset.class;

            if (streamClass === selectedClass) {

                option.hidden = false;

            } else {

                option.hidden = true;

            }

        });

        const selectedOption =
            streamSelect.options[streamSelect.selectedIndex];

        if (
            selectedOption &&
            selectedOption.value !== '' &&
            selectedOption.hidden
        ) {

            streamSelect.value = '';

        }

    }

    classSelect.addEventListener('change', filterStreams);

    filterStreams();

});

</script>

@endsection