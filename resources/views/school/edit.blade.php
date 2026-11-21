{{-- =========================================================
     ScholarERP
     School Setup / Edit School Profile
     resources/views/school/edit.blade.php
     ========================================================= --}}

@extends('layouts.app')

@section('title', 'School Setup')

@section('content')

<style>

    /* =========================================================
       PAGE
       ========================================================= */

    .school-setup-page {
        padding: 24px;
        background: #f8fafc;
        min-height: calc(100vh - 70px);
    }

    .school-setup-container {
        max-width: 1250px;
        margin: 0 auto;
    }


    /* =========================================================
       HEADER
       ========================================================= */

    .setup-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 24px;
    }

    .setup-header-left {
        min-width: 0;
    }

    .setup-title {
        margin: 0;
        color: #0f172a;
        font-size: 25px;
        font-weight: 700;
        letter-spacing: -0.4px;
    }

    .setup-description {
        margin: 6px 0 0;
        color: #64748b;
        font-size: 13px;
    }

    .setup-save-button {
        display: inline-flex;
        align-items: center;
        gap: 8px;

        padding: 10px 18px;

        border: 0;
        border-radius: 9px;

        background: #2563eb;
        color: #fff;

        font-size: 13px;
        font-weight: 600;

        box-shadow: 0 5px 14px rgba(37, 99, 235, .18);

        transition: .2s ease;
    }

    .setup-save-button:hover {
        background: #1d4ed8;
        transform: translateY(-1px);
        color: #fff;
    }


    /* =========================================================
       ALERTS
       ========================================================= */

    .setup-alert {
        border-radius: 10px;
        border: 1px solid;
        padding: 13px 15px;
        margin-bottom: 18px;
        font-size: 13px;
    }

    .setup-alert-success {
        background: #f0fdf4;
        border-color: #bbf7d0;
        color: #166534;
    }

    .setup-alert-danger {
        background: #fef2f2;
        border-color: #fecaca;
        color: #991b1b;
    }

    .setup-alert ul {
        margin: 7px 0 0 18px;
        padding: 0;
    }


    /* =========================================================
       CARDS
       ========================================================= */

    .setup-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 13px;
        margin-bottom: 20px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(15, 23, 42, .035);
    }

    .setup-card-header {
        padding: 16px 20px;

        display: flex;
        align-items: center;
        gap: 11px;

        border-bottom: 1px solid #e2e8f0;

        background: #fff;
    }

    .setup-section-icon {
        width: 36px;
        height: 36px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 9px;

        background: #eff6ff;
        color: #2563eb;

        font-size: 16px;
        flex-shrink: 0;
    }

    .setup-card-title {
        margin: 0;
        color: #0f172a;
        font-size: 15px;
        font-weight: 700;
    }

    .setup-card-description {
        margin: 3px 0 0;
        color: #94a3b8;
        font-size: 11px;
    }

    .setup-card-body {
        padding: 20px;
    }


    /* =========================================================
       FORM
       ========================================================= */

    .form-label {
        margin-bottom: 6px;
        color: #334155;
        font-size: 12px;
        font-weight: 600;
    }

    .form-control,
    .form-select {
        min-height: 42px;

        border-color: #dbe2ea;

        border-radius: 8px;

        color: #0f172a;

        font-size: 13px;

        box-shadow: none;

        transition: .2s ease;
    }

    textarea.form-control {
        min-height: 90px;
        resize: vertical;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #60a5fa;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, .08);
    }

    .form-text {
        color: #94a3b8;
        font-size: 10px;
        margin-top: 5px;
    }

    .required {
        color: #dc2626;
    }

    .field-error {
        margin-top: 5px;
        color: #dc2626;
        font-size: 10px;
    }


    /* =========================================================
       IMAGE UPLOAD
       ========================================================= */

    .image-upload-card {
        height: 100%;

        padding: 15px;

        border: 1px solid #e2e8f0;
        border-radius: 10px;

        background: #f8fafc;
    }

    .image-upload-title {
        margin-bottom: 3px;

        color: #0f172a;

        font-size: 12px;
        font-weight: 700;
    }

    .image-upload-description {
        margin-bottom: 12px;

        color: #94a3b8;

        font-size: 10px;
    }

    .image-preview-wrapper {
        width: 100%;
        height: 155px;

        margin-bottom: 12px;

        display: flex;
        align-items: center;
        justify-content: center;

        border: 1px dashed #cbd5e1;
        border-radius: 9px;

        background: #fff;

        overflow: hidden;
    }

    .image-preview {
        max-width: 100%;
        max-height: 145px;
        object-fit: contain;
    }

    .image-placeholder {
        text-align: center;
        color: #94a3b8;
        font-size: 11px;
    }

    .image-placeholder i {
        display: block;
        margin-bottom: 7px;
        font-size: 28px;
        color: #cbd5e1;
    }

    .image-actions {
        display: flex;
        align-items: center;
        gap: 7px;
        flex-wrap: wrap;
    }

    .upload-label {
        display: inline-flex;
        align-items: center;
        gap: 6px;

        padding: 7px 11px;

        border-radius: 7px;

        background: #2563eb;
        color: #fff;

        font-size: 11px;
        font-weight: 600;

        cursor: pointer;
    }

    .upload-label:hover {
        background: #1d4ed8;
    }

    .remove-image-button {
        display: inline-flex;
        align-items: center;
        gap: 6px;

        padding: 7px 11px;

        border: 1px solid #fecaca;
        border-radius: 7px;

        background: #fff;
        color: #dc2626;

        font-size: 11px;
        font-weight: 600;

        cursor: pointer;
    }

    .remove-image-button:hover {
        background: #fef2f2;
    }

    .image-input {
        display: none;
    }


    /* =========================================================
       TOGGLE
       ========================================================= */

    .notification-row {
        min-height: 58px;

        display: flex;
        align-items: center;
        justify-content: space-between;

        padding: 13px 15px;

        border: 1px solid #e2e8f0;
        border-radius: 9px;

        background: #f8fafc;

        margin-bottom: 10px;
    }

    .notification-row:last-child {
        margin-bottom: 0;
    }

    .notification-title {
        margin: 0;
        color: #334155;
        font-size: 12px;
        font-weight: 600;
    }

    .notification-description {
        margin: 3px 0 0;
        color: #94a3b8;
        font-size: 10px;
    }

    .form-switch .form-check-input {
        width: 38px;
        height: 20px;
        cursor: pointer;
    }


    /* =========================================================
       BOTTOM ACTIONS
       ========================================================= */

    .setup-bottom-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;

        padding: 18px 20px;

        border-top: 1px solid #e2e8f0;

        background: #f8fafc;
    }

    .cancel-button {
        display: inline-flex;
        align-items: center;
        gap: 7px;

        padding: 9px 16px;

        border: 1px solid #cbd5e1;
        border-radius: 8px;

        background: #fff;
        color: #475569;

        font-size: 12px;
        font-weight: 600;

        text-decoration: none;
    }

    .cancel-button:hover {
        background: #f8fafc;
        color: #334155;
    }


    /* =========================================================
       RESPONSIVE
       ========================================================= */

    @media (max-width: 767px) {

        .school-setup-page {
            padding: 15px;
        }

        .setup-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .setup-save-button {
            width: 100%;
            justify-content: center;
        }

        .setup-card-body {
            padding: 15px;
        }

        .setup-card-header {
            padding: 14px 15px;
        }

    }

