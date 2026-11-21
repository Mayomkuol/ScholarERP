@extends('layouts.app')

@section('title', 'Add Discipline Record')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="fw-bold mb-1">
                Add Discipline Record
            </h4>

            <p class="text-muted mb-0">
                Add a disciplinary record for
                <strong>{{ $student->full_name }}</strong>.
            </p>
        </div>

        <a href="{{ route('students.show', $student) }}"
           class="btn btn-secondary">

            <i class="bi bi-arrow-left me-1"></i>
            Back to Student

        </a>

    </div>


    <div class="card shadow-sm border-0">

        <div class="card-header bg-danger text-white">

            <h5 class="mb-0">
                <i class="bi bi-shield-exclamation me-2"></i>
                Discipline Information
            </h5>

        </div>


        <div class="card-body">

            <form method="POST"
                  action="{{ route('students.discipline.store', $student) }}">

                @csrf


                <div class="row">


                    {{-- Category --}}

                    <div class="col-md-6 mb-3">

                        <label class="form-label fw-semibold">
                            Category
                            <span class="text-danger">*</span>
                        </label>

                        <input type="text"
                               name="category"
                               value="{{ old('category') }}"
                               class="form-control @error('category') is-invalid @enderror"
                               placeholder="e.g. Misconduct, Absence, Fighting">

                        @error('category')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Incident Date --}}

                    <div class="col-md-6 mb-3">

                        <label class="form-label fw-semibold">
                            Incident Date
                            <span class="text-danger">*</span>
                        </label>

                        <input type="date"
                               name="incident_date"
                               value="{{ old('incident_date', now()->format('Y-m-d')) }}"
                               class="form-control @error('incident_date') is-invalid @enderror">

                        @error('incident_date')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Incident --}}

                    <div class="col-12 mb-3">

                        <label class="form-label fw-semibold">
                            Incident
                            <span class="text-danger">*</span>
                        </label>

                        <textarea name="incident"
                                  rows="4"
                                  class="form-control @error('incident') is-invalid @enderror"
                                  placeholder="Describe the incident...">{{ old('incident') }}</textarea>

                        @error('incident')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Action Taken --}}

                    <div class="col-md-8 mb-3">

                        <label class="form-label fw-semibold">
                            Action Taken
                        </label>

                        <textarea name="action_taken"
                                  rows="3"
                                  class="form-control @error('action_taken') is-invalid @enderror"
                                  placeholder="Describe the action taken...">{{ old('action_taken') }}</textarea>

                        @error('action_taken')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Status --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label fw-semibold">
                            Status
                            <span class="text-danger">*</span>
                        </label>

                        <select name="status"
                                class="form-select @error('status') is-invalid @enderror">

                            <option value="Pending"
                                {{ old('status') === 'Pending' ? 'selected' : '' }}>
                                Pending
                            </option>

                            <option value="Resolved"
                                {{ old('status') === 'Resolved' ? 'selected' : '' }}>
                                Resolved
                            </option>

                        </select>

                        @error('status')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Reported By --}}

                    <div class="col-md-6 mb-3">

                        <label class="form-label fw-semibold">
                            Reported By
                        </label>

                        <input type="text"
                               name="reported_by"
                               value="{{ old('reported_by', auth()->user()->name ?? '') }}"
                               class="form-control @error('reported_by') is-invalid @enderror"
                               placeholder="Name of staff member">

                        @error('reported_by')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>


                <hr class="my-4">


                <div class="d-flex justify-content-end gap-2">

                    <a href="{{ route('students.show', $student) }}"
                       class="btn btn-secondary">

                        Cancel

                    </a>

                    <button type="submit"
                            class="btn btn-danger">

                        <i class="bi bi-save me-1"></i>
                        Save Discipline Record

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection