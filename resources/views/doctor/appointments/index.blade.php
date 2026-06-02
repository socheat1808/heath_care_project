@extends('doctor.layout')
@section('title', 'Appointments')
@section('content')

@php
$user = Auth::user();
$doctor = \App\Models\Doctor::where('email', $user->email)->first();
@endphp

{{-- ── Page Header ── --}}
<div class="page-header">
    <div>
        <h1>Appointments</h1>
        <p>Manage and review all your scheduled appointments.</p>
    </div>
    <a href="{{ route('doctor.appointments.create') }}" class="btn-primary">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
        </svg>
        New Appointment
    </a>
</div>

{{-- ── Flash ── --}}
@if(session('success'))
<div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:12px;padding:.85rem 1.1rem;
            margin-bottom:1.25rem;font-size:.875rem;color:#15803d;display:flex;align-items:center;gap:.65rem">
    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:16px;height:16px;flex-shrink:0">
        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
    </svg>
    {{ session('success') }}
</div>
@endif

{{-- ── Stat Cards ── --}}
<div class="stats-grid">

    <div class="stat-card">
        <div class="stat-card-top">
            <div class="stat-icon green">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            <span class="stat-trend up">Today</span>
        </div>
        <div class="stat-value">{{ $todayCount }}</div>
        <div class="stat-label">Today's Appointments</div>
    </div>

    <div class="stat-card">
        <div class="stat-card-top">
            <div class="stat-icon amber">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <span class="stat-trend down">Pending</span>
        </div>
        <div class="stat-value">{{ $pendingCount }}</div>
        <div class="stat-label">Pending</div>
    </div>

    <div class="stat-card">
        <div class="stat-card-top">
            <div class="stat-icon purple">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <span class="stat-trend up">This month</span>
        </div>
        <div class="stat-value">{{ $completedCount }}</div>
        <div class="stat-label">Completed</div>
    </div>

    <div class="stat-card">
        <div class="stat-card-top">
            <div class="stat-icon blue">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </div>
            <span class="stat-trend down">This month</span>
        </div>
        <div class="stat-value">{{ $cancelledCount }}</div>
        <div class="stat-label">Cancelled</div>
    </div>

</div>

