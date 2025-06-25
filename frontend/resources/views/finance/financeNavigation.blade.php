<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Finance Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" />
  <style>
    body {
      background-color: #f8f9fc;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    /* Navbar Styling */
    .navbar-finance {
      background-color: #ffffff;
      box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
      padding: 0.75rem 1.5rem;
    }

    .navbar-title {
      font-weight: 700;
      font-size: 1.25rem;
      color: #28a745;
    }

    .logout-btn {
      border-radius: 20px;
      font-weight: 500;
      border-color: #28a745;
      color: #28a745;
    }

    .logout-btn:hover {
      background-color: #28a745;
      color: #ffffff;
    }

    /* Main Content */
    .main-content {
      padding: 2rem 1.5rem;
    }

    /* Footer */
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
  <nav class="navbar navbar-expand-lg navbar-finance">
    <div class="container-fluid d-flex justify-content-between align-items-center">
      <div class="d-flex align-items-center gap-2">
        <i class="bi bi-bar-chart-line-fill text-success fs-4"></i>
        <span class="navbar-title">Finance Dashboard</span>
      </div>
      <form id="logoutForm" action="{{ route('logout') }}" method="POST" class="mb-0">
        @csrf
        <button type="button" id="logoutBtn" class="btn btn-outline-success btn-sm logout-btn">
          <i class="bi bi-box-arrow-right me-1"></i> Logout
        </button>
      </form>
    </div>
  </nav>

  <!-- Main Content -->
  <div class="main-content">
    @yield('content')
  </div>

  <!-- Footer -->
  <footer>
   &copy; 2025 D'Dahar. All rights reserved.
  </footer>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    // Logout confirmation
    document.getElementById('logoutBtn').addEventListener('click', function () {
      Swal.fire({
        title: 'Konfirmasi Logout',
        text: "Apakah Anda yakin ingin keluar?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Logout',
        cancelButtonText: 'Batal'
      }).then((result) => {
        if (result.isConfirmed) {
          document.getElementById('logoutForm').submit();
        }
      });
    });
  </script>
</body>
</html>
