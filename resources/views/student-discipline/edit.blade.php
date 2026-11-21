@extends('layouts.app')

@section('title', 'Edit Discipline Record')

@section('content')

<div class="container-fluid py-4">
{{-- Page Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h4 class="mb-1">
            <i class="bi bi-pencil-square me-2"></i>
            Edit Discipline Record
        </h4>

        <p class="text-muted mb-0">
            {{ $student->first_name }}
            {{ $student->middle_name }}
            {{ $student->last_name }}
            —
            {{ $student->admission_no }}
        </p>
    </div>

    <a
        href="{{ route(
            'students.discipline.show',
            [
                'student' => $student,
                'discipline' => $discipline
            ]
        ) }}"
        class="btn btn-outline-secondary"
    >
        <i class="bi bi-arrow-left me-1"></i>
        Back to Record
    </a>

</div>

{{-- Validation Errors --}}
@if ($errors->any())

    <div class="alert alert-danger">

        <strong>
            Please correct the following:
        </strong>

        <ul class="mb-0 mt-2">

            @foreach ($errors->all() as $error)

                <li>
                    {{ $error }}
                </li>

            @endforeach

        </ul>

    </div>

@endif

{{-- Form --}}
<div class="card border-0 shadow-sm">

    <div class="card-header bg-white py-3">

        <h5 class="mb-0">
            Discipline Information
        </h5>

    </div>

    <div class="card-body">

        <form
            method="POST"
            action="{{ route(
                'students.discipline.update',
                [
                    'student' => $student,
                    'discipline' => $discipline
                ]
            ) }}"
        >

            @csrf
            @method('PUT')

            <div class="row g-3">

                {{-- Incident Date --}}
                <div class="col-md-6">

                    <label
                        for="incident_date"
                        class="form-label"
                    >
                        Incident Date
                        <span class="text-danger">*</span>
                    </label>

                    <input
                        type="date"
                        name="incident_date"
                        id="incident_date"
                        class="form-control @error('incident_date') is-invalid @enderror"
                        value="{{ old(
                            'incident_date',
                            $discipline->incident_date?->format('Y-m-d')
                        ) }}"
                        required
                    >

                    @error('incident_date')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>

                {{-- Category --}}
                <div class="col-md-6">

                    <label
                        for="category"
                        class="form-label"
                    >
                        Category
                        <span class="text-danger">*</span>
                    </label>

                    <select
                        name="category"
                        id="category"
                        class="form-select @error('category') is-invalid @enderror"
                        required
                    >

                        <option value="">
                            Select category
                        </option>

                        @php
                            $categories = [
                                'Late Coming',
                                'Absenteeism',
                                'Misconduct',
                                'Bullying',
                                'Fighting',
                                'Academic',
                                'Other',
                            ];
                        @endphp

                        @foreach ($categories as $category)

                            <option
                                value="{{ $category }}"
                                @selected(
                                    old(
                                        'category',
                                        $discipline->category
                                    ) === $category
                                )
                            >
                                {{ $category }}
                            </option>

                        @endforeach

                    </select>

                    @error('category')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>

                {{-- Incident --}}
                <div class="col-12">

                    <label
                        for="incident"
                        class="form-label"
                    >
                        Incident Description
                        <span class="text-danger">*</span>
                    </label>

                    <textarea
                        name="incident"
                        id="incident"
                        rows="6"
                        class="form-control @error('incident') is-invalid @enderror"
                        placeholder="Describe what happened..."
                        required
                    >{{ old('incident', $discipline->incident) }}</textarea>

                    @error('incident')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>

                {{-- Action Taken --}}
                <div class="col-12">

                    <label
                        for="action_taken"
                        class="form-label"
                    >
                        Action Taken
                    </label>

                    <textarea
                        name="action_taken"
                        id="action_taken"
                        rows="5"
                        class="form-control @error('action_taken') is-invalid @enderror"
                        placeholder="Describe the action taken..."
                    >{{ old(
                        'action_taken',
                        $discipline->action_taken
                    ) }}</textarea>

                    @error('action_taken')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>

                {{-- Status --}}
                <div class="col-md-6">

                    <label
                        for="status"
                        class="form-label"
                    >
                        Status
                        <span class="text-danger">*</span>
                    </label>

                    <select
                        name="status"
                        id="status"
                        class="form-select @error('status') is-invalid @enderror"
                        required
                    >

                        <option
                            value="Pending"
                            @selected(
                                old(
                                    'status',
                                    $discipline->status
                                ) === 'Pending'
                            )
                        >
                            Pending
                        </option>

                        <option
                            value="Resolved"
                            @selected(
                                old(
                                    'status',
                                    $discipline->status
                                ) === 'Resolved'
                            )
                        >
                            Resolved
                        </option>

                    </select>

                    @error('status')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>

            </div>

            {{-- Actions --}}
            <div class="d-flex justify-content-end gap-2 mt-4">

                <a
                    href="{{ route(
                        'students.discipline.show',
                        [
                            'student' => $student,
                            'discipline' => $discipline
                        ]
                    ) }}"
                    class="btn btn-light"
                >
                    Cancel
                </a>

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    <i class="bi bi-save me-1"></i>
                    Update Discipline Record
                </button>

            </div>

        </form>

    </div>

</div>

</div>

@endsection
