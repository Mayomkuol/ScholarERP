<div class="card shadow-sm">

    <div class="card-header bg-primary text-white">

        Academic Information

    </div>

    <div class="card-body">

        <table class="table table-bordered">

            <tr>

                <th width="30%">Admission Number</th>

                <td>{{ $student->admission_no }}</td>

            </tr>

            <tr>

                <th>Admission Date</th>

                <td>{{ optional($student->admission_date)->format('d M Y') }}</td>

            </tr>

            <tr>

                <th>Academic Year</th>

                <td>{{ optional($student->academicYear)->name }}</td>

            </tr>

            <tr>

                <th>Term</th>

                <td>{{ optional($student->term)->name }}</td>

            </tr>

            <tr>

                <th>Class</th>

                <td>{{ optional($student->schoolClass)->name }}</td>

            </tr>

            <tr>

                <th>Stream</th>

                <td>{{ optional($student->stream)->name }}</td>

            </tr>

            <tr>

                <th>Status</th>

                <td>

                    <span class="badge bg-success">

                        {{ $student->status }}

                    </span>

                </td>

            </tr>

        </table>

    </div>

</div>