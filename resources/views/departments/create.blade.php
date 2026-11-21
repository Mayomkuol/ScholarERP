@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <!-- Page Header -->

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="fw-bold mb-0">
                Add Department
            </h3>
            <small class="text-muted">
                Create a new school department
            </small>
        </div>

        <a href="{{ route('departments.index') }}"
           class="btn btn-secondary">

            <i class="bi bi-arrow-left"></i>

            Back to Departments

        </a>

    </div>

    <!-- Validation Errors -->

    @if ($errors->any())

        <div class="alert alert-danger">

            <strong>Please fix the following errors:</strong>

            <ul class="mb-0 mt-2">

                @foreach ($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif

    <!-- Form Card -->

    <div class="card shadow">

        <div class="card-header bg-primary text-white">

            <h5 class="mb-0">

                Department Information

            </h5>

        </div>

        <div class="card-body">

            <form action="{{ route('departments.store') }}"
                  method="POST">

                @csrf

                <div class="row">

                    <!-- Department Name -->

                    <div class="col-md-6 mb-3">

                        <label class="form-label">

                            Department Name
                            <span class="text-danger">*</span>

                        </label>

                        <input
                            type="text"
                            name="name"
                            class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name') }}"
                            placeholder="e.g. Mathematics Department"
                            required>

                        @error('name')

                            <div class="invalid-feedback">

                                {{ $message }}

                            </div>

                        @enderror

                    </div>

                    <!-- Department Code -->

                    <div class="col-md-6 mb-3">

                        <label class="form-label">

                            Department Code
                            <span class="text-danger">*</span>

                        </label>

                        <input
                            type="text"
                            name="code"
                            class="form-control @error('code') is-invalid @enderror"
                            value="{{ old('code') }}"
                            placeholder="e.g. MATH"
                            required>

                        @error('code')

                            <div class="invalid-feedback">

                                {{ $message }}

                            </div>

                        @enderror

                    </div>

                </div>

                <!-- Description -->

                <div class="mb-3">

                    <label class="form-label">

                        Description

                    </label>

                    <textarea
                        name="description"
                        rows="4"
                        class="form-control @error('description') is-invalid @enderror"
                        placeholder="Enter department description...">{{ old('description') }}</textarea>

                    @error('description')

                        <div class="invalid-feedback">

                            {{ $message }}

                        </div>

                    @enderror

                </div>

                <!-- Status -->

                <div class="mb-4">

                    <label class="form-label">

                        Status

                    </label>

                    <select
                        name="status"
                        class="form-select @error('status') is-invalid @enderror">

                        <option value="1"
                            {{ old('status',1)==1 ? 'selected' : '' }}>

                            Active

                        </option>

                        <option value="0"
                            {{ old('status')==='0' ? 'selected' : '' }}>

                            Inactive

                        </option>

                    </select>

                    @error('status')

                        <div class="invalid-feedback">

                            {{ $message }}

                        </div>

                    @enderror

                </div>

                <!-- Buttons -->

                <div class="d-flex justify-content-end">

                    <a href="{{ route('departments.index') }}"
                       class="btn btn-secondary me-2">

                        Cancel

                    </a>

                    <button type="submit"
                            class="btn btn-success">

                        <i class="bi bi-check-circle"></i>

                        Save Department

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection