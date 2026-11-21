@extends('layouts.app')

@section('content')

<div class="container-fluid py-4">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1 fw-bold">
                <i class="bi bi-database-fill me-2"></i>
                Backup & Restore
            </h4>

            <p class="text-muted mb-0">
                Protect and manage your ScholarERP database backups.
            </p>
        </div>

        <form
            action="{{ route('backups.create') }}"
            method="POST"
        >
            @csrf

            <button
                type="submit"
                class="btn btn-primary"
                onclick="return confirm('Create a new database backup now?');"
            >
                <i class="bi bi-cloud-arrow-down me-1"></i>
                Create New Backup
            </button>
        </form>

    </div>


    {{-- Success Message --}}
    @if(session('success'))

        <div
            class="alert alert-success alert-dismissible fade show"
            role="alert"
        >
            <i class="bi bi-check-circle-fill me-2"></i>

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>
        </div>

    @endif


    {{-- Error Message --}}
    @if(session('error'))

        <div
            class="alert alert-danger alert-dismissible fade show"
            role="alert"
        >
            <i class="bi bi-exclamation-triangle-fill me-2"></i>

            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>
        </div>

    @endif


    {{-- Database Information --}}
    <div class="row g-4 mb-4">

        <div class="col-md-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex align-items-center">

                        <div class="bg-primary bg-opacity-10 rounded p-3 me-3">
                            <i class="bi bi-database-fill text-primary fs-4"></i>
                        </div>

                        <div>

                            <small class="text-muted">
                                Database
                            </small>

                            <h6 class="mb-0 fw-bold">
                                {{ config('database.connections.mysql.database') }}
                            </h6>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex align-items-center">

                        <div class="bg-success bg-opacity-10 rounded p-3 me-3">
                            <i class="bi bi-check-circle-fill text-success fs-4"></i>
                        </div>

                        <div>

                            <small class="text-muted">
                                Database Status
                            </small>

                            <h6 class="mb-0 fw-bold text-success">
                                Connected
                            </h6>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex align-items-center">

                        <div class="bg-info bg-opacity-10 rounded p-3 me-3">
                            <i class="bi bi-archive-fill text-info fs-4"></i>
                        </div>

                        <div>

                            <small class="text-muted">
                                Available Backups
                            </small>

                            <h6 class="mb-0 fw-bold">
                                {{ $backups->count() }}
                            </h6>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Important Warning --}}
    <div class="alert alert-warning border-0 shadow-sm">

        <div class="d-flex">

            <div class="me-3">
                <i class="bi bi-shield-exclamation fs-4"></i>
            </div>

            <div>

                <h6 class="fw-bold mb-1">
                    Important
                </h6>

                <p class="mb-0">
                    Restoring a backup will replace the current ScholarERP
                    database with the selected backup. A safety backup of the
                    current database will automatically be created before
                    restoration.
                </p>

            </div>

        </div>

    </div>


    {{-- Backup History --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white py-3">

            <div class="d-flex justify-content-between align-items-center">

                <div>
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-clock-history me-2"></i>
                        Backup History
                    </h5>

                    <small class="text-muted">
                        Database backups stored on this server
                    </small>
                </div>

            </div>

        </div>


        <div class="card-body p-0">

            @if($backups->count())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th class="px-4">
                                    Backup File
                                </th>

                                <th>
                                    Type
                                </th>

                                <th>
                                    Created
                                </th>

                                <th>
                                    Size
                                </th>

                                <th class="text-end px-4">
                                    Actions
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($backups as $backup)

                                <tr>

                                    <td class="px-4">

                                        <div class="d-flex align-items-center">

                                            <div class="me-3">

                                                <i class="bi bi-file-earmark-database-fill text-primary fs-4"></i>

                                            </div>

                                            <div>

                                                <div class="fw-semibold">
                                                    {{ $backup['name'] }}
                                                </div>

                                                <small class="text-muted">
                                                    SQL Database Backup
                                                </small>

                                            </div>

                                        </div>

                                    </td>


                                    <td>

                                        @if($backup['is_safety'])

                                            <span class="badge bg-warning text-dark">
                                                <i class="bi bi-shield-check me-1"></i>
                                                Safety Backup
                                            </span>

                                        @else

                                            <span class="badge bg-primary">
                                                <i class="bi bi-database me-1"></i>
                                                Manual Backup
                                            </span>

                                        @endif

                                    </td>


                                    <td>

                                        <div>
                                            {{ $backup['created_at'] }}
                                        </div>

                                    </td>


                                    <td>

                                        <span class="fw-semibold">
                                            {{ $backup['size_human'] }}
                                        </span>

                                    </td>


                                    <td class="text-end px-4">

                                        <div class="btn-group">

                                            {{-- Download --}}
                                            <a
                                                href="{{ route('backups.download', ['backup' => $backup['name']]) }}"
                                                class="btn btn-sm btn-outline-primary"
                                                title="Download Backup"
                                            >
                                                <i class="bi bi-download"></i>
                                            </a>


                                            {{-- Restore --}}
                                            <form
                                                action="{{ route('backups.restore', ['backup' => $backup['name']]) }}"
                                                method="POST"
                                                class="d-inline"
                                            >

                                                @csrf

                                                <button
                                                    type="submit"
                                                    class="btn btn-sm btn-outline-warning"
                                                    title="Restore Backup"
                                                    onclick="return confirmRestore('{{ addslashes($backup['name']) }}');"
                                                >
                                                    <i class="bi bi-arrow-counterclockwise"></i>
                                                </button>

                                            </form>


                                            {{-- Delete --}}
                                            <form
                                                action="{{ route('backups.destroy', ['backup' => $backup['name']]) }}"
                                                method="POST"
                                                class="d-inline"
                                            >

                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="btn btn-sm btn-outline-danger"
                                                    title="Delete Backup"
                                                    onclick="return confirmDelete('{{ addslashes($backup['name']) }}');"
                                                >
                                                    <i class="bi bi-trash"></i>
                                                </button>

                                            </form>

                                        </div>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                {{-- No Backups --}}
                <div class="text-center py-5">

                    <div class="mb-3">

                        <i
                            class="bi bi-database-x text-muted"
                            style="font-size: 4rem;"
                        ></i>

                    </div>

                    <h5 class="fw-bold">
                        No Backups Found
                    </h5>

                    <p class="text-muted mb-4">
                        You haven't created any ScholarERP database backups yet.
                    </p>

                    <form
                        action="{{ route('backups.create') }}"
                        method="POST"
                    >

                        @csrf

                        <button
                            type="submit"
                            class="btn btn-primary"
                            onclick="return confirm('Create a new database backup now?');"
                        >
                            <i class="bi bi-database-add me-1"></i>
                            Create Your First Backup
                        </button>

                    </form>

                </div>

            @endif

        </div>

    </div>


    {{-- Security Information --}}
    <div class="card border-0 shadow-sm mt-4">

        <div class="card-body">

            <h6 class="fw-bold mb-3">
                <i class="bi bi-shield-lock me-2"></i>
                Backup Security
            </h6>

            <div class="row">

                <div class="col-md-4 mb-3 mb-md-0">

                    <div class="d-flex">

                        <i class="bi bi-person-lock text-primary fs-4 me-3"></i>

                        <div>

                            <strong>Super Admin Only</strong>

                            <p class="small text-muted mb-0">
                                Backup and restore operations are restricted
                                to Super Administrators.
                            </p>

                        </div>

                    </div>

                </div>


                <div class="col-md-4 mb-3 mb-md-0">

                    <div class="d-flex">

                        <i class="bi bi-shield-check text-success fs-4 me-3"></i>

                        <div>

                            <strong>Safety Backup</strong>

                            <p class="small text-muted mb-0">
                                A safety backup is created automatically before
                                every database restoration.
                            </p>

                        </div>

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="d-flex">

                        <i class="bi bi-file-earmark-lock text-warning fs-4 me-3"></i>

                        <div>

                            <strong>SQL Format</strong>

                            <p class="small text-muted mb-0">
                                Backups contain the ScholarERP MySQL/MariaDB
                                database in SQL format.
                            </p>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


<script>

function confirmRestore(filename)
{
    return confirm(
        "WARNING: Database Restoration\n\n" +

        "You are about to restore:\n\n" +

        filename +

        "\n\n" +

        "This will replace the current ScholarERP database.\n\n" +

        "A safety backup of the current database will automatically " +
        "be created before restoration.\n\n" +

        "Do you want to continue?"
    );
}


function confirmDelete(filename)
{
    return confirm(
        "Delete Backup?\n\n" +

        filename +

        "\n\n" +

        "This backup file will be permanently deleted.\n\n" +

        "This action cannot be undone.\n\n" +

        "Continue?"
    );
}

</script>

@endsection