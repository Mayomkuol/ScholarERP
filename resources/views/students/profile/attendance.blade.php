<div class="card shadow-sm">

    <div class="card-header bg-warning d-flex justify-content-between align-items-center">

        <h5 class="mb-0">

            Attendance Record

        </h5>

        <span class="badge bg-primary">

            {{ $student->attendance_percentage }}%

        </span>

    </div>

    <div class="card-body">

        @if($student->attendances->count())

        <table class="table table-striped table-hover">

            <thead>

                <tr>

                    <th>Date</th>
                    <th>Status</th>
                    <th>Remarks</th>

                </tr>

            </thead>

            <tbody>

                @foreach($student->attendances as $attendance)

                <tr>

                    <td>{{ $attendance->date }}</td>

                    <td>

                        @if($attendance->status=='Present')

                            <span class="badge bg-success">

                                Present

                            </span>

                        @elseif($attendance->status=='Absent')

                            <span class="badge bg-danger">

                                Absent

                            </span>

                        @elseif($attendance->status=='Late')

                            <span class="badge bg-warning">

                                Late

                            </span>

                        @else

                            <span class="badge bg-secondary">

                                {{ $attendance->status }}

                            </span>

                        @endif

                    </td>

                    <td>

                        {{ $attendance->remarks }}

                    </td>

                </tr>

                @endforeach

            </tbody>

        </table>

        @else

        <div class="alert alert-info">

            No attendance records available.

        </div>

        @endif

    </div>

</div>