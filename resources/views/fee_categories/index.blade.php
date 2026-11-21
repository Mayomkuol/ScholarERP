@extends('layouts.app')

@section('title', 'Fee Categories')

@section('content')

<div class="container-fluid">

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>
    @endif


    {{-- Fee Categories Card --}}
    <div class="card shadow">

        {{-- Card Header --}}
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">

            <h4 class="mb-0">
                Fee Categories
            </h4>

            <a
                href="{{ route('fee-categories.create') }}"
                class="btn btn-light">

                <i class="bi bi-plus-circle"></i>
                New Category

            </a>

        </div>


        {{-- Card Body --}}
        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle">

                    <thead class="table-light">

                        <tr>

                            <th width="60">#</th>

                            <th>Category</th>

                            <th>Description</th>

                            <th>Status</th>

                            <th width="170">Actions</th>

                        </tr>

                    </thead>


                    <tbody>

                    @forelse($categories as $category)

                        <tr>

                            {{-- Number --}}
                            <td>
                                {{ $loop->iteration + ($categories->firstItem() - 1) }}
                            </td>


                            {{-- Category Name --}}
                            <td>
                                <strong>
                                    {{ $category->name }}
                                </strong>
                            </td>


                            {{-- Description --}}
                            <td>
                                {{ $category->description ?: '—' }}
                            </td>


                            {{-- Status --}}
                            <td>

                                @if($category->is_active)

                                    <span class="badge bg-success">
                                        Active
                                    </span>

                                @else

                                    <span class="badge bg-secondary">
                                        Inactive
                                    </span>

                                @endif

                            </td>


                            {{-- Actions --}}
                            <td>

                                {{-- Edit --}}
                                <a
                                    href="{{ route('fee-categories.edit', $category) }}"
                                    class="btn btn-warning btn-sm"
                                    title="Edit">

                                    <i class="bi bi-pencil"></i>

                                </a>


                                {{-- Delete --}}
                                <form
                                    action="{{ route('fee-categories.destroy', $category) }}"
                                    method="POST"
                                    class="d-inline">

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="btn btn-danger btn-sm"
                                        title="Delete"
                                        onclick="return confirm('Are you sure you want to delete this fee category?')">

                                        <i class="bi bi-trash"></i>

                                    </button>

                                </form>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="5"
                                class="text-center py-4 text-muted">

                                <i class="bi bi-inbox fs-3 d-block mb-2"></i>

                                No fee categories found.

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>


            {{-- Pagination --}}
            @if($categories->hasPages())

                <div class="mt-3">

                    {{ $categories->links() }}

                </div>

            @endif

        </div>

    </div>

</div>

@endsection