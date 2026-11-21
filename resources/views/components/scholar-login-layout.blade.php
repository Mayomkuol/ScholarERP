<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'ScholarERP') }} | Secure Login</title>

    <link rel="icon" type="image/svg+xml" href="{{ asset('images/scholarerp-mark.svg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* =========================================
           SCHOLARERP BRAND VARIABLES (FROM POSTER)
        ========================================= */
        :root {
            --scholar-navy: #0B1933;
            --scholar-navy-mid: #10254B;
            --scholar-blue: #2563EB;
            --scholar-blue-hover: #1D4ED8;
            --scholar-gold: #F59E0B;
            --scholar-sky: #93C5FD;

            --scholar-text: #0F172A;
            --scholar-muted: #64748B;
            --scholar-border: #E2E8F0;
            --scholar-canvas: #F8FAFC;
            --scholar-surface: #FFFFFF;
        }

        /* =========================================
           GLOBAL RESET & BASE
        ========================================= */
        .scholar-auth,
        .scholar-auth * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            background: var(--scholar-canvas);
            color: var(--scholar-text);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        .scholar-auth {
            min-height: 100vh;
            width: 100%;
            display: flex;
        }

        /* =========================================
           LEFT BRANDING & SHOWCASE PANEL
        ========================================= */
        .scholar-brand {
            width: 52%;
            min-height: 100vh;
            padding: 40px 6%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
            color: #FFFFFF;
            background: radial-gradient(circle at 85% 15%, #1E40AF 0%, transparent 45%),
                        radial-gradient(circle at 15% 85%, #1D4ED8 0%, transparent 50%),
                        linear-gradient(155deg, #09152B 0%, #10254B 55%, #17376E 100%);
        }

        /* Subtle Technical Dot Matrix Background */
        .scholar-brand::before {
            content: "";
            position: absolute;
            inset: 0;
            background-image: radial-gradient(rgba(255, 255, 255, 0.12) 1px, transparent 1px);
            background-size: 24px 24px;
            mask-image: linear-gradient(to bottom, rgba(0,0,0,0.6), transparent 90%);
            -webkit-mask-image: linear-gradient(to bottom, rgba(0,0,0,0.6), transparent 90%);
            pointer-events: none;
        }

        .scholar-brand-content {
            position: relative;
            z-index: 2;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
            max-width: 560px;
            margin: 0 auto;
            width: 100%;
        }

        /* Top Bar: Logo + Subtitle */
        .scholar-brand-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }

        .scholar-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: #FFFFFF;
        }

        .scholar-logo-icon {
            width: 46px;
            height: 46px;
            object-fit: contain;
            flex-shrink: 0;
        }

        .scholar-logo-text-wrap {
            display: flex;
            flex-direction: column;
        }

        .scholar-logo-title {
            font-size: 22px;
            font-weight: 800;
            letter-spacing: -0.5px;
            line-height: 1.1;
        }

        .scholar-logo-title span {
            color: var(--scholar-sky);
        }

        .scholar-logo-subtitle {
            font-size: 11px;
            color: #93C5FD;
            font-weight: 500;
            letter-spacing: 0.2px;
            margin-top: 2px;
        }

        .scholar-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.14);
            backdrop-filter: blur(8px);
            font-size: 11px;
            font-weight: 600;
            color: #E2E8F0;
            letter-spacing: 0.3px;
        }

        .scholar-badge-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--scholar-gold);
            box-shadow: 0 0 8px var(--scholar-gold);
        }

        /* Hero Copy (Matched to Poster Tagline) */
        .scholar-intro {
            margin: 28px 0;
        }

        .scholar-intro h1 {
            color: #FFFFFF;
            font-size: clamp(30px, 3vw, 42px);
            line-height: 1.15;
            font-weight: 800;
            letter-spacing: -1.2px;
            margin: 0 0 14px;
        }

        .scholar-intro h1 span {
            background: linear-gradient(90deg, #F59E0B 0%, #FBBF24 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .scholar-intro p {
            color: #CBD5E1;
            font-size: 14px;
            line-height: 1.65;
            margin: 0 0 24px;
            max-width: 450px;
        }

        /* =========================================
           GLASSMORPHIC FEATURE PILLARS WIDGET
        ========================================= */
        .scholar-preview-card {
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.14);
            border-radius: 16px;
            padding: 18px 20px;
            backdrop-filter: blur(12px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.25);
        }

        .scholar-preview-header {
            font-size: 11.5px;
            font-weight: 700;
            color: var(--scholar-sky);
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .scholar-pillars-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }

        .scholar-pillar-item {
            background: rgba(15, 23, 42, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 10px;
            padding: 10px 12px;
            display: flex;
            align-items: center;
            gap: 10px;
            color: #F1F5F9;
            font-size: 12.5px;
            font-weight: 500;
        }

        .scholar-pillar-item svg {
            width: 15px;
            height: 15px;
            color: var(--scholar-gold);
            flex-shrink: 0;
        }

        /* Footer */
        .scholar-brand-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 14px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            color: #94A3B8;
            font-size: 11.5px;
        }

        /* =========================================
           RIGHT LOGIN PANEL & ELEVATED CARD
        ========================================= */
        .scholar-login-panel {
            width: 48%;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 32px 24px;
            background: var(--scholar-canvas);
            position: relative;
        }

        .scholar-login-card {
            width: 100%;
            max-width: 440px;
            background: var(--scholar-surface);
            border: 1px solid var(--scholar-border);
            border-radius: 16px;
            padding: 40px 36px;
            box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.05),
                        0 8px 10px -6px rgba(15, 23, 42, 0.02);
        }

        .scholar-mobile-header {
            display: none;
        }

        /* =========================================
           FORM COMPONENT OVERRIDES (FOR $slot)
        ========================================= */
        .scholar-heading {
            margin-bottom: 28px;
        }

        .scholar-heading .eyebrow {
            display: inline-block;
            margin-bottom: 8px;
            color: var(--scholar-blue);
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .scholar-heading h2 {
            color: var(--scholar-text);
            font-size: 26px;
            font-weight: 800;
            letter-spacing: -0.6px;
            margin: 0 0 8px;
        }

        .scholar-heading p {
            color: var(--scholar-muted);
            font-size: 14px;
            line-height: 1.6;
            margin: 0;
        }

        .scholar-form-group {
            margin-bottom: 20px;
        }

        .scholar-form-group label {
            display: block;
            color: #334155;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 7px;
        }

        .scholar-input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .scholar-input-icon {
            position: absolute;
            left: 14px;
            width: 18px;
            height: 18px;
            color: #94A3B8;
            pointer-events: none;
            transition: color 0.2s ease;
        }

        .scholar-input-wrapper input {
            width: 100%;
            height: 48px;
            padding: 0 44px;
            border: 1px solid var(--scholar-border);
            border-radius: 10px;
            background: #F8FAFC;
            color: var(--scholar-text);
            font-family: inherit;
            font-size: 14px;
            outline: none;
            transition: all 0.2s ease;
        }

        .scholar-input-wrapper input::placeholder {
            color: #94A3B8;
        }

        .scholar-input-wrapper input:hover {
            border-color: #CBD5E1;
            background: #FFFFFF;
        }

        .scholar-input-wrapper input:focus {
            background: #FFFFFF;
            border-color: var(--scholar-blue);
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.12);
        }

        .scholar-input-wrapper:focus-within .scholar-input-icon {
            color: var(--scholar-blue);
        }

        .scholar-input-wrapper input.scholar-invalid {
            border-color: #DC2626;
            background: #FEF2F2;
        }

        .scholar-input-wrapper input:-webkit-autofill,
        .scholar-input-wrapper input:-webkit-autofill:hover,
        .scholar-input-wrapper input:-webkit-autofill:focus {
            -webkit-text-fill-color: var(--scholar-text);
            -webkit-box-shadow: 0 0 0 1000px #FFFFFF inset;
            transition: background-color 9999s ease-in-out 0s;
        }

        /* Password Visibility Button */
        .scholar-toggle-password {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            padding: 6px;
            border: none;
            background: transparent;
            color: #94A3B8;
            cursor: pointer;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .scholar-toggle-password svg {
            width: 18px;
            height: 18px;
        }

        .scholar-toggle-password:hover {
            color: var(--scholar-text);
            background: #F1F5F9;
        }

        /* Alerts & Errors */
        .scholar-field-error {
            display: block;
            color: #DC2626;
            font-size: 12px;
            font-weight: 500;
            margin-top: 6px;
        }

        .scholar-alert {
            padding: 12px 14px;
            border-radius: 10px;
            font-size: 13px;
            margin-bottom: 20px;
        }

        .scholar-alert.error {
            color: #991B1B;
            background: #FEF2F2;
            border: 1px solid #FECACA;
        }

        .scholar-alert.success {
            color: #166534;
            background: #F0FDF4;
            border: 1px solid #BBF7D0;
        }

        /* Form Options */
        .scholar-form-options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            margin: 4px 0 24px;
        }

        .scholar-remember {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #475569;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
        }

        .scholar-remember input {
            width: 16px;
            height: 16px;
            accent-color: var(--scholar-blue);
            cursor: pointer;
            border-radius: 4px;
        }

        .scholar-forgot {
            color: var(--scholar-blue);
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
        }

        .scholar-forgot:hover {
            color: var(--scholar-blue-hover);
            text-decoration: underline;
        }

        /* Primary Submit Button */
        .scholar-submit {
            width: 100%;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            border: none;
            border-radius: 10px;
            background: var(--scholar-blue);
            color: #FFFFFF;
            font-family: inherit;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.22);
            transition: all 0.2s ease;
        }

        .scholar-submit:hover {
            background: var(--scholar-blue-hover);
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(37, 99, 235, 0.28);
        }

        .scholar-submit:active {
            transform: translateY(0);
        }

        .scholar-submit svg {
            width: 18px;
            height: 18px;
        }

        /* Security & Copyright */
        .scholar-security {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 22px;
            padding-top: 18px;
            border-top: 1px solid var(--scholar-border);
            color: var(--scholar-muted);
            font-size: 12px;
        }

        .scholar-security svg {
            width: 16px;
            height: 16px;
            color: #16A34A;
            flex-shrink: 0;
        }

        .scholar-copyright {
            margin-top: 24px;
            text-align: center;
            color: #94A3B8;
            font-size: 12px;
        }

        /* =========================================
           RESPONSIVE BREAKPOINTS
        ========================================= */
        @media (max-width: 1024px) {
            .scholar-brand {
                width: 48%;
                padding: 32px 4%;
            }
            .scholar-login-panel {
                width: 52%;
            }
        }

        @media (max-width: 768px) {
            .scholar-auth {
                flex-direction: column;
            }

            .scholar-brand {
                display: none;
            }

            .scholar-mobile-header {
                display: flex;
                align-items: center;
                justify-content: space-between;
                width: 100%;
                padding: 16px 24px;
                background: var(--scholar-navy);
                color: #FFFFFF;
            }

            .scholar-mobile-brand {
                display: flex;
                align-items: center;
                gap: 10px;
                font-size: 19px;
                font-weight: 800;
                letter-spacing: -0.5px;
            }

            .scholar-mobile-brand img {
                width: 32px;
                height: 32px;
                object-fit: contain;
            }

            .scholar-mobile-brand span span {
                color: var(--scholar-sky);
            }

            .scholar-login-panel {
                width: 100%;
                min-height: calc(100vh - 64px);
                padding: 24px 16px;
            }

            .scholar-login-card {
                padding: 28px 22px;
            }
        }
    </style>
