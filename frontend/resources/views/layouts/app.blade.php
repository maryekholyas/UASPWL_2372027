<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', 'Where2Go')</title>
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
    /* Navbar */
    .navbar-custom {
        background-color: #007bff;
        color: white;
    }
    .navbar-custom .navbar-brand,
    .navbar-custom .nav-link {
        color: white !important;
        font-weight: 600;
    }
    .navbar-custom .nav-link:hover {
        color: #dceeff !important;
        text-shadow: 0 0 6px rgba(255,255,255,0.5);
    }

    .btn-login {
        background-color: #ffffff;
        color: #007bff;
        border-radius: 50px;
        font-weight: 600;
        padding: 6px 20px;
        transition: 0.3s;
    }
    .btn-login:hover {
        background-color: #f0f8ff;
        color: #0056b3;
        box-shadow: 0 0 10px rgba(0,123,255,0.2);
    }

    /* Search Form */
    .search-form {
        background: white;
        border-radius: 50px;
        padding: 8px 20px;
        box-shadow: 0 4px 20px rgba(0,123,255,0.1);
    }

    .search-button {
        background: #007bff;
        color: white;
        border-radius: 30px;
        font-weight: 600;
        padding: 8px 25px;
        transition: 0.3s;
    }
    .search-button:hover {
        background: #0056b3;
    }

    .search-input {
        border: none;
        background: transparent;
        padding: 10px;
    }
    .search-input:focus {
        outline: none;
        box-shadow: none;
    }

    /* Footer */
    .main-footer {
        background: #f0f8ff;
        padding: 60px 0 30px;
        margin-top: 80px;
    }

    .footer-title {
        color: #007bff;
        font-weight: 600;
        margin-bottom: 20px;
    }

    .footer-link {
        color: #6c757d !important;
        padding: 8px 0;
        display: block;
        transition: 0.3s ease;
    }

    .footer-link:hover {
        color: #007bff !important;
        transform: translateX(5px);
        text-decoration: none;
    }

    .social-links .btn {
        border-color: #007bff;
        color: #007bff;
        transition: 0.3s;
    }

    .social-links .btn:hover {
        background: #007bff;
        color: white;
    }

    .footer-bottom {
        border-top: 1px solid #dee2e6;
        margin-top: 40px;
        padding-top: 25px;
        font-size: 0.9rem;
    }
</style>


</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-custom shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">D'Dahar</a>

            <button
                class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarNav"
                aria-controls="navbarNav"
                aria-expanded="false"
                aria-label="Toggle navigation"
            >
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
                <div class="search-container">
                    <form class="search-form d-flex align-items-center" method="GET" action="{{ url('/events') }}">
                        <i class="bi bi-search text-purple me-2"></i>
                        <input
                            class="search-input flex-grow-1"
                            type="search"
                            name="q"
                            placeholder="Temukan event seru di sekitarmu..."
                            aria-label="Search events"
                        />
                        <button class="search-button btn" type="submit">Cari</button>
                    </form>
                </div>
            </div>

            @if(Session::has('user'))
                <div class="d-flex align-items-center">
                    <span class="btn btn-login me-2">Hai, {{ Session::get('user')['nama'] }}</span>
                    @if(Session::get('user')['role'] === 'member')
                        <a href="{{ route('member.dashboard') }}" class="btn btn-outline-primary me-2">
                            <i class="bi bi-calendar-check"></i> My Registered Events
                        </a>
                    @endif
                    <form id="logoutForm" action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-secondary">Logout</button>
                    </form>
                </div>
            @else
                <div class="d-flex">
                    <a href="{{ route('login') }}" class="btn btn-login">Login</a>
                </div>
            @endif
        </div>
    </nav>

    <main class="container mt-4">
        @yield('content')
    </main>

    <!-- Enhanced Footer -->
    <footer class="bg-light text-center text-lg-start mt-5 border-top">
    <div class="container py-4">
        <div class="row justify-content-between align-items-center">
            <div class="col-md-6 text-md-start mb-3 mb-md-0">
                <h6 class="text-primary fw-bold">D'Dahar</h6>
                <p class="text-muted mb-1 small">
                    Jalan Bukit Permata, Bandung<br>
                    <a href="mailto:hello@ddahar.id" class="text-decoration-none text-primary">hello@ddahar.id</a>
                </p>
            </div>
            <div class="col-md-6 text-md-end">
                <a href="#" class="text-primary me-3"><i class="bi bi-instagram fs-5"></i></a>
                <a href="#" class="text-primary me-3"><i class="bi bi-facebook fs-5"></i></a>
                <a href="#" class="text-primary"><i class="bi bi-twitter-x fs-5"></i></a>
            </div>
        </div>
        <hr class="my-3">
        <div class="text-center text-muted small">
            &copy; 2025 D'Dahar. All rights reserved.
        </div>
    </div>
</footer>

    <!-- Bootstrap JS Bundle CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>