@extends('admin.layout')
@section('title', 'Doctors')
@section('content')

<div class="page-content active" id="page-doctors">

    <div class="page-header">
        <div class="page-header-left">
            <h1>Doctors</h1>
            <p>All approved doctors currently active on One-Health.</p>
        </div>
    </div>

    <!-- STAT STRIP -->
    <div class="stats-grid" style="grid-template-columns:repeat(3,1fr);margin-bottom:1.5rem">
        <div class="stat-card c-green">
            <div class="stat-card-top">
                <div class="stat-icon green">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
            </div>
            <div>
                <div class="stat-value">{{ $approved->count() }}</div>
                <div class="stat-label">Active Doctors</div>
            </div>
        </div>
        <div class="stat-card c-amber">
            <div class="stat-card-top">
                <div class="stat-icon amber">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div>
                <div class="stat-value">{{ $pending->count() }}</div>
                <div class="stat-label">Pending Approval</div>
            </div>
        </div>
        <div class="stat-card c-purple">
            <div class="stat-card-top">
                <div class="stat-icon purple">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div>
                <div class="stat-value">{{ $rejected->count() }}</div>
                <div class="stat-label">Rejected</div>
            </div>
        </div>
    </div>

    <!-- TABLE CARD -->
    <div class="chart-card" style="padding:0;overflow:hidden">

        <!-- Search + filter bar -->
        <div style="display:flex;align-items:center;gap:1rem;padding:1.25rem 1.5rem;border-bottom:1px solid var(--border)">
            <div class="topbar-search" style="flex:1;max-width:320px">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="text" id="doctorSearch" placeholder="Search doctors…" oninput="filterDoctors(this.value)">
            </div>
            <a href="{{ route('admin.doctors.doctor-approvals') }}" class="btn btn-primary" style="font-size:.85rem;padding:.55rem 1.25rem;display:flex;align-items:center;gap:.4rem;text-decoration:none">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:15px;height:15px">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Manage Approvals
                @if($pending->count() > 0)
                <span class="nav-badge">{{ $pending->count() }}</span>
                @endif
            </a>
        </div>

        <!-- Doctor list -->
        <div id="doctorList">
            @forelse($approved as $doctor)
            <div class="doctor-row" data-name="{{ strtolower($doctor->name) }}" data-spec="{{ strtolower($doctor->specialization ?? '') }}">
                <div class="avatar av-green" style="width:44px;height:44px;font-size:.85rem;flex-shrink:0">
                    {{ strtoupper(substr($doctor->name, 0, 2)) }}
                </div>
                <div class="doctor-info">
                    <div class="doctor-name">{{ $doctor->name }}</div>
                    <div class="doctor-meta">
                        <span>{{ $doctor->email }}</span>
                        @if($doctor->specialization ?? false)
                        <span class="dot-sep">·</span>
                        <span>{{ $doctor->specialization }}</span>
                        @endif
                        @if($doctor->phone ?? false)
                        <span class="dot-sep">·</span>
                        <span>{{ $doctor->phone }}</span>
                        @endif
                    </div>
                </div>
                <span class="badge badge-green">Active</span>
            </div>
            @empty
            <div class="empty-state">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <p>No approved doctors yet. <a href="{{ route('admin.doctor-approvals') }}" style="color:var(--green)">Review pending requests →</a></p>
            </div>
            @endforelse
        </div>

    </div>

</div>

<style>
    .doctor-row {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem 1.5rem;
        border-bottom: 1px solid var(--border);
        transition: background .15s
    }

    .doctor-row:last-child {
        border-bottom: none
    }

    .doctor-row:hover {
        background: rgba(0, 0, 0, .02)
    }

    .doctor-info {
        flex: 1;
        min-width: 0
    }

    .doctor-name {
        font-size: .9375rem;
        font-weight: 600;
        color: var(--text)
    }

    .doctor-meta {
        font-size: .8rem;
        color: var(--text-muted);
        margin-top: .15rem;
        display: flex;
        flex-wrap: wrap;
        gap: .2rem;
        align-items: center
    }

    .dot-sep {
        opacity: .4
    }

    .empty-state {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: .75rem;
        padding: 3rem 1.5rem;
        color: var(--text-muted);
        font-size: .875rem
    }

    .empty-state svg {
        width: 48px;
        height: 48px;
        opacity: .35
    }
</style>

<script>
    function filterDoctors(q) {
        q = q.toLowerCase();
        document.querySelectorAll('#doctorList .doctor-row').forEach(row => {
            const match = row.dataset.name.includes(q) || row.dataset.spec.includes(q);
            row.style.display = match ? '' : 'none';
        });
    }
</script>

@endsection