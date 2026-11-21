<div class="row">

    <div class="col-lg-4">

        <div class="card shadow-sm mb-4">

            <div class="card-body text-center">

                @if($student->photo)

                    <img src="{{ asset('storage/'.$student->photo) }}"
                         class="rounded-circle img-thumbnail mb-3"
                         width="170"
                         height="170">

                @else

                    <img src="https://ui-avatars.com/api/?name={{ urlencode($student->full_name) }}&size=170"
                         class="rounded-circle img-thumbnail mb-3">

                @endif

                <h3 class="mb-1">

                    {{ $student->full_name }}

                </h3>

                <p class="text-muted">

                    {{ $student->admission_no }}

                </p>

                <span class="badge bg-success">

                    {{ $student->status }}

                </span>

            </div>

        </div>

    </div>


    <div class="col-lg-8">

        <div class="card shadow-sm">

            <div class="card-header bg-primary text-white">

                Student Overview

            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-6">

                        <table class="table table-borderless">

                            <tr>
                                <th>Admission No</th>
                                <td>{{ $student->admission_no }}</td>
                            </tr>

                            <tr>
                                <th>Admission Date</th>
                                <td>{{ optional($student->admission_date)->format('d M Y') }}</td>
                            </tr>

                            <tr>
                                <th>Gender</th>
                                <td>{{ $student->gender }}</td>
                            </tr>

                            <tr>
                                <th>Date of Birth</th>
                                <td>{{ optional($student->dob)->format('d M Y') }}</td>
                            </tr>

                            <tr>
                                <th>Nationality</th>
                                <td>{{ $student->nationality }}</td>
                            </tr>

                        </table>

                    </div>


                    <div class="col-md-6">

                        <table class="table table-borderless">

                            <tr>
                                <th>Class</th>
                                <td>{{ optional($student->schoolClass)->name }}</td>
                            </tr>

                            <tr>
                                <th>Stream</th>
                                <td>{{ optional($student->stream)->name }}</td>
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
                                <th>Attendance</th>
                                <td>

                                    <span class="badge bg-info">

                                        {{ $student->attendance_percentage }}%

                                    </span>

                                </td>
                            </tr>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>