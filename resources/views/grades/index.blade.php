@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3 class="fw-bold">

                Grade Settings

            </h3>

            <small class="text-muted">

                Configure grading system

            </small>

        </div>

        <a href="{{ route('grades.create') }}"
           class="btn btn-primary">

            <i class="bi bi-plus-circle"></i>

            Add Grade

        </a>

    </div>

    @if(session('success'))

        <div class="alert alert-success">

            {{ session('success') }}

        </div>

    @endif

    <div class="card shadow">

        <div class="card-body p-0">

            <table class="table table-hover mb-0">

                <thead class="table-dark">

                    <tr>

                        <th>#</th>

                        <th>Grade</th>

                        <th>Minimum</th>

                        <th>Maximum</th>

                        <th>Points</th>

                        <th>Remarks</th>

                        <th>Colour</th>

                        <th width="180">Action</th>

                    </tr>

                </thead>

                <tbody>

                @forelse($grades as $grade)

                    <tr>

                        <td>{{ $loop->iteration }}</td>

                        <td>

                            <strong>{{ $grade->grade }}</strong>

                        </td>

                        <td>{{ $grade->min_mark }}</td>

                        <td>{{ $grade->max_mark }}</td>

                        <td>{{ $grade->points }}</td>

                        <td>{{ $grade->remarks }}</td>

                        <td>

                            <span class="badge"

                                  style="background:{{ $grade->color ?? '#0d6efd' }}">

                                {{ $grade->color }}

                            </span>

                        </td>

                        <td>

                            <a href="{{ route('grades.show',$grade) }}"
                               class="btn btn-info btn-sm">

                                View

                            </a>

                            <a href="{{ route('grades.edit',$grade) }}"
                               class="btn btn-warning btn-sm">

                                Edit

                            </a>

                            <form action="{{ route('grades.destroy',$grade) }}"
                                  method="POST"
                                  class="d-inline">

                                @csrf

                                @method('DELETE')

                                <button class="btn btn-danger btn-sm"
                                        onclick="return confirm('Delete this grade?')">

                                    Delete

                                </button>

                            </form>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="8"
                            class="text-center py-5">

                            No grades configured.

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection