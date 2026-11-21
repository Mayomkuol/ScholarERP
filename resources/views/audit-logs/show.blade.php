@extends('layouts.app')

@section('content')

<div class="container-fluid py-4">

    {{-- Page Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1 fw-bold">
                <i class="fas fa-history me-2"></i>
                Audit Log Details
            </h4>

            <p class="text-muted mb-0">
                Detailed information about this ScholarERP activity.
            </p>
        </div>

        <div class="mt-3 mt-md-0">

            <a href="{{ route('audit-logs.index') }}"
               class="btn btn-outline-secondary">

                <i class="fas fa-arrow-left me-1"></i>
                Back to Audit Logs

            </a>

        </div>

    </div>


    {{-- Activity Summary --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white border-0 py-3">

            <div class="d-flex align-items-center">

                <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-3">
                    <i class="fas fa-shield-alt text-primary"></i>
                </div>

                <div>
                    <h5 class="mb-0 fw-bold">
                        Activity Information
                    </h5>

                    <small class="text-muted">
                        Audit record #{{ $auditLog->id }}
                    </small>
                </div>

            </div>

        </div>


        <div class="card-body">

            <div class="row g-4">

                {{-- User --}}
                <div class="col-md-6 col-lg-3">

                    <div class="small text-muted mb-1">
                        User
                    </div>

                    <div class="fw-semibold">

                        @if($auditLog->user)

                            {{ $auditLog->user->name }}

                        @else

                            System

                        @endif

                    </div>

                </div>


                {{-- Email --}}
                <div class="col-md-6 col-lg-3">

                    <div class="small text-muted mb-1">
                        Email
                    </div>

                    <div class="fw-semibold">

                        @if($auditLog->user)

                            {{ $auditLog->user->email }}

                        @else

                            —

                        @endif

                    </div>

                </div>


                {{-- Action --}}
                <div class="col-md-6 col-lg-3">

                    <div class="small text-muted mb-1">
                        Action
                    </div>

                    @php

                        $actionClass = match(strtolower($auditLog->action)) {

                            'created' => 'bg-success',

                            'updated' => 'bg-warning text-dark',

                            'deleted' => 'bg-danger',

                            default => 'bg-primary'

                        };

                    @endphp

                    <span class="badge {{ $actionClass }} px-3 py-2">

                        {{ ucfirst(str_replace('_', ' ', $auditLog->action)) }}

                    </span>

                </div>


                {{-- Module --}}
                <div class="col-md-6 col-lg-3">

                    <div class="small text-muted mb-1">
                        Module
                    </div>

                    <span class="badge bg-light text-dark border px-3 py-2">

                        {{ $auditLog->module ?: 'System' }}

                    </span>

                </div>


                {{-- Description --}}
                <div class="col-12">

                    <div class="small text-muted mb-1">
                        Description
                    </div>

                    <div class="p-3 bg-light rounded">

                        <i class="fas fa-info-circle text-primary me-2"></i>

                        {{ $auditLog->description ?: 'No description available.' }}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Changes --}}
    <div class="row g-4">

        {{-- Previous Values --}}
        <div class="col-lg-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white border-0 py-3">

                    <h5 class="mb-0 fw-bold">

                        <i class="fas fa-history text-danger me-2"></i>

                        Previous Values

                    </h5>

                </div>


                <div class="card-body">

                    @if(!empty($auditLog->old_values))

                        <div class="audit-json">

                            @foreach($auditLog->old_values as $key => $value)

                                <div class="border-bottom py-2">

                                    <div class="small text-muted">
                                        {{ ucwords(str_replace('_', ' ', $key)) }}
                                    </div>

                                    <div class="fw-semibold text-break">

                                        @if(is_array($value))

                                            {{ json_encode($value, JSON_PRETTY_PRINT) }}

                                        @elseif(is_null($value))

                                            <span class="text-muted">
                                                —
                                            </span>

                                        @else

                                            {{ $value }}

                                        @endif

                                    </div>

                                </div>

                            @endforeach

                        </div>

                    @else

                        <div class="text-center text-muted py-4">

                            <i class="fas fa-minus-circle fa-2x mb-2"></i>

                            <p class="mb-0">
                                No previous values.
                            </p>

                        </div>

                    @endif

                </div>

            </div>

        </div>


        {{-- New Values --}}
        <div class="col-lg-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white border-0 py-3">

                    <h5 class="mb-0 fw-bold">

                        <i class="fas fa-check-circle text-success me-2"></i>

                        New Values

                    </h5>

                </div>


                <div class="card-body">

                    @if(!empty($auditLog->new_values))

                        <div class="audit-json">

                            @foreach($auditLog->new_values as $key => $value)

                                <div class="border-bottom py-2">

                                    <div class="small text-muted">
                                        {{ ucwords(str_replace('_', ' ', $key)) }}
                                    </div>

                                    <div class="fw-semibold text-break">

                                        @if(is_array($value))

                                            {{ json_encode($value, JSON_PRETTY_PRINT) }}

                                        @elseif(is_null($value))

                                            <span class="text-muted">
                                                —
                                            </span>

                                        @else

                                            {{ $value }}

                                        @endif

                                    </div>

                                </div>

                            @endforeach

                        </div>

                    @else

                        <div class="text-center text-muted py-4">

                            <i class="fas fa-minus-circle fa-2x mb-2"></i>

                            <p class="mb-0">
                                No new values.
                            </p>

                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>


    {{-- Request Information --}}
    <div class="card border-0 shadow-sm mt-4">

        <div class="card-header bg-white border-0 py-3">

            <h5 class="mb-0 fw-bold">

                <i class="fas fa-network-wired text-primary me-2"></i>

                Request Information

            </h5>

        </div>


        <div class="card-body">

            <div class="row g-4">

                {{-- IP --}}
                <div class="col-md-4">

                    <div class="small text-muted mb-1">
                        IP Address
                    </div>

                    <div class="fw-semibold">

                        <span class="badge bg-light text-dark border">

                            {{ $auditLog->ip_address ?: 'Unknown' }}

                        </span>

                    </div>

                </div>


                {{-- Date --}}
                <div class="col-md-4">

                    <div class="small text-muted mb-1">
                        Date
                    </div>

                    <div class="fw-semibold">

                        {{ $auditLog->created_at?->format('d F Y') }}

                    </div>

                </div>


                {{-- Time --}}
                <div class="col-md-4">

                    <div class="small text-muted mb-1">
                        Time
                    </div>

                    <div class="fw-semibold">

                        {{ $auditLog->created_at?->format('h:i:s A') }}

                    </div>

                </div>


                {{-- User Agent --}}
                <div class="col-12">

                    <div class="small text-muted mb-1">
                        User Agent
                    </div>

                    <div class="bg-light rounded p-3 text-break small">

                        {{ $auditLog->user_agent ?: 'Unknown' }}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Auditable Record --}}
    @if($auditLog->auditable)

        <div class="card border-0 shadow-sm mt-4">

            <div class="card-header bg-white border-0 py-3">

                <h5 class="mb-0 fw-bold">

                    <i class="fas fa-link text-primary me-2"></i>

                    Related Record

                </h5>

            </div>


            <div class="card-body">

                <div class="row">

                    <div class="col-md-4">

                        <div class="small text-muted">
                            Record Type
                        </div>

                        <div class="fw-semibold">

                            {{ class_basename($auditLog->auditable_type) }}

                        </div>

                    </div>


                    <div class="col-md-4">

                        <div class="small text-muted">
                            Record ID
                        </div>

                        <div class="fw-semibold">

                            #{{ $auditLog->auditable_id }}

                        </div>

                    </div>

                </div>

            </div>

        </div>

    @endif


</div>


<style>

.audit-json {
    max-height: 650px;
    overflow-y: auto;
}

.audit-json .border-bottom:last-child {
    border-bottom: 0 !important;
}

.text-break {
    word-break: break-word;
}

</style>

@endsection