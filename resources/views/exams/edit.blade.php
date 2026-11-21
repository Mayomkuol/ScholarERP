```blade
@extends('layouts.app')

@section('title', 'Edit Examination')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3>Edit Examination</h3>
            <p class="text-muted mb-0">Update examination details.</p>
        </div>

        <a href="{{ route('exams.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back to Exams
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Please correct the following errors:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header">
            <h5 class="mb-0">Examination Information</h5>
        </div>

        <div class="card-body">

            <form action="{{ route('exams.update', $exam) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label">Examination Name</label>
                        <input
                            type="text"
                            name="name"
                            class="form-control"
                            value="{{ old('name', $exam->name) }}"
                            required
                        >
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Examination Type</label>
                        <select name="exam_type_id" class="form-select" required>
                            <option value="">Select Exam Type</option>
                            @foreach($types as $type)
                                <option
                                    value="{{ $type->id }}"
                                    @selected(old('exam_type_id', $exam->exam_type_id) == $type->id)
                                >
                                    {{ $type->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Academic Year</label>
                        <select name="academic_year_id" class="form-select" required>
                            <option value="">Select Academic Year</option>
                            @foreach($years as $year)
                                <option
                                    value="{{ $year->id }}"
                                    @selected(old('academic_year_id', $exam->academic_year_id) == $year->id)
                                >
                                    {{ $year->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Term</label>
                        <select name="term_id" class="form-select" required>
                            <option value="">Select Term</option>
                            @foreach($terms as $term)
                                <option
                                    value="{{ $term->id }}"
                                    @selected(old('term_id', $exam->term_id) == $term->id)
                                >
                                    {{ $term->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Class</label>
                        <select name="school_class_id" class="form-select" required>
                            <option value="">Select Class</option>
                            @foreach($classes as $class)
                                <option
                                    value="{{ $class->id }}"
                                    @selected(old('school_class_id', $exam->school_class_id) == $class->id)
                                >
                                    {{ $class->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" required>
                            @foreach(['Draft', 'Open', 'Closed', 'Published'] as $status)
                                <option
                                    value="{{ $status }}"
                                    @selected(old('status', $exam->status) == $status)
                                >
                                    {{ $status }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Start Date</label>
                        <input
                            type="date"
                            name="start_date"
                            class="form-control"
                            value="{{ old('start_date', $exam->start_date?->format('Y-m-d')) }}"
                            required
                        >
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">End Date</label>
                        <input
                            type="date"
                            name="end_date"
                            class="form-control"
                            value="{{ old('end_date', $exam->end_date?->format('Y-m-d')) }}"
                            required
                        >
                    </div>

                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('exams.index') }}" class="btn btn-light">
                        Cancel
                    </a>

                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle"></i> Update Examination
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>
@endsection
```