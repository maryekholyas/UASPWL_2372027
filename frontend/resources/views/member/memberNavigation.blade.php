<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('dashboard-name') - Member Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --sidebar-width: 250px;
            --topbar-height: 64px;
            --transition-speed: 0.3s;
            --sidebar-bg: #1e293b;
            --sidebar-text: #cbd5e1;
            --hover-color: #0ea5e9;
            --active-bg: #0ea5e9;
            --active-text: #ffffff;
            --main-bg: #f1f5f9;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--main-bg);
            color: #1e293b;
            margin: 0;
            overflow-x: hidden;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background-color: var(--sidebar-bg);
            padding-top: var(--topbar-height);
            z-index: 1000;
            transition: transform var(--transition-speed);
        }

        .sidebar-brand {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: var(--topbar-height);
            background-color: var(--sidebar-bg);
            color: var(--hover-color);
            font-weight: bold;
            font-size: 1.25rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-bottom: 1px solid #334155;
            z-index: 1010;
        }

        .sidebar-menu {
            margin-top: 1rem;
            padding: 0 1rem;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 0.75rem 1rem;
            color: var(--sidebar-text);
            text-decoration: none;
            border-radius: 0.5rem;
            transition: all 0.2s ease;
        }

        .sidebar-link:hover {
            background: rgba(14, 165, 233, 0.1);
            color: var(--hover-color);
        }

        .sidebar-link.active {
            background-color: var(--active-bg);
            color: var(--active-text);
            font-weight: 600;
        }

        .topbar {
            position: fixed;
            top: 0;
            left: var(--sidebar-width);
            right: 0;
            height: var(--topbar-height);
            background-color: #ffffff;
            display: flex;
            align-items: center;
            padding: 0 1.5rem;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.05);
            z-index: 1000;
        }

        .topbar .toggle-btn {
            background: none;
            border: none;
            font-size: 1.5rem;
            color: #1e293b;
            cursor: pointer;
            display: none;
        }

        .main-content {
            margin-top: var(--topbar-height);
            margin-left: var(--sidebar-width);
            padding: 2rem 1.5rem;
            transition: all var(--transition-speed);
        }

        footer {
            font-size: 0.875rem;
            text-align: center;
            color: #64748b;
        }

        /* Responsive */
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
        <i class="bi bi-people-fill me-2"></i>@yield('dashboard-name')
    </div>
    <div class="sidebar-menu">
        <a href="{{ route('member.dashboard') }}" class="sidebar-link {{ request()->routeIs('member.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2 me-2"></i>Dashboard
        </a>
        <a href="{{ route('events.index') }}" class="sidebar-link {{ request()->routeIs('events.*') ? 'active' : '' }}">
            <i class="bi bi-calendar-event me-2"></i>Events
        </a>
        <a href="#" onclick="event.preventDefault(); document.getElementById('logoutForm').submit();" class="sidebar-link">
            <i class="bi bi-box-arrow-right me-2"></i>Logout
        </a>
    </div>
</div>

<!-- Topbar -->
<div class="topbar">
    <button class="toggle-btn" id="sidebarToggle">
        <i class="bi bi-list"></i>
    </button>
    <div class="d-flex align-items-center flex-grow-1">
        <span class="text-muted">Selamat datang, {{ Session::get('user')['nama'] ?? 'Member' }}</span>
    </div>
    <form id="logoutForm" action="{{ route('logout') }}" method="POST">
        @csrf
    </form>
</div>

<!-- Main Content -->
<div class="main-content">
    @yield('content')
    <footer class="mt-5">
        &copy; 2025 D'Dahar. All rights reserved.
    </footer>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById('sidebarToggle').addEventListener('click', function () {
        document.querySelector('.sidebar').classList.toggle('active');
    });
</script>
</body>
</html>