{{-- ── Filters + Table ── --}}
<div class="chart-card" style="margin-top:0">

    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem;margin-bottom:1.25rem">
        <div>
            <div class="card-title">All Appointments</div>
            <div class="card-subtitle">{{ $appointments->total() }} records found</div>
        </div>

        {{-- Filter form --}}
        <form method="GET" action="{{ route('doctor.appointments.index') }}"
            style="display:flex;gap:.65rem;flex-wrap:wrap;align-items:center">

            <div style="position:relative">
                <svg style="position:absolute;left:.7rem;top:50%;transform:translateY(-50%);
                            width:15px;height:15px;color:var(--text-muted)"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 105 11a6 6 0 0012 0z" />
                </svg>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Search patient…"
                    style="padding:.5rem .85rem .5rem 2.1rem;border:1px solid var(--border);border-radius:8px;
                           background:var(--bg);color:var(--text);font-size:.83rem;width:190px;outline:none">
            </div>

            <select name="status"
                style="padding:.5rem .85rem;border:1px solid var(--border);border-radius:8px;
                       background:var(--bg);color:var(--text);font-size:.83rem;outline:none">
                <option value="">All Statuses</option>
                <option value="pending" {{ request('status')==='pending'   ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ request('status')==='approved'  ? 'selected' : '' }}>Approved</option>
                <option value="completed" {{ request('status')==='completed' ? 'selected' : '' }}>Completed</option>
                <option value="cancelled" {{ request('status')==='cancelled' ? 'selected' : '' }}>Cancelled</option>
                <option value="rejected" {{ request('status')==='rejected'  ? 'selected' : '' }}>Rejected</option>
            </select>

            <input type="date" name="date" value="{{ request('date') }}"
                style="padding:.5rem .85rem;border:1px solid var(--border);border-radius:8px;
                       background:var(--bg);color:var(--text);font-size:.83rem;outline:none">

            <button type="submit" class="btn-primary" style="padding:.5rem 1.1rem;font-size:.83rem">
                Filter
            </button>

            @if(request()->hasAny(['search','status','date']))
            <a href="{{ route('doctor.appointments.index') }}"
                style="padding:.5rem 1rem;font-size:.83rem;border:1px solid var(--border);border-radius:8px;
                       color:var(--text-muted);text-decoration:none;background:var(--bg)">
                Clear
            </a>
            @endif
        </form>
    </div>

    {{-- Table --}}
    <div style="overflow-x:auto">
        <table style="width:100%;border-collapse:collapse;font-size:.875rem">
            <thead>
                <tr style="border-bottom:2px solid var(--border)">
                    <th style="text-align:left;padding:.65rem .75rem;color:var(--text-muted);font-weight:600;white-space:nowrap">#</th>
                    <th style="text-align:left;padding:.65rem .75rem;color:var(--text-muted);font-weight:600;white-space:nowrap">Patient</th>
                    <th style="text-align:left;padding:.65rem .75rem;color:var(--text-muted);font-weight:600;white-space:nowrap">Date</th>
                    <th style="text-align:left;padding:.65rem .75rem;color:var(--text-muted);font-weight:600;white-space:nowrap">Time</th>
                    <th style="text-align:left;padding:.65rem .75rem;color:var(--text-muted);font-weight:600;white-space:nowrap">Department</th>
                    <th style="text-align:left;padding:.65rem .75rem;color:var(--text-muted);font-weight:600;white-space:nowrap">Status</th>
                    <th style="text-align:left;padding:.65rem .75rem;color:var(--text-muted);font-weight:600;white-space:nowrap">Notes</th>
                    <th style="text-align:center;padding:.65rem .75rem;color:var(--text-muted);font-weight:600;white-space:nowrap">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($appointments as $i => $appt)
                <tr style="border-bottom:1px solid var(--border);transition:background .15s"
                    onmouseover="this.style.background='var(--bg)'"
                    onmouseout="this.style.background='transparent'">

                    {{-- Row number --}}
                    <td style="padding:.75rem;color:var(--text-muted);font-size:.8rem">
                        {{ $appointments->firstItem() + $i }}
                    </td>

                    {{-- Patient --}}
                    <td style="padding:.75rem">
                        <div style="display:flex;align-items:center;gap:.65rem">
                            <div style="width:34px;height:34px;border-radius:50%;background:var(--green);
                                        color:#fff;display:flex;align-items:center;justify-content:center;
                                        font-size:.75rem;font-weight:700;flex-shrink:0">
                                {{ strtoupper(substr($appt->patient->name ?? 'P', 0, 2)) }}
                            </div>
                            <div>
                                <div style="font-weight:600;color:var(--text)">{{ $appt->patient->name ?? '—' }}</div>
                                <div style="font-size:.76rem;color:var(--text-muted)">{{ $appt->patient->email ?? '' }}</div>
                            </div>
                        </div>
                    </td>

                    {{-- Date --}}
                    <td style="padding:.75rem;color:var(--text);white-space:nowrap">
                        {{ \Carbon\Carbon::parse($appt->appointment_date)->format('d M Y') }}
                    </td>

                    {{-- Time --}}
                    <td style="padding:.75rem;color:var(--text);white-space:nowrap;font-weight:600">
                        {{ \Carbon\Carbon::parse($appt->appointment_time)->format('H:i') }}
                    </td>

                    {{-- Department --}}
                    <td style="padding:.75rem;color:var(--text-muted)">
                        {{ $appt->department ?? 'General' }}
                    </td>

                    {{-- Status --}}
                    <td style="padding:.75rem">
                        @php
                        $badgeClass = match($appt->status) {
                        'approved' => 'badge-green',
                        'pending' => 'badge-amber',
                        'completed' => 'badge-blue',
                        'cancelled' => 'badge-red',
                        'rejected' => 'badge-red',
                        default => 'badge-amber',
                        };
                        @endphp
                        <span class="badge {{ $badgeClass }}">{{ ucfirst($appt->status) }}</span>
                    </td>

                    {{-- Notes --}}
                    <td style="padding:.75rem;color:var(--text-muted);max-width:160px">
                        <span style="display:block;overflow:hidden;text-overflow:ellipsis;
                                     white-space:nowrap;font-size:.8rem">
                            {{ $appt->notes ?? '—' }}
                        </span>
                    </td>

                    {{-- Actions --}}
                    <td style="padding:.75rem;text-align:center">
                        <div style="display:flex;gap:.45rem;justify-content:center;flex-wrap:wrap">

                            {{-- APPROVE (pending only) --}}
                            @if($appt->status === 'pending')
                            <form method="POST"
                                action="{{ route('doctor.appointments.approve', $appt->id) }}">
                                @csrf @method('PATCH')
                                <button type="submit" title="Approve"
                                    style="display:inline-flex;align-items:center;justify-content:center;
                                           width:30px;height:30px;border-radius:7px;background:#f0fdf4;
                                           border:1px solid #bbf7d0;color:#16a34a;cursor:pointer;transition:all .15s"
                                    onmouseover="this.style.background='#16a34a';this.style.color='#fff'"
                                    onmouseout="this.style.background='#f0fdf4';this.style.color='#16a34a'">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" style="width:13px;height:13px">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                    </svg>
                                </button>
                            </form>

                            {{-- REJECT (pending only) --}}
                            <button type="button" title="Reject"
                                onclick="openRejectModal({{ $appt->id }})"
                                style="display:inline-flex;align-items:center;justify-content:center;
                                       width:30px;height:30px;border-radius:7px;background:#fef2f2;
                                       border:1px solid #fecaca;color:#dc2626;cursor:pointer;transition:all .15s"
                                onmouseover="this.style.background='#dc2626';this.style.color='#fff'"
                                onmouseout="this.style.background='#fef2f2';this.style.color='#dc2626'">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" style="width:13px;height:13px">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                            @endif

                            {{-- COMPLETE (approved only) --}}
                            @if($appt->status === 'approved')
                            <form method="POST"
                                action="{{ route('doctor.appointments.complete', $appt->id) }}">
                                @csrf @method('PATCH')
                                <button type="submit" title="Mark Complete"
                                    style="display:inline-flex;align-items:center;justify-content:center;
                                           width:30px;height:30px;border-radius:7px;background:#eff6ff;
                                           border:1px solid #bfdbfe;color:#2563eb;cursor:pointer;transition:all .15s"
                                    onmouseover="this.style.background='#2563eb';this.style.color='#fff'"
                                    onmouseout="this.style.background='#eff6ff';this.style.color='#2563eb'">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:13px;height:13px">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </button>
                            </form>

                            {{-- CANCEL (approved only) --}}
                            <form method="POST"
                                action="{{ route('doctor.appointments.cancel', $appt->id) }}"
                                onsubmit="return confirm('Cancel this appointment?')">
                                @csrf @method('PATCH')
                                <button type="submit" title="Cancel"
                                    style="display:inline-flex;align-items:center;justify-content:center;
                                           width:30px;height:30px;border-radius:7px;background:#fff7ed;
                                           border:1px solid #fed7aa;color:#ea580c;cursor:pointer;transition:all .15s"
                                    onmouseover="this.style.background='#ea580c';this.style.color='#fff'"
                                    onmouseout="this.style.background='#fff7ed';this.style.color='#ea580c'">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:13px;height:13px">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                    </svg>
                                </button>
                            </form>
                            @endif

                            {{-- ADD NOTES --}}
                            @if(in_array($appt->status, ['approved','completed']))
                            <button type="button" title="Add Notes"
                                onclick="openNotesModal({{ $appt->id }}, '{{ addslashes($appt->notes ?? '') }}')"
                                style="display:inline-flex;align-items:center;justify-content:center;
                                       width:30px;height:30px;border-radius:7px;background:var(--bg);
                                       border:1px solid var(--border);color:var(--text-muted);cursor:pointer;transition:all .15s"
                                onmouseover="this.style.borderColor='var(--green)';this.style.color='var(--green)'"
                                onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--text-muted)'">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:13px;height:13px">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>
                            @endif

                        </div>
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="8" style="padding:3rem;text-align:center;color:var(--text-muted)">
                        <div style="font-size:40px;margin-bottom:.5rem">📅</div>
                        <div style="font-size:.9rem;font-weight:600;margin-bottom:.25rem">No appointments found</div>
                        <div style="font-size:.8rem">Try adjusting your filters or create a new appointment.</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($appointments->hasPages())
    <div style="display:flex;align-items:center;justify-content:space-between;margin-top:1.25rem;
                padding-top:1rem;border-top:1px solid var(--border);font-size:.83rem">
        <div style="color:var(--text-muted)">
            Showing {{ $appointments->firstItem() }}–{{ $appointments->lastItem() }} of {{ $appointments->total() }}
        </div>
        <div style="display:flex;gap:.35rem">
            @if($appointments->onFirstPage())
            <span style="padding:.4rem .75rem;border:1px solid var(--border);border-radius:7px;
                         color:var(--text-muted);opacity:.45">← Prev</span>
            @else
            <a href="{{ $appointments->previousPageUrl() }}&{{ http_build_query(request()->except('page')) }}"
                style="padding:.4rem .75rem;border:1px solid var(--border);border-radius:7px;
                       color:var(--text);text-decoration:none">← Prev</a>
            @endif

            @foreach($appointments->getUrlRange(max(1,$appointments->currentPage()-2), min($appointments->lastPage(),$appointments->currentPage()+2)) as $page => $url)
            <a href="{{ $url }}&{{ http_build_query(request()->except('page')) }}"
                style="padding:.4rem .75rem;border:1px solid {{ $page == $appointments->currentPage() ? 'var(--green)' : 'var(--border)' }};
                       border-radius:7px;color:{{ $page == $appointments->currentPage() ? 'var(--green)' : 'var(--text)' }};
                       font-weight:{{ $page == $appointments->currentPage() ? '700' : '400' }};
                       text-decoration:none">{{ $page }}</a>
            @endforeach

            @if($appointments->hasMorePages())
            <a href="{{ $appointments->nextPageUrl() }}&{{ http_build_query(request()->except('page')) }}"
                style="padding:.4rem .75rem;border:1px solid var(--border);border-radius:7px;
                       color:var(--text);text-decoration:none">Next →</a>
            @else
            <span style="padding:.4rem .75rem;border:1px solid var(--border);border-radius:7px;
                         color:var(--text-muted);opacity:.45">Next →</span>
            @endif
        </div>
    </div>
    @endif

