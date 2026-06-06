<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NSH Distribution ERP — @yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background-color: #f0f2f5; font-family: 'Segoe UI', sans-serif; }
        .sidebar {
            width: 250px;
            min-height: 100vh;
            background: linear-gradient(180deg, #0d1b2a 0%, #1b2838 50%, #0d1b2a 100%);
            position: fixed;
            top: 0; left: 0;
            z-index: 1050;
            overflow-y: auto;
            transition: transform 0.3s ease;
        }
        .sidebar .brand { padding: 20px; border-bottom: 1px solid rgba(255,255,255,0.1); }
        .sidebar .brand h4 { color: #00b4d8; font-weight: 700; margin: 0; font-size: 16px; }
        .sidebar .brand small { color: rgba(255,255,255,0.5); font-size: 11px; }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.7);
            padding: 9px 20px;
            border-radius: 8px;
            margin: 2px 8px;
            transition: all 0.3s;
            font-size: 13px;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: rgba(0,180,216,0.2);
            color: #00b4d8;
        }
        .sidebar .nav-link i { width: 20px; margin-right: 8px; }
        .sidebar .nav-section {
            color: rgba(255,255,255,0.3);
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1px;
            padding: 12px 20px 4px;
            text-transform: uppercase;
        }
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1040;
        }
        .sidebar-overlay.active { display: block; }
        .main-content {
            margin-left: 250px;
            min-height: 100vh;
            transition: all 0.3s ease;
        }
        .main-content.full { margin-left: 0; }
        .sidebar.hidden { transform: translateX(-100%); }
        .topbar {
            background: #fff;
            padding: 12px 25px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky; top: 0; z-index: 999;
        }
        .topbar h5 { margin: 0; font-weight: 600; color: #0d1b2a; font-size: 15px; }
        .content-area { padding: 20px; }
        .hamburger-btn {
            display: block;
            background: none; border: none;
            font-size: 22px; color: #0d1b2a;
            cursor: pointer; padding: 0; margin-right: 10px;
        }
        .card { border: none; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); }
        .card-header {
            background: #fff;
            border-bottom: 1px solid #f0f2f5;
            border-radius: 12px 12px 0 0 !important;
            font-weight: 600; padding: 15px 20px;
        }
        .btn-primary { background: #00b4d8; border-color: #00b4d8; }
        .btn-primary:hover { background: #0096c7; border-color: #0096c7; }
        .table th {
            background: #f8f9fa;
            font-size: 12px; font-weight: 600;
            color: #6c757d; text-transform: uppercase;
        }
        .table td { font-size: 13px; vertical-align: middle; }
        .badge { font-size: 11px; padding: 5px 10px; border-radius: 20px; }
        @media (max-width: 991px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar:not(.hidden) { transform: translateX(0); }
            .main-content { margin-left: 0 !important; }
            .content-area { padding: 15px; }
            .topbar { padding: 12px 15px; }
        }
    </style>
</head>
<body>

<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

<div class="sidebar" id="sidebar">
    <div class="brand">
        <h4><i class="bi bi-building"></i> NSH Distribution</h4>
        <small>{{ Auth::user()->name }} — {{ ucfirst(Auth::user()->role) }}</small>
    </div>

    <nav class="mt-3">
        <div class="nav-section">Main</div>
        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>

        <div class="nav-section">Sales</div>
        <a href="{{ route('sales.create') }}" class="nav-link {{ request()->routeIs('sales.create') ? 'active' : '' }}">
            <i class="bi bi-plus-circle"></i> New Invoice
        </a>
        <a href="{{ route('sales.index') }}" class="nav-link {{ request()->routeIs('sales.index') ? 'active' : '' }}">
            <i class="bi bi-receipt"></i> All Invoices
        </a>
        <a href="{{ route('recoveries.index') }}" class="nav-link {{ request()->routeIs('recoveries.*') ? 'active' : '' }}">
            <i class="bi bi-cash-coin"></i> Recovery
        </a>

        <div class="nav-section">Inventory</div>
        <a href="{{ route('products.index') }}" class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}">
            <i class="bi bi-box-seam"></i> Products
        </a>
        <a href="{{ route('suppliers.index') }}" class="nav-link {{ request()->routeIs('suppliers.*') ? 'active' : '' }}">
            <i class="bi bi-truck"></i> Suppliers
        </a>

        <div class="nav-section">Parties</div>
        <a href="{{ route('customers.index') }}" class="nav-link {{ request()->routeIs('customers.*') ? 'active' : '' }}">
            <i class="bi bi-people"></i> Customers
        </a>

        <div class="nav-section">Finance</div>
        <a href="{{ route('expenses.index') }}" class="nav-link {{ request()->routeIs('expenses.*') ? 'active' : '' }}">
            <i class="bi bi-wallet2"></i> Expenses
        </a>
        <a href="{{ route('reports.profit-loss') }}" class="nav-link {{ request()->routeIs('reports.profit-loss') ? 'active' : '' }}">
            <i class="bi bi-graph-up"></i> Profit & Loss
        </a>

        <div class="nav-section">Reports</div>
        <a href="{{ route('reports.index') }}" class="nav-link {{ request()->routeIs('reports.index') ? 'active' : '' }}">
            <i class="bi bi-bar-chart"></i> All Reports
        </a>

 @if(Auth::user()->role == 'admin')
<div class="nav-section">Admin</div>
<a href="{{ route('roles.index') }}" class="nav-link {{ request()->routeIs('roles.*') ? 'active' : '' }}">
    <i class="bi bi-shield-check"></i> Roles
</a>
<a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
    <i class="bi bi-person-gear"></i> Users
</a>
@endif
    </nav>
</div>

<div class="main-content" id="mainContent">
    <div class="topbar">
        <div class="d-flex align-items-center">
            <button class="hamburger-btn" onclick="toggleSidebar()">
                <i class="bi bi-list"></i>
            </button>
            <h5>@yield('title')</h5>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="text-muted d-none d-md-block" style="font-size:12px;">
                <i class="bi bi-calendar3"></i> {{ now()->format('d M Y') }}
            </span>
            <div class="dropdown">
                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="bi bi-person-circle"></i>
                    <span class="d-none d-md-inline"> {{ Auth::user()->name }}</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><span class="dropdown-item-text text-muted" style="font-size:12px;">{{ ucfirst(Auth::user()->role) }}</span></li>
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
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('mainContent');
    const overlay = document.getElementById('sidebarOverlay');

    function openSidebar() {
        sidebar.classList.remove('hidden');
        overlay.classList.add('active');
        mainContent.classList.remove('full');
        if (window.innerWidth < 992) document.body.style.overflow = 'hidden';
    }

    function closeSidebar() {
        sidebar.classList.add('hidden');
        overlay.classList.remove('active');
        mainContent.classList.add('full');
        document.body.style.overflow = '';
    }

    function toggleSidebar() {
        sidebar.classList.contains('hidden') ? openSidebar() : closeSidebar();
    }

    window.addEventListener('load', function() {
        if (window.innerWidth < 992) closeSidebar();
    });

    window.addEventListener('resize', function() {
        if (window.innerWidth >= 992) {
            openSidebar();
            overlay.classList.remove('active');
            document.body.style.overflow = '';
        } else {
            closeSidebar();
        }
    });
</script>
@yield('scripts')
</body>
</html>