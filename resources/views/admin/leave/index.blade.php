@extends('admin.layout')
@section('title', 'Leave Requests')
@section('content')

{{-- ── Page Header ── --}}
<div class="page-header">
    <div class="page-header-left">
        <h1>Leave Requests</h1>
        <p>Review and manage doctor leave requests.</p>
    </div>
</div>

{{-- ── Stat Cards ── --}}
<div class="stats-grid">

    <div class="stat-card c-amber">
        <div class="stat-card-top">
            <div class="stat-icon amber">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <span class="stat-trend down">Waiting</span>
        </div>
        <div class="stat-value">{{ $leaveRequests->where('status','pending')->count() }}</div>
        <div class="stat-label">Pending Requests</div>
    </div>

    <div class="stat-card c-green">
        <div class="stat-card-top">
            <div class="stat-icon green">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <span class="stat-trend up">Done</span>
        </div>
        <div class="stat-value">{{ $leaveRequests->where('status','approved')->count() }}</div>
        <div class="stat-label">Approved</div>
    </div>

    <div class="stat-card c-red">
        <div class="stat-card-top">
            <div class="stat-icon red" style="background:rgba(220,38,38,.1)">
                <svg fill="none" viewBox="0 0 24 24" stroke="#dc2626" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M6 18L18 6M6 6l12 12" />
                </svg>
            </div>
            <span class="stat-trend down">Declined</span>
        </div>
        <div class="stat-value">{{ $leaveRequests->where('status','rejected')->count() }}</div>
        <div class="stat-label">Rejected</div>
    </div>

    <div class="stat-card c-blue">
        <div class="stat-card-top">
            <div class="stat-icon blue">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
            <span class="stat-trend up">Total</span>
        </div>
        <div class="stat-value">{{ $leaveRequests->total() }}</div>
        <div class="stat-label">Total Requests</div>
    </div>

</div>

{{-- ── Leave Requests Table ── --}}
<div class="table-card">
    <div class="table-header">
        <div>
            <div class="table-title">All Leave Requests</div>
            <div class="table-subtitle">{{ $leaveRequests->total() }} requests found</div>
        </div>
    </div>

    @forelse($leaveRequests as $leave)
    @php
    $badgeClass = match($leave->status) {
    'approved' => 'badge-green',
    'pending' => 'badge-amber',
    'rejected' => 'badge-red',
    default => 'badge-amber',
    };
    $days = $leave->from_date->diffInDays($leave->to_date) + 1;
    @endphp

    <div style="border:1px solid var(--border);border-radius:12px;padding:1rem 1.25rem;
                margin-bottom:.85rem;transition:border-color .15s"
        onmouseover="this.style.borderColor='var(--green)'"
        onmouseout="this.style.borderColor='var(--border)'">

        <div style="display:flex;align-items:flex-start;justify-content:space-between;
                    gap:.75rem;flex-wrap:wrap">

            {{-- Doctor info --}}
            <div style="display:flex;align-items:center;gap:.85rem">
                <div style="width:42px;height:42px;border-radius:50%;background:var(--green);color:#fff;
                            display:flex;align-items:center;justify-content:center;
                            font-size:.85rem;font-weight:700;flex-shrink:0">
                    {{ strtoupper(substr($leave->doctor->first_name ?? 'D',0,1).substr($leave->doctor->last_name ?? 'R',0,1)) }}
                </div>
                <div>
                    <div style="font-size:.95rem;font-weight:700;color:var(--text)">
                        Dr. {{ $leave->doctor->first_name ?? '' }} {{ $leave->doctor->last_name ?? '—' }}
                    </div>
                    <div style="font-size:.78rem;color:var(--text-muted)">
                        {{ $leave->doctor->specialization ?? 'General' }}
                    </div>
                </div>
            </div>

            <span class="badge {{ $badgeClass }}">{{ ucfirst($leave->status) }}</span>
        </div>

        {{-- Leave details --}}
        <div style="display:flex;gap:1.5rem;flex-wrap:wrap;margin-top:.85rem;
                    padding-top:.85rem;border-top:1px solid var(--border)">

            <div style="display:flex;align-items:center;gap:.4rem;font-size:.83rem;color:var(--text-muted)">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:14px;height:14px">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span style="color:var(--text);font-weight:600">
                    {{ $leave->from_date->format('d M Y') }}
                    @if($leave->from_date != $leave->to_date)
                    → {{ $leave->to_date->format('d M Y') }}
                    @endif
                </span>
            </div>

            <div style="display:flex;align-items:center;gap:.4rem;font-size:.83rem;color:var(--text-muted)">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:14px;height:14px">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ $days }} day{{ $days > 1 ? 's' : '' }}
            </div>

            <div style="font-size:.83rem;color:var(--text-muted)">
                <span style="font-weight:600;color:var(--text)">Reason:</span>
                {{ $leave->reason }}
            </div>

            <div style="font-size:.75rem;color:var(--text-muted);margin-left:auto">
                Submitted {{ $leave->created_at->diffForHumans() }}
            </div>
        </div>

        {{-- Rejection reason --}}
        @if($leave->status === 'rejected' && $leave->rejection_reason)
        <div style="margin-top:.75rem;padding:.65rem 1rem;background:#fef2f2;
                    border:1px solid #fecaca;border-radius:8px;font-size:.82rem;color:#b91c1c">
            <span style="font-weight:600">Rejection reason:</span> {{ $leave->rejection_reason }}
        </div>
        @endif

        {{-- Action buttons for pending --}}
        @if($leave->status === 'pending')
        <div style="margin-top:.85rem;display:flex;gap:.65rem;justify-content:flex-end">

            {{-- Approve --}}
            <form method="POST" action="{{ route('admin.leave.approve', $leave->id) }}">
                @csrf @method('PATCH')
                <button type="submit"
                    onclick="return confirm('Approve leave for Dr. {{ $leave->doctor->first_name }}? Their status will be set to On Leave.')"
                    style="display:inline-flex;align-items:center;gap:.45rem;padding:.5rem 1.1rem;
                           border-radius:8px;background:#f0fdf4;border:1px solid #bbf7d0;
                           color:#16a34a;font-size:.83rem;font-weight:600;cursor:pointer;
                           transition:all .15s"
                    onmouseover="this.style.background='#16a34a';this.style.color='#fff'"
                    onmouseout="this.style.background='#f0fdf4';this.style.color='#16a34a'">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" style="width:13px;height:13px">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                    Approve
                </button>
            </form>

            {{-- Reject --}}
            <button type="button"
                onclick="openRejectModal({{ $leave->id }})"
                style="display:inline-flex;align-items:center;gap:.45rem;padding:.5rem 1.1rem;
                       border-radius:8px;background:#fef2f2;border:1px solid #fecaca;
                       color:#dc2626;font-size:.83rem;font-weight:600;cursor:pointer;
                       transition:all .15s"
                onmouseover="this.style.background='#dc2626';this.style.color='#fff'"
                onmouseout="this.style.background='#fef2f2';this.style.color='#dc2626'">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" style="width:13px;height:13px">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
                Reject
            </button>
        </div>
        @endif

    </div>
    @empty
    <div style="text-align:center;padding:4rem;color:#6b7280">
        <div style="font-size:48px;margin-bottom:.75rem">🏖️</div>
        <div style="font-size:1rem;font-weight:600;color:#111827;margin-bottom:.35rem">
            No leave requests
        </div>
        <div style="font-size:.875rem">
            Doctors haven't submitted any leave requests yet.
        </div>
    </div>
    @endforelse

    {{-- Pagination --}}
    @if($leaveRequests->hasPages())
    <div style="display:flex;align-items:center;justify-content:space-between;
                margin-top:1.25rem;padding-top:1rem;border-top:1px solid var(--border);font-size:.83rem">
        <div style="color:#6b7280">
            Showing {{ $leaveRequests->firstItem() }}–{{ $leaveRequests->lastItem() }}
            of {{ $leaveRequests->total() }}
        </div>
        <div style="display:flex;gap:.35rem">
            @if(!$leaveRequests->onFirstPage())
            <a href="{{ $leaveRequests->previousPageUrl() }}"
                style="padding:.4rem .75rem;border:1px solid var(--border);border-radius:7px;
                       color:#111827;text-decoration:none">← Prev</a>
            @endif
            @if($leaveRequests->hasMorePages())
            <a href="{{ $leaveRequests->nextPageUrl() }}"
                style="padding:.4rem .75rem;border:1px solid var(--border);border-radius:7px;
                       color:#111827;text-decoration:none">Next →</a>
            @endif
        </div>
    </div>
    @endif
