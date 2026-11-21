<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    {{-- CSRF TOKEN FOR AJAX REQUESTS --}}
    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >

    <title>
        @yield('title', 'ScholarERP')
    </title>


    {{-- VITE --}}
    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])


    {{-- BOOTSTRAP --}}
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >


    {{-- BOOTSTRAP ICONS --}}
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.css"
        rel="stylesheet"
    >


    <style>

        /* =========================================================
           SCHOLAREERP GLOBAL LAYOUT
        ========================================================= */

        :root {

            --sidebar-width: 272px;

            --primary-blue: #0d6efd;

            --sidebar-bg: #0b1426;

            --sidebar-bg-2: #111c31;

            --sidebar-text: #aebbd0;

            --sidebar-muted: #71809a;

            --sidebar-border: rgba(255,255,255,.08);

            --page-bg: #f7f9fc;

            --navbar-height: 58px;
        }


        * {
            box-sizing: border-box;
        }


        html,
        body {

            margin: 0;

            padding: 0;

            min-height: 100%;
        }


        body {

            background: var(--page-bg);

            font-family:
                Inter,
                -apple-system,
                BlinkMacSystemFont,
                "Segoe UI",
                Roboto,
                Arial,
                sans-serif;

            color: #172033;
        }


        /* =========================================================
           APPLICATION
        ========================================================= */

        .scholar-app {

            min-height: 100vh;

            width: 100%;
        }


        /* =========================================================
           SIDEBAR
        ========================================================= */

        .scholar-sidebar {

            position: fixed;

            top: 0;

            left: 0;

            width: var(--sidebar-width);

            height: 100vh;

            z-index: 1050;

            overflow-y: auto;

            overflow-x: hidden;
        }


        /* =========================================================
           MAIN
        ========================================================= */

        .scholar-main {

            margin-left: var(--sidebar-width);

            width: calc(100% - var(--sidebar-width));

            min-height: 100vh;

            display: flex;

            flex-direction: column;

            overflow-x: hidden;
        }


        /* =========================================================
           NAVBAR
        ========================================================= */

        .scholar-navbar {

            min-height: var(--navbar-height);

            position: sticky;

            top: 0;

            z-index: 1000;

            background: #ffffff;

            border-bottom: 1px solid #e5e7eb;

            box-shadow:
                0 1px 4px rgba(0,0,0,.04);
        }


        /* =========================================================
           PAGE CONTENT
        ========================================================= */

        .scholar-content {

            flex: 1;

            width: 100%;

            padding: 24px;
        }


        /* =========================================================
           NAV LINKS
        ========================================================= */

        .nav-link {

            border-radius: 8px;

            transition:
                background-color .2s ease,
                color .2s ease,
                transform .15s ease;
        }


        .nav-link:hover {

            background: var(--primary-blue);

            color: #fff !important;
        }


        .nav-link.active {

            background: var(--primary-blue);

            color: #fff !important;

            box-shadow:
                0 4px 12px rgba(13,110,253,.20);
        }


        /* =========================================================
           SIDEBAR TITLES
        ========================================================= */

        .sidebar-title {

            letter-spacing: 1px;

            font-weight: 700;
        }


        .sidebar-section {

            color: #71809a;

            font-size: .70rem;

            font-weight: 700;

            text-transform: uppercase;

            letter-spacing: 1px;

            padding: 18px 16px 7px;
        }


        /* =========================================================
           SCROLLBAR
        ========================================================= */

        .scholar-sidebar::-webkit-scrollbar {

            width: 5px;
        }


        .scholar-sidebar::-webkit-scrollbar-track {

            background: transparent;
        }


        .scholar-sidebar::-webkit-scrollbar-thumb {

            background:
                rgba(255,255,255,.15);

            border-radius: 10px;
        }


        .scholar-sidebar::-webkit-scrollbar-thumb:hover {

            background:
                rgba(255,255,255,.25);
        }


        /* =========================================================
           TABLET
        ========================================================= */

        @media (max-width: 991.98px) {

            :root {

                --sidebar-width: 250px;
            }


            .scholar-content {

                padding: 18px;
            }

        }


        /* =========================================================
           MOBILE
        ========================================================= */

        @media (max-width: 767.98px) {

            .scholar-sidebar {

                width: 250px;

                transform:
                    translateX(-100%);

                transition:
                    transform .25s ease;
            }


            .scholar-sidebar.show {

                transform:
                    translateX(0);
            }


            .scholar-main {

                margin-left: 0;

                width: 100%;
            }


            .scholar-content {

                padding: 15px;
            }

        }

    </style>


    @stack('styles')

</head>


<body>

<div class="scholar-app">


    {{-- =========================================================
         SIDEBAR
    ========================================================== --}}

    <aside class="scholar-sidebar">

        @include('layouts.sidebar')

    </aside>


    {{-- =========================================================
         MAIN APPLICATION
    ========================================================== --}}

    <div class="scholar-main">


        {{-- =====================================================
             NAVBAR
        ====================================================== --}}

        <header class="scholar-navbar">

            @include('layouts.navbar')

        </header>


        {{-- =====================================================
             PAGE CONTENT
        ====================================================== --}}

        <main class="scholar-content">

            @yield('content')

        </main>


    </div>

</div>


{{-- =============================================================
     BOOTSTRAP JAVASCRIPT
============================================================= --}}

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js">
</script>


@stack('scripts')

</body>

</html>