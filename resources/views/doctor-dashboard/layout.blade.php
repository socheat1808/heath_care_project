<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Doctor Dashboard') — One-Health</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600;9..40,700&display=swap" rel="stylesheet" />
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --green: #1A8A6E;
            --green-dark: #12705A;
            --green-light: rgba(26, 138, 110, .1);
            --blue: #2563EB;
            --amber: #D97706;
            --red: #DC2626;
            --purple: #7C3AED;
            --text: #111827;
            --text-muted: #6B7280;
            --border: #E5E7EB;
            --bg: #F9FAFB;
            --sidebar-w: 260px;
            --sidebar-bg: #0d1f1a;
            --sidebar-text: rgba(255, 255, 255, .75);
            --sidebar-active: rgba(26, 138, 110, .25);
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            display: flex;
            min-height: 100vh;
        }

        /* ── SIDEBAR ── */
        .sidebar {
            width: var(--sidebar-w);
            background: var(--sidebar-bg);
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            display: flex;
            flex-direction: column;
            z-index: 200;
            overflow-y: auto;
        }

        .sidebar-logo {
            padding: 1.5rem 1.25rem 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, .06);
        }

        .sidebar-logo a {
            display: flex;
            align-items: center;
            gap: .65rem;
            text-decoration: none;
        }

        .logo-icon {
            width: 34px;
            height: 34px;
            background: var(--green);
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .logo-icon svg {
            width: 18px;
            height: 18px;
        }

        .logo-text {
            font-family: 'DM Serif Display', serif;
            font-size: 1.1rem;
            color: #fff;
        }

        .logo-text span {
            color: var(--green);
        }

        .logo-badge {
            margin-left: auto;
            font-size: .65rem;
            font-weight: 700;
            padding: .18rem .5rem;
            border-radius: 99px;
            background: rgba(26, 138, 110, .3);
            color: #6ee7b7;
            letter-spacing: .04em;
        }

        .sidebar-section {
            padding: 1rem .75rem .25rem;
        }

        .sidebar-section-label {
            font-size: .65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .1em;
            color: rgba(255, 255, 255, .3);
            padding: 0 .5rem;
            margin-bottom: .35rem;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: .7rem;
            padding: .6rem .75rem;
            border-radius: 9px;
            color: var(--sidebar-text);
            font-size: .875rem;
            font-weight: 500;
            text-decoration: none;
            cursor: pointer;
            transition: background .15s, color .15s;
            margin-bottom: .15rem;
        }

        .nav-item:hover {
            background: rgba(255, 255, 255, .06);
            color: #fff;
        }

        .nav-item.active {
            background: var(--sidebar-active);
            color: #6ee7b7;
        }

        .nav-item.active .nav-icon svg {
            stroke: #6ee7b7;
        }

        .nav-icon {
            width: 18px;
            height: 18px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .nav-icon svg {
            width: 18px;
            height: 18px;
            stroke: var(--sidebar-text);
        }

        .sidebar-bottom {
            margin-top: auto;
            padding: 1rem .75rem;
            border-top: 1px solid rgba(255, 255, 255, .06);
        }

        .doctor-profile-mini {
            display: flex;
            align-items: center;
            gap: .75rem;
            padding: .6rem .5rem;
        }

        .doc-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: var(--green);
            color: #fff;
            font-size: .75rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .doc-info {
            flex: 1;
            min-width: 0;
        }

        .doc-name {
            font-size: .8rem;
            font-weight: 600;
            color: #fff;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .doc-role {
            font-size: .7rem;
            color: rgba(255, 255, 255, .4);
        }

        /* ── MAIN ── */
        .main {
            margin-left: var(--sidebar-w);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* ── TOPBAR ── */
        .topbar {
            background: #fff;
            border-bottom: 1px solid var(--border);
            padding: 0 2rem;
            height: 64px;
            display: flex;
            align-items: center;
            gap: 1rem;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .topbar-title {
            font-size: 1rem;
            font-weight: 600;
            color: var(--text);
        }

        .topbar-right {
            margin-left: auto;
            display: flex;
            align-items: center;
            gap: .75rem;
        }

        .topbar-date {
            font-size: .8rem;
            color: var(--text-muted);
        }

        .icon-btn {
            width: 36px;
            height: 36px;
            border-radius: 9px;
            border: 1px solid var(--border);
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            position: relative;
            transition: background .15s;
        }

        .icon-btn:hover {
            background: var(--bg);
        }

        .icon-btn svg {
            width: 18px;
            height: 18px;
            stroke: var(--text-muted);
        }

        .notif-dot {
            position: absolute;
            top: 6px;
            right: 6px;
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: var(--red);
            border: 1.5px solid #fff;
        }

        /* ── PAGE CONTENT ── */
        .page-content {
            padding: 2rem;
            flex: 1;
        }

        .page-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 1.75rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .page-header h1 {
            font-family: 'DM Serif Display', serif;
            font-size: 1.6rem;
            color: var(--text);
            margin-bottom: .2rem;
        }

        .page-header p {
            font-size: .875rem;
            color: var(--text-muted);
        }

        /* ── STAT CARDS ── */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .stat-card {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 1.25rem 1.5rem;
            box-shadow: 0 1px 4px rgba(0, 0, 0, .04);
        }

        .stat-card-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: .75rem;
        }

        .stat-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .stat-icon svg {
            width: 20px;
            height: 20px;
        }

        .stat-icon.green {
            background: rgba(26, 138, 110, .12);
            stroke: var(--green);
        }

        .stat-icon.green svg {
            stroke: var(--green);
        }

        .stat-icon.blue {
            background: rgba(37, 99, 235, .1);
        }

        .stat-icon.blue svg {
            stroke: var(--blue);
        }

        .stat-icon.amber {
            background: rgba(217, 119, 6, .1);
        }

        .stat-icon.amber svg {
            stroke: var(--amber);
        }

        .stat-icon.purple {
            background: rgba(124, 58, 237, .1);
        }

        .stat-icon.purple svg {
            stroke: var(--purple);
        }

        .stat-value {
            font-size: 1.6rem;
            font-weight: 700;
            color: var(--text);
            line-height: 1;
        }

        .stat-label {
            font-size: .8rem;
            color: var(--text-muted);
            margin-top: .3rem;
        }

        .stat-trend {
            font-size: .78rem;
            font-weight: 600;
            padding: .2rem .5rem;
            border-radius: 99px;
        }

        .stat-trend.up {
            background: rgba(26, 138, 110, .1);
            color: var(--green);
        }

        .stat-trend.down {
            background: rgba(220, 38, 38, .08);
            color: var(--red);
        }

        /* ── CARDS ── */
        .chart-card {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 1.5rem;
            box-shadow: 0 1px 4px rgba(0, 0, 0, .04);
        }

        .card-title {
            font-size: .9375rem;
            font-weight: 600;
            color: var(--text);
            margin-bottom: .2rem;
        }

        .card-subtitle {
            font-size: .8rem;
            color: var(--text-muted);
        }

        /* ── BADGES ── */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: .25rem .65rem;
            border-radius: 99px;
            font-size: .75rem;
            font-weight: 600;
        }

        .badge-green {
            background: rgba(26, 138, 110, .1);
            color: var(--green);
        }

        .badge-blue {
            background: rgba(37, 99, 235, .1);
            color: var(--blue);
        }

        .badge-amber {
            background: rgba(217, 119, 6, .1);
            color: var(--amber);
        }

        .badge-red {
            background: rgba(220, 38, 38, .08);
            color: var(--red);
        }

        .badge-purple {
            background: rgba(124, 58, 237, .1);
            color: var(--purple);
        }

        /* ── BTN ── */
        .btn-primary {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            padding: .6rem 1.25rem;
            border-radius: 10px;
            background: var(--green);
            color: #fff;
            font-size: .875rem;
            font-weight: 600;
            border: none;
            cursor: pointer;
            text-decoration: none;
            transition: opacity .2s, transform .1s;
            font-family: inherit;
        }

        .btn-primary:hover {
            opacity: .88;
            transform: translateY(-1px);
        }

        .btn-primary svg {
            width: 15px;
            height: 15px;
        }
    </style>
</head>

<body>

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="sidebar-logo">
            <a href="{{ route('doctor-dashboard.index') }}">
                <div class="logo-icon">
                    <svg fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                </div>
                <span class="logo-text"><span>One</span>-Health</span>
                <span class="logo-badge">Doctor</span>
            </a>
        </div>

        <!-- Overview -->
        <div class="sidebar-section">
            <div class="sidebar-section-label">Overview</div>
            <a href="{{ route('doctor-dashboard.index') }}" class="nav-item {{ request()->routeIs('doctor-dashboard.index') ? 'active' : '' }}">
                <div class="nav-icon"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg></div>
                Dashboard
            </a>
        </div>

        <!-- Practice -->
        <div class="sidebar-section">
            <div class="sidebar-section-label">Practice</div>
            <a href="#" class="nav-item {{ request()->routeIs('doctor.appointments.*') ? 'active' : '' }}">
                <div class="nav-icon"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg></div>
                Appointments
            </a>
            <a href="#" class="nav-item {{ request()->routeIs('doctor.patients.*') ? 'active' : '' }}">
                <div class="nav-icon"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg></div>
                My Patients
            </a>
            <a href="#" class="nav-item {{ request()->routeIs('doctor.schedule.*') ? 'active' : '' }}">
                <div class="nav-icon"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg></div>
                Schedule
            </a>
        </div>

        <!-- Account -->
        <div class="sidebar-section">
            <div class="sidebar-section-label">Account</div>
            <a href="{{ route('doctor-dashboard.profile') }}" class="nav-item {{ request()->routeIs('doctor-dashboard.profile') ? 'active' : '' }}">
                <div class="nav-icon"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg></div>
                My Profile
            </a>
        </div>

        <!-- Bottom profile + logout -->
        <div class="sidebar-bottom">
            <div class="doctor-profile-mini">
                <div class="doc-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</div>
                <div class="doc-info">
                    <div class="doc-name">{{ Auth::user()->name }}</div>
                    <div class="doc-role">Doctor</div>
                </div>
                <form method="POST" action="{{ route('logout') }}" style="margin:0">
                    @csrf
                    <button type="submit" style="background:none;border:none;cursor:pointer;padding:4px;display:flex;opacity:.5;transition:opacity .15s" onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='.5'">
                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- MAIN -->
    <div class="main">
        <header class="topbar">
            <span class="topbar-title">@yield('title', 'Dashboard')</span>
            <div class="topbar-right">
                <span class="topbar-date">{{ now()->format('l, d M Y') }}</span>
                <button class="icon-btn">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    <span class="notif-dot"></span>
                </button>
                <div style="width:34px;height:34px;border-radius:50%;background:var(--green);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.75rem">
                    {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                </div>
            </div>
        </header>

        <div class="page-content">
            @yield('content')
        </div>
    </div>

</body>

</html>