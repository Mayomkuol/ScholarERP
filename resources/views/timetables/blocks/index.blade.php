@extends('layouts.app')

@section('content')

<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">

        <div>
            <h4 class="fw-bold mb-1">
                <i class="bi bi-calendar3-week me-2"></i>
                Timetable Blocks
            </h4>

            <p class="text-muted mb-0">
                Manage whole-term timetable schedules for classes and streams.
            </p>
        </div>

        @if(auth()->user()->can('timetables.create'))
            <a
                href="{{ route('timetables.blocks.create') }}"
                class="btn btn-primary"
            >
                <i class="bi bi-plus-lg me-1"></i>
                Create Timetable Block
            </a>
        @endif

    </div>


    {{-- Alerts --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle me-2"></i>
            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>
        </div>
    @endif


    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">

            <strong>
                <i class="bi bi-exclamation-triangle me-2"></i>
                Please correct the following:
            </strong>

            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>
    @endif


    {{-- Filters --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <form
                method="GET"
                action="{{ route('timetables.blocks.index') }}"
            >

                <div class="row g-3 align-items-end">

                    <div class="col-lg-3 col-md-6">

                        <label class="form-label fw-semibold">
                            Academic Year
                        </label>

                        <select
                            name="academic_year_id"
                            class="form-select"
                        >

                            <option value="">
                                All Academic Years
                            </option>

                            @foreach($academicYears as $year)

                                <option
                                    value="{{ $year->id }}"
                                    @selected(request('academic_year_id') == $year->id)
                                >
                                    {{ $year->name }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-lg-2 col-md-6">

                        <label class="form-label fw-semibold">
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

                                <option
                                    value="{{ $term->id }}"
                                    @selected(request('term_id') == $term->id)
                                >
                                    {{ $term->name }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-lg-3 col-md-6">

                        <label class="form-label fw-semibold">
                            Class
                        </label>

                        <select
                            name="school_class_id"
                            class="form-select"
                        >

                            <option value="">
                                All Classes
                            </option>

                            @foreach($classes as $class)

                                <option
                                    value="{{ $class->id }}"
                                    @selected(request('school_class_id') == $class->id)
                                >
                                    {{ $class->name }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-lg-2 col-md-6">

                        <label class="form-label fw-semibold">
                            Status
                        </label>

                        <select
                            name="status"
                            class="form-select"
                        >

                            <option value="">
                                All Statuses
                            </option>

                            <option
                                value="draft"
                                @selected(request('status') === 'draft')
                            >
                                Draft
                            </option>

                            <option
                                value="published"
                                @selected(request('status') === 'published')
                            >
                                Published
                            </option>

                            <option
                                value="archived"
                                @selected(request('status') === 'archived')
                            >
                                Archived
                            </option>

                        </select>

                    </div>


                    <div class="col-lg-2 d-flex gap-2">

                        <button
                            type="submit"
                            class="btn btn-primary flex-fill"
                        >
                            <i class="bi bi-search me-1"></i>
                            Filter
                        </button>

                        <a
                            href="{{ route('timetables.blocks.index') }}"
                            class="btn btn-light border"
                            title="Reset filters"
                        >
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- Summary --}}
    <div class="row g-3 mb-4">

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body d-flex align-items-center">

                    <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3">
                        <i class="bi bi-calendar-week text-primary fs-4"></i>
                    </div>

                    <div>
                        <div class="text-muted small">
                            Total Blocks
                        </div>

                        <div class="fs-4 fw-bold">
                            {{ $blocks->total() }}
                        </div>
                    </div>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body d-flex align-items-center">

                    <div class="rounded-circle bg-success bg-opacity-10 p-3 me-3">
                        <i class="bi bi-check-circle text-success fs-4"></i>
                    </div>

                    <div>
                        <div class="text-muted small">
                            Current Page
                        </div>

                        <div class="fs-4 fw-bold">
                            {{ $blocks->count() }}
                        </div>
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Table --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white border-0 py-3">

            <div class="d-flex justify-content-between align-items-center">

                <div>
                    <h6 class="fw-bold mb-1">
                        Term Timetable Blocks
                    </h6>

                    <small class="text-muted">
                        Each block represents a timetable schedule covering a defined period.
                    </small>
                </div>

            </div>

        </div>


        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">

                    <tr>

                        <th>#</th>

                        <th>Block</th>

                        <th>Academic Period</th>

                        <th>Class / Stream</th>

                        <th>Effective Period</th>

                        <th>Lessons</th>

                        <th>Status</th>

                        <th class="text-end">
                            Actions
                        </th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($blocks as $block)

                        <tr>

                            <td class="text-muted">
                                {{ $blocks->firstItem() + $loop->index }}
                            </td>


                            <td>

                                <div class="fw-semibold">
                                    {{ $block->name }}
                                </div>

                                @if($block->notes)

                                    <small class="text-muted">
                                        {{ \Illuminate\Support\Str::limit($block->notes, 60) }}
                                    </small>

                                @endif

                            </td>


                            <td>

                                <div class="fw-semibold">
                                    {{ $block->academicYear?->name ?? '—' }}
                                </div>

                                <small class="text-muted">
                                    {{ $block->term?->name ?? '—' }}
                                </small>

                            </td>


                            <td>

                                <div class="fw-semibold">
                                    {{ $block->schoolClass?->name ?? '—' }}
                                </div>

                                <small class="text-muted">

                                    @if($block->stream)
                                        {{ $block->stream->name }}
                                    @else
                                        All Streams
                                    @endif

                                </small>

                            </td>


                            <td>

                                <div>
                                    {{ optional($block->effective_from)->format('d M Y') }}
                                </div>

                                <small class="text-muted">
                                    to
                                    {{ optional($block->effective_to)->format('d M Y') }}
                                </small>

                            </td>


                            <td>

                                <span class="badge bg-light text-dark border">

                                    <i class="bi bi-list-check me-1"></i>

                                    {{ $block->timetables_count ?? $block->timetables->count() }}

                                </span>

                            </td>


                            <td>

                                @if($block->isPublished())

                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle me-1"></i>
                                        Published
                                    </span>

                                @elseif($block->isDraft())

                                    <span class="badge bg-warning text-dark">
                                        <i class="bi bi-pencil-square me-1"></i>
                                        Draft
                                    </span>

                                @else

                                    <span class="badge bg-secondary">
                                        <i class="bi bi-archive me-1"></i>
                                        Archived
                                    </span>

                                @endif

                            </td>


                            <td class="text-end">

                                <div class="dropdown">

                                    <button
                                        class="btn btn-sm btn-light border"
                                        data-bs-toggle="dropdown"
                                    >
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>

                                    <ul class="dropdown-menu dropdown-menu-end">

                                        <li>

                                            <a
                                                class="dropdown-item"
                                                href="{{ route('timetables.blocks.show', $block) }}"
                                            >
                                                <i class="bi bi-eye me-2"></i>
                                                View Block
                                            </a>

                                        </li>


                                        @if(auth()->user()->can('timetables.edit'))

                                            <li>

                                                <a
                                                    class="dropdown-item"
                                                    href="{{ route('timetables.blocks.edit', $block) }}"
                                                >
                                                    <i class="bi bi-pencil me-2"></i>
                                                    Edit
                                                </a>

                                            </li>

                                        @endif


                                        @if(auth()->user()->can('timetables.delete'))

                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>

                                            <li>

                                                <form
                                                    method="POST"
                                                    action="{{ route('timetables.blocks.destroy', $block) }}"
                                                    onsubmit="return confirm('Delete this timetable block?');"
                                                >

                                                    @csrf
                                                    @method('DELETE')

                                                    <button
                                                        type="submit"
                                                        class="dropdown-item text-danger"
                                                    >
                                                        <i class="bi bi-trash me-2"></i>
                                                        Delete
                                                    </button>

                                                </form>

                                            </li>

                                        @endif

                                    </ul>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="8"
                                class="text-center py-5"
                            >

                                <div class="text-muted">

                                    <i class="bi bi-calendar-x fs-1 d-block mb-3"></i>

                                    <h6 class="fw-bold">
                                        No timetable blocks found
                                    </h6>

                                    <p class="mb-3">
                                        Create your first whole-term timetable block.
                                    </p>

                                    @if(auth()->user()->can('timetables.create'))

                                        <a
                                            href="{{ route('timetables.blocks.create') }}"
                                            class="btn btn-primary"
                                        >
                                            <i class="bi bi-plus-lg me-1"></i>
                                            Create Timetable Block
                                        </a>

                                    @endif

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        @if($blocks->hasPages())

            <div class="card-footer bg-white border-0">
                {{ $blocks->links() }}
            </div>

        @endif

    </div>

</div>

@endsection