@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <h2>Academic Years</h2>

    <a href="{{ route('academic-years.create') }}"
       class="btn btn-primary">

        New Academic Year

    </a>

</div>

<div class="card shadow-sm">

    <div class="card-body">

        <table class="table table-hover">

            <thead>

            <tr>

                <th>Name</th>

                <th>Start</th>

                <th>End</th>

                <th>Status</th>

                <th>Current</th>

                <th></th>

            </tr>

            </thead>

            <tbody>

            @forelse($years as $year)

                <tr>

                    <td>{{ $year->name }}</td>

                    <td>{{ $year->start_date }}</td>

                    <td>{{ $year->end_date }}</td>

                    <td>{{ $year->status }}</td>

                    <td>

                        @if($year->is_current)

                            <span class="badge bg-success">

                                Current

                            </span>

                        @endif

                    </td>

                    <td>

                        <a href="{{ route('academic-years.edit',$year) }}"
                           class="btn btn-sm btn-warning">

                            Edit

                        </a>

                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="6" class="text-center">

                        No Academic Years Found

                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

        {{ $years->links() }}

    </div>

</div>

@endsection