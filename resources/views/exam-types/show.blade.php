@extends('layouts.app')

@section('content')

<div class="container">

    <div class="card shadow">

        <div class="card-header bg-info text-white">

            <h4>

                {{ $examType->name }}

            </h4>

        </div>

        <div class="card-body">

            <table class="table table-bordered">

                <tr>

                    <th width="220">

                        Name

                    </th>

                    <td>

                        {{ $examType->name }}

                    </td>

                </tr>

                <tr>

                    <th>

                        Code

                    </th>

                    <td>

                        {{ $examType->code }}

                    </td>

                </tr>

                <tr>

                    <th>

                        Percentage

                    </th>

                    <td>

                        {{ $examType->percentage }}%

                    </td>

                </tr>

                <tr>

                    <th>

                        Status

                    </th>

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

                </tr>

                <tr>

                    <th>

                        Created

                    </th>

                    <td>

                        {{ $examType->created_at->format('d M Y') }}

                    </td>

                </tr>

            </table>

            <a href="{{ route('exam-types.edit',$examType) }}"
               class="btn btn-warning">

                Edit

            </a>

            <a href="{{ route('exam-types.index') }}"
               class="btn btn-secondary">

                Back

            </a>

        </div>

    </div>

</div>

@endsection