</style>


<div class="school-setup-page">

    <div class="school-setup-container">


        {{-- =====================================================
             PAGE HEADER
             ===================================================== --}}

        <div class="setup-header">

            <div class="setup-header-left">

                <h1 class="setup-title">
                    School Setup
                </h1>

                <p class="setup-description">
                    Configure your school's information, branding,
                    contact details, academic and system settings.
                </p>

            </div>

            <button
                type="submit"
                form="schoolSetupForm"
                class="setup-save-button"
            >
                <i class="bi bi-check-lg"></i>
                Save Changes
            </button>

        </div>


        {{-- =====================================================
             SUCCESS MESSAGE
             ===================================================== --}}

        @if(session('success'))

            <div class="setup-alert setup-alert-success">

                <i class="bi bi-check-circle-fill me-1"></i>

                {{ session('success') }}

            </div>

        @endif


        {{-- =====================================================
             VALIDATION ERRORS
             ===================================================== --}}

        @if($errors->any())

            <div class="setup-alert setup-alert-danger">

                <strong>
                    <i class="bi bi-exclamation-triangle-fill me-1"></i>
                    Please correct the following errors:
                </strong>

                <ul>

                    @foreach($errors->all() as $error)

                        <li>{{ $error }}</li>

                    @endforeach

                </ul>

            </div>

        @endif


        {{-- =====================================================
             FORM
             ===================================================== --}}

        <form
            id="schoolSetupForm"
            action="{{ route('school.update') }}"
            method="POST"
            enctype="multipart/form-data"
        >

            @csrf

            @method('PUT')


            {{-- =================================================
                 SCHOOL INFORMATION
                 ================================================= --}}

            <div class="setup-card">

                <div class="setup-card-header">

                    <div class="setup-section-icon">
                        <i class="bi bi-building"></i>
                    </div>

                    <div>

                        <h2 class="setup-card-title">
                            School Information
                        </h2>

                        <p class="setup-card-description">
                            Basic identification and registration details.
                        </p>

                    </div>

                </div>


                <div class="setup-card-body">

                    <div class="row g-3">


                        {{-- School Name --}}

                        <div class="col-md-6">

                            <label class="form-label">
                                School Name
                                <span class="required">*</span>
                            </label>

                            <input
                                type="text"
                                name="name"
                                class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name', $school->name ?? '') }}"
                                placeholder="e.g. Juba International Academy"
                                required
                            >

                            @error('name')
                                <div class="field-error">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- School Code --}}

                        <div class="col-md-6">

                            <label class="form-label">
                                School Code
                                <span class="required">*</span>
                            </label>

                            <input
                                type="text"
                                name="code"
                                class="form-control @error('code') is-invalid @enderror"
                                value="{{ old('code', $school->code ?? '') }}"
                                placeholder="e.g. JIA001"
                                required
                            >

                            @error('code')
                                <div class="field-error">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Registration Number --}}

                        <div class="col-md-6">

                            <label class="form-label">
                                Registration No.
                            </label>

                            <input
                                type="text"
                                name="registration_no"
                                class="form-control"
                                value="{{ old('registration_no', $school->registration_no ?? '') }}"
                                placeholder="e.g. SCH/REG/00125"
                            >

                        </div>


                        {{-- Motto --}}

                        <div class="col-md-6">

                            <label class="form-label">
                                Motto
                            </label>

                            <input
                                type="text"
                                name="motto"
                                class="form-control"
                                value="{{ old('motto', $school->motto ?? '') }}"
                                placeholder="e.g. Excellence Through Knowledge"
                            >

                        </div>


                        {{-- School Type --}}

                        <div class="col-md-6">

                            <label class="form-label">
                                School Type
                            </label>

                            <select
                                name="school_type"
                                class="form-select"
                            >

                                <option value="">
                                    Select school type
                                </option>

                                @foreach([
                                    'Primary School',
                                    'Secondary School',
                                    'Primary & Secondary School',
                                    'International School',
                                    'College',
                                    'University',
                                    'Vocational / Technical'
                                ] as $type)

                                    <option
                                        value="{{ $type }}"
                                        {{ old('school_type', $school->school_type ?? '') == $type ? 'selected' : '' }}
                                    >
                                        {{ $type }}
                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- Ownership --}}

                        <div class="col-md-6">

                            <label class="form-label">
                                Ownership
                            </label>

                            <select
                                name="ownership"
                                class="form-select"
                            >

                                <option value="">
                                    Select ownership
                                </option>

                                @foreach([
                                    'Public',
                                    'Private',
                                    'Faith-Based',
                                    'Community',
                                    'Non-Profit'
                                ] as $ownership)

                                    <option
                                        value="{{ $ownership }}"
                                        {{ old('ownership', $school->ownership ?? '') == $ownership ? 'selected' : '' }}
                                    >
                                        {{ $ownership }}
                                    </option>

                                @endforeach

                            </select>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================================
                 BRANDING
                 ================================================= --}}

            <div class="setup-card">

                <div class="setup-card-header">

                    <div class="setup-section-icon">
                        <i class="bi bi-palette"></i>
                    </div>

                    <div>

                        <h2 class="setup-card-title">
                            Branding
                        </h2>

                        <p class="setup-card-description">
                            Upload the visual assets used throughout ScholarERP.
                        </p>

                    </div>

                </div>


                <div class="setup-card-body">

                    <div class="row g-3">


                        {{-- =================================================
                             LOGO
                             ================================================= --}}

                        <div class="col-md-6">

                            <div class="image-upload-card">

                                <div class="image-upload-title">
                                    School Logo
                                </div>

                                <div class="image-upload-description">
                                    Recommended: PNG with transparent background.
                                </div>

                                <div
                                    class="image-preview-wrapper"
                                    id="logoPreviewWrapper"
                                >

                                    @if(!empty($school->logo))

                                        <img
                                            src="{{ asset('storage/' . $school->logo) }}"
                                            class="image-preview"
                                            id="logoPreview"
                                            alt="School Logo"
                                        >

                                    @else

                                        <div
                                            class="image-placeholder"
                                            id="logoPlaceholder"
                                        >
                                            <i class="bi bi-image"></i>
                                            No logo uploaded
                                        </div>

                                        <img
                                            src=""
                                            class="image-preview d-none"
                                            id="logoPreview"
                                            alt="Logo Preview"
                                        >

                                    @endif

                                </div>


                                <input
                                    type="file"
                                    name="logo"
                                    id="logoInput"
                                    class="image-input"
                                    accept="image/png,image/jpeg,image/jpg"
                                >


                                <div class="image-actions">

                                    <label
                                        for="logoInput"
                                        class="upload-label"
                                    >
                                        <i class="bi bi-upload"></i>
                                        Choose Logo
                                    </label>

                                    @if(!empty($school->logo))

                                        <button
                                            type="button"
                                            class="remove-image-button"
                                            onclick="removeImage('logo')"
                                        >
                                            <i class="bi bi-trash"></i>
                                            Remove
                                        </button>

                                    @endif

                                </div>

                                <input
                                    type="hidden"
                                    name="remove_logo"
                                    id="remove_logo"
                                    value="0"
                                >

                            </div>

                        </div>


                        {{-- =================================================
                             STAMP
                             ================================================= --}}

                        <div class="col-md-6">

                            <div class="image-upload-card">

                                <div class="image-upload-title">
                                    School Stamp
                                </div>

                                <div class="image-upload-description">
                                    Official school stamp or seal.
                                </div>

                                <div
                                    class="image-preview-wrapper"
                                    id="stampPreviewWrapper"
                                >

                                    @if(!empty($school->stamp))

                                        <img
                                            src="{{ asset('storage/' . $school->stamp) }}"
                                            class="image-preview"
                                            id="stampPreview"
                                            alt="School Stamp"
                                        >

                                    @else

                                        <div
                                            class="image-placeholder"
                                            id="stampPlaceholder"
                                        >
                                            <i class="bi bi-patch-check"></i>
                                            No stamp uploaded
                                        </div>

                                        <img
                                            src=""
                                            class="image-preview d-none"
                                            id="stampPreview"
                                            alt="Stamp Preview"
                                        >

                                    @endif

                                </div>


                                <input
                                    type="file"
                                    name="stamp"
                                    id="stampInput"
                                    class="image-input"
                                    accept="image/png,image/jpeg,image/jpg"
                                >


                                <div class="image-actions">

                                    <label
                                        for="stampInput"
                                        class="upload-label"
                                    >
                                        <i class="bi bi-upload"></i>
                                        Choose Stamp
                                    </label>

                                    @if(!empty($school->stamp))

                                        <button
                                            type="button"
                                            class="remove-image-button"
                                            onclick="removeImage('stamp')"
                                        >
                                            <i class="bi bi-trash"></i>
                                            Remove
                                        </button>

                                    @endif

                                </div>

                                <input
                                    type="hidden"
                                    name="remove_stamp"
                                    id="remove_stamp"
                                    value="0"
                                >

                            </div>

                        </div>


                        {{-- =================================================
                             FAVICON
                             ================================================= --}}

                        <div class="col-md-6">

                            <div class="image-upload-card">

                                <div class="image-upload-title">
                                    Favicon
                                </div>

                                <div class="image-upload-description">
                                    Small icon displayed in the browser tab.
                                </div>

                                <div
                                    class="image-preview-wrapper"
                                    id="faviconPreviewWrapper"
                                >

                                    @if(!empty($school->favicon))

                                        <img
                                            src="{{ asset('storage/' . $school->favicon) }}"
                                            class="image-preview"
                                            id="faviconPreview"
                                            alt="Favicon"
                                        >

                                    @else

                                        <div
                                            class="image-placeholder"
                                            id="faviconPlaceholder"
                                        >
                                            <i class="bi bi-browser-chrome"></i>
                                            No favicon uploaded
                                        </div>

                                        <img
                                            src=""
                                            class="image-preview d-none"
                                            id="faviconPreview"
                                            alt="Favicon Preview"
                                        >

                                    @endif

                                </div>


                                <input
                                    type="file"
                                    name="favicon"
                                    id="faviconInput"
                                    class="image-input"
                                    accept="image/png,image/jpeg,image/jpg,image/x-icon"
                                >


                                <div class="image-actions">

                                    <label
                                        for="faviconInput"
                                        class="upload-label"
                                    >
                                        <i class="bi bi-upload"></i>
                                        Choose Favicon
                                    </label>

                                    @if(!empty($school->favicon))

                                        <button
                                            type="button"
                                            class="remove-image-button"
                                            onclick="removeImage('favicon')"
                                        >
                                            <i class="bi bi-trash"></i>
                                            Remove
                                        </button>

                                    @endif

                                </div>

                                <input
                                    type="hidden"
                                    name="remove_favicon"
                                    id="remove_favicon"
                                    value="0"
                                >

                            </div>

                        </div>


                        {{-- =================================================
                             LOGIN BACKGROUND
                             ================================================= --}}

                        <div class="col-md-6">

                            <div class="image-upload-card">

                                <div class="image-upload-title">
                                    Login Background
                                </div>

                                <div class="image-upload-description">
                                    Background image displayed on the login page.
                                </div>

                                <div
                                    class="image-preview-wrapper"
                                    id="loginBackgroundPreviewWrapper"
                                >

                                    @if(!empty($school->login_background))

                                        <img
                                            src="{{ asset('storage/' . $school->login_background) }}"
                                            class="image-preview"
                                            id="loginBackgroundPreview"
                                            alt="Login Background"
                                        >

                                    @else

                                        <div
                                            class="image-placeholder"
                                            id="loginBackgroundPlaceholder"
                                        >
                                            <i class="bi bi-image"></i>
                                            No background uploaded
                                        </div>

                                        <img
                                            src=""
                                            class="image-preview d-none"
                                            id="loginBackgroundPreview"
                                            alt="Background Preview"
                                        >

                                    @endif

                                </div>


                                <input
                                    type="file"
                                    name="login_background"
                                    id="loginBackgroundInput"
                                    class="image-input"
                                    accept="image/png,image/jpeg,image/jpg"
                                >


                                <div class="image-actions">

                                    <label
                                        for="loginBackgroundInput"
                                        class="upload-label"
                                    >
                                        <i class="bi bi-upload"></i>
                                        Choose Background
                                    </label>

                                    @if(!empty($school->login_background))

                                        <button
                                            type="button"
                                            class="remove-image-button"
                                            onclick="removeImage('login_background')"
                                        >
                                            <i class="bi bi-trash"></i>
                                            Remove
                                        </button>

                                    @endif

                                </div>

                                <input
                                    type="hidden"
                                    name="remove_login_background"
                                    id="remove_login_background"
                                    value="0"
                                >

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================================
                 CONTACT INFORMATION
                 ================================================= --}}

            <div class="setup-card">

                <div class="setup-card-header">

                    <div class="setup-section-icon">
                        <i class="bi bi-telephone"></i>
                    </div>

                    <div>

                        <h2 class="setup-card-title">
                            Contact Information
                        </h2>

                        <p class="setup-card-description">
                            Public contact information for your school.
                        </p>

                    </div>

                </div>


                <div class="setup-card-body">

                    <div class="row g-3">


                        {{-- Email --}}

                        <div class="col-md-6">

                            <label class="form-label">
                                Email
                            </label>

                            <input
                                type="email"
                                name="email"
                                class="form-control"
                                value="{{ old('email', $school->email ?? '') }}"
                                placeholder="info@school.edu"
                            >

                        </div>


                        {{-- Phone --}}

                        <div class="col-md-6">

                            <label class="form-label">
                                Phone
                            </label>

                            <input
                                type="text"
                                name="phone"
                                class="form-control"
                                value="{{ old('phone', $school->phone ?? '') }}"
                                placeholder="+254 700 000 000"
                            >

                        </div>


                        {{-- Website --}}

                        <div class="col-md-6">

                            <label class="form-label">
                                Website
                            </label>

                            <input
                                type="url"
                                name="website"
                                class="form-control"
                                value="{{ old('website', $school->website ?? '') }}"
                                placeholder="https://www.school.edu"
                            >

                        </div>


                        {{-- Country --}}

                        <div class="col-md-6">

                            <label class="form-label">
                                Country
                                <span class="required">*</span>
                            </label>

                            <select
                                name="country"
                                class="form-select"
                                required
                            >

                                @php
                                    $selectedCountry =
                                        old(
                                            'country',
                                            $school->country ?? 'Kenya'
                                        );
                                @endphp

                                <option
                                    value="Kenya"
                                    {{ $selectedCountry == 'Kenya' ? 'selected' : '' }}
                                >
                                    Kenya
                                </option>

                                <option
                                    value="South Sudan"
                                    {{ $selectedCountry == 'South Sudan' ? 'selected' : '' }}
                                >
                                    South Sudan
                                </option>

                                <option
                                    value="Uganda"
                                    {{ $selectedCountry == 'Uganda' ? 'selected' : '' }}
                                >
                                    Uganda
                                </option>

                                <option
                                    value="Tanzania"
                                    {{ $selectedCountry == 'Tanzania' ? 'selected' : '' }}
                                >
                                    Tanzania
                                </option>

                                <option
                                    value="Other"
                                    {{ $selectedCountry == 'Other' ? 'selected' : '' }}
                                >
                                    Other
                                </option>

                            </select>

                        </div>


                        {{-- Physical Address --}}

                        <div class="col-12">

                            <label class="form-label">
                                Physical Address
                            </label>

                            <textarea
                                name="address"
                                class="form-control"
                                placeholder="Enter the school's physical address"
                            >{{ old('address', $school->address ?? '') }}</textarea>

                        </div>


                        {{-- Timezone --}}

                        <div class="col-md-6">

                            <label class="form-label">
                                Timezone
                                <span class="required">*</span>
                            </label>

                            @php
                                $selectedTimezone =
                                    old(
                                        'timezone',
                                        $school->timezone ?? 'Africa/Nairobi'
                                    );
                            @endphp

                            <select
                                name="timezone"
                                class="form-select"
                                required
                            >

                                <option
                                    value="Africa/Nairobi"
                                    {{ $selectedTimezone == 'Africa/Nairobi' ? 'selected' : '' }}
                                >
                                    Africa/Nairobi (EAT)
                                </option>

                                <option
                                    value="Africa/Juba"
                                    {{ $selectedTimezone == 'Africa/Juba' ? 'selected' : '' }}
                                >
                                    Africa/Juba (CAT)
                                </option>

                                <option
                                    value="Africa/Kampala"
                                    {{ $selectedTimezone == 'Africa/Kampala' ? 'selected' : '' }}
                                >
                                    Africa/Kampala (EAT)
                                </option>

                                <option
                                    value="Africa/Dar_es_Salaam"
                                    {{ $selectedTimezone == 'Africa/Dar_es_Salaam' ? 'selected' : '' }}
                                >
                                    Africa/Dar es Salaam (EAT)
                                </option>

                            </select>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================================
                 PRINCIPAL INFORMATION
                 ================================================= --}}

            <div class="setup-card">

                <div class="setup-card-header">

                    <div class="setup-section-icon">
                        <i class="bi bi-person-badge"></i>
                    </div>

                    <div>

                        <h2 class="setup-card-title">
                            Principal Information
                        </h2>

                        <p class="setup-card-description">
                            Details of the school's principal or head teacher.
                        </p>

                    </div>

                </div>


                <div class="setup-card-body">

                    <div class="row g-3">


                        {{-- Name --}}

                        <div class="col-md-6">

                            <label class="form-label">
                                Name
                            </label>

                            <input
                                type="text"
                                name="principal_name"
                                class="form-control"
                                value="{{ old('principal_name', $school->principal_name ?? '') }}"
                                placeholder="e.g. John Peter"
                            >

                        </div>


                        {{-- Email --}}

                        <div class="col-md-6">

                            <label class="form-label">
                                Email
                            </label>

                            <input
                                type="email"
                                name="principal_email"
                                class="form-control"
                                value="{{ old('principal_email', $school->principal_email ?? '') }}"
                                placeholder="principal@school.edu"
                            >

                        </div>


                        {{-- Phone --}}

                        <div class="col-md-6">

                            <label class="form-label">
                                Phone
                            </label>

                            <input
                                type="text"
                                name="principal_phone"
                                class="form-control"
                                value="{{ old('principal_phone', $school->principal_phone ?? '') }}"
                                placeholder="+254 700 000 000"
                            >

                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================================
                 ACADEMIC SETTINGS
                 ================================================= --}}

            <div class="setup-card">

                <div class="setup-card-header">

                    <div class="setup-section-icon">
                        <i class="bi bi-mortarboard"></i>
                    </div>

                    <div>

                        <h2 class="setup-card-title">
                            Academic Settings
                        </h2>

                        <p class="setup-card-description">
                            Configure the current academic session and grading settings.
                        </p>

                    </div>

                </div>


                <div class="setup-card-body">

                    <div class="row g-3">


                        {{-- Academic Year --}}

                        <div class="col-md-4">

                            <label class="form-label">
                                Current Academic Year
                            </label>

                            @if(isset($years) && $years->count())

                                <select
                                    name="current_academic_year"
                                    class="form-select"
                                >

                                    <option value="">
                                        Select academic year
                                    </option>

                                    @foreach($years as $year)

                                        <option
                                            value="{{ $year->name }}"
                                            {{ old('current_academic_year', $school->current_academic_year ?? '') == $year->name ? 'selected' : '' }}
                                        >
                                            {{ $year->name }}
                                        </option>

                                    @endforeach

                                </select>

                            @else

                                <input
                                    type="text"
                                    name="current_academic_year"
                                    class="form-control"
                                    value="{{ old('current_academic_year', $school->current_academic_year ?? '2026/2027') }}"
                                    placeholder="2026/2027"
                                >

                            @endif

                        </div>


                        {{-- Term --}}

                        <div class="col-md-4">

                            <label class="form-label">
                                Current Term
                            </label>

                            @if(isset($terms) && $terms->count())

                                <select
                                    name="current_term"
                                    class="form-select"
                                >

                                    <option value="">
                                        Select term
                                    </option>

                                    @foreach($terms as $term)

                                        <option
                                            value="{{ $term->name }}"
                                            {{ old('current_term', $school->current_term ?? '') == $term->name ? 'selected' : '' }}
                                        >
                                            {{ $term->name }}
                                        </option>

                                    @endforeach

                                </select>

                            @else

                                <input
                                    type="text"
                                    name="current_term"
                                    class="form-control"
                                    value="{{ old('current_term', $school->current_term ?? 'Term 3') }}"
                                    placeholder="Term 3"
                                >

                            @endif

                        </div>


                        {{-- Pass Mark --}}

                        <div class="col-md-4">

                            <label class="form-label">
                                Pass Mark (%)
                            </label>

                            <input
                                type="number"
                                name="pass_mark"
                                class="form-control"
                                value="{{ old('pass_mark', $school->pass_mark ?? 50) }}"
                                min="0"
                                max="100"
                                step="1"
                                placeholder="50"
                            >

                            <div class="form-text">
                                Minimum percentage required to pass.
                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================================
                 FINANCIAL SETTINGS
                 ================================================= --}}

            <div class="setup-card">

                <div class="setup-card-header">

                    <div class="setup-section-icon">
                        <i class="bi bi-cash-stack"></i>
                    </div>

                    <div>

                        <h2 class="setup-card-title">
                            Financial Settings
                        </h2>

                        <p class="setup-card-description">
                            Configure currency and document numbering.
                        </p>

                    </div>

                </div>


                <div class="setup-card-body">

                    <div class="row g-3">


                        {{-- Currency --}}

                        <div class="col-md-3">

                            <label class="form-label">
                                Currency
                            </label>

                            <select
                                name="currency"
                                class="form-select"
                            >

                                @foreach([
                                    'KES' => 'Kenyan Shilling',
                                    'SSP' => 'South Sudanese Pound',
                                    'UGX' => 'Ugandan Shilling',
                                    'TZS' => 'Tanzanian Shilling',
                                    'USD' => 'US Dollar'
                                ] as $code => $currencyName)

                                    <option
                                        value="{{ $code }}"
                                        {{ old('currency', $school->currency ?? 'KES') == $code ? 'selected' : '' }}
                                    >
                                        {{ $code }} - {{ $currencyName }}
                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- Currency Symbol --}}

                        <div class="col-md-3">

                            <label class="form-label">
                                Currency Symbol
                            </label>

                            <input
                                type="text"
                                name="currency_symbol"
                                class="form-control"
                                value="{{ old('currency_symbol', $school->currency_symbol ?? 'KSh') }}"
                                placeholder="KSh"
                            >

                        </div>


                        {{-- Receipt Prefix --}}

                        <div class="col-md-3">

                            <label class="form-label">
                                Receipt Prefix
                            </label>

                            <input
                                type="text"
                                name="receipt_prefix"
                                class="form-control"
                                value="{{ old('receipt_prefix', $school->receipt_prefix ?? 'REC') }}"
                                placeholder="REC"
                            >

                        </div>


                        {{-- Invoice Prefix --}}

                        <div class="col-md-3">

                            <label class="form-label">
                                Invoice Prefix
                            </label>

                            <input
                                type="text"
                                name="invoice_prefix"
                                class="form-control"
                                value="{{ old('invoice_prefix', $school->invoice_prefix ?? 'INV') }}"
                                placeholder="INV"
                            >

                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================================
                 SYSTEM NOTIFICATIONS
                 ================================================= --}}

            <div class="setup-card">

                <div class="setup-card-header">

                    <div class="setup-section-icon">
                        <i class="bi bi-bell"></i>
                    </div>

                    <div>

                        <h2 class="setup-card-title">
                            System Notifications
                        </h2>

                        <p class="setup-card-description">
                            Control communication and system availability.
                        </p>

                    </div>

                </div>


                <div class="setup-card-body">


                    {{-- Email Notifications --}}

                    <div class="notification-row">

                        <div>

                            <p class="notification-title">
                                Enable Email Notifications
                            </p>

                            <p class="notification-description">
                                Allow ScholarERP to send school notifications by email.
                            </p>

                        </div>

                        <div class="form-check form-switch">

                            <input
                                type="hidden"
                                name="email_notifications"
                                value="0"
                            >

                            <input
                                class="form-check-input"
                                type="checkbox"
                                name="email_notifications"
                                value="1"
                                {{ old('email_notifications', $school->email_notifications ?? false) ? 'checked' : '' }}
                            >

                        </div>

                    </div>


                    {{-- SMS Notifications --}}

                    <div class="notification-row">

                        <div>

                            <p class="notification-title">
                                Enable SMS Notifications
                            </p>

                            <p class="notification-description">
                                Allow ScholarERP to send school notifications by SMS.
                            </p>

                        </div>

                        <div class="form-check form-switch">

                            <input
                                type="hidden"
                                name="sms_notifications"
                                value="0"
                            >

                            <input
                                class="form-check-input"
                                type="checkbox"
                                name="sms_notifications"
                                value="1"
                                {{ old('sms_notifications', $school->sms_notifications ?? false) ? 'checked' : '' }}
                            >

                        </div>

                    </div>


                    {{-- Maintenance Mode --}}

                    <div class="notification-row">

                        <div>

                            <p class="notification-title">
                                Maintenance Mode
                            </p>

                            <p class="notification-description">
                                Temporarily place ScholarERP into maintenance mode.
                            </p>

                        </div>

                        <div class="form-check form-switch">

                            <input
                                type="hidden"
                                name="maintenance_mode"
                                value="0"
                            >

                            <input
                                class="form-check-input"
                                type="checkbox"
                                name="maintenance_mode"
                                value="1"
                                {{ old('maintenance_mode', $school->maintenance_mode ?? false) ? 'checked' : '' }}
                            >

                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================================
                 BOTTOM ACTIONS
                 ================================================= --}}

            <div class="setup-card">

                <div class="setup-bottom-actions">

                    @if(Route::has('dashboard'))

                        <a
                            href="{{ route('dashboard') }}"
                            class="cancel-button"
                        >
                            <i class="bi bi-arrow-left"></i>
                            Cancel
                        </a>

                    @endif

                    <button
                        type="submit"
                        class="setup-save-button"
                    >
                        <i class="bi bi-check-lg"></i>
                        Save School Settings
                    </button>

                </div>

            </div>


        </form>

    </div>

