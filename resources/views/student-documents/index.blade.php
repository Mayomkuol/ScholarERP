
@extends('layouts.app')

@section('content')

<div class="container-fluid">

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow">

        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">

            <div>
                <h4 class="mb-0">Student Documents</h4>

                <small>
                    {{ $student->admission_no }}
                    -
                    {{ $student->first_name }}
                    {{ $student->last_name }}
                </small>
            </div>

            <div>
                <a href="{{ route('students.documents.create', $student) }}"
                   class="btn btn-light">

                    <i class="bi bi-upload"></i>
                    Upload Document

                </a>

                <a href="{{ route('students.show', $student) }}"
                   class="btn btn-warning">

                    Back to Student

                </a>
            </div>

        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle">

                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Type</th>
                            <th>Title</th>
                            <th>Preview</th>
                            <th>Remarks</th>
                            <th>Date Uploaded</th>
                            <th width="190">Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                    @forelse($documents as $document)

                        @php
                            $extension = strtolower(
                                pathinfo($document->file, PATHINFO_EXTENSION)
                            );

                            $downloadUrl = route(
                                'students.documents.download',
                                $document
                            );
                        @endphp

                        <tr>

                            <td>{{ $loop->iteration }}</td>

                            <td>
                                <span class="badge bg-info">
                                    {{ $document->document_type }}
                                </span>
                            </td>

                            <td>{{ $document->title }}</td>

                            <td>

                                @if(in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp']))

                                    <a href="{{ $downloadUrl }}"
                                       target="_blank"
                                       rel="noopener noreferrer">

                                        <img
                                            src="{{ $downloadUrl }}"
                                            width="70"
                                            height="70"
                                            class="img-thumbnail"
                                            style="object-fit: cover;"
                                            alt="Document preview">

                                    </a>

                                @elseif($extension === 'pdf')

                                    <a href="{{ $downloadUrl }}"
                                       target="_blank"
                                       rel="noopener noreferrer"
                                       class="text-danger">

                                        <i class="bi bi-file-earmark-pdf-fill fs-2"></i>

                                    </a>

                                @else

                                    <i class="bi bi-file-earmark fs-2"></i>

                                @endif

                            </td>

                            <td>
                                {{ $document->remarks ?: '—' }}
                            </td>

                            <td>
                                {{ $document->created_at->format('d M Y') }}
                            </td>

                            <td>

                                <a href="{{ $downloadUrl }}"
                                   class="btn btn-success btn-sm"
                                   title="Download document">

                                    <i class="bi bi-download"></i>

                                </a>

                                <form
                                    action="{{ route('student-documents.destroy', $document) }}"
                                    method="POST"
                                    class="d-inline">

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        onclick="return confirm('Delete this document?')"
                                        class="btn btn-danger btn-sm"
                                        title="Delete document">

                                        <i class="bi bi-trash"></i>

                                    </button>

                                </form>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="7" class="text-center text-muted">
                                No documents uploaded.
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
