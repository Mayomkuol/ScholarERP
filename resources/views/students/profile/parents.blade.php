<div class="card shadow-sm">

    <div class="card-header bg-success text-white">

        <h5 class="mb-0">
            Parent Information
        </h5>

    </div>

    <div class="card-body">

        <div class="row">

            <div class="col-md-6">

                <table class="table table-bordered">

                    <tr>
                        <th width="40%">Parent Name</th>
                        <td>{{ $student->parent_name ?: '-' }}</td>
                    </tr>

                    <tr>
                        <th>Phone</th>
                        <td>{{ $student->parent_phone ?: '-' }}</td>
                    </tr>

                    <tr>
                        <th>Email</th>
                        <td>{{ $student->parent_email ?: '-' }}</td>
                    </tr>

                    <tr>
                        <th>Address</th>
                        <td>{{ $student->address ?: '-' }}</td>
                    </tr>

                </table>

            </div>

            <div class="col-md-6">

                <table class="table table-bordered">

                    <tr>
                        <th width="40%">Father</th>
                        <td>{{ $student->father_name ?: '-' }}</td>
                    </tr>

                    <tr>
                        <th>Father Phone</th>
                        <td>{{ $student->father_phone ?: '-' }}</td>
                    </tr>

                    <tr>
                        <th>Mother</th>
                        <td>{{ $student->mother_name ?: '-' }}</td>
                    </tr>

                    <tr>
                        <th>Mother Phone</th>
                        <td>{{ $student->mother_phone ?: '-' }}</td>
                    </tr>

                </table>

            </div>

        </div>

    </div>

</div>