<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Inventory') — Material App</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:ital,wght@0,300;0,400;0,500;1,300&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        :root {
            --bg: #0d0f14;
            --surface: #151821;
            --surface-2: #1c2030;
            --surface-3: #232840;
            --border: rgba(255,255,255,0.07);
            --border-active: rgba(99,179,237,0.3);
            --text: #e8eaf0;
            --text-muted: #7b8299;
            --text-dim: #4a5068;
            --accent: #4f8ef7;
            --accent-glow: rgba(79,142,247,0.15);
            --accent-2: #7c6bef;
            --success: #34d399;
            --success-bg: rgba(52,211,153,0.1);
            --warning: #fbbf24;
            --warning-bg: rgba(251,191,36,0.1);
            --danger: #f87171;
            --danger-bg: rgba(248,113,113,0.1);
            --info: #60a5fa;
            --sidebar-w: 260px;
            --header-h: 60px;
            --radius: 12px;
            --radius-sm: 8px;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            font-size: 14px;
            line-height: 1.6;
        }

        /* === SIDEBAR === */
        .sidebar {
            width: var(--sidebar-w);
            height: 100vh;
            position: fixed;
            left: 0; top: 0;
            background: var(--surface);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            z-index: 100;
            overflow: hidden;
        }

        .sidebar-logo {
            padding: 20px 24px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .logo-icon {
            width: 36px; height: 36px;
            background: linear-gradient(135deg, var(--accent), var(--accent-2));
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            color: white;
            flex-shrink: 0;
        }

        .logo-text {
            font-family: 'Syne', sans-serif;
            font-weight: 800;
            font-size: 17px;
            color: var(--text);
            letter-spacing: -0.3px;
        }

        .logo-sub {
            font-size: 10px;
            color: var(--text-muted);
            letter-spacing: 1px;
            text-transform: uppercase;
            font-weight: 300;
        }

        .sidebar-nav {
            flex: 1;
            padding: 12px 0;
            overflow-y: auto;
        }

        .nav-section {
            padding: 16px 20px 8px;
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: var(--text-dim);
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 20px;
            color: var(--text-muted);
            text-decoration: none;
            font-weight: 400;
            font-size: 13.5px;
            transition: all 0.2s;
            position: relative;
            border-radius: 0;
            margin: 1px 8px;
            border-radius: var(--radius-sm);
        }

        .nav-item:hover {
            background: var(--surface-2);
            color: var(--text);
        }

        .nav-item.active {
            background: var(--accent-glow);
            color: var(--accent);
            font-weight: 500;
        }

        .nav-item.active::before {
            content: '';
            position: absolute;
            left: 0; top: 50%;
            transform: translateY(-50%);
            width: 3px; height: 60%;
            background: var(--accent);
            border-radius: 0 2px 2px 0;
            left: -8px;
        }

        .nav-item i {
            width: 18px;
            text-align: center;
            font-size: 14px;
        }

        .nav-badge {
            margin-left: auto;
            background: var(--danger-bg);
            color: var(--danger);
            font-size: 10px;
            font-weight: 600;
            padding: 2px 7px;
            border-radius: 20px;
        }

        .sidebar-footer {
            padding: 16px 20px;
            border-top: 1px solid var(--border);
        }

        .user-card {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-avatar {
            width: 34px; height: 34px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--accent), var(--accent-2));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 700;
            color: white;
            flex-shrink: 0;
        }

        .user-name {
            font-size: 13px;
            font-weight: 500;
            color: var(--text);
        }

        .user-role {
            font-size: 11px;
            color: var(--text-muted);
        }

        /* === MAIN === */
        .main {
            margin-left: var(--sidebar-w);
            flex: 1;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .topbar {
            height: var(--header-h);
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            padding: 0 28px;
            gap: 16px;
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .topbar-title {
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: 16px;
            color: var(--text);
            flex: 1;
        }

        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* === CONTENT === */
        .content {
            flex: 1;
            padding: 28px;
        }

        /* === CARDS === */
        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            overflow: hidden;
        }

        .card-header {
            padding: 18px 24px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .card-title {
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: 15px;
            color: var(--text);
        }

        .card-body {
            padding: 24px;
        }

        /* === STAT CARDS === */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }

        .stat-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 20px;
            position: relative;
            overflow: hidden;
            transition: border-color 0.2s;
        }

        .stat-card:hover { border-color: var(--border-active); }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 2px;
        }

        .stat-card.blue::before { background: var(--accent); }
        .stat-card.green::before { background: var(--success); }
        .stat-card.yellow::before { background: var(--warning); }
        .stat-card.red::before { background: var(--danger); }
        .stat-card.purple::before { background: var(--accent-2); }

        .stat-icon {
            width: 40px; height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 14px;
            font-size: 16px;
        }

        .stat-card.blue .stat-icon { background: var(--accent-glow); color: var(--accent); }
        .stat-card.green .stat-icon { background: var(--success-bg); color: var(--success); }
        .stat-card.yellow .stat-icon { background: var(--warning-bg); color: var(--warning); }
        .stat-card.red .stat-icon { background: var(--danger-bg); color: var(--danger); }
        .stat-card.purple .stat-icon { background: rgba(124,107,239,0.1); color: var(--accent-2); }

        .stat-value {
            font-family: 'Syne', sans-serif;
            font-size: 28px;
            font-weight: 800;
            color: var(--text);
            line-height: 1;
            margin-bottom: 6px;
        }

        .stat-label {
            font-size: 12px;
            color: var(--text-muted);
            font-weight: 400;
        }

        /* === BUTTONS === */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 18px;
            border-radius: var(--radius-sm);
            font-size: 13px;
            font-weight: 500;
            font-family: 'DM Sans', sans-serif;
            cursor: pointer;
            border: none;
            text-decoration: none;
            transition: all 0.2s;
            white-space: nowrap;
        }

        .btn-primary {
            background: var(--accent);
            color: white;
        }
        .btn-primary:hover { background: #3d7de8; transform: translateY(-1px); }

        .btn-secondary {
            background: var(--surface-2);
            color: var(--text);
            border: 1px solid var(--border);
        }
        .btn-secondary:hover { background: var(--surface-3); }

        .btn-success {
            background: var(--success);
            color: #0d1a15;
        }
        .btn-success:hover { opacity: 0.9; }

        .btn-danger {
            background: transparent;
            color: var(--danger);
            border: 1px solid var(--danger);
        }
        .btn-danger:hover { background: var(--danger-bg); }

        .btn-sm { padding: 6px 12px; font-size: 12px; }
        .btn-xs { padding: 4px 10px; font-size: 11px; border-radius: 6px; }
        .btn-ghost {
            background: transparent;
            color: var(--text-muted);
            border: 1px solid var(--border);
        }
        .btn-ghost:hover { background: var(--surface-2); color: var(--text); }

        /* === TABLES === */
        .table-wrap { overflow-x: auto; }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13.5px;
        }

        thead th {
            padding: 12px 16px;
            text-align: left;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            color: var(--text-dim);
            border-bottom: 1px solid var(--border);
            background: var(--surface-2);
            white-space: nowrap;
        }

        tbody td {
            padding: 13px 16px;
            border-bottom: 1px solid var(--border);
            color: var(--text);
            vertical-align: middle;
        }

        tbody tr:last-child td { border-bottom: none; }

        tbody tr:hover { background: var(--surface-2); }

        .text-center { text-align: center; }
        .text-right { text-align: right; }

        /* === BADGES === */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }

        .badge-success { background: var(--success-bg); color: var(--success); }
        .badge-warning { background: var(--warning-bg); color: var(--warning); }
        .badge-danger { background: var(--danger-bg); color: var(--danger); }
        .badge-info { background: rgba(96,165,250,0.1); color: var(--info); }
        .badge-muted { background: var(--surface-3); color: var(--text-muted); }
        .badge-in { background: var(--success-bg); color: var(--success); }
        .badge-out { background: var(--danger-bg); color: var(--danger); }

        /* === FORMS === */
        .form-group {
            margin-bottom: 18px;
        }

        .form-label {
            display: block;
            margin-bottom: 6px;
            font-size: 12.5px;
            font-weight: 500;
            color: var(--text-muted);
            letter-spacing: 0.3px;
        }

        .form-label .required { color: var(--danger); }

        .form-control {
            width: 100%;
            padding: 10px 14px;
            background: var(--surface-2);
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            color: var(--text);
            font-size: 13.5px;
            font-family: 'DM Sans', sans-serif;
            transition: border-color 0.2s, box-shadow 0.2s;
            outline: none;
        }

        .form-control:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px var(--accent-glow);
        }

        .form-control::placeholder { color: var(--text-dim); }

        select.form-control { cursor: pointer; }
        textarea.form-control { resize: vertical; min-height: 90px; }

        .form-error {
            color: var(--danger);
            font-size: 12px;
            margin-top: 5px;
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
        }

        /* === ALERTS === */
        .alert {
            padding: 13px 18px;
            border-radius: var(--radius-sm);
            font-size: 13.5px;
            margin-bottom: 20px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }

        .alert-success { background: var(--success-bg); color: var(--success); border: 1px solid rgba(52,211,153,0.2); }
        .alert-danger { background: var(--danger-bg); color: var(--danger); border: 1px solid rgba(248,113,113,0.2); }
        .alert-warning { background: var(--warning-bg); color: var(--warning); border: 1px solid rgba(251,191,36,0.2); }

        /* === PAGINATION === */
        .pagination-wrap {
            padding: 16px 24px;
            border-top: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
        }

        .pagination-info { font-size: 12.5px; color: var(--text-muted); }

        .pagination {
            display: flex;
            gap: 4px;
            list-style: none;
        }

        .pagination .page-link {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 32px; height: 32px;
            border-radius: 8px;
            background: var(--surface-2);
            border: 1px solid var(--border);
            color: var(--text-muted);
            font-size: 13px;
            text-decoration: none;
            transition: all 0.2s;
        }

        .pagination .page-link:hover { background: var(--surface-3); color: var(--text); }
        .pagination .page-item.active .page-link { background: var(--accent); color: white; border-color: var(--accent); }
        .pagination .page-item.disabled .page-link { opacity: 0.4; cursor: not-allowed; }

        /* === SEARCH BAR === */
        .search-bar {
            display: flex;
            gap: 10px;
            align-items: center;
            flex-wrap: wrap;
        }

        .search-input-wrap {
            position: relative;
            flex: 1;
            min-width: 200px;
        }

        .search-input-wrap i {
            position: absolute;
            left: 12px; top: 50%;
            transform: translateY(-50%);
            color: var(--text-dim);
            font-size: 13px;
        }

        .search-input-wrap .form-control {
            padding-left: 36px;
        }

        /* === PAGE HEADER === */
        .page-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 24px;
            gap: 16px;
            flex-wrap: wrap;
        }

        .page-title {
            font-family: 'Syne', sans-serif;
            font-weight: 800;
            font-size: 22px;
            color: var(--text);
            margin-bottom: 3px;
        }

        .page-subtitle {
            font-size: 13px;
            color: var(--text-muted);
            font-weight: 300;
        }

        /* === EMPTY STATE === */
        .empty-state {
            text-align: center;
            padding: 60px 24px;
        }

        .empty-state i {
            font-size: 40px;
            color: var(--text-dim);
            margin-bottom: 14px;
            display: block;
        }

        .empty-state h4 {
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            color: var(--text-muted);
            margin-bottom: 6px;
        }

        .empty-state p {
            font-size: 13px;
            color: var(--text-dim);
        }

        /* === BREADCRUMB === */
        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 12.5px;
            color: var(--text-muted);
            margin-bottom: 20px;
        }

        .breadcrumb a { color: var(--accent); text-decoration: none; }
        .breadcrumb a:hover { text-decoration: underline; }
        .breadcrumb .sep { color: var(--text-dim); }

        /* === DIVIDER === */
        .divider { height: 1px; background: var(--border); margin: 20px 0; }

        /* === TOOLTIPS & misc === */
        .mono { font-family: 'Courier New', monospace; font-size: 12px; }

        .stock-in { color: var(--success); font-weight: 600; }
        .stock-out { color: var(--danger); font-weight: 600; }

        .tooltip-wrap { position: relative; }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: var(--surface-3); border-radius: 3px; }

        /* Print */
        @media print {
            .sidebar, .topbar, .no-print { display: none !important; }
            .main { margin-left: 0; }
            body { background: white; color: black; }
            .card { border: 1px solid #ccc; }
        }
    </style>

    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-logo">
            <div class="logo-icon"><i class="fas fa-boxes-stacked"></i></div>
            <div>
                <div class="logo-text">Material App</div>
                <div class="logo-sub">Inventory System</div>
            </div>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-section">Main</div>
            <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fas fa-chart-squares"></i> Dashboard
            </a>

            <div class="nav-section">Master Data</div>
            <a href="{{ route('materials.index') }}" class="nav-item {{ request()->routeIs('materials.*') ? 'active' : '' }}">
                <i class="fas fa-cube"></i> Data Material
            </a>

            <div class="nav-section">Transaksi</div>
            <a href="{{ route('stock-cards.index') }}" class="nav-item {{ request()->routeIs('stock-cards.*') ? 'active' : '' }}">
                <i class="fas fa-table-list"></i> Kartu Stok
            </a>
            <a href="{{ route('stock-cards.create') }}" class="nav-item {{ request()->routeIs('stock-cards.create') ? 'active' : '' }}">
                <i class="fas fa-arrow-right-to-bracket"></i> Penerimaan Barang
            </a>
            <a href="{{ route('withdrawal-cards.index') }}" class="nav-item {{ request()->routeIs('withdrawal-cards.*') ? 'active' : '' }}">
                <i class="fas fa-file-invoice"></i> Kartu Pengambilan
            </a>

            <div class="nav-section">Laporan</div>
            <a href="{{ route('reports.stock') }}" class="nav-item {{ request()->routeIs('reports.stock') ? 'active' : '' }}">
                <i class="fas fa-chart-bar"></i> Laporan Stok
            </a>
            <a href="{{ route('reports.transactions') }}" class="nav-item {{ request()->routeIs('reports.transactions') ? 'active' : '' }}">
                <i class="fas fa-right-left"></i> Laporan Transaksi
            </a>
            <a href="{{ route('reports.withdrawals') }}" class="nav-item {{ request()->routeIs('reports.withdrawals') ? 'active' : '' }}">
                <i class="fas fa-file-lines"></i> Laporan Pengambilan
            </a>
        </nav>

        <div class="sidebar-footer">
            <div class="user-card">
                <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}</div>
                <div>
                    <div class="user-name">{{ auth()->user()->name ?? 'Admin' }}</div>
                    <div class="user-role">Administrator</div>
                </div>
                <a href="{{ route('logout') }}" style="margin-left:auto; color: var(--text-muted);"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                   title="Logout">
                    <i class="fas fa-arrow-right-from-bracket"></i>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
                    @csrf
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="main">
        <header class="topbar">
            <div class="topbar-title">@yield('topbar-title', 'Dashboard')</div>
            <div class="topbar-actions">
                @yield('topbar-actions')
            </div>
        </header>

        <main class="content">
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-circle-check"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    <i class="fas fa-circle-exclamation"></i>
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>