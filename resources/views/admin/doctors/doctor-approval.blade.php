@extends('admin.layout')
@section('title', 'Doctor Approvals')
@section('content')

@php use App\Models\Doctor; @endphp

<div class="page-content active" id="page-doctor-approvals">

    <div class="page-header">
        <div class="page-header-left">
            <h1>Doctor Approvals</h1>
            <p>Review and manage new doctor registration requests.</p>
        </div>
    </div>

    {{-- Flash --}}
    @if(session('success'))
    <div class="alert-banner alert-green" style="margin-bottom:1.25rem">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:18px;height:18px;flex-shrink:0">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
        </svg>
        {{ session('success') }}
    </div>
    @endif

    {{-- Stats --}}
    <div class="stats-grid" style="grid-template-columns:repeat(3,1fr);margin-bottom:1.5rem">
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
                <div class="stat-label">Pending Review</div>
            </div>
        </div>
        <div class="stat-card c-green">
            <div class="stat-card-top">
                <div class="stat-icon green">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div>
                <div class="stat-value">{{ $approved->count() }}</div>
                <div class="stat-label">Approved Doctors</div>
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

    {{-- Tabs Card --}}
    <div class="chart-card" style="padding:0;overflow:hidden">

        <div class="approval-tabs">
            <button class="approval-tab active" data-tab="pending">
                Pending
                @if($pending->count())
                <span class="tab-badge">{{ $pending->count() }}</span>
                @endif
            </button>
            <button class="approval-tab" data-tab="approved">Approved</button>
            <button class="approval-tab" data-tab="rejected">Rejected</button>
        </div>

        {{-- ── PENDING ── --}}
        <div class="approval-panel active" id="tab-pending">
            @forelse($pending as $doctor)
            @php $docProfile = Doctor::where('email', $doctor->email)->first(); @endphp
            <div class="doctor-row">
                <div class="avatar av-amber" style="width:46px;height:46px;font-size:.85rem;flex-shrink:0">
                    {{ strtoupper(substr($doctor->name, 0, 2)) }}
                </div>
                <div class="doctor-info">
                    <div class="doctor-name">{{ $doctor->name }}</div>
                    <div class="doctor-meta">
                        <span>{{ $doctor->email }}</span>
                        @if($docProfile && $docProfile->specialization)
                        <span class="dot-sep">·</span>
                        <span>{{ $docProfile->specialization }}</span>
                        @endif
                        @if($doctor->phone)
                        <span class="dot-sep">·</span>
                        <span>{{ $doctor->phone }}</span>
                        @endif
                        <span class="dot-sep">·</span>
                        <span>Registered {{ $doctor->created_at->diffForHumans() }}</span>
                    </div>
                </div>
                <div class="doctor-actions">
                    <form method="POST" action="{{ route('admin.doctors.doctor-approvals.approve', $doctor) }}">
                        @csrf
                        <button type="submit" class="btn-action btn-approve">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2" style="width:14px;height:14px">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                            Approve
                        </button>
                    </form>
                    <form method="POST" action="{{ route('admin.doctors.doctor-approvals.reject', $doctor) }}">
                        @csrf
                        <button type="submit" class="btn-action btn-reject"
                            onclick="return confirm('Reject Dr. {{ addslashes($doctor->name) }}?')">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2" style="width:14px;height:14px">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Reject
                        </button>
                    </form>
                </div>
            </div>
            @empty
            <div class="empty-state">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p>No pending requests — you're all caught up!</p>
            </div>
            @endforelse
        </div>

        {{-- ── APPROVED ── --}}
        <div class="approval-panel" id="tab-approved">
            @forelse($approved as $doctor)
            @php $docProfile = Doctor::where('email', $doctor->email)->first(); @endphp
            <div class="doctor-row">
                <div class="avatar av-green" style="width:46px;height:46px;font-size:.85rem;flex-shrink:0">
                    {{ strtoupper(substr($doctor->name, 0, 2)) }}
                </div>
                <div class="doctor-info">
                    <div class="doctor-name">{{ $doctor->name }}</div>
                    <div class="doctor-meta">
                        <span>{{ $doctor->email }}</span>
                        @if($docProfile && $docProfile->specialization)
                        <span class="dot-sep">·</span>
                        <span>{{ $docProfile->specialization }}</span>
                        @endif
                        @if($doctor->phone)
                        <span class="dot-sep">·</span>
                        <span>{{ $doctor->phone }}</span>
                        @endif
                        <span class="dot-sep">·</span>
                        <span>Approved {{ $doctor->updated_at->diffForHumans() }}</span>
                    </div>
                </div>
                <div class="doctor-actions">
                    {{-- Edit in admin doctors --}}
                    @if($docProfile)
                    <a href="{{ route('admin.doctors.edit', $docProfile->DoctorID) }}"
                        class="btn-action" style="background:rgba(99,102,241,.1);color:#6366f1;text-decoration:none;">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:13px;height:13px">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit
                    </a>
                    @endif
                    {{-- Revoke --}}
                    <form method="POST" action="{{ route('admin.doctors.doctor-approvals.reject', $doctor) }}">
                        @csrf
                        <button type="submit" class="btn-action btn-reject"
                            onclick="return confirm('Revoke Dr. {{ addslashes($doctor->name) }}\'s approval?')">
                            Revoke
                        </button>
                    </form>
                    <span class="badge badge-green">Approved</span>
                </div>
            </div>
            @empty
            <div class="empty-state">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <p>No approved doctors yet.</p>
            </div>
            @endforelse
        </div>

        {{-- ── REJECTED ── --}}
        <div class="approval-panel" id="tab-rejected">
            @forelse($rejected as $doctor)
            @php $docProfile = Doctor::where('email', $doctor->email)->first(); @endphp
            <div class="doctor-row">
                <div class="avatar av-pink" style="width:46px;height:46px;font-size:.85rem;flex-shrink:0">
                    {{ strtoupper(substr($doctor->name, 0, 2)) }}
                </div>
                <div class="doctor-info">
                    <div class="doctor-name">{{ $doctor->name }}</div>
                    <div class="doctor-meta">
                        <span>{{ $doctor->email }}</span>
                        @if($docProfile && $docProfile->specialization)
                        <span class="dot-sep">·</span>
                        <span>{{ $docProfile->specialization }}</span>
                        @endif
                        @if($doctor->phone)
                        <span class="dot-sep">·</span>
                        <span>{{ $doctor->phone }}</span>
                        @endif
                        <span class="dot-sep">·</span>
                        <span>Rejected {{ $doctor->updated_at->diffForHumans() }}</span>
                    </div>
                </div>
                <div class="doctor-actions">
                    <form method="POST" action="{{ route('admin.doctors.doctor-approvals.approve', $doctor) }}">
                        @csrf
                        <button type="submit" class="btn-action btn-approve">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2" style="width:13px;height:13px">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                            Re-approve
                        </button>
                    </form>
                    <span class="badge badge-red">Rejected</span>
                </div>
            </div>
            @empty
            <div class="empty-state">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636" />
                </svg>
                <p>No rejected doctors.</p>
            </div>
            @endforelse
        </div>

    </div>{{-- /chart-card --}}

