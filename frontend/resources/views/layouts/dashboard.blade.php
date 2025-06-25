<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root {
            --sidebar-width: 260px;
            --topbar-height: 64px;
            --transition-speed: 0.3s;

            --primary-color: #0d47a1;
            --accent-color: #42a5f5;
            --bg-light: #f4f6f9;
            --hover-bg: #e3f2fd;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: var(--bg-light);
            overflow-x: hidden;
        }

        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background: linear-gradient(to bottom, var(--primary-color), #1565c0);
            color: white;
            z-index: 1000;
            padding-top: var(--topbar-height);
            transition: transform var(--transition-speed) ease;
        }

        .sidebar-brand {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: var(--topbar-height);
            background-color: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.2rem;
            z-index: 1010;
        }

        .sidebar-menu {
            padding: 1rem 0;
        }

        .sidebar-item {
            margin: 0.25rem 0;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            color: #e3f2fd;
            text-decoration: none;
            transition: all 0.15s ease;
        }

        .sidebar-link:hover {
            background-color: var(--hover-bg);
            color: var(--primary-color);
        }

        .sidebar-link.active {
            background-color: white;
            color: var(--primary-color);
            font-weight: bold;
        }

        .sidebar-icon {
            margin-right: 0.75rem;
            font-size: 1.1rem;
        }

        .topbar {
            position: fixed;
            top: 0;
            left: var(--sidebar-width);
            right: 0;
            height: var(--topbar-height);
            background: linear-gradient(to right, #1976d2, #2196f3);
            color: white;
            display: flex;
            align-items: center;
            padding: 0 1rem;
            z-index: 1001;
            transition: all var(--transition-speed);
        }

        .topbar .toggle-btn {
            background: none;
            border: none;
            color: white;
            font-size: 1.3rem;
            display: none;
            cursor: pointer;
        }

        .topbar .topbar-content {
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .main-content {
            margin-top: var(--topbar-height);
            margin-left: var(--sidebar-width);
            padding: 1.5rem;
            transition: all var(--transition-speed);
        }

        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .topbar {
                left: 0;
            }

            .main-content {
                margin-left: 0;
            }

            .topbar .toggle-btn {
                display: block;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-brand">
            <i class="bi bi-speedometer2 me-2"></i> Admin Panel
        </div>
        <div class="sidebar-menu">
            <div class="sidebar-item">
                <a href="#" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-house-door sidebar-icon"></i> Dashboard
                </a>
            </div>
            <div class="sidebar-item">
                <a href="#" class="sidebar-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                    <i class="bi bi-people sidebar-icon"></i> Pengguna
                </a>
            </div>
            <div class="sidebar-item">
                <a href="#" class="sidebar-link {{ request()->routeIs('admin.products*') ? 'active' : '' }}">
                    <i class="bi bi-box sidebar-icon"></i> Produk
                </a>
            </div>
            <div class="sidebar-item">
                <a href="#" class="sidebar-link {{ request()->routeIs('admin.orders*') ? 'active' : '' }}">
                    <i class="bi bi-cart sidebar-icon"></i> Pesanan
                </a>
            </div>
            <div class="sidebar-item">
                <a href="#" class="sidebar-link {{ request()->routeIs('admin.analytics*') ? 'active' : '' }}">
                    <i class="bi bi-graph-up sidebar-icon"></i> Analitik
                </a>
            </div>
        </div>
    </div>

    <!-- Topbar -->
    <div class="topbar">
        <div class="topbar-content">
            <button class="toggle-btn" id="sidebarToggle">
                <i class="bi bi-list"></i>
            </button>
            <div class="d-flex align-items-center">
                <span class="me-3 fw-semibold">@yield('dashboard-name')</span>
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-light btn-sm">
                        <i class="bi bi-box-arrow-right me-1"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        @yield('content')
    </div>

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('sidebarToggle').addEventListener('click', function () {
            document.querySelector('.sidebar').classList.toggle('active');
        });
    </script>
</body>
</html>
