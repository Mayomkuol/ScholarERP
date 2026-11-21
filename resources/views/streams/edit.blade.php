@extends('layouts.app')

@section('title', 'Edit Stream')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="fw-bold mb-1">
                <i class="bi bi-pencil-square me-2"></i>
                Edit Stream
            </h4>

            <p class="text-muted mb-0">
                Update stream information.
            </p>

        </div>

        <a
            href="{{ route('streams.index') }}"
            class="btn btn-secondary">

            <i class="bi bi-arrow-left me-1"></i>

            Back to Streams

        </a>

    </div>


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


    <div class="card shadow-sm border-0">

        <div class="card-header bg-primary text-white">

            <h5 class="mb-0">

                Editing:
                {{ $stream->display_name }}

            </h5>

        </div>


        <div class="card-body">

            <form
                method="POST"
                action="{{ route('streams.update', $stream) }}">

                @csrf

                @method('PUT')


                <div class="row g-3">


                    {{-- CLASS --}}

                    <div class="col-md-4">

                        <label class="form-label fw-semibold">
                            Class
                        </label>

                        <select
                            name="school_class_id"
                            class="form-select"
                            required>

                            @foreach($classes as $class)

                                <option
                                    value="{{ $class->id }}"
                                    {{ old(
                                        'school_class_id',
                                        $stream->school_class_id
                                    ) == $class->id
                                        ? 'selected'
                                        : '' }}>

                                    {{ $class->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- STREAM --}}

                    <div class="col-md-4">

                        <label class="form-label fw-semibold">
                            Stream Name
                        </label>

                        <input
                            type="text"
                            name="name"
                            value="{{ old('name', $stream->name) }}"
                            class="form-control"
                            required>

                    </div>


                    {{-- CODE --}}

                    <div class="col-md-4">

                        <label class="form-label fw-semibold">
                            Code
                        </label>

                        <input
                            type="text"
                            name="code"
                            value="{{ old('code', $stream->code) }}"
                            class="form-control"
                            required>

                    </div>


                    {{-- CAPACITY --}}

                    <div class="col-md-4">

                        <label class="form-label fw-semibold">
                            Capacity
                        </label>

                        <input
                            type="number"
                            name="capacity"
                            value="{{ old('capacity', $stream->capacity) }}"
                            class="form-control"
                            min="1"
                            required>

                        <small class="text-muted">

                            Current students:
                            {{ $stream->students()->count() }}

                        </small>

                    </div>


                    {{-- STATUS --}}

                    <div class="col-md-4">

                        <label class="form-label fw-semibold">
                            Status
                        </label>

                        <select
                            name="status"
                            class="form-select">

                            <option
                                value="Active"
                                {{ old(
                                    'status',
                                    $stream->status
                                ) === 'Active'
                                    ? 'selected'
                                    : '' }}>

                                Active

                            </option>

                            <option
                                value="Inactive"
                                {{ old(
                                    'status',
                                    $stream->status
                                ) === 'Inactive'
                                    ? 'selected'
                                    : '' }}>

                                Inactive

                            </option>

                        </select>

                    </div>


                    {{-- DESCRIPTION --}}

                    <div class="col-md-4">

                        <label class="form-label fw-semibold">
                            Description
                        </label>

                        <textarea
                            name="description"
                            rows="2"
                            class="form-control">{{ old('description', $stream->description) }}</textarea>

                    </div>

                </div>


                <hr class="my-4">


                <div class="d-flex justify-content-end gap-2">

                    <a
                        href="{{ route('streams.index') }}"
                        class="btn btn-secondary">

                        Cancel

                    </a>

                    <button
                        type="submit"
                        class="btn btn-primary">

                        <i class="bi bi-save me-1"></i>

                        Update Stream

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection