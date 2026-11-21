<div class="card shadow-sm">

    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            Student Documents
        </h5>

        <a href="{{ route('students.documents.create', $student) }}"
           class="btn btn-primary btn-sm">
            <i class="bi bi-upload"></i>
            Upload Document
        </a>
    </div>

    <div class="card-body">

        @forelse($student->documents as $document)

            <div class="card mb-2">
                <div class="card-body">

                    <div class="row align-items-center">

                        <div class="col-md-8">
                            <strong>
                                {{ $document->document_type }}
                            </strong>

                            <br>

                            {{ $document->title }}

                            @if($document->remarks)
                                <br>
                                <small class="text-muted">
                                    {{ $document->remarks }}
                                </small>
                            @endif
                        </div>

                        <div class="col-md-4 text-end">

                            <a href="{{ route('students.documents.download', $document) }}"
                               target="_blank"
                               class="btn btn-success btn-sm">
                                <i class="bi bi-eye"></i>
                                View
                            </a>

                        </div>

                    </div>

                </div>
            </div>

        @empty

            <div class="alert alert-info">
                No documents uploaded.
            </div>

        @endforelse

    </div>
</div>
