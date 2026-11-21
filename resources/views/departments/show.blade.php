@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3 class="fw-bold">

                Department Details

            </h3>

            <small class="text-muted">

                View department information

            </small>

        </div>

        <div>

            <a href="{{ route('departments.edit',$department) }}"
               class="btn btn-warning">

                <i class="bi bi-pencil"></i>

                Edit

            </a>

            <a href="{{ route('departments.index') }}"
               class="btn btn-secondary">

                Back

            </a>

        </div>

    </div>

    <div class="card shadow">

        <div class="card-header bg-primary text-white">

            <h5 class="mb-0">

                {{ $department->name }}

            </h5>

        </div>

        <div class="card-body">

            <table class="table table-bordered">

                <tr>

                    <th width="220">

                        Department Name

                    </th>

                    <td>

                        {{ $department->name }}

                    </td>

                </tr>

                <tr>

                    <th>

                        Department Code

                    </th>

                    <td>

                        {{ $department->code }}

                    </td>

                </tr>

                <tr>

                    <th>

                        Description

                    </th>

                    <td>

                        {{ $department->description ?: '-' }}

                    </td>

                </tr>

                <tr>

                    <th>

                        Status

                    </th>

                    <td>

                        @if($department->status)

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

                        {{ $department->created_at->format('d M Y h:i A') }}

                    </td>

                </tr>

                <tr>

                    <th>

                        Last Updated

                    </th>

                    <td>

                        {{ $department->updated_at->format('d M Y h:i A') }}

                    </td>

                </tr>

            </table>

        </div>

    </div>

</div>

@endsection