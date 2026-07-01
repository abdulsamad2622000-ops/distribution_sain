<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NSH Platform — @yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background-color: #f0f2f5; font-family: 'Segoe UI', sans-serif; }
        .sidebar {
            width: 250px; min-height: 100vh;
            background: linear-gradient(180deg, #1a1a2e 0%, #16213e 50%, #1a1a2e 100%);
            position: fixed; top: 0; left: 0; z-index: 1050; overflow-y: auto;
            transition: transform 0.3s ease;
        }
        .sidebar .brand { padding: 20px; border-bottom: 1px solid rgba(255,255,255,0.1); }
        .sidebar .brand h4 { color: #6c5ce7; font-weight: 700; margin: 0; font-size: 16px; }
        .sidebar .brand small { color: rgba(255,255,255,0.5); font-size: 11px; }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.7); padding: 9px 20px; border-radius: 8px;
            margin: 2px 8px; transition: all 0.3s; font-size: 13px;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background: rgba(108,92,231,0.25); color: #a29bfe;
        }
        .sidebar .nav-link i { width: 20px; margin-right: 8px; }
        .sidebar .nav-section {
            color: rgba(255,255,255,0.3); font-size: 10px; font-weight: 700;
            letter-spacing: 1px; padding: 12px 20px 4px; text-transform: uppercase;
        }
        .sidebar-overlay {
            display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.5); z-index: 1040;
        }
        .sidebar-overlay.active { display: block; }
        .main-content { margin-left: 250px; min-height: 100vh; transition: all 0.3s ease; }
        .topbar {
            background: #fff; padding: 12px 25px; border-bottom: 1px solid #e9ecef;
            display: flex; justify-content: space-between; align-items: center;
            position: sticky; top: 0; z-index: 999;
        }
        .topbar h5 { margin: 0; font-weight: 600; color: #1a1a2e; font-size: 15px; }
        .content-area { padding: 20px; }
        .hamburger-btn {
            display: block; background: none; border: none; font-size: 22px;
            color: #1a1a2e; cursor: pointer; padding: 0; margin-right: 10px;
        }
        .card { border: none; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); }
        .card-header {
            background: #fff; border-bottom: 1px solid #f0f2f5;
            border-radius: 12px 12px 0 0 !important; font-weight: 600; padding: 15px 20px;
        }
        .btn-primary { background: #6c5ce7; border-color: #6c5ce7; }
        .btn-primary:hover { background: #5a4bd1; border-color: #5a4bd1; }
        .text-primary { color: #6c5ce7 !important; }
        .stat-card { border-radius: 12px; padding: 20px; color: #fff; }
        .table th {
            background: #f8f9fa; font-size: 12px; font-weight: 600;
            color: #6c757d; text-transform: uppercase;
        }
        .table td { font-size: 13px; vertical-align: middle; }
        .badge { font-size: 11px; padding: 5px 10px; border-radius: 20px; }
        @media (max-width: 991px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar:not(.hidden) { transform: translateX(0); }
            .main-content { margin-left: 0 !important; }
            .content-area { padding: 15px; }
        }
    </style>
</head>
<body>

<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

<div class="sidebar" id="sidebar">
    <div class="brand">
        <h4><i class="bi bi-shield-lock"></i> NSH Platform</h4>
        <small>Super Admin — {{ Auth::user()->name }}</small>
    </div>

    <nav class="mt-3">
        <div class="nav-section">Control Center</div>
        <a href="{{ route('superadmin.dashboard') }}" class="nav-link {{ request()->routeIs('superadmin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>
        <a href="{{ route('superadmin.companies.index') }}" class="nav-link {{ request()->routeIs('superadmin.companies.*') ? 'active' : '' }}">
            <i class="bi bi-buildings"></i> Companies
        </a>
        <a href="{{ route('superadmin.plans.index') }}" class="nav-link {{ request()->routeIs('superadmin.plans.*') ? 'active' : '' }}">
            <i class="bi bi-box-seam"></i> Subscription Plans
        </a>
    </nav>
</div>

<div class="main-content" id="mainContent">
    <div class="topbar">
        <div class="d-flex align-items-center">
            <button class="hamburger-btn" onclick="toggleSidebar()"><i class="bi bi-list"></i></button>
            <h5>@yield('title')</h5>
        </div>
        <div class="dropdown">
            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                <i class="bi bi-person-circle"></i>
                <span class="d-none d-md-inline"> {{ Auth::user()->name }}</span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><span class="dropdown-item-text text-muted" style="font-size:12px;">Platform Owner</span></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>

    <div class="content-area">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="bi bi-exclamation-circle"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('hidden');
        document.getElementById('sidebarOverlay').classList.toggle('active');
    }
    function closeSidebar() {
        document.getElementById('sidebar').classList.add('hidden');
        document.getElementById('sidebarOverlay').classList.remove('active');
    }
</script>
</body>
</html>
