@props([
    'title' => null,
    'subtitle' => null,
    'documentNumber' => null,
    'status' => null,
])

@php
    $school = \App\Models\School::first();
@endphp

<div class="school-document-header">

    <div class="row align-items-center">

        {{-- School Logo --}}
        <div class="col-md-2 text-center">

            @if($school?->logo)
                <img
                    src="{{ asset('storage/' . $school->logo) }}"
                    alt="School Logo"
                    class="img-fluid"
                    style="max-height: 100px; max-width: 120px;"
                >
            @else
                <img
                    src="{{ asset('images/logo.png') }}"
                    alt="School Logo"
                    class="img-fluid"
                    style="max-height: 100px; max-width: 120px;"
                    onerror="this.style.display='none'"
                >
            @endif

        </div>


        {{-- School Information --}}
        <div class="col-md-7 text-center text-md-start">

            <h2 class="fw-bold text-uppercase mb-1">
                {{ $school?->name ?? 'School Name' }}
            </h2>

            @if($school?->motto)
                <div class="fst-italic text-muted mb-2">
                    "{{ $school->motto }}"
                </div>
            @endif

            @if($school?->address)
                <div>
                    {{ $school->address }}
                </div>
            @endif

            <div class="small">

                @if($school?->phone)
                    <strong>Tel:</strong> {{ $school->phone }}
                @endif

                @if($school?->email)
                    <span class="mx-2">|</span>
                    <strong>Email:</strong> {{ $school->email }}
                @endif

            </div>

            <div class="small">

                @if($school?->website)
                    <strong>Web:</strong> {{ $school->website }}
                @endif

            </div>

            <div class="small mt-1">

                @if($school?->school_code)
                    <strong>School Code:</strong>
                    {{ $school->school_code }}
                @endif

                @if($school?->registration_number)
                    <span class="mx-2">|</span>

                    <strong>Reg. No:</strong>
                    {{ $school->registration_number }}
                @endif

            </div>

        </div>


        {{-- Document Information --}}
        <div class="col-md-3 text-center text-md-end">

            @if($status)

                @if(strtoupper($status) === 'PAID')

                    <span class="badge bg-success px-3 py-2">
                        PAID
                    </span>

                @elseif(strtoupper($status) === 'REVERSED')

                    <span class="badge bg-danger px-3 py-2">
                        REVERSED
                    </span>

                @else

                    <span class="badge bg-secondary px-3 py-2">
                        {{ strtoupper($status) }}
                    </span>

                @endif

            @endif


            @if($title)

                <h5 class="fw-bold mt-3 mb-1">
                    {{ $title }}
                </h5>

            @endif


            @if($subtitle)

                <div class="text-muted small">
                    {{ $subtitle }}
                </div>

            @endif


            @if($documentNumber)

                <div class="mt-2">

                    <small class="text-muted">
                        Document No.
                    </small>

                    <div class="fw-bold text-primary">
                        {{ $documentNumber }}
                    </div>

                </div>

            @endif

        </div>

    </div>

    <hr class="my-3">

</div>