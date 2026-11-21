<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm">

    <div class="container-fluid">

        <span class="navbar-brand fw-bold">
            Dashboard
        </span>

        <div class="d-flex align-items-center">

            @auth
                <span class="me-3">
                    <i class="bi bi-person-circle"></i>
                    {{ auth()->user()->name }}
                </span>
            @else
                <span class="text-muted">
                    Guest
                </span>
            @endauth

        </div>

    </div>

</nav>