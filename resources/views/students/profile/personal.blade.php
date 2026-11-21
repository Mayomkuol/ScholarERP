<div class="card shadow-sm">

    <div class="card-header bg-light">

        <strong>

            Personal Information

        </strong>

    </div>

    <div class="card-body">

        <table class="table table-striped">

            <tr>
                <th width="250">Gender</th>
                <td>{{ $student->gender }}</td>
            </tr>

            <tr>
                <th>Date of Birth</th>
                <td>{{ optional($student->dob)->format('d M Y') }}</td>
            </tr>

            <tr>
                <th>Birth Certificate</th>
                <td>{{ $student->birth_certificate }}</td>
            </tr>

            <tr>
                <th>Religion</th>
                <td>{{ $student->religion }}</td>
            </tr>

            <tr>
                <th>Nationality</th>
                <td>{{ $student->nationality }}</td>
            </tr>

            <tr>
                <th>Blood Group</th>
                <td>{{ $student->blood_group }}</td>
            </tr>

            <tr>
                <th>Phone</th>
                <td>{{ $student->phone }}</td>
            </tr>

            <tr>
                <th>Email</th>
                <td>{{ $student->email }}</td>
            </tr>

            <tr>
                <th>Address</th>
                <td>{{ $student->address }}</td>
            </tr>

        </table>

    </div>

</div>