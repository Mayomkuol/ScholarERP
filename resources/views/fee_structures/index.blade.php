@extends('layouts.app')

@section('title','Fee Structures')

@section('content')

<div class="container-fluid">
     <x-school-header
    title="FINANCE"
    subtitle="FEE STRUCTURE"
/>

    @if(session('success'))

        <div class="alert alert-success">

            {{ session('success') }}

        </div>

    @endif

    <div class="card shadow">

        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">

            <h4 class="mb-0">

                Fee Structures

            </h4>

            <a
                href="{{ route('fee-structures.create') }}"
                class="btn btn-light">

                <i class="bi bi-plus-circle"></i>

                New Fee Structure

            </a>

        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle">

                    <thead class="table-dark">

                        <tr>

                            <th>#</th>

                            <th>Academic Year</th>

                            <th>Term</th>

                            <th>Class</th>

                            <th>Fee Category</th>

                            <th class="text-end">

                                Amount

                            </th>

                            <th width="150">

                                Actions

                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($feeStructures as $structure)

                        <tr>

                            <td>

                                {{ $loop->iteration + ($feeStructures->firstItem() - 1) }}

                            </td>

                            <td>

                                {{ $structure->academicYear->name }}

                            </td>

                            <td>

                                {{ $structure->term->name }}

                            </td>

                            <td>

                                {{ $structure->schoolClass->name }}

                            </td>

                            <td>

                                {{ $structure->feeCategory->name }}

                            </td>

                            <td class="text-end">

                                {{ number_format($structure->amount,2) }}

                            </td>

                            <td>

                                <a
                                    href="{{ route('fee-structures.edit',$structure) }}"
                                    class="btn btn-warning btn-sm">

                                    <i class="bi bi-pencil"></i>

                                </a>

                                <form
                                    action="{{ route('fee-structures.destroy',$structure) }}"
                                    method="POST"
                                    class="d-inline">

                                    @csrf

                                    @method('DELETE')

                                    <button
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('Delete this fee structure?')">

                                        <i class="bi bi-trash"></i>

                                    </button>

                                </form>

                            </td>

                        </tr>

                        @empty

                        <tr>

                            <td colspan="7" class="text-center">

                                No fee structures found.

                            </td>

                        </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            <div class="mt-3">

                {{ $feeStructures->links() }}

            </div>

        </div>

    </div>

</div>

@endsection