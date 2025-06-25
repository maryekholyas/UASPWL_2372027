<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Staff Panitia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fc;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
        }

        .navbar {
            background-color: #007bff;
            color: white;
        }

        .navbar .navbar-brand,
        .navbar .nav-link,
        .navbar .btn {
            color: white;
        }

        .navbar .nav-link:hover,
        .navbar .btn:hover {
            color: #d1e3ff;
        }

        .main-content {
            padding: 90px 2rem 2rem 2rem;
            background-color: #f8f9fc;
            min-height: 100vh;
        }

        footer {
            text-align: center;
            padding: 1rem 0;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <i class="bi bi-speedometer2 me-2"></i> Admin Panel
            </a>
            <div class="ms-auto d-flex align-items-center">
                <span class="me-3">Selamat datang, Admin</span>
                <form id="logoutForm" action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="button" id="logoutBtn" class="btn btn-outline-light btn-sm">
                        <i class="bi bi-box-arrow-right me-1"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <div class="main-content">
        @yield('content')
        <footer>
            &copy; 2025 D'Dahar. All rights reserved.
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.getElementById('logoutBtn').addEventListener('click', function () {
            Swal.fire({
                title: 'Konfirmasi',
                text: "Yakin ingin logout?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, logout!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logoutForm').submit();
                }
            });
        });

        if (document.getElementById('updateStaffBtn')) {
            document.getElementById('updateStaffBtn').addEventListener('click', function () {
                document.getElementById('editStaffModal').querySelector('.btn-close').click();
                Swal.fire('Sukses', 'Data staff berhasil diperbarui!', 'success');
            });
        }
    </script>
</body>
</html>
