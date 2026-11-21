@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}

    <div class="mb-4">

        <h4 class="fw-bold mb-1">
            Subjects
        </h4>

        <p class="text-muted mb-0">
            Manage the subjects offered by the school.
        </p>

    </div>


    {{-- =========================================================
        ALERTS
    ========================================================== --}}

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <i class="bi bi-check-circle me-1"></i>

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            <i class="bi bi-exclamation-triangle me-1"></i>

            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    @if($errors->any())

        <div class="alert alert-danger alert-dismissible fade show">

            <strong>
                Please correct the following:
            </strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    {{-- =========================================================
        ADD SUBJECT
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white py-3">

            <h5 class="fw-bold mb-0">

                <i class="bi bi-book me-1"></i>

                Add New Subject

            </h5>

        </div>


        <div class="card-body">

            <form
                method="POST"
                action="{{ route('subjects.store') }}"
            >

                @csrf


                <div class="row g-3">


                    {{-- =================================================
                        SUBJECT NAME
                    ================================================== --}}

                    <div class="col-md-4">

                        <label
                            for="name"
                            class="form-label fw-semibold"
                        >
                            Subject Name
                        </label>

                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name') }}"
                            class="form-control"
                            placeholder="e.g. Mathematics"
                            required
                        >

                    </div>


                    {{-- =================================================
                        SUBJECT CODE
                    ================================================== --}}

                    <div class="col-md-2">

                        <label
                            for="code"
                            class="form-label fw-semibold"
                        >
                            Subject Code
                        </label>

                        <input
                            type="text"
                            id="code"
                            name="code"
                            value="{{ old('code') }}"
                            class="form-control"
                            placeholder="e.g. MAT"
                            required
                        >

                    </div>


                    {{-- =================================================
                        LEVEL
                    ================================================== --}}

                    <div class="col-md-2">

                        <label
                            for="level"
                            class="form-label fw-semibold"
                        >
                            Education Level
                        </label>

                        <select
                            id="level"
                            name="level"
                            class="form-select"
                            required
                        >

                            <option value="">
                                Select Level
                            </option>

                            <option
                                value="Primary"
                                @selected(old('level') === 'Primary')
                            >
                                Primary
                            </option>

                            <option
                                value="Secondary"
                                @selected(old('level') === 'Secondary')
                            >
                                Secondary
                            </option>

                            <option
                                value="College"
                                @selected(old('level') === 'College')
                            >
                                College
                            </option>

                        </select>

                    </div>


                    {{-- =================================================
                        CATEGORY
                    ================================================== --}}

                    <div class="col-md-2">

                        <label
                            for="category"
                            class="form-label fw-semibold"
                        >
                            Category
                        </label>

                        <select
                            id="category"
                            name="category"
                            class="form-select"
                            required
                        >

                            <option
                                value="Compulsory"
                                @selected(
                                    old('category', 'Compulsory') === 'Compulsory'
                                )
                            >
                                Compulsory
                            </option>

                            <option
                                value="Optional"
                                @selected(
                                    old('category') === 'Optional'
                                )
                            >
                                Optional
                            </option>

                        </select>

                    </div>


                    {{-- =================================================
                        BUTTON
                    ================================================== --}}

                    <div class="col-md-2 d-flex align-items-end">

                        <button
                            type="submit"
                            class="btn btn-primary w-100"
                        >

                            <i class="bi bi-plus-circle me-1"></i>

                            Add Subject

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- =========================================================
        SUBJECT LIST
    ========================================================== --}}

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white py-3">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <h5 class="fw-bold mb-1">
                        Subject List
                    </h5>

                    <small class="text-muted">
                        Subjects available for allocation to classes and streams.
                    </small>

                </div>


                {{-- TOTAL --}}

                <span class="badge bg-dark">

                    {{ $subjects->count() }}

                    {{ Str::plural('Subject', $subjects->count()) }}

                </span>

            </div>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th
                                width="60"
                                class="text-center"
                            >
                                #
                            </th>

                            <th>
                                Subject
                            </th>

                            <th>
                                Code
                            </th>

                            <th>
                                Level
                            </th>

                            <th>
                                Category
                            </th>

                            <th>
                                Status
                            </th>

                            <th
                                width="100"
                                class="text-center"
                            >
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($subjects as $subject)

                            <tr>

                                {{-- NUMBER --}}

                                <td class="text-center">

                                    {{ $loop->iteration }}

                                </td>


                                {{-- SUBJECT --}}

                                <td>

                                    <span class="fw-semibold">

                                        {{ $subject->name }}

                                    </span>

                                </td>


                                {{-- CODE --}}

                                <td>

                                    <span class="text-muted">

                                        {{ $subject->code }}

                                    </span>

                                </td>


                                {{-- LEVEL --}}

                                <td>

                                    @switch($subject->level)

                                        @case('Primary')

                                            <span class="badge bg-success">
                                                Primary
                                            </span>

                                            @break

                                        @case('Secondary')

                                            <span class="badge bg-primary">
                                                Secondary
                                            </span>

                                            @break

                                        @case('College')

                                            <span class="badge bg-dark">
                                                College
                                            </span>

                                            @break

                                        @default

                                            <span class="badge bg-secondary">
                                                —
                                            </span>

                                    @endswitch

                                </td>


                                {{-- CATEGORY --}}

                                <td>

                                    @if($subject->category === 'Compulsory')

                                        <span class="badge bg-info text-dark">
                                            Compulsory
                                        </span>

                                    @else

                                        <span class="badge bg-secondary">
                                            Optional
                                        </span>

                                    @endif

                                </td>


                                {{-- STATUS --}}

                                <td>

                                    @if($subject->active)

                                        <span class="badge bg-success">

                                            <i class="bi bi-check-circle me-1"></i>

                                            Active

                                        </span>

                                    @else

                                        <span class="badge bg-danger">

                                            <i class="bi bi-x-circle me-1"></i>

                                            Inactive

                                        </span>

                                    @endif

                                </td>


                                {{-- ACTION --}}

                                <td class="text-center">

                                    <form
                                        action="{{ route('subjects.destroy', $subject) }}"
                                        method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete {{ addslashes($subject->name) }}?');"
                                    >

                                        @csrf

                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="btn btn-outline-danger btn-sm"
                                            title="Delete Subject"
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
                                    class="text-center py-5"
                                >

                                    <div class="text-muted">

                                        <i class="bi bi-book fs-2"></i>

                                        <p class="mt-2 mb-1 fw-semibold">
                                            No subjects found
                                        </p>

                                        <small>
                                            Add your first subject using the form above.
                                        </small>

                                    </div>

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