</div>

{{-- ══════════════════════════════════════
     REJECT MODAL
══════════════════════════════════════ --}}
<div id="rejectModal"
    style="display:none;position:fixed;inset:0;z-index:9999;
           background:rgba(0,0,0,.5);align-items:center;justify-content:center">
    <div style="background:#fff;border-radius:16px;padding:1.75rem;width:100%;max-width:440px;
                box-shadow:0 20px 60px rgba(0,0,0,.2);margin:1rem">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem">
            <div style="font-size:1rem;font-weight:700;color:var(--text)">Reject Appointment</div>
            <button onclick="closeRejectModal()"
                style="background:none;border:none;cursor:pointer;color:var(--text-muted);font-size:1.25rem">
                ✕
            </button>
        </div>
        <form id="rejectForm" method="POST">
            @csrf @method('PATCH')
            <div style="margin-bottom:1.25rem">
                <label style="display:block;font-size:.83rem;font-weight:600;
                               color:var(--text-muted);margin-bottom:.45rem">
                    Reason for rejection <span style="color:#ef4444">*</span>
                </label>
                <textarea name="rejection_reason" rows="4" required
                    placeholder="Explain why this appointment is being rejected..."
                    style="width:100%;padding:.7rem .9rem;border:1px solid var(--border);border-radius:10px;
                           background:var(--bg);color:var(--text);font-size:.875rem;outline:none;
                           resize:vertical;font-family:inherit;box-sizing:border-box"
                    onfocus="this.style.borderColor='var(--red)'"
                    onblur="this.style.borderColor='var(--border)'"></textarea>
            </div>
            <div style="display:flex;gap:.75rem;justify-content:flex-end">
                <button type="button" onclick="closeRejectModal()"
                    style="padding:.6rem 1.4rem;border:1px solid var(--border);border-radius:10px;
                           color:var(--text-muted);background:var(--bg);font-size:.875rem;
                           font-weight:600;cursor:pointer">
                    Cancel
                </button>
                <button type="submit"
                    style="padding:.6rem 1.4rem;border-radius:10px;background:#dc2626;
                           color:#fff;font-size:.875rem;font-weight:600;border:none;cursor:pointer">
                    Reject Appointment
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ══════════════════════════════════════
     NOTES MODAL
