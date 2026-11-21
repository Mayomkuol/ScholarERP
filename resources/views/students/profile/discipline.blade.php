<div class="card shadow-sm">

    <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">

        <h5 class="mb-0">
            <i class="bi bi-shield-exclamation me-2"></i>
            Discipline Records
        </h5>

        {{-- Only show Add Record if the route actually exists --}}
        @if(Route::has('students.discipline.create'))

            <a href="{{ route('students.discipline.create', $student) }}"
               class="btn btn-light btn-sm">

                <i class="bi bi-plus-circle me-1"></i>
                Add Record

            </a>

        @endif

    </div>


    <div class="card-body">

        @forelse($student->disciplineRecords as $record)

            <div class="card mb-3 border-start border-danger border-4 shadow-sm">

                <div class="card-body">

                    <div class="row">

                        {{-- =====================================================
                             DISCIPLINE DETAILS
                        ====================================================== --}}

                        <div class="col-md-9">

                            <h6 class="fw-bold mb-2">

                                {{ $record->category ?? 'Discipline Incident' }}

                            </h6>


                            @if($record->incident)

                                <p class="mb-3">

                                    {{ $record->incident }}

                                </p>

                            @endif


                            @if($record->action_taken)

                                <strong>
                                    Action Taken
                                </strong>

                                <p class="mb-3">

                                    {{ $record->action_taken }}

                                </p>

                            @endif


                            @if($record->reported_by)

                                <small class="text-muted">

                                    <i class="bi bi-person me-1"></i>

                                    Reported by:

                                    {{ $record->reported_by }}

                                </small>

                            @endif

                        </div>


                        {{-- =====================================================
                             DATE / STATUS
                        ====================================================== --}}

                        <div class="col-md-3 text-md-end">

                            <strong>

                                @if($record->incident_date)

                                    {{ \Carbon\Carbon::parse($record->incident_date)->format('d M Y') }}

                                @else

                                    —

                                @endif

                            </strong>


                            <br><br>


                            @if($record->status === 'Resolved')

                                <span class="badge bg-success">

                                    <i class="bi bi-check-circle me-1"></i>

                                    Resolved

                                </span>

                            @elseif($record->status === 'Pending')

                                <span class="badge bg-danger">

                                    <i class="bi bi-exclamation-circle me-1"></i>

                                    Pending

                                </span>

                            @else

                                <span class="badge bg-secondary">

                                    {{ $record->status ?? 'Unknown' }}

                                </span>

                            @endif

                        </div>

                    </div>

                </div>

            </div>

        @empty

            <div class="alert alert-success mb-0">

                <i class="bi bi-check-circle me-2"></i>

                No discipline records for this student.

            </div>

        @endforelse

    </div>

</div>