@extends('layouts.app')

@section('content')

<div class="container-fluid">

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card shadow">

        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">

            <h4 class="mb-0">
                Exam Types
            </h4>

            <a href="{{ route('exam-types.create') }}"
               class="btn btn-light">

                <i class="bi bi-plus-circle"></i>

                New Exam Type

            </a>

        </div>

        <div class="card-body">

            <table class="table table-bordered table-hover">

                <thead class="table-light">

                    <tr>

                        <th width="60">#</th>

                        <th>Name</th>

                        <th>Code</th>

                        <th>Percentage</th>

                        <th>Status</th>

                        <th width="220">Actions</th>

                    </tr>

                </thead>

                <tbody>

                @forelse($examTypes as $examType)

                    <tr>

                        <td>{{ $loop->iteration }}</td>

                        <td>{{ $examType->name }}</td>

                        <td>{{ $examType->code }}</td>

                        <td>{{ $examType->percentage }}%</td>

                        <td>

                            @if($examType->active)

                                <span class="badge bg-success">

                                    Active

                                </span>

                            @else

                                <span class="badge bg-danger">

                                    Inactive

                                </span>

                            @endif

                        </td>

                        <td>

                            <a href="{{ route('exam-types.show',$examType) }}"
                               class="btn btn-info btn-sm">

                                View

                            </a>

                            <a href="{{ route('exam-types.edit',$examType) }}"
                               class="btn btn-warning btn-sm">

                                Edit

                            </a>

                            <form
                                action="{{ route('exam-types.destroy',$examType) }}"
                                method="POST"
                                class="d-inline">

                                @csrf
                                @method('DELETE')

                                <button
                                    class="btn btn-danger btn-sm"
                                    onclick="return confirm('Delete this exam type?')">

                                    Delete

                                </button>

                            </form>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="6" class="text-center">

                            No Exam Types Found

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

            {{ $examTypes->links() }}

        </div>

    </div>

</div>

@endsection