══════════════════════════════════════ --}}
<div id="notesModal"
    style="display:none;position:fixed;inset:0;z-index:9999;
           background:rgba(0,0,0,.5);align-items:center;justify-content:center">
    <div style="background:#fff;border-radius:16px;padding:1.75rem;width:100%;max-width:440px;
                box-shadow:0 20px 60px rgba(0,0,0,.2);margin:1rem">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem">
            <div style="font-size:1rem;font-weight:700;color:var(--text)">Add / Edit Notes</div>
            <button onclick="closeNotesModal()"
                style="background:none;border:none;cursor:pointer;color:var(--text-muted);font-size:1.25rem">
                ✕
            </button>
        </div>
        <form id="notesForm" method="POST">
            @csrf @method('PATCH')
            <div style="margin-bottom:1.25rem">
                <label style="display:block;font-size:.83rem;font-weight:600;
                               color:var(--text-muted);margin-bottom:.45rem">
                    Medical Notes
                </label>
                <textarea id="notesText" name="notes" rows="5"
                    placeholder="Enter medical notes, observations, prescriptions..."
                    style="width:100%;padding:.7rem .9rem;border:1px solid var(--border);border-radius:10px;
                           background:var(--bg);color:var(--text);font-size:.875rem;outline:none;
                           resize:vertical;font-family:inherit;box-sizing:border-box"
                    onfocus="this.style.borderColor='var(--green)'"
                    onblur="this.style.borderColor='var(--border)'"></textarea>
            </div>
            <div style="display:flex;gap:.75rem;justify-content:flex-end">
                <button type="button" onclick="closeNotesModal()"
                    style="padding:.6rem 1.4rem;border:1px solid var(--border);border-radius:10px;
                           color:var(--text-muted);background:var(--bg);font-size:.875rem;
                           font-weight:600;cursor:pointer">
                    Cancel
                </button>
                <button type="submit" class="btn-primary" style="padding:.6rem 1.4rem;font-size:.875rem">
                    Save Notes
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ── JS ── --}}
<script>
    // Reject modal
    function openRejectModal(appointmentId) {
        const modal = document.getElementById('rejectModal');
        const form = document.getElementById('rejectForm');
        form.action = '/appointments/' + appointmentId + '/reject';
        modal.style.display = 'flex';
    }

    function closeRejectModal() {
        document.getElementById('rejectModal').style.display = 'none';
    }

    // Notes modal
    function openNotesModal(appointmentId, existingNotes) {
        const modal = document.getElementById('notesModal');
        const form = document.getElementById('notesForm');
        const text = document.getElementById('notesText');
        form.action = '/appointments/' + appointmentId + '/notes';
        text.value = existingNotes;
        modal.style.display = 'flex';
    }

    function closeNotesModal() {
        document.getElementById('notesModal').style.display = 'none';
    }

    // Close modals on backdrop click
    document.getElementById('rejectModal').addEventListener('click', function(e) {
        if (e.target === this) closeRejectModal();
    });
    document.getElementById('notesModal').addEventListener('click', function(e) {
        if (e.target === this) closeNotesModal();
    });
</script>

@endsection