</div>

<style>
    .alert-banner {
        display: flex;
        align-items: center;
        gap: .6rem;
        padding: .75rem 1.25rem;
        border-radius: 10px;
        font-size: .875rem;
        font-weight: 500
    }

    .alert-green {
        background: rgba(26, 138, 110, .1);
        color: var(--green);
        border: 1px solid rgba(26, 138, 110, .2)
    }

    .approval-tabs {
        display: flex;
        border-bottom: 1px solid var(--border);
        padding: 0 1.5rem;
        gap: .25rem
    }

    .approval-tab {
        display: flex;
        align-items: center;
        gap: .45rem;
        padding: .875rem 1rem;
        background: none;
        border: none;
        border-bottom: 2px solid transparent;
        color: var(--text-muted);
        font-size: .875rem;
        font-weight: 500;
        cursor: pointer;
        transition: color .2s, border-color .2s;
        margin-bottom: -1px;
        font-family: inherit;
    }

    .approval-tab:hover {
        color: var(--text)
    }

    .approval-tab.active {
        color: var(--green);
        border-bottom-color: var(--green)
    }

    .tab-badge {
        background: var(--amber);
        color: #fff;
        font-size: .68rem;
        font-weight: 700;
        padding: .1rem .42rem;
        border-radius: 99px;
        line-height: 1.4
    }

    .approval-panel {
        display: none
    }

    .approval-panel.active {
        display: block
    }

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

    .doctor-actions {
        display: flex;
        gap: .5rem;
        align-items: center;
        flex-shrink: 0
    }

    .btn-action {
        display: inline-flex;
        align-items: center;
        gap: .35rem;
        padding: .45rem .95rem;
        border-radius: 8px;
        font-size: .8125rem;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: opacity .15s, transform .1s;
        font-family: inherit
    }

    .btn-action:hover {
        opacity: .82;
        transform: translateY(-1px)
    }

    .btn-approve {
        background: rgba(26, 138, 110, .12);
        color: var(--green)
    }

    .btn-reject {
        background: rgba(220, 38, 38, .08);
        color: var(--red)
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
        opacity: .3
    }
</style>

<script>
    document.querySelectorAll('.approval-tab').forEach(tab => {
        tab.addEventListener('click', () => {
            document.querySelectorAll('.approval-tab').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.approval-panel').forEach(p => p.classList.remove('active'));
            tab.classList.add('active');
            document.getElementById('tab-' + tab.dataset.tab).classList.add('active');
        });
    });
</script>

@endsection