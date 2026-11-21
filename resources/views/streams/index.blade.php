@extends('layouts.app')

@section('title', 'Streams')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
         PAGE HEADER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="fw-bold mb-1">
                <i class="bi bi-diagram-3 me-2"></i>
                Streams
            </h4>

            <p class="text-muted mb-0">
                Manage class streams and student capacity.
            </p>
        </div>

    </div>


    {{-- =========================================================
         SUCCESS MESSAGE
    ========================================================== --}}

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <i class="bi bi-check-circle me-2"></i>

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- =========================================================
         ERROR MESSAGE
    ========================================================== --}}

    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            <i class="bi bi-exclamation-triangle me-2"></i>

            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- =========================================================
         VALIDATION ERRORS
    ========================================================== --}}

    @if($errors->any())

        <div class="alert alert-danger">

            <strong>
                Please correct the following:
            </strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- =========================================================
         ADD STREAM
    ========================================================== --}}

    <div class="card shadow-sm border-0 mb-4">

        <div class="card-header bg-success text-white">

            <h5 class="mb-0">

                <i class="bi bi-plus-circle me-2"></i>

                Add New Stream

            </h5>

        </div>


        <div class="card-body">

            <form
                method="POST"
                action="{{ route('streams.store') }}">

                @csrf


                <div class="row g-3">

                    {{-- CLASS --}}

                    <div class="col-md-3">

                        <label class="form-label fw-semibold">
                            Class <span class="text-danger">*</span>
                        </label>

                        <select
                            name="school_class_id"
                            class="form-select"
                            required>

                            <option value="">
                                Select Class
                            </option>

                            @foreach($classes as $class)

                                <option
                                    value="{{ $class->id }}"
                                    {{ old('school_class_id') == $class->id ? 'selected' : '' }}>

                                    {{ $class->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- STREAM NAME --}}

                    <div class="col-md-3">

                        <label class="form-label fw-semibold">
                            Stream Name <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            class="form-control"
                            placeholder="Blue"
                            required>

                        <small class="text-muted">
                            Example: Blue, Red, Green
                        </small>

                    </div>


                    {{-- CODE --}}

                    <div class="col-md-2">

                        <label class="form-label fw-semibold">
                            Code <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="code"
                            value="{{ old('code') }}"
                            class="form-control"
                            placeholder="B"
                            maxlength="20"
                            required>

                    </div>


                    {{-- CAPACITY --}}

                    <div class="col-md-2">

                        <label class="form-label fw-semibold">
                            Capacity <span class="text-danger">*</span>
                        </label>

                        <input
                            type="number"
                            name="capacity"
                            value="{{ old('capacity', 40) }}"
                            class="form-control"
                            min="1"
                            required>

                    </div>


                    {{-- STATUS --}}

                    <div class="col-md-2">

                        <label class="form-label fw-semibold">
                            Status
                        </label>

                        <select
                            name="status"
                            class="form-select">

                            <option value="Active">
                                Active
                            </option>

                            <option value="Inactive">
                                Inactive
                            </option>

                        </select>

                    </div>


                    {{-- DESCRIPTION --}}

                    <div class="col-md-10">

                        <label class="form-label fw-semibold">
                            Description
                        </label>

                        <textarea
                            name="description"
                            class="form-control"
                            rows="2"
                            placeholder="Optional description...">{{ old('description') }}</textarea>

                    </div>


                    {{-- BUTTON --}}

                    <div class="col-md-2 d-flex align-items-end">

                        <button
                            type="submit"
                            class="btn btn-success w-100">

                            <i class="bi bi-plus-circle me-1"></i>

                            Add Stream

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- =========================================================
         STREAM LIST
    ========================================================== --}}

    <div class="card shadow-sm border-0">

        <div class="card-header bg-white">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <h5 class="mb-0 fw-bold">
                        All Streams
                    </h5>

                    <small class="text-muted">
                        Streams are identified by class and stream name.
                    </small>

                </div>

                <span class="badge bg-primary">
                    {{ $streams->count() }} Streams
                </span>

            </div>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th class="ps-3">
                                #
                            </th>

                            <th>
                                Stream
                            </th>

                            <th>
                                Code
                            </th>

                            <th>
                                Capacity
                            </th>

                            <th>
                                Students
                            </th>

                            <th>
                                Available
                            </th>

                            <th>
                                Status
                            </th>

                            <th class="text-end pe-3">
                                Actions
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($streams as $stream)

                            @php

                                $studentCount =
                                    $stream->students_count;

                                $available =
                                    max(
                                        0,
                                        $stream->capacity -
                                        $studentCount
                                    );

                                $isFull =
                                    $studentCount >=
                                    $stream->capacity;

                            @endphp


                            <tr>

                                <td class="ps-3">
                                    {{ $loop->iteration }}
                                </td>


                                {{-- STREAM NAME --}}

                                <td>

                                    <div class="fw-bold">

                                        {{ $stream->display_name }}

                                    </div>

                                    <small class="text-muted">

                                        {{ $stream->schoolClass->name }}

                                    </small>

                                </td>


                                {{-- CODE --}}

                                <td>

                                    <span class="badge bg-secondary">

                                        {{ strtoupper($stream->code) }}

                                    </span>

                                </td>


                                {{-- CAPACITY --}}

                                <td>

                                    {{ $stream->capacity }}

                                </td>


                                {{-- STUDENTS --}}

                                <td>

                                    <span class="fw-semibold">

                                        {{ $studentCount }}

                                    </span>

                                </td>


                                {{-- AVAILABLE --}}

                                <td>

                                    @if($isFull)

                                        <span class="badge bg-danger">

                                            Full

                                        </span>

                                    @else

                                        <span class="badge bg-success">

                                            {{ $available }} spaces

                                        </span>

                                    @endif

                                </td>


                                {{-- STATUS --}}

                                <td>

                                    @if($stream->status === 'Active')

                                        <span class="badge bg-success">

                                            Active

                                        </span>

                                    @else

                                        <span class="badge bg-secondary">

                                            Inactive

                                        </span>

                                    @endif

                                </td>


                                {{-- ACTIONS --}}

                                <td class="text-end pe-3">

                                    <a
                                        href="{{ route('streams.edit', $stream) }}"
                                        class="btn btn-sm btn-outline-primary">

                                        <i class="bi bi-pencil"></i>

                                        Edit

                                    </a>


                                    @if($studentCount == 0)

                                        <form
                                            action="{{ route('streams.destroy', $stream) }}"
                                            method="POST"
                                            class="d-inline">

                                            @csrf

                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="btn btn-sm btn-outline-danger"
                                                onclick="return confirm('Are you sure you want to delete {{ $stream->display_name }}?')">

                                                <i class="bi bi-trash"></i>

                                                Delete

                                            </button>

                                        </form>

                                    @else

                                        <button
                                            type="button"
                                            class="btn btn-sm btn-outline-secondary"
                                            disabled
                                            title="Students are assigned to this stream">

                                            <i class="bi bi-lock"></i>

                                        </button>

                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="8"
                                    class="text-center py-5">

                                    <i class="bi bi-diagram-3 fs-1 text-muted"></i>

                                    <h6 class="mt-3">
                                        No streams found
                                    </h6>

                                    <p class="text-muted mb-0">
                                        Create your first stream above.
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

@endsection