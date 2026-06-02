<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Patient Dashboard') — HealthCare</title>
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0
        }

        :root {
            --green: #16a34a;
            --green-light: #f0fdf4;
            --border: #e5e7eb;
            --bg: #f9fafb;
            --card: #ffffff;
            --text: #111827;
            --text-muted: #6b7280;
            --sidebar-w: 240px;
        }

        body {
            font-family: 'Segoe UI', system-ui, sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
        }

        /* ── Sidebar ── */
        .sidebar {
            width: var(--sidebar-w);
            background: var(--card);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            z-index: 100;
        }

        .sidebar-logo {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: .65rem;
        }

        .sidebar-logo-icon {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            background: var(--green);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .sidebar-logo-text {
            font-size: .95rem;
            font-weight: 700;
            color: var(--text);
        }

        .sidebar-logo-sub {
            font-size: .7rem;
            color: var(--text-muted);
        }

        /* Nav section label */
        .nav-label {
            font-size: .68rem;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: var(--text-muted);
            padding: 1rem 1.5rem .4rem;
        }

        /* Nav item */
        .nav-item {
            display: flex;
            align-items: center;
            gap: .65rem;
            padding: .6rem 1.25rem;
            margin: .15rem .75rem;
            border-radius: 10px;
            text-decoration: none;
            font-size: .875rem;
            font-weight: 500;
            color: var(--text-muted);
            transition: all .15s;
        }

        .nav-item:hover {
            background: var(--bg);
            color: var(--text);
        }

        .nav-item.active {
            background: var(--green-light);
            color: var(--green);
            font-weight: 600;
        }

        .nav-item svg {
            width: 17px;
            height: 17px;
            flex-shrink: 0;
        }

        /* Sidebar bottom user card */
        .sidebar-user {
            margin-top: auto;
            padding: 1rem 1.25rem;
            border-top: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: .75rem;
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: var(--green);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .8rem;
            font-weight: 700;
            flex-shrink: 0;
        }

        .user-name {
            font-size: .83rem;
            font-weight: 600;
            color: var(--text);
        }

        .user-role {
            font-size: .72rem;
            color: var(--text-muted);
        }

        /* ── Main content ── */
        .main {
            margin-left: var(--sidebar-w);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Top bar */
        .topbar {
            background: var(--card);
            border-bottom: 1px solid var(--border);
            padding: .85rem 1.75rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .topbar-title {
            font-size: 1rem;
            font-weight: 600;
            color: var(--text);
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: .75rem;
        }

        .btn-book {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            padding: .45rem 1rem;
            border-radius: 8px;
            background: var(--green);
            color: #fff;
            text-decoration: none;
            font-size: .83rem;
            font-weight: 600;
        }

        /* Content area */
        .content {
            padding: 1.75rem;
            flex: 1;
        }

        /* ── Shared component styles ── */
        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .page-header h1 {
            font-size: 1.4rem;
            font-weight: 700;
            margin: 0 0 .2rem;
        }

        .page-header p {
            font-size: .875rem;
            color: var(--text-muted);
            margin: 0;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .stat-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 1.1rem 1.25rem;
        }

        .stat-card-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: .75rem;
        }

        .stat-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .stat-icon svg {
            width: 18px;
            height: 18px;
            stroke: currentColor
        }

        .stat-icon.green {
            background: #f0fdf4;
            color: #16a34a
        }

        .stat-icon.blue {
            background: #eff6ff;
            color: #2563eb
        }

        .stat-icon.amber {
            background: #fffbeb;
            color: #d97706
        }

        .stat-icon.purple {
            background: #f5f3ff;
            color: #7c3aed
        }

        .stat-icon.red {
            background: #fef2f2;
            color: #dc2626
        }

        .stat-trend {
            font-size: .72rem;
            font-weight: 600;
            padding: .2rem .6rem;
            border-radius: 20px;
        }

        .stat-trend.up {
            background: #f0fdf4;
            color: #16a34a
        }

        .stat-trend.down {
            background: #fffbeb;
            color: #d97706
        }

        .stat-trend.red {
            background: #fef2f2;
            color: #dc2626
        }

        .stat-value {
            font-size: 1.75rem;
            font-weight: 700;
            line-height: 1
        }

        .stat-label {
            font-size: .8rem;
            color: var(--text-muted);
            margin-top: .3rem
        }

        .chart-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 1.25rem;
            margin-bottom: 1.25rem;
        }

        .card-title {
            font-size: .95rem;
            font-weight: 700;
            color: var(--text)
        }

        .card-subtitle {
            font-size: .78rem;
            color: var(--text-muted);
            margin-top: .15rem
        }

        .badge {
            display: inline-flex;
            align-items: center;
            padding: .25rem .7rem;
            border-radius: 20px;
            font-size: .75rem;
            font-weight: 600;
        }

        .badge-green {
            background: #f0fdf4;
            color: #15803d;
            border: 1px solid #bbf7d0
        }

        .badge-amber {
            background: #fffbeb;
            color: #92400e;
            border: 1px solid #fde68a
        }

        .badge-blue {
            background: #eff6ff;
            color: #1d4ed8;
            border: 1px solid #bfdbfe
        }

        .badge-red {
            background: #fef2f2;
            color: #b91c1c;
            border: 1px solid #fecaca
        }

        .badge-purple {
            background: #f5f3ff;
            color: #6d28d9;
            border: 1px solid #ddd6fe
        }

        .btn-primary {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            padding: .55rem 1.2rem;
            border-radius: 10px;
            background: var(--green);
            color: #fff;
            text-decoration: none;
            font-size: .875rem;
            font-weight: 600;
            border: none;
            cursor: pointer;
        }
    </style>
</head>

<body>

    @php $user = Auth::user(); @endphp

    {{-- ── Sidebar ── --}}
    <aside class="sidebar">

        {{-- Logo --}}
        <div class="sidebar-logo">
            <div class="sidebar-logo-icon">
                <svg fill="none" viewBox="0 0 24 24" stroke="#fff" stroke-width="2" style="width:18px;height:18px">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
            </div>
            <div>
                <div class="sidebar-logo-text">HealthCare</div>
                <div class="sidebar-logo-sub">Patient Portal</div>
            </div>
        </div>

        {{-- Nav --}}
        <nav style="flex:1;overflow-y:auto;padding:.5rem 0">

            <div class="nav-label">Main</div>

            <a href="{{ route('patient.dashboard') }}"
                class="nav-item {{ request()->routeIs('patient.dashboard') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Dashboard
            </a>

            <a href="{{route('patient.appointments.index') }}"
                class="nav-item {{ request()->routeIs('patient.appointments.index') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                My Appointments
                @if(isset($pendingBadge) && $pendingBadge > 0)
                <span style="margin-left:auto;background:#fde68a;color:#92400e;
                         font-size:.68rem;font-weight:700;padding:.15rem .5rem;border-radius:20px">
                    {{ $pendingBadge }}
                </span>
                @endif
            </a>

            <a href="{{ route('patient.appointments.find-doctors') }}"
                class="nav-item {{ request()->routeIs('patient.appointments.find-doctors') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                Find Doctors
            </a>

            <div class="nav-label">Account</div>

            <a href="{{ route('profile.edit') }}"
                class="nav-item {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                My Profile
            </a>
            <a href="{{ route('home') }}" class="nav-item">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                🌐 Public Site
            </a>

            {{-- Logout --}}
            <form method="POST" action="{{ route('logout') }}" style="margin:.15rem .75rem">
                @csrf
                <button type="submit" class="nav-item" style="width:100%;background:none;border:none;cursor:pointer;text-align:left">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    Logout
                </button>
            </form>

        </nav>

        {{-- User card --}}
        <div class="sidebar-user">
            <div class="user-avatar">{{ strtoupper(substr($user->name,0,2)) }}</div>
            <div style="min-width:0;flex:1">
                <div class="user-name" style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                    {{ $user->name }}
                </div>
                <div class="user-role">Patient</div>
            </div>
        </div>

    </aside>

    {{-- ── Main ── --}}
    <div class="main">

        {{-- Top bar --}}
        <header class="topbar">
            <div class="topbar-title">@yield('title', 'Dashboard')</div>
            <div class="topbar-right">
                <span style="font-size:.8rem;color:var(--text-muted)">{{ now()->format('d M Y') }}</span>
                <a href="{{ route('patient.appointments.find-doctors') }}" class="btn-book">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:14px;height:14px">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    Book Appointment
                </a>
            </div>
        </header>

        {{-- Page content --}}
        <main class="content">

            {{-- ← ADD THIS --}}
            @if(session('error'))
            <div style="position:fixed;top:1rem;right:1rem;z-index:9999;
                        background:#fef2f2;border:1px solid #fecaca;border-radius:12px;
                        padding:.85rem 1.1rem;font-size:.875rem;color:#b91c1c;
                        display:flex;align-items:center;gap:.65rem;
                        box-shadow:0 4px 12px rgba(0,0,0,0.1);max-width:360px">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:16px;height:16px;flex-shrink:0">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ session('error') }}
            </div>
            @endif

            @if(session('success'))
            <div style="position:fixed;top:1rem;right:1rem;z-index:9999;
                        background:#f0fdf4;border:1px solid #bbf7d0;border-radius:12px;
                        padding:.85rem 1.1rem;font-size:.875rem;color:#15803d;
                        display:flex;align-items:center;gap:.65rem;
                        box-shadow:0 4px 12px rgba(0,0,0,0.1);max-width:360px">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:16px;height:16px;flex-shrink:0">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ session('success') }}
            </div>
            @endif

            @yield('content')
        </main>

    </div>

</body>

</html>