</head>

<body>
    <div class="scholar-auth">

        {{-- =====================================
             MOBILE TOP BAR (< 768px)
        ====================================== --}}
        <header class="scholar-mobile-header">
            <div class="scholar-mobile-brand">
                <img src="{{ asset('images/scholarerp-mark.svg') }}" alt="ScholarERP Logo">
                <span>Scholar<span>ERP</span></span>
            </div>
            <span class="scholar-badge">
                <span class="scholar-badge-dot"></span>
                Secure Portal
            </span>
        </header>

        {{-- =====================================
             LEFT BRANDING & SHOWCASE PANEL
        ====================================== --}}
        <section class="scholar-brand">
            <div class="scholar-brand-content">

                {{-- Top Logo & Status Pill --}}
                <div class="scholar-brand-header">
                    <a href="#" class="scholar-logo">
                        <img src="{{ asset('images/scholarerp-mark.svg') }}" alt="ScholarERP Logo" class="scholar-logo-icon">
                        <div class="scholar-logo-text-wrap">
                            <div class="scholar-logo-title">Scholar<span>ERP</span></div>
                            <div class="scholar-logo-subtitle">Smart School Management System</div>
                        </div>
                    </a>

                    <div class="scholar-badge">
                        <span class="scholar-badge-dot"></span>
                        Secure Cloud Access
                    </div>
                </div>

                {{-- Hero Copy & Tagline Alignment --}}
                <div class="scholar-intro">
                    <h1>
                        Building Smarter Schools.<br>
                        <span>Empowering Education.</span>
                    </h1>

                    <p>
                        A comprehensive, integrated, and secure School Management System designed to simplify administration, enhance teaching and learning, and improve academic and financial outcomes.
                    </p>

                    {{-- Core Modules Showcase Widget --}}
                    <div class="scholar-preview-card" aria-hidden="true">
                        <div class="scholar-preview-header">
                            <span>System Modules & Control</span>
                            <span>Integrated</span>
                        </div>

                        <div class="scholar-pillars-grid">
                            <div class="scholar-pillar-item">
                                <svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                Academic Structure
                            </div>
                            <div class="scholar-pillar-item">
                                <svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                Student Management
                            </div>
                            <div class="scholar-pillar-item">
                                <svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                Fee & Finance
                            </div>
                            <div class="scholar-pillar-item">
                                <svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                Exams & Reports
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Footer (Matching poster's motto) --}}
                <div class="scholar-brand-footer">
                    <span>© {{ date('Y') }} ScholarERP Systems</span>
                    <span>One System. One School. Endless Possibilities.</span>
                </div>

            </div>
        </section>

        {{-- =====================================
             RIGHT LOGIN PANEL
        ====================================== --}}
        <main class="scholar-login-panel">
            <div class="scholar-login-card">
                {{ $slot }}
            </div>

            <div class="scholar-copyright">
                Need institutional access? Contact your <strong>School Administrator</strong>.
            </div>
        </main>

    </div>

    @stack('scripts')
</body>
</html>