</div>

{{-- ── Reject Modal ── --}}
<div id="rejectModal"
    style="display:none;position:fixed;inset:0;z-index:9999;
           background:rgba(0,0,0,.5);align-items:center;justify-content:center">
    <div style="background:#fff;border-radius:16px;padding:1.75rem;width:100%;max-width:440px;
                box-shadow:0 20px 60px rgba(0,0,0,.2);margin:1rem">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem">
            <div style="font-size:1rem;font-weight:700;color:#111827">Reject Leave Request</div>
            <button onclick="closeRejectModal()"
                style="background:none;border:none;cursor:pointer;color:#6b7280;font-size:1.25rem">✕</button>
        </div>
        <form id="rejectForm" method="POST">
            @csrf @method('PATCH')
            <div style="margin-bottom:1.25rem">
                <label style="display:block;font-size:.83rem;font-weight:600;
                               color:#6b7280;margin-bottom:.45rem">
                    Reason for rejection <span style="color:#ef4444">*</span>
                </label>
                <textarea name="rejection_reason" rows="4" required
                    placeholder="Explain why this leave request is being rejected..."
                    style="width:100%;padding:.7rem .9rem;border:1px solid #e5e7eb;border-radius:10px;
                           background:#f9fafb;color:#111827;font-size:.875rem;outline:none;
                           resize:vertical;font-family:inherit;box-sizing:border-box"></textarea>
            </div>
            <div style="display:flex;gap:.75rem;justify-content:flex-end">
                <button type="button" onclick="closeRejectModal()"
                    style="padding:.6rem 1.4rem;border:1px solid #e5e7eb;border-radius:10px;
                           color:#6b7280;background:#f9fafb;font-size:.875rem;font-weight:600;cursor:pointer">
                    Cancel
                </button>
                <button type="submit"
                    style="padding:.6rem 1.4rem;border-radius:10px;background:#dc2626;
                           color:#fff;font-size:.875rem;font-weight:600;border:none;cursor:pointer">
                    Reject Request
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openRejectModal(leaveId) {
        const modal = document.getElementById('rejectModal');
        const form = document.getElementById('rejectForm');
        form.action = '/admin/leave-requests/' + leaveId + '/reject';
        modal.style.display = 'flex';
    }

    function closeRejectModal() {
        document.getElementById('rejectModal').style.display = 'none';
    }
    document.getElementById('rejectModal').addEventListener('click', function(e) {
        if (e.target === this) closeRejectModal();
    });
</script>

@endsection