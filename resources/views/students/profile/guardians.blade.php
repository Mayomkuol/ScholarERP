<div class="card shadow-sm">

    <div class="card-header bg-info text-white">

        <h5 class="mb-0">

            Guardian Information

        </h5>

    </div>

    <div class="card-body">

        @if($student->guardian_name)

        <table class="table table-bordered">

            <tr>
                <th width="30%">Guardian</th>
                <td>{{ $student->guardian_name }}</td>
            </tr>

            <tr>
                <th>Phone</th>
                <td>{{ $student->guardian_phone }}</td>
            </tr>

            <tr>
                <th>Relationship</th>
                <td>{{ $student->guardian_relationship }}</td>
            </tr>

        </table>

        @else

        <div class="alert alert-warning">

            No guardian information available.

        </div>

        @endif

    </div>

</div>