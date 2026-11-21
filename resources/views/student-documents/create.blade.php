@extends('layouts.app')

@section('title', 'Upload Student Document')

@section('content')

<div class="container-fluid py-4">

```
{{-- Page Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1">
            <i class="bi bi-file-earmark-arrow-up me-2"></i>
            Upload Student Document
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
        href="{{ route('students.documents.index', $student) }}"
        class="btn btn-outline-secondary"
    >
        <i class="bi bi-arrow-left me-1"></i>
        Back to Documents
    </a>
</div>

{{-- Validation Errors --}}
@if ($errors->any())
    <div class="alert alert-danger">
        <strong>Please correct the following:</strong>

        <ul class="mb-0 mt-2">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- Upload Form --}}
<div class="card border-0 shadow-sm">

    <div class="card-header bg-white py-3">
        <h5 class="mb-0">
            Document Information
        </h5>
    </div>

    <div class="card-body">

        <form
            method="POST"
            action="{{ route('students.documents.store', $student) }}"
            enctype="multipart/form-data"
        >

            @csrf

            <div class="row g-3">

                {{-- Document Type --}}
                <div class="col-md-6">

                    <label for="document_type" class="form-label">
                        Document Type
                        <span class="text-danger">*</span>
                    </label>

                    <select
                        name="document_type"
                        id="document_type"
                        class="form-select @error('document_type') is-invalid @enderror"
                        required
                    >
                        <option value="">
                            Select document type
                        </option>

                        <option value="Birth Certificate"
                            @selected(old('document_type') === 'Birth Certificate')>
                            Birth Certificate
                        </option>

                        <option value="Passport"
                            @selected(old('document_type') === 'Passport')>
                            Passport
                        </option>

                        <option value="National ID"
                            @selected(old('document_type') === 'National ID')>
                            National ID
                        </option>

                        <option value="Report Card"
                            @selected(old('document_type') === 'Report Card')>
                            Report Card
                        </option>

                        <option value="Transfer Certificate"
                            @selected(old('document_type') === 'Transfer Certificate')>
                            Transfer Certificate
                        </option>

                        <option value="Medical Record"
                            @selected(old('document_type') === 'Medical Record')>
                            Medical Record
                        </option>

                        <option value="Other"
                            @selected(old('document_type') === 'Other')>
                            Other
                        </option>
                    </select>

                    @error('document_type')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

                {{-- Title --}}
                <div class="col-md-6">

                    <label for="title" class="form-label">
                        Document Title
                        <span class="text-danger">*</span>
                    </label>

                    <input
                        type="text"
                        name="title"
                        id="title"
                        class="form-control @error('title') is-invalid @enderror"
                        value="{{ old('title') }}"
                        placeholder="e.g. Term 1 Report Card"
                        maxlength="255"
                        required
                    >

                    @error('title')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

                {{-- File --}}
                <div class="col-12">

                    <label for="file" class="form-label">
                        Select File
                        <span class="text-danger">*</span>
                    </label>

                    <input
                        type="file"
                        name="file"
                        id="file"
                        class="form-control @error('file') is-invalid @enderror"
                        accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"
                        required
                    >

                    <div class="form-text">
                        Accepted formats:
                        PDF, JPG, JPEG, PNG, DOC and DOCX.
                        Maximum size: 5 MB.
                    </div>

                    @error('file')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

                {{-- Remarks --}}
                <div class="col-12">

                    <label for="remarks" class="form-label">
                        Remarks
                    </label>

                    <textarea
                        name="remarks"
                        id="remarks"
                        rows="4"
                        class="form-control @error('remarks') is-invalid @enderror"
                        maxlength="2000"
                        placeholder="Optional notes about this document..."
                    >{{ old('remarks') }}</textarea>

                    @error('remarks')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

            </div>

            {{-- Actions --}}
            <div class="d-flex justify-content-end gap-2 mt-4">

                <a
                    href="{{ route('students.documents.index', $student) }}"
                    class="btn btn-light"
                >
                    Cancel
                </a>

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    <i class="bi bi-cloud-arrow-up me-1"></i>
                    Upload Document
                </button>

            </div>

        </form>

    </div>

</div>

</div>

@endsection
