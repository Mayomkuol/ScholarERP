<div class="card shadow-sm">

    <div class="card-header bg-danger text-white">

        <h5 class="mb-0">

            Medical Information

        </h5>

    </div>

    <div class="card-body">

        <table class="table table-bordered">

            <tr>

                <th width="35%">Blood Group</th>

                <td>{{ $student->blood_group ?: '-' }}</td>

            </tr>

            <tr>

                <th>Medical Conditions</th>

                <td>

                    {{ $student->medical_conditions ?: 'None' }}

                </td>

            </tr>

            <tr>

                <th>Allergies</th>

                <td>

                    {{ $student->allergies ?: 'None' }}

                </td>

            </tr>

            <tr>

                <th>Previous School</th>

                <td>

                    {{ $student->previous_school ?: '-' }}

                </td>

            </tr>

        </table>

    </div>

</div>