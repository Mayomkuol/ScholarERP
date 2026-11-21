@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="fw-bold mb-1">
                Edit Attendance
            </h4>

            <p class="text-muted mb-0">
                Update the attendance record for this student.
            </p>

        </div>

        <a href="{{ route('attendance.show', $attendance) }}"
           class="btn btn-secondary">

            <i class="bi bi-arrow-left"></i>
            Back

        </a>

    </div>


    {{-- Student Information --}}
    <div class="card shadow-sm mb-4">

        <div class="card-header bg-primary text-white">

            <h5 class="mb-0">
                Student Information
            </h5>

        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-3">

                    <small class="text-muted">
                        Admission No.
                    </small>

                    <div class="fw-bold">

                        {{ $attendance->student->admission_no ?? '-' }}

                    </div>

                </div>


                <div class="col-md-5">

                    <small class="text-muted">
                        Student
                    </small>

                    <div class="fw-bold">

                        {{ $attendance->student->first_name ?? '' }}
                        {{ $attendance->student->middle_name ?? '' }}
                        {{ $attendance->student->last_name ?? '' }}

                    </div>

                </div>


                <div class="col-md-2">

                    <small class="text-muted">
                        Class
                    </small>

                    <div class="fw-bold">

                        {{ $attendance->schoolClass->name ?? '-' }}

                    </div>

                </div>


                <div class="col-md-2">

                    <small class="text-muted">
                        Stream
                    </small>

                    <div class="fw-bold">

                        {{ $attendance->stream->name ?? '-' }}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Edit Form --}}
    <div class="card shadow-sm">

        <div class="card-header">

            <h5 class="mb-0">
                Attendance Details
            </h5>

        </div>

        <div class="card-body">

            <form method="POST"
                  action="{{ route('attendance.update', $attendance) }}">

                @csrf

                @method('PUT')


                <div class="row">


                    {{-- Academic Year --}}
                    <div class="col-md-3 mb-3">

                        <label class="form-label">
                            Academic Year
                        </label>

                        <select name="academic_year_id"
                                class="form-select"
                                disabled>

                            @foreach($years as $year)

                                <option value="{{ $year->id }}"
                                    {{ $attendance->academic_year_id == $year->id ? 'selected' : '' }}>

                                    {{ $year->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Term --}}
                    <div class="col-md-3 mb-3">

                        <label class="form-label">
                            Term
                        </label>

                        <select name="term_id"
                                class="form-select"
                                disabled>

                            @foreach($terms as $term)

                                <option value="{{ $term->id }}"
                                    {{ $attendance->term_id == $term->id ? 'selected' : '' }}>

                                    {{ $term->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Class --}}
                    <div class="col-md-3 mb-3">

                        <label class="form-label">
                            Class
                        </label>

                        <select name="school_class_id"
                                class="form-select"
                                disabled>

                            @foreach($classes as $class)

                                <option value="{{ $class->id }}"
                                    {{ $attendance->school_class_id == $class->id ? 'selected' : '' }}>

                                    {{ $class->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Date --}}
                    <div class="col-md-3 mb-3">

                        <label class="form-label">
                            Attendance Date
                        </label>

                        <input type="date"
                               class="form-control"
                               value="{{ $attendance->attendance_date }}"
                               disabled>

                    </div>


                </div>


                <hr>


                {{-- Status --}}
                <div class="mb-4">

                    <label class="form-label fw-bold">
                        Attendance Status
                    </label>

                    <div class="d-flex flex-wrap gap-4">

                        @foreach(['Present','Absent','Late','Excused','Sick'] as $status)

                            <div class="form-check">

                                <input
                                    class="form-check-input"
                                    type="radio"
                                    name="status"
                                    value="{{ $status }}"
                                    id="status{{ $status }}"
                                    {{ $attendance->status === $status ? 'checked' : '' }}
                                    required>

                                <label
                                    class="form-check-label"
                                    for="status{{ $status }}">

                                    {{ $status }}

                                </label>

                            </div>

                        @endforeach

                    </div>

                    @error('status')

                        <div class="text-danger small mt-2">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                <div class="row">


                    {{-- Arrival Time --}}
                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Arrival Time
                        </label>

                        <input
                            type="time"
                            name="arrival_time"
                            class="form-control"
                            value="{{ $attendance->arrival_time }}">

                    </div>


                    {{-- Remarks --}}
                    <div class="col-md-8 mb-3">

                        <label class="form-label">
                            Remarks
                        </label>

                        <textarea
                            name="remarks"
                            class="form-control"
                            rows="3"
                            placeholder="Enter any remarks...">{{ old('remarks', $attendance->remarks) }}</textarea>

                        @error('remarks')

                            <div class="text-danger small mt-2">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>


                {{-- Buttons --}}
                <div class="d-flex justify-content-end gap-2 mt-4">

                    <a href="{{ route('attendance.show', $attendance) }}"
                       class="btn btn-secondary">

                        Cancel

                    </a>

                    <button type="submit"
                            class="btn btn-success">

                        <i class="bi bi-check-circle"></i>

                        Update Attendance

                    </button>

                </div>


            </form>

        </div>

    </div>

</div>

@endsection