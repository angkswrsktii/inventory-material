<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — Nexstock</title>

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
            --sidebar-w: 220px;
            --sidebar-collapsed-w: 68px;
            --header-h: 60px;
            --radius: 12px;
            --radius-sm: 8px;
        }

        /* ── LIGHT MODE ── */
        body.light-mode {
            --bg: #f0f2f7;
            --surface: #ffffff;
            --surface-2: #f5f6fa;
            --surface-3: #eaecf3;
            --border: rgba(0,0,0,0.08);
            --border-active: rgba(79,142,247,0.4);
            --text: #1a1d2e;
            --text-muted: #6b7280;
            --text-dim: #9ca3af;
            --accent: #3b7de8;
            --accent-glow: rgba(59,125,232,0.12);
            --accent-2: #6c5ce7;
            --success: #059669;
            --success-bg: rgba(5,150,105,0.08);
            --warning: #d97706;
            --warning-bg: rgba(217,119,6,0.08);
            --danger: #dc2626;
            --danger-bg: rgba(220,38,38,0.08);
            --info: #2563eb;
        }

        /* ── THEME TOGGLE BUTTON ── */
        .theme-toggle {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: var(--surface-2);
            border: 1px solid var(--border);
            color: var(--text-muted);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            transition: all 0.2s;
            flex-shrink: 0;
        }
        .theme-toggle:hover {
            background: var(--surface-3);
            color: var(--text);
            border-color: var(--border-active);
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
            gap: 12px;
            margin-left: auto;
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

        .btn-sm { padding: 5px 10px; font-size: 11.5px; }
        .btn-xs { padding: 3px 8px; font-size: 11px; border-radius: 6px; }
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
        .badge-primary { background: rgba(16, 110, 225, 0.1); color: var(--info); }
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

        /* === SIDEBAR TOGGLE === */
        .sidebar-toggle-btn {
            width: 32px; height: 32px;
            border-radius: 8px;
            background: var(--surface-2);
            border: 1px solid var(--border);
            color: var(--text-muted);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            transition: all 0.2s;
            flex-shrink: 0;
        }
        .sidebar-toggle-btn:hover {
            background: var(--surface-3);
            color: var(--text);
            border-color: var(--border-active);
        }

        /* Collapsed sidebar */
        body.sidebar-collapsed .sidebar {
            width: var(--sidebar-collapsed-w);
        }
        body.sidebar-collapsed .main {
            margin-left: var(--sidebar-collapsed-w);
        }
        body.sidebar-collapsed .logo-text,
        body.sidebar-collapsed .logo-sub,
        body.sidebar-collapsed .nav-section,
        body.sidebar-collapsed .nav-item span,
        body.sidebar-collapsed .nav-badge,
        body.sidebar-collapsed .user-name,
        body.sidebar-collapsed .user-role {
            display: none;
        }
        body.sidebar-collapsed .sidebar-logo {
            padding: 20px;
            justify-content: center;
        }
        body.sidebar-collapsed .nav-item {
            justify-content: center;
            padding: 10px;
            margin: 1px 6px;
        }
        body.sidebar-collapsed .nav-item i {
            width: auto;
            font-size: 16px;
        }
        body.sidebar-collapsed .nav-item.active::before {
            left: -6px;
        }
        body.sidebar-collapsed .user-card {
            justify-content: center;
            gap: 0;
        }
        body.sidebar-collapsed .sidebar-footer {
            padding: 16px 10px;
        }
        body.sidebar-collapsed .user-avatar {
            margin: 0 auto;
        }
        body.sidebar-collapsed .logout-btn {
            display: none;
        }

        /* Tooltip untuk collapsed mode */
        body.sidebar-collapsed .nav-item {
            position: relative;
        }
        body.sidebar-collapsed .nav-item::after {
            content: attr(data-label);
            position: absolute;
            left: calc(var(--sidebar-collapsed-w) - 4px);
            top: 50%;
            transform: translateY(-50%);
            background: var(--surface-3);
            color: var(--text);
            padding: 5px 10px;
            border-radius: 6px;
            font-size: 12px;
            white-space: nowrap;
            border: 1px solid var(--border);
            pointer-events: none;
            opacity: 0;
            z-index: 200;
            transition: opacity 0.15s;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        }
        body.sidebar-collapsed .nav-item:hover::after {
            opacity: 1;
        }

        /* Sidebar & main transition */
        .sidebar, .main {
            transition: width 0.25s ease, margin-left 0.25s ease;
        }

        /* === CLOCK === */
        .topbar-clock {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            line-height: 1.2;
            margin-right: 4px;
        }
        .clock-time {
            font-family: 'Syne', sans-serif;
            font-size: 14px;
            font-weight: 700;
            color: var(--text);
            letter-spacing: 0.5px;
        }
        .clock-date {
            font-size: 10.5px;
            color: var(--text-muted);
            font-weight: 400;
        }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: var(--surface-3); border-radius: 3px; }

        /* === DASHBOARD GRIDS === */
        .dash-grid-2col {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }
        .dash-grid-main {
            display: grid;
            grid-template-columns: 1.5fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }

        /* === SIDEBAR OVERLAY (mobile backdrop) === */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.55);
            z-index: 99;
            backdrop-filter: blur(2px);
        }
        .sidebar-overlay.active { display: block; }

        /* ================================================================
           RESPONSIVE BREAKPOINTS
           ----------------------------------------------------------------
           > 1024px  : Desktop  — sidebar selalu visible, toggle = icon-only
           768–1024px: Tablet   — sidebar default collapsed (icon-only)
           < 768px   : Mobile   — sidebar off-canvas, muncul via overlay
        ================================================================ */

        /* ── TABLET (768px – 1024px) ── */
        @media (max-width: 1024px) and (min-width: 768px) {
            /* Default collapsed di tablet */
            body:not(.sidebar-mobile-open) .sidebar {
                width: var(--sidebar-collapsed-w);
            }
            body:not(.sidebar-mobile-open) .main {
                margin-left: var(--sidebar-collapsed-w);
            }
            body:not(.sidebar-mobile-open) .logo-text,
            body:not(.sidebar-mobile-open) .logo-sub,
            body:not(.sidebar-mobile-open) .nav-section,
            body:not(.sidebar-mobile-open) .nav-item span,
            body:not(.sidebar-mobile-open) .nav-badge,
            body:not(.sidebar-mobile-open) .user-name,
            body:not(.sidebar-mobile-open) .user-role,
            body:not(.sidebar-mobile-open) .logout-btn {
                display: none;
            }
            body:not(.sidebar-mobile-open) .sidebar-logo {
                padding: 20px;
                justify-content: center;
            }
            body:not(.sidebar-mobile-open) .nav-item {
                justify-content: center;
                padding: 10px;
                margin: 1px 6px;
            }
            body:not(.sidebar-mobile-open) .nav-item i {
                width: auto;
                font-size: 16px;
            }
            body:not(.sidebar-mobile-open) .user-card {
                justify-content: center;
            }
            body:not(.sidebar-mobile-open) .sidebar-footer {
                padding: 16px 10px;
            }

            /* Saat toggle dibuka di tablet → sidebar full width */
            body.sidebar-mobile-open .sidebar {
                width: var(--sidebar-w);
                z-index: 200;
            }
            body.sidebar-mobile-open .sidebar-overlay { display: block; }

            /* Stats grid 2 kolom di tablet */
            .stats-grid { grid-template-columns: repeat(2, 1fr); }

            /* Dashboard grids 1 kolom di tablet */
            .dash-grid-2col,
            .dash-grid-main { grid-template-columns: 1fr; }

            /* Form row 1 kolom */
            .form-row { grid-template-columns: 1fr; }

            /* Content padding lebih kecil */
            .content { padding: 20px; }

            /* Clock date hidden di tablet kecil */
            .clock-date { display: none; }
        }

        /* ── MOBILE (< 768px) ── */
        @media (max-width: 767px) {
            /* Sidebar off-canvas: sembunyikan ke kiri */
            .sidebar {
                transform: translateX(-100%);
                width: var(--sidebar-w) !important;
                transition: transform 0.28s cubic-bezier(0.4, 0, 0.2, 1), width 0s;
                z-index: 200;
                box-shadow: 4px 0 24px rgba(0,0,0,0.4);
            }

            /* Sidebar terbuka */
            body.sidebar-mobile-open .sidebar {
                transform: translateX(0);
            }
            body.sidebar-mobile-open .sidebar-overlay { display: block; }

            /* Main full width di mobile */
            .main {
                margin-left: 0 !important;
                transition: none;
            }

            /* Topbar */
            .topbar {
                padding: 0 14px;
                gap: 10px;
            }
            .topbar-title {
                font-size: 14px;
            }

            /* Sembunyikan jam detik & tanggal di mobile kecil */
            .clock-date { display: none; }
            .clock-time { font-size: 12px; }

            /* Stats 1 kolom */
            .stats-grid { grid-template-columns: 1fr 1fr; gap: 10px; }

            /* Dashboard grids semua jadi 1 kolom */
            .dash-grid-2col,
            .dash-grid-main { grid-template-columns: 1fr; gap: 14px; }

            /* Page header stack vertikal */
            .page-header {
                flex-direction: column;
                align-items: stretch;
                gap: 12px;
            }
            .page-header .btn { width: 100%; justify-content: center; }

            /* Card body padding lebih kecil */
            .card-body { padding: 16px; }
            .card-header { padding: 14px 16px; }

            /* Content padding */
            .content { padding: 14px; }

            /* Form row 1 kolom */
            .form-row { grid-template-columns: 1fr; }

            /* Table scroll */
            .table-wrap { overflow-x: auto; -webkit-overflow-scrolling: touch; }
            table { min-width: 540px; }

            /* Pagination */
            .pagination-wrap { flex-direction: column; align-items: flex-start; gap: 10px; }

            /* Search bar stack */
            .search-bar { flex-direction: column; align-items: stretch; }
            .search-input-wrap { min-width: unset; }

            /* Breadcrumb kecil */
            .breadcrumb { font-size: 11.5px; }

            /* Stat value lebih kecil */
            .stat-value { font-size: 22px; }
            .stat-card { padding: 14px; }

            /* Sembunyikan elemen non-esensial */
            .no-mobile { display: none !important; }
        }

        /* ── MOBILE XS (< 480px) ── */
        @media (max-width: 479px) {
            .stats-grid { grid-template-columns: 1fr; }
            .topbar-clock { display: none; }
            .topbar-title { font-size: 13px; }
            .page-title { font-size: 18px; }
        }

        /* ── DESKTOP LARGE (> 1280px) ── */
        @media (min-width: 1280px) {
            .stats-grid { grid-template-columns: repeat(4, 1fr); }
            .content { padding: 32px; }
        }

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
    <!-- Sidebar Overlay (mobile/tablet backdrop) -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-logo">
            <div class="logo-icon"><i class="fas fa-boxes-stacked"></i></div>
            <div>
                <div class="logo-text">Nexstock</div>
                <div class="logo-sub">SIM Inventory</div>
            </div>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-section">Main</div>
            <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}" data-label="Dashboard">
                <i class="fas fa-gauge-high"></i> <span>Dashboard</span>
            </a>

            {{-- Master Data: hanya Pimpinan, Admin, Kepala Gudang --}}
            @if(auth()->user()->isManagement() || auth()->user()->isKepalaGudang())
            <div class="nav-section">Master Data</div>
            <a href="{{ route('materials.index') }}" class="nav-item {{ request()->routeIs('materials.*') ? 'active' : '' }}" data-label="Data Material">
                <i class="fas fa-cube"></i> <span>Data Material</span>
            </a>
            <a href="{{ route('parts.index') }}" class="nav-item {{ request()->routeIs('parts.*') ? 'active' : '' }}" data-label="Data Part">
                <i class="fas fa-cubes"></i> <span>Data Part</span>
            </a>
            <a href="{{ route('suppliers.index') }}" class="nav-item {{ request()->routeIs('suppliers.*') ? 'active' : '' }}" data-label="Data Supplier">
                <i class="fas fa-building"></i> <span>Data Supplier</span>
            </a>
            <a href="{{ route('customers.index') }}" class="nav-item {{ request()->routeIs('customers.*') ? 'active' : '' }}" data-label="Data Customer">
                <i class="fas fa-users"></i> <span>Data Customer</span>
            </a>

            <a href="{{ route('projects.index') }}" class="nav-item {{ request()->routeIs('projects.*') ? 'active' : '' }}" data-label="Data Project">
                <i class="fas fa-diagram-project"></i> <span>Data Project</span>
            </a>
            @endif

            <div class="nav-section">Purchasing</div>
            {{-- Purchase Request: hanya Kepala Gudang, Pimpinan, Admin --}}
            @if(auth()->user()->isManagement() || auth()->user()->isKepalaGudang())
            <a href="{{ route('purchase-requests.index') }}" class="nav-item {{ request()->routeIs('purchase-requests.*') ? 'active' : '' }}" data-label="Purchase Request">
                <i class="fas fa-cart-plus"></i> <span>Purchase Request</span>
            </a>
            @endif

            {{-- Purchase Order: hanya Kepala Gudang, Pimpinan, Admin --}}
            @if(auth()->user()->isManagement() || auth()->user()->isKepalaGudang())
            <a href="{{ route('purchase-orders.index') }}" class="nav-item {{ request()->routeIs('purchase-orders.*') ? 'active' : '' }}" data-label="Purchase Order">
                <i class="fas fa-file-invoice-dollar"></i> <span>Purchase Order</span>
            </a>
            @endif

            {{-- Good Receipt group --}}
            <div class="nav-section">Good Receipt</div>
            <a href="{{ route('good-receipts.index') }}" class="nav-item {{ request()->routeIs('good-receipts.*') ? 'active' : '' }}" data-label="Good Receipt">
                <i class="fas fa-arrow-right-to-bracket"></i> <span>Good Receipt</span>
            </a>
            <a href="{{ route('reports.receiving') }}" class="nav-item {{ request()->routeIs('reports.receiving') ? 'active' : '' }}" data-label="Receiving Report">
                <i class="fas fa-file-invoice"></i> <span>Receiving Report</span>
            </a>

            {{-- Good Issue group --}}
            <div class="nav-section">Good Issue</div>
            <a href="{{ route('good-issues.index') }}" class="nav-item {{ request()->routeIs('good-issues.*') ? 'active' : '' }}" data-label="Good Issue">
                <i class="fas fa-arrow-right-from-bracket"></i> <span>Good Issue</span>
            </a>
            <a href="{{ route('reports.disbursal') }}" class="nav-item {{ request()->routeIs('reports.disbursal') ? 'active' : '' }}" data-label="Disbursal Report">
                <i class="fas fa-file-lines"></i> <span>Disbursal Report</span>
            </a>
            <a href="{{ route('return-gi.index') }}" class="nav-item {{ request()->routeIs('return-gi.*') ? 'active' : '' }}" data-label="Recycle Good Issue">
                <i class="fas fa-undo"></i> <span>Recycle Good Issue</span>
            </a>

            <div class="nav-section">Inventory</div>
            <a href="{{ route('goods-adjustment.index') }}" class="nav-item {{ request()->routeIs('goods-adjustment.*') ? 'active' : '' }}" data-label="Goods Adjustment">
                <i class="fas fa-sliders"></i> <span>Goods Adjustment</span>
            </a>
            <a href="{{ route('inventory-stocks.index') }}" class="nav-item {{ request()->routeIs('inventory-stocks.*') ? 'active' : '' }}" data-label="Inventory Stock">
                <i class="fas fa-boxes-stacked"></i> <span>Inventory Stock</span>
            </a>
             <a href="{{ route('mutasi.index') }}" class="nav-item {{ request()->routeIs('mutasi.*') ? 'active' : '' }}" data-label="Riwayat Mutasi">
                <i class="fas fa-clock-rotate-left"></i> <span>Riwayat Mutasi</span>
            </a>

            <div class="nav-section">Work Order</div>
            <a href="{{ route('production-qc.index') }}" class="nav-item {{ request()->routeIs('production-qc.*') ? 'active' : '' }}" data-label="Quality Check">
                <i class="fas fa-clipboard-check"></i> <span>Quality Check</span>
            </a>


            {{-- Administrasi: hanya Pimpinan & Admin --}}
            @if(auth()->user()->isManagement())
            <div class="nav-section">Administrasi</div>
            <a href="{{ route('users.index') }}" class="nav-item {{ request()->routeIs('users.*') ? 'active' : '' }}" data-label="Manajemen Akun">
                <i class="fas fa-users-gear"></i> <span>Manajemen Akun</span>
            </a>
            @endif
           

        </nav>

        <div class="sidebar-footer">
            <div class="user-card">
                <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}</div>
                <div>
                    <div class="user-name">{{ auth()->user()->name ?? 'Admin' }}</div>
                    <div class="user-role">{{ auth()->user()->role_label }}</div>
                </div>
                <a href="{{ route('logout') }}" class="logout-btn" style="margin-left:auto; color: var(--text-muted);"
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
            <button class="sidebar-toggle-btn" id="sidebarToggle" title="Toggle Menu" onclick="toggleSidebar()">
                <i class="fas fa-bars" id="toggleIcon"></i>
            </button>
            <div class="topbar-title">@yield('topbar-title', 'Dashboard')</div>
            <div class="topbar-actions">
                <div class="topbar-clock">
                    <div class="clock-time" id="clockTime">00:00:00</div>
                    <div class="clock-date" id="clockDate">-</div>
                </div>
                <button class="theme-toggle" id="themeToggle" title="Ganti Tema" onclick="toggleTheme()">
                    <i class="fas fa-moon" id="themeIcon"></i>
                </button>
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

    <script>
        // ── Theme Toggle ──────────────────────────────────
        const THEME_KEY = 'nexstock_theme';

        function applyTheme(theme) {
            const icon = document.getElementById('themeIcon');
            if (theme === 'light') {
                document.body.classList.add('light-mode');
                icon.className = 'fas fa-sun';
            } else {
                document.body.classList.remove('light-mode');
                icon.className = 'fas fa-moon';
            }
        }

        function toggleTheme() {
            const current = localStorage.getItem(THEME_KEY) || 'dark';
            const next    = current === 'dark' ? 'light' : 'dark';
            localStorage.setItem(THEME_KEY, next);
            applyTheme(next);
        }

        // Apply saved theme on load (before paint)
        (function () {
            const saved = localStorage.getItem(THEME_KEY) || 'dark';
            applyTheme(saved);
        })();

        // ── Sidebar Toggle (responsive-aware) ─────────────
        const SIDEBAR_KEY = 'nexstock_sidebar';

        function isMobile()  { return window.innerWidth < 768; }
        function isTablet()  { return window.innerWidth >= 768 && window.innerWidth <= 1024; }
        function isDesktop() { return window.innerWidth > 1024; }

        function closeSidebar() {
            document.body.classList.remove('sidebar-mobile-open');
            document.body.classList.remove('sidebar-collapsed');
            if (isDesktop()) {
                document.body.classList.add('sidebar-collapsed');
                localStorage.setItem(SIDEBAR_KEY, 'collapsed');
            }
        }

        function toggleSidebar() {
            if (isMobile() || isTablet()) {
                // Mobile & tablet: pakai off-canvas class
                const open = document.body.classList.toggle('sidebar-mobile-open');
                // Di tablet: juga kelola collapsed state
                if (isTablet()) {
                    if (open) {
                        document.body.classList.remove('sidebar-collapsed');
                    }
                }
            } else {
                // Desktop: collapsed icon-only
                const collapsed = document.body.classList.toggle('sidebar-collapsed');
                localStorage.setItem(SIDEBAR_KEY, collapsed ? 'collapsed' : 'expanded');
            }
        }

        // Tutup sidebar saat resize ke mobile/tablet
        window.addEventListener('resize', () => {
            if (isMobile() || isTablet()) {
                document.body.classList.remove('sidebar-collapsed');
            } else {
                document.body.classList.remove('sidebar-mobile-open');
            }
        });

        // Apply saved sidebar state (hanya desktop)
        (function () {
            if (isDesktop()) {
                const saved = localStorage.getItem(SIDEBAR_KEY) || 'expanded';
                if (saved === 'collapsed') {
                    document.body.classList.add('sidebar-collapsed');
                }
            }
        })();

        // ── Clock ─────────────────────────────────────────
        const DAYS   = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
        const MONTHS = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agt','Sep','Okt','Nov','Des'];

        function updateClock() {
            const now = new Date();
            const hh  = String(now.getHours()).padStart(2,'0');
            const mm  = String(now.getMinutes()).padStart(2,'0');
            const ss  = String(now.getSeconds()).padStart(2,'0');
            const day = DAYS[now.getDay()];
            const dd  = now.getDate();
            const mon = MONTHS[now.getMonth()];
            const yr  = now.getFullYear();

            document.getElementById('clockTime').textContent = `${hh}:${mm}:${ss}`;
            document.getElementById('clockDate').textContent = `${day}, ${dd} ${mon} ${yr}`;
        }

        updateClock();
        setInterval(updateClock, 1000);
    </script>
</body>
</html>