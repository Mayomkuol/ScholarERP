<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Attendance Report - ScholarERP</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >

    <style>
        body {
            background: #f5f6f8;
            font-family: Arial, Helvetica, sans-serif;
        }

        .report-wrapper {
            max-width: 1000px;
            margin: 40px auto;
        }

        .report-card {
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, .06);
        }

        .report-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .report-header i {
            font-size: 40px;
        }

        .report-header h2 {
            margin-top: 10px;
            font-weight: 700;
        }

        .report-header p {
            color: #777;
            margin-bottom: 0;
        }

        .form-label {
            font-weight: 600;
            font-size: 13px;
        }

        .form-control,
        .form-select {
            min-height: 44px;
        }

        .btn-generate {
            min-height: 46px;
            font-weight: 600;
        }

        .back-btn {
            margin-bottom: 15px;
        }
    </style>
</head>

<body>

<div class="container report-wrapper">

    <a
        href="{{ route('attendance.index') }}"
        class="btn btn-light border back-btn"
    >
        <i class="bi bi-arrow-left"></i>
        Back to Attendance
    </a>

    <div class="report-card">

        <div class="report-header">

            <i class="bi bi-printer"></i>

            <h2>Attendance Report</h2>

            <p>
                Select the class, stream and reporting period
            </p>

        </div>

        <form
            method="GET"
            action="{{ route('attendance.print') }}"
            target="_blank"
        >

            <div class="row g-4">

                {{-- Academic Year --}}
                <div class="col-md-6">

                    <label class="form-label">
                        Academic Year
                    </label>

                    <select
                        name="academic_year_id"
                        class="form-select"
                    >

                        <option value="">
                            All Academic Years
                        </option>

                        @foreach($years as $year)

                            <option value="{{ $year->id }}">
                                {{ $year->name }}
                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- Term --}}
                <div class="col-md-6">

                    <label class="form-label">
                        Term
                    </label>

                    <select
                        name="term_id"
                        class="form-select"
                    >

                        <option value="">
                            All Terms
                        </option>

                        @foreach($terms as $term)

                            <option value="{{ $term->id }}">
                                {{ $term->name }}
                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- Class --}}
                <div class="col-md-6">

                    <label class="form-label">
                        Class <span class="text-danger">*</span>
                    </label>

                    <select
                        name="school_class_id"
                        class="form-select"
                        required
                    >

                        <option value="">
                            Select Class
                        </option>

                        @foreach($classes as $class)

                            <option value="{{ $class->id }}">
                                {{ $class->name }}
                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- Stream --}}
                <div class="col-md-6">

                    <label class="form-label">
                        Stream
                    </label>

                    <select
                        name="stream_id"
                        class="form-select"
                    >

                        <option value="">
                            All Streams
                        </option>

                        @foreach($streams as $stream)

                            <option
                                value="{{ $stream->id }}"
                                data-class="{{ $stream->school_class_id }}"
                            >
                                {{ $stream->name }}
                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- Report Type --}}
                <div class="col-md-6">

                    <label class="form-label">
                        Report Type
                    </label>

                    <select
                        name="report_type"
                        id="reportType"
                        class="form-select"
                        required
                    >

                        <option value="daily">
                            Daily
                        </option>

                        <option value="weekly" selected>
                            Weekly
                        </option>

                        <option value="monthly">
                            Monthly
                        </option>

                        <option value="custom">
                            Custom Period
                        </option>

                    </select>

                </div>


                {{-- Start Date --}}
                <div class="col-md-6">

                    <label class="form-label">
                        Start Date
                    </label>

                    <input
                        type="date"
                        name="start_date"
                        id="startDate"
                        class="form-control"
                        value="{{ now()->format('Y-m-d') }}"
                        required
                    >

                </div>


                {{-- End Date --}}
                <div class="col-md-6">

                    <label class="form-label">
                        End Date
                    </label>

                    <input
                        type="date"
                        name="end_date"
                        id="endDate"
                        class="form-control"
                        value="{{ now()->format('Y-m-d') }}"
                        required
                    >

                </div>


                {{-- Generate --}}
                <div class="col-12 mt-4">

                    <button
                        type="submit"
                        class="btn btn-dark w-100 btn-generate"
                    >

                        <i class="bi bi-printer me-2"></i>

                        Generate Attendance Report

                    </button>

                </div>

            </div>

        </form>

    </div>

</div>


<script>

document.addEventListener('DOMContentLoaded', function () {

    const classSelect =
        document.querySelector('select[name="school_class_id"]');

    const streamSelect =
        document.querySelector('select[name="stream_id"]');

    const reportType =
        document.getElementById('reportType');

    const startDate =
        document.getElementById('startDate');

    const endDate =
        document.getElementById('endDate');


    /*
     * STREAM FILTER
     * Only show streams belonging to selected class.
     */

    function filterStreams() {

        const selectedClass =
            classSelect.value;

        Array.from(streamSelect.options).forEach(function (option) {

            if (!option.value) {
                option.hidden = false;
                return;
            }

            const streamClass =
                option.getAttribute('data-class');

            option.hidden =
                selectedClass &&
                streamClass !== selectedClass;

        });

        streamSelect.value = '';

    }


    classSelect.addEventListener(
        'change',
        filterStreams
    );


    /*
     * REPORT TYPE
     */

    function updateDates() {

        const type =
            reportType.value;

        if (type === 'daily') {

            endDate.value =
                startDate.value;

            endDate.disabled = true;

        } else {

            endDate.disabled = false;

        }

    }


    reportType.addEventListener(
        'change',
        updateDates
    );


    startDate.addEventListener(
        'change',
        function () {

            if (reportType.value === 'daily') {

                endDate.value =
                    startDate.value;

            }

        }
    );


    updateDates();

});

</script>

</body>

</html>