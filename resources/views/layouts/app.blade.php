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

        /* Language Switcher */
        .lang-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: var(--surface-2);
            border: 1px solid var(--border);
            color: var(--text-muted);
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-decoration: none;
            transition: all 0.2s;
            flex-shrink: 0;
        }
        .lang-btn:hover {
            background: var(--surface-3);
            color: var(--text);
            border-color: var(--border-active);
        }
        .lang-btn.lang-active {
            background: var(--accent);
            color: #fff;
            border-color: var(--accent);
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
                <div class="logo-sub">{{ __('app.brand.subtitle') }}</div>
            </div>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-section">{{ __('app.nav.main') }}</div>
            <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}" data-label="{{ __('app.nav.dashboard') }}">
                <i class="fas fa-gauge-high"></i> <span>{{ __('app.nav.dashboard') }}</span>
            </a>

            {{-- Database: hanya Pimpinan, Admin, Kepala Gudang --}}
            @if(auth()->user()->isManagement() || auth()->user()->isKepalaGudang())
            <div class="nav-section">{{ __('app.nav.master_data') }}</div>

            <a href="{{ route('suppliers.index') }}" class="nav-item {{ request()->routeIs('suppliers.*') ? 'active' : '' }}" data-label="{{ __('app.nav.data_supplier') }}">
                <i class="fas fa-building"></i> <span>{{ __('app.nav.data_supplier') }}</span>
            </a>

            <a href="{{ route('customers.index') }}" class="nav-item {{ request()->routeIs('customers.*') ? 'active' : '' }}" data-label="{{ __('app.nav.data_customer') }}">
                <i class="fas fa-users"></i> <span>{{ __('app.nav.data_customer') }}</span>
            </a>

            <a href="{{ route('warehouses.index') }}" class="nav-item {{ request()->routeIs('warehouses.*') ? 'active' : '' }}" data-label="{{ __('app.nav.data_warehouse') }}">
                <i class="fas fa-warehouse"></i> <span>{{ __('app.nav.data_warehouse') }}</span>
            </a>

            <a href="{{ route('projects.index') }}" class="nav-item {{ request()->routeIs('projects.*') ? 'active' : '' }}" data-label="{{ __('app.nav.data_project') }}">
                <i class="fas fa-diagram-project"></i> <span>{{ __('app.nav.data_project') }}</span>
            </a>

             <a href="{{ route('materials.index') }}" class="nav-item {{ request()->routeIs('materials.*') ? 'active' : '' }}" data-label="{{ __('app.nav.data_material') }}">
                <i class="fas fa-cube"></i> <span>{{ __('app.nav.data_material') }}</span>
            </a>
            
            <a href="{{ route('parts.index') }}" class="nav-item {{ request()->routeIs('parts.*') ? 'active' : '' }}" data-label="{{ __('app.nav.data_part') }}">
                <i class="fas fa-cubes"></i> <span>{{ __('app.nav.data_part') }}</span>
            </a>
            @endif

            <div class="nav-section">{{ __('app.nav.purchasing') }}</div>
            {{-- Purchase Request: hanya Kepala Gudang, Pimpinan, Admin --}}
            @if(auth()->user()->isManagement() || auth()->user()->isKepalaGudang())
            <a href="{{ route('purchase-requests.index') }}" class="nav-item {{ request()->routeIs('purchase-requests.*') ? 'active' : '' }}" data-label="{{ __('app.nav.purchase_request') }}">
                <i class="fas fa-cart-plus"></i> <span>{{ __('app.nav.purchase_request') }}</span>
            </a>
            @endif

            {{-- Purchase Order: hanya Kepala Gudang, Pimpinan, Admin --}}
            @if(auth()->user()->isManagement() || auth()->user()->isKepalaGudang())
            <a href="{{ route('purchase-orders.index') }}" class="nav-item {{ request()->routeIs('purchase-orders.*') ? 'active' : '' }}" data-label="{{ __('app.nav.purchase_order') }}">
                <i class="fas fa-file-invoice-dollar"></i> <span>{{ __('app.nav.purchase_order') }}</span>
            </a>
            @endif

            {{-- Good Receipt group --}}
            <div class="nav-section">{{ __('app.nav.good_receipt') }}</div>
            <a href="{{ route('good-receipts.index') }}" class="nav-item {{ request()->routeIs('good-receipts.*') ? 'active' : '' }}" data-label="{{ __('app.nav.good_receipt') }}">
                <i class="fas fa-arrow-right-to-bracket"></i> <span>{{ __('app.nav.good_receipt') }}</span>
            </a>
            <a href="{{ route('reports.receiving') }}" class="nav-item {{ request()->routeIs('reports.receiving') ? 'active' : '' }}" data-label="{{ __('app.nav.receiving_report') }}">
                <i class="fas fa-file-invoice"></i> <span>{{ __('app.nav.receiving_report') }}</span>
            </a>

            {{-- Good Issue group --}}
            <div class="nav-section">{{ __('app.nav.good_issue') }}</div>
            <a href="{{ route('good-issues.index') }}" class="nav-item {{ request()->routeIs('good-issues.*') ? 'active' : '' }}" data-label="{{ __('app.nav.good_issue') }}">
                <i class="fas fa-arrow-right-from-bracket"></i> <span>{{ __('app.nav.good_issue') }}</span>
            </a>
            <a href="{{ route('reports.disbursal') }}" class="nav-item {{ request()->routeIs('reports.disbursal') ? 'active' : '' }}" data-label="{{ __('app.nav.disbursal_report') }}">
                <i class="fas fa-file-lines"></i> <span>{{ __('app.nav.disbursal_report') }}</span>
            </a>

            <div class="nav-section">{{ __('app.nav.inventory') }}</div>
            @if(!auth()->user()->isKaryawan())
            <a href="{{ route('goods-adjustment.index') }}" class="nav-item {{ request()->routeIs('goods-adjustment.*') ? 'active' : '' }}" data-label="{{ __('app.nav.goods_adjustment') }}">
                <i class="fas fa-sliders"></i> <span>{{ __('app.nav.goods_adjustment') }}</span>
            </a>
            @endif
            <a href="{{ route('inventory-stocks.index') }}" class="nav-item {{ request()->routeIs('inventory-stocks.*') ? 'active' : '' }}" data-label="{{ __('app.nav.inventory_stock') }}">
                <i class="fas fa-boxes-stacked"></i> <span>{{ __('app.nav.inventory_stock') }}</span>
            </a>
            <a href="{{ route('mutasi.index') }}" class="nav-item {{ request()->routeIs('mutasi.*') ? 'active' : '' }}" data-label="{{ __('app.nav.mutation_history') }}">
                <i class="fas fa-clock-rotate-left"></i> <span>{{ __('app.nav.mutation_history') }}</span>
            </a>

            @if(!auth()->user()->isKaryawan())
            <div class="nav-section">{{ __('app.nav.work_order') }}</div>
            <a href="{{ route('production-qc.index') }}" class="nav-item {{ request()->routeIs('production-qc.*') ? 'active' : '' }}" data-label="{{ __('app.nav.quality_check') }}">
                <i class="fas fa-clipboard-check"></i> <span>{{ __('app.nav.quality_check') }}</span>
            </a>
            @endif


            {{-- Administrasi: hanya Pimpinan & Admin --}}
            @if(auth()->user()->isManagement())
            <div class="nav-section">{{ __('app.nav.administration') }}</div>
            <a href="{{ route('users.index') }}" class="nav-item {{ request()->routeIs('users.*') ? 'active' : '' }}" data-label="{{ __('app.nav.account_mgmt') }}">
                <i class="fas fa-users-gear"></i> <span>{{ __('app.nav.account_mgmt') }}</span>
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
                   title="{{ __('app.nav.logout') }}">
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
            <button class="sidebar-toggle-btn" id="sidebarToggle" title="{{ __('app.topbar.toggle_menu') }}" onclick="toggleSidebar()">
                <i class="fas fa-bars" id="toggleIcon"></i>
            </button>
            <div class="topbar-title">@yield('topbar-title',  __('app.nav.dashboard'))</div>
            <div class="topbar-actions">
                <div class="topbar-clock">
                    <div class="clock-time" id="clockTime">00:00:00</div>
                    <div class="clock-date" id="clockDate">-</div>
                </div>

                {{-- Language Switcher --}}
                <div class="lang-switcher" style="display:flex;gap:4px;align-items:center;">
                    <a href="{{ route('lang.switch', 'id') }}"
                       class="lang-btn {{ app()->getLocale() === 'id' ? 'lang-active' : '' }}"
                       title="Bahasa Indonesia">ID</a>
                    <a href="{{ route('lang.switch', 'en') }}"
                       class="lang-btn {{ app()->getLocale() === 'en' ? 'lang-active' : '' }}"
                       title="English">EN</a>
                </div>

                <button class="theme-toggle" id="themeToggle" title="{{ __('app.topbar.toggle_theme') }}" onclick="toggleTheme()">
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

    {{-- ── Global Confirm Modal ─────────────────────────── --}}
    <div id="appModal" style="display:none; position:fixed; inset:0; z-index:99999; align-items:center; justify-content:center; padding:20px;">
        <div id="appModalBackdrop" style="position:absolute; inset:0; background:rgba(0,0,0,0.65); backdrop-filter:blur(4px);"></div>
        <div style="position:relative; background:var(--surface-1); border:1px solid var(--border); border-radius:18px;
                    padding:36px 32px 28px; max-width:420px; width:100%;
                    box-shadow:0 24px 64px rgba(0,0,0,0.55); animation:modalIn .18s ease;">
            <div style="text-align:center; margin-bottom:18px;">
                <span id="appModalIconWrap" style="display:inline-flex; align-items:center; justify-content:center;
                      width:60px; height:60px; border-radius:50%; background:rgba(239,68,68,0.12); font-size:26px; color:var(--danger);">
                    <i id="appModalIcon" class="fas fa-triangle-exclamation"></i>
                </span>
            </div>
            <h3 id="appModalTitle" style="text-align:center; font-size:17px; font-weight:700; color:var(--text); margin-bottom:10px;"></h3>
            <p  id="appModalMessage" style="text-align:center; font-size:13px; color:var(--text-muted); margin-bottom:28px; line-height:1.6;"></p>
            <div style="display:flex; gap:10px; justify-content:center;">
                <button id="appModalCancelBtn" class="btn btn-ghost" style="min-width:110px; font-size:14px;"></button>
                <button id="appModalConfirmBtn" class="btn btn-danger" style="min-width:110px; font-size:14px;"></button>
            </div>
        </div>
    </div>
    <style>
        @keyframes modalIn {
            from { opacity:0; transform:scale(.94) translateY(10px); }
            to   { opacity:1; transform:scale(1)   translateY(0); }
        }
    </style>

    <script>
        // ── Global Confirm Modal ──────────────────────────
        let _appModalCallback = null;

        function openAppModal(opts) {
            document.getElementById('appModalTitle').textContent      = opts.title   || '';
            document.getElementById('appModalMessage').textContent    = opts.message || '';
            document.getElementById('appModalCancelBtn').textContent  = opts.cancel  || '{{ __("app.btn.cancel") }}';
            const okBtn = document.getElementById('appModalConfirmBtn');
            okBtn.textContent  = opts.ok    || '{{ __("app.btn.confirm") }}';
            okBtn.className    = 'btn ' + (opts.okClass || 'btn-danger');
            const iconEl = document.getElementById('appModalIcon');
            const iconWrap = document.getElementById('appModalIconWrap');
            iconEl.className   = 'fas ' + (opts.icon || 'fa-triangle-exclamation');
            iconWrap.style.background = opts.iconBg || 'rgba(239,68,68,0.12)';
            iconWrap.style.color      = opts.iconColor || 'var(--danger)';
            _appModalCallback  = opts.onConfirm || null;
            const modal = document.getElementById('appModal');
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeAppModal() {
            document.getElementById('appModal').style.display = 'none';
            document.body.style.overflow = '';
            _appModalCallback = null;
        }

        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('appModalConfirmBtn').addEventListener('click', function () {
                const cb = _appModalCallback; // simpan dulu sebelum closeAppModal null-kan
                closeAppModal();
                if (cb) cb();
            });
            document.getElementById('appModalCancelBtn').addEventListener('click', closeAppModal);
            document.getElementById('appModalBackdrop').addEventListener('click', closeAppModal);
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') closeAppModal();
            });

            // ── Handle forms with data-confirm ────────────
            document.body.addEventListener('submit', function (e) {
                const form = e.target;
                if (!form.hasAttribute('data-confirm')) return;
                e.preventDefault();
                e.stopImmediatePropagation();
                const msg       = form.getAttribute('data-confirm');
                const title     = form.getAttribute('data-confirm-title')  || '{{ __("app.common.confirm_delete") }}';
                const okText    = form.getAttribute('data-confirm-ok')     || '{{ __("app.btn.delete") }}';
                const okClass   = form.getAttribute('data-confirm-class')  || 'btn-danger';
                const icon      = form.getAttribute('data-confirm-icon')   || 'fa-trash';
                openAppModal({
                    title, message: msg, ok: okText, okClass, icon,
                    onConfirm: function () {
                        // Hapus atribut agar submit berikutnya tidak ditahan lagi
                        form.removeAttribute('data-confirm');
                        form.submit();
                    }
                });
            }, true);

            // ── Handle buttons/links with data-confirm-btn ─
            document.body.addEventListener('click', function (e) {
                const btn = e.target.closest('[data-confirm-btn]');
                if (!btn) return;
                e.preventDefault();
                e.stopImmediatePropagation();
                // Capture referensi form sekarang, bukan di dalam closure async
                const targetForm = btn.closest('form');
                openAppModal({
                    title:     btn.getAttribute('data-confirm-title')  || '{{ __("app.btn.confirm") }}',
                    message:   btn.getAttribute('data-confirm-btn'),
                    ok:        btn.getAttribute('data-confirm-ok')     || '{{ __("app.btn.confirm") }}',
                    okClass:   btn.getAttribute('data-confirm-class')  || 'btn-primary',
                    icon:      btn.getAttribute('data-confirm-icon')   || 'fa-circle-question',
                    iconBg:    btn.getAttribute('data-confirm-iconbg') || 'rgba(99,102,241,0.12)',
                    iconColor: btn.getAttribute('data-confirm-iconc')  || 'var(--accent)',
                    onConfirm: function () {
                        if (targetForm) {
                            targetForm.removeAttribute('data-confirm');
                            targetForm.submit();
                        }
                    }
                });
            }, true);
        });
    </script>

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
        const DAYS = {!! json_encode(__('app.days')) !!};
        const MONTHS = {!! json_encode(__('app.months')) !!};

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

        // ── Sidebar: scroll ke menu yang aktif saat load ───
        (function () {
            const nav        = document.querySelector('.sidebar-nav');
            const activeItem = nav ? nav.querySelector('.nav-item.active') : null;
            if (!nav || !activeItem) return;

            // Hitung posisi item aktif relatif terhadap nav container
            const navRect    = nav.getBoundingClientRect();
            const itemRect   = activeItem.getBoundingClientRect();
            const itemTop    = activeItem.offsetTop;
            const navHeight  = nav.clientHeight;

            // Scroll agar item aktif berada di tengah area nav
            const scrollTo   = itemTop - (navHeight / 2) + (activeItem.clientHeight / 2);
            nav.scrollTop    = Math.max(0, scrollTo);
        })();
    </script>
</body>
</html>