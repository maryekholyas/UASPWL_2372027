<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Committee Dashboard - @yield('title', 'Dashboard')</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" />
  <style>
    :root {
      --main-color: #6f42c1; /* Ungu elegan */
      --text-dark: #343a40;
      --bg-light: #f8f9fc;
      --hover-color: #e6e0f7;
    }

    body {
      background-color: var(--bg-light);
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .navbar {
      background-color: #ffffff;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .navbar-brand {
      font-weight: bold;
      color: var(--main-color);
    }

    .nav-link {
      font-weight: 500;
      color: var(--text-dark);
      transition: 0.3s;
    }

    .nav-link:hover,
    .nav-link.active {
      color: var(--main-color) !important;
      background-color: var(--hover-color);
      border-radius: 0.375rem;
    }

    .main-content {
      padding: 2rem 1rem;
      margin-top: 80px;
    }

    .logout-btn {
      border-radius: 20px;
      font-weight: 500;
      border-color: var(--main-color);
      color: var(--main-color);
    }

    .logout-btn:hover {
      background-color: var(--main-color);
      color: #fff;
    }

    footer {
      margin-top: 5rem;
      padding: 2rem 1rem 1rem;
      text-align: center;
      color: #6c757d;
      font-size: 0.9rem;
      border-top: 1px solid #dee2e6;
    }
  </style>
</head>
<body>

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg fixed-top">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">
        <i class="bi bi-calendar-event me-2"></i> Committee Panel
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse justify-content-between" id="navbarMenu">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a href="{{ route('committee.dashboard') }}" class="nav-link @if(request()->routeIs('committee.dashboard')) active @endif">
              <i class="bi bi-speedometer2 me-1"></i> Dashboard
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('committee.event.index') }}" class="nav-link @if(request()->routeIs('committee.event.*')) active @endif">
              <i class="bi bi-calendar-check me-1"></i> Events
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('committee.event.scan') }}" class="nav-link @if(request()->routeIs('committee.event.scan')) active @endif">
              <i class="bi bi-qr-code me-1"></i> Scan Absensi
            </a>
          </li>
        </ul>

        <div class="d-flex align-items-center gap-3">
          <span class="fw-semibold text-dark">Hai, {{ Auth::user()->name ?? 'Committee' }}</span>
          <form id="logoutForm" action="{{ route('logout') }}" method="POST" class="d-inline">
            @csrf
            <button type="button" id="logoutBtn" class="btn btn-outline-primary logout-btn">
              <i class="bi bi-box-arrow-right me-1"></i> Logout
            </button>
          </form>
        </div>
      </div>
    </div>
  </nav>

  <!-- Main Content -->
  <div class="main-content container">
    @yield('content')

    <footer>
      &copy; 2025 D'Dahar. All rights reserved.
    </footer>
  </div>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script>
    document.getElementById('logoutBtn').addEventListener('click', () => {
      Swal.fire({
        title: 'Konfirmasi',
        text: "Yakin ingin logout?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#6f42c1',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, logout!',
        cancelButtonText: 'Batal'
      }).then(result => {
        if (result.isConfirmed) {
          document.getElementById('logoutForm').submit();
        }
      });
    });
  </script>
</body>
</html>