</div>


{{-- =========================================================
     IMAGE PREVIEW JAVASCRIPT
     ========================================================= --}}

<script>

document.addEventListener('DOMContentLoaded', function () {


    /*
    |--------------------------------------------------------------------------
    | Generic Image Preview
    |--------------------------------------------------------------------------
    */

    function setupImagePreview(inputId, previewId, placeholderId) {

        const input =
            document.getElementById(inputId);

        const preview =
            document.getElementById(previewId);

        const placeholder =
            document.getElementById(placeholderId);


        if (!input || !preview) {
            return;
        }


        input.addEventListener('change', function () {

            const file =
                this.files && this.files[0];


            if (!file) {
                return;
            }


            if (!file.type.startsWith('image/')) {

                alert('Please select a valid image file.');

                this.value = '';

                return;
            }


            const reader =
                new FileReader();


            reader.onload = function (event) {

                preview.src =
                    event.target.result;

                preview.classList.remove('d-none');


                if (placeholder) {

                    placeholder.style.display =
                        'none';

                }

            };


            reader.readAsDataURL(file);

        });

    }


    /*
    |--------------------------------------------------------------------------
    | Logo
    |--------------------------------------------------------------------------
    */

    setupImagePreview(
        'logoInput',
        'logoPreview',
        'logoPlaceholder'
    );


    /*
    |--------------------------------------------------------------------------
    | Stamp
    |--------------------------------------------------------------------------
    */

    setupImagePreview(
        'stampInput',
        'stampPreview',
        'stampPlaceholder'
    );


    /*
    |--------------------------------------------------------------------------
    | Favicon
    |--------------------------------------------------------------------------
    */

    setupImagePreview(
        'faviconInput',
        'faviconPreview',
        'faviconPlaceholder'
    );


    /*
    |--------------------------------------------------------------------------
    | Login Background
    |--------------------------------------------------------------------------
    */

    setupImagePreview(
        'loginBackgroundInput',
        'loginBackgroundPreview',
        'loginBackgroundPlaceholder'
    );

});


/*
|--------------------------------------------------------------------------
| Remove Existing Image
|--------------------------------------------------------------------------
*/

function removeImage(type) {

    const hiddenInput =
        document.getElementById(
            'remove_' + type
        );


    if (hiddenInput) {

        hiddenInput.value = '1';

    }


    const preview =
        document.getElementById(
            type === 'login_background'
                ? 'loginBackgroundPreview'
                : type + 'Preview'
        );


    const placeholder =
        document.getElementById(
            type === 'login_background'
                ? 'loginBackgroundPlaceholder'
                : type + 'Placeholder'
        );


    if (preview) {

        preview.src = '';

        preview.classList.add('d-none');

    }


    if (placeholder) {

        placeholder.style.display = 'block';

    }


    const input =
        document.getElementById(
            type === 'login_background'
                ? 'loginBackgroundInput'
                : type + 'Input'
        );


    if (input) {

        input.value = '';

    }

}

</script>

@endsection

