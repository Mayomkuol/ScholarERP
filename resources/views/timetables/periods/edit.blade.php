@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3 class="fw-bold mb-1">
                <i class="bi bi-pencil-square me-2"></i>
                Edit Timetable Period
            </h3>

            <p class="text-muted mb-0">
                Update the selected timetable period.
            </p>

        </div>

        <a href="{{ route('timetables.periods.index') }}"
           class="btn btn-outline-secondary">

            <i class="bi bi-arrow-left me-1"></i>
            Back

        </a>

    </div>


    <div class="row justify-content-center">

        <div class="col-xl-8 col-lg-10">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-primary text-white py-3">

                    <h5 class="mb-0">
                        Edit: {{ $timetablePeriod->name }}
                    </h5>

                </div>


                <div class="card-body p-4">

                    @if($errors->any())

                        <div class="alert alert-danger">

                            <strong>Please correct the following:</strong>

                            <ul class="mb-0 mt-2">

                                @foreach($errors->all() as $error)

                                    <li>{{ $error }}</li>

                                @endforeach

                            </ul>

                        </div>

                    @endif


                    <form method="POST"
                          action="{{ route('timetables.periods.update', $timetablePeriod) }}">

                        @csrf
                        @method('PUT')


                        <div class="row g-3">


                            <div class="col-md-8">

                                <label class="form-label fw-semibold">
                                    Period Name
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="text"
                                       name="name"
                                       class="form-control"
                                       value="{{ old('name', $timetablePeriod->name) }}"
                                       required>

                            </div>


                            <div class="col-md-4">

                                <label class="form-label fw-semibold">
                                    Period Number
                                </label>

                                <input type="number"
                                       name="period_number"
                                       class="form-control"
                                       value="{{ old('period_number', $timetablePeriod->period_number) }}"
                                       min="1"
                                       max="50">

                            </div>


                            <div class="col-md-6">

                                <label class="form-label fw-semibold">
                                    Start Time
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="time"
                                       name="start_time"
                                       class="form-control"
                                       value="{{ old('start_time', \Carbon\Carbon::parse($timetablePeriod->start_time)->format('H:i')) }}"
                                       required>

                            </div>


                            <div class="col-md-6">

                                <label class="form-label fw-semibold">
                                    End Time
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="time"
                                       name="end_time"
                                       class="form-control"
                                       value="{{ old('end_time', \Carbon\Carbon::parse($timetablePeriod->end_time)->format('H:i')) }}"
                                       required>

                            </div>


                            <div class="col-md-6">

                                <label class="form-label fw-semibold">
                                    Period Type
                                    <span class="text-danger">*</span>
                                </label>

                                <select name="type"
                                        class="form-select"
                                        required>

                                    @foreach([
                                        'lesson' => 'Lesson',
                                        'break' => 'Break',
                                        'lunch' => 'Lunch',
                                        'assembly' => 'Assembly',
                                        'activity' => 'Activity'
                                    ] as $value => $label)

                                        <option value="{{ $value }}"
                                            {{ old('type', $timetablePeriod->type) === $value ? 'selected' : '' }}>

                                            {{ $label }}

                                        </option>

                                    @endforeach

                                </select>

                            </div>


                            <div class="col-md-6">

                                <label class="form-label fw-semibold">
                                    Status
                                </label>

                                <div class="form-control bg-light">

                                    <div class="form-check form-switch">

                                        <input class="form-check-input"
                                               type="checkbox"
                                               name="is_active"
                                               value="1"
                                               id="is_active"
                                               {{ old('is_active', $timetablePeriod->is_active) ? 'checked' : '' }}>

                                        <label class="form-check-label"
                                               for="is_active">

                                            Active Period

                                        </label>

                                    </div>

                                </div>

                            </div>


                        </div>


                        <hr class="my-4">


                        <div class="d-flex justify-content-between">

                            <a href="{{ route('timetables.periods.index') }}"
                               class="btn btn-light border">

                                Cancel

                            </a>

                            <button type="submit"
                                    class="btn btn-primary">

                                <i class="bi bi-save me-1"></i>
                                Update Period

                            </button>

                        </div>


                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection