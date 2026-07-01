<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Patient Dashboard') — One-Health</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600;9..40,700&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/css/patient.css') }}">
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

        .nav-badge {
            margin-left: auto;
            background: var(--amber);
            color: #fff;
            font-size: .65rem;
            font-weight: 700;
            padding: .1rem .4rem;
            border-radius: 99px;
        }

        .sidebar-bottom {
            margin-top: auto;
            padding: 1rem .75rem;
            border-top: 1px solid rgba(255, 255, 255, .06);
        }

        .patient-profile-mini {
            display: flex;
            align-items: center;
            gap: .75rem;
            padding: .6rem .5rem;
        }

        .patient-avatar {
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

        .patient-info {
            flex: 1;
            min-width: 0;
        }

        .patient-name {
            font-size: .8rem;
            font-weight: 600;
            color: #fff;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .patient-role {
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
            text-decoration: none;
        }

        .icon-btn:hover {
            background: var(--bg);
        }

        .icon-btn svg {
            width: 18px;
            height: 18px;
            stroke: var(--text-muted);
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

        .stat-icon.red {
            background: rgba(220, 38, 38, .08);
        }

        .stat-icon.red svg {
            stroke: var(--red);
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
            margin-bottom: 1.25rem;
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

        .badge-gray {
            background: #f3f4f6;
            color: #6b7280;
        }

        /* ── BUTTONS ── */
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

        .btn-outline {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            padding: .6rem 1.25rem;
            border-radius: 10px;
            background: transparent;
            color: var(--text);
            font-size: .875rem;
            font-weight: 600;
            border: 1px solid var(--border);
            cursor: pointer;
            text-decoration: none;
            transition: background .15s;
            font-family: inherit;
        }

        .btn-outline:hover {
            background: var(--bg);
        }
    </style>
</head>

<body>

    @php $user = Auth::user(); @endphp

    {{-- ── SIDEBAR ── --}}
    <aside class="sidebar">

        {{-- Logo --}}
        <div class="sidebar-logo">
            <a href="{{ route('patient.dashboard') }}">
                <div class="logo-icon">
                    <svg fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                </div>
                <span class="logo-text"><span>One</span>-Health</span>
                <span class="logo-badge">Patient</span>
            </a>
        </div>

        {{-- Main Nav --}}
        <div class="sidebar-section">
            <div class="sidebar-section-label">Main</div>

            <a href="{{ route('patient.dashboard') }}"
                class="nav-item {{ request()->routeIs('patient.dashboard') ? 'active' : '' }}">
                <div class="nav-icon">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                </div>
                Dashboard
            </a>

            <a href="{{ route('patient.appointments.index') }}"
                class="nav-item {{ request()->routeIs('patient.appointments.index') ? 'active' : '' }}">
                <div class="nav-icon">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                My Appointments
                @php
                $pendingBadge = \App\Models\Appointment::where('patient_id', Auth::id())
                ->where('status', 'pending')->count();
                @endphp
                @if($pendingBadge > 0)
                <span class="nav-badge">{{ $pendingBadge }}</span>
                @endif
            </a>

            <a href="{{ route('patient.appointments.find-doctors') }}"
                class="nav-item {{ request()->routeIs('patient.appointments.find-doctors') ? 'active' : '' }}">
                <div class="nav-icon">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                Find Doctors
            </a>
        </div>

        {{-- Account Nav --}}
        <div class="sidebar-section">
            <div class="sidebar-section-label">Account</div>

            <a href="{{ route('profile.edit') }}"
                class="nav-item {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                <div class="nav-icon">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                My Profile
            </a>

            <a href="{{ route('home') }}" class="nav-item">
                <div class="nav-icon">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                🌐 Public Site
            </a>
        </div>

        {{-- Bottom --}}
        <div class="sidebar-bottom">
            <div class="patient-profile-mini">
                <div class="patient-avatar">
                    {{ strtoupper(substr($user->name, 0, 2)) }}
                </div>
                <div class="patient-info">
                    <div class="patient-name">{{ $user->name }}</div>
                    <div class="patient-role">Patient</div>
                </div>
                <form method="POST" action="{{ route('logout') }}" style="margin:0">
                    @csrf
                    <button type="submit"
                        style="background:none;border:none;cursor:pointer;padding:4px;display:flex;
                               opacity:.5;transition:opacity .15s"
                        onmouseover="this.style.opacity='1'"
                        onmouseout="this.style.opacity='.5'">
                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>

    </aside>

    {{-- ── MAIN ── --}}
    <div class="main">

        {{-- Topbar --}}
        <header class="topbar">
            <span class="topbar-title">@yield('title', 'Dashboard')</span>
            <div class="topbar-right">
                <span class="topbar-date">{{ now()->format('l, d M Y') }}</span>

                {{-- Book appointment button --}}
                <a href="{{ route('patient.appointments.find-doctors') }}" class="btn-primary"
                    style="padding:.45rem 1rem;font-size:.83rem">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:14px;height:14px">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    Book Appointment
                </a>

                {{-- Avatar --}}
                <div style="width:34px;height:34px;border-radius:50%;background:var(--green);color:#fff;
                            display:flex;align-items:center;justify-content:center;
                            font-weight:700;font-size:.75rem">
                    {{ strtoupper(substr($user->name, 0, 2)) }}
                </div>
            </div>
        </header>

        {{-- Page content --}}
        <div class="page-content">

            {{-- Error flash --}}
            @if(session('error'))
            <div style="position:fixed;top:1rem;right:1rem;z-index:9999;
                        background:#fef2f2;border:1px solid #fecaca;border-radius:12px;
                        padding:.85rem 1.1rem;font-size:.875rem;color:#b91c1c;
                        display:flex;align-items:center;gap:.65rem;
                        box-shadow:0 4px 12px rgba(0,0,0,.1);max-width:360px">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:16px;height:16px;flex-shrink:0">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ session('error') }}
            </div>
            @endif

            {{-- Success flash --}}
            @if(session('success'))
            <div style="position:fixed;top:1rem;right:1rem;z-index:9999;
                        background:#f0fdf4;border:1px solid #bbf7d0;border-radius:12px;
                        padding:.85rem 1.1rem;font-size:.875rem;color:#15803d;
                        display:flex;align-items:center;gap:.65rem;
                        box-shadow:0 4px 12px rgba(0,0,0,.1);max-width:360px">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:16px;height:16px;flex-shrink:0">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ session('success') }}
            </div>
            @endif

            @yield('content')
        </div>

    </div>

</body>

</html>