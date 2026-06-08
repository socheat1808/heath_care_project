@extends('doctor.layout')
@section('title', 'Leave Requests')
@section('content')

{{-- TEMP DEBUG --}}
<div style="background:#fffbeb;border:1px solid #fde68a;border-radius:10px;
            padding:1rem;margin-bottom:1rem;font-size:.83rem;color:#92400e">
    <strong>🔍 Debug Info:</strong><br>
    Logged in email: {{ Auth::user()->email }}<br>
    Doctor ID: {{ $doctor->DoctorID }}<br>
    Doctor Name: {{ $doctor->first_name }} {{ $doctor->last_name }}<br>
    Total leave requests: {{ $leaveRequests->count() }}<br>
    Leave doctor_ids: {{ $leaveRequests->pluck('doctor_id')->implode(', ') }}
</div>

@php
$user = Auth::user();
@endphp

{{-- ── Page Header ── --}}
<div class="page-header">
    <div>
        <h1>Leave Requests</h1>
        <p>Request time off and manage your leave history.</p>
    </div>
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

@if(session('error'))
<div style="background:#fef2f2;border:1px solid #fecaca;border-radius:12px;padding:.85rem 1.1rem;
            margin-bottom:1.25rem;font-size:.875rem;color:#b91c1c;display:flex;align-items:center;gap:.65rem">
    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:16px;height:16px;flex-shrink:0">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
    </svg>
    {{ session('error') }}
</div>
@endif

<div style="display:grid;grid-template-columns:1fr 360px;gap:1.25rem;align-items:start">

    {{-- ── Leave History ── --}}
    <div class="chart-card" style="margin-bottom:0">
        <div style="margin-bottom:1.25rem">
            <div class="card-title">My Leave Requests</div>
            <div class="card-subtitle">{{ $leaveRequests->count() }} requests total</div>
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

            <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:.75rem;flex-wrap:wrap">
                <div>
                    <div style="font-size:.9rem;font-weight:700;color:var(--text);margin-bottom:.25rem">
                        {{ $leave->from_date->format('d M Y') }}
                        @if($leave->from_date != $leave->to_date)
                        → {{ $leave->to_date->format('d M Y') }}
                        @endif
                        <span style="font-size:.78rem;font-weight:500;color:var(--text-muted);margin-left:.35rem">
                            ({{ $days }} day{{ $days > 1 ? 's' : '' }})
                        </span>
                    </div>
                    <div style="font-size:.83rem;color:var(--text-muted)">
                        {{ $leave->reason }}
                    </div>
                </div>
                <span class="badge {{ $badgeClass }}">{{ ucfirst($leave->status) }}</span>
            </div>

            {{-- Rejection reason --}}
            @if($leave->status === 'rejected' && $leave->rejection_reason)
            <div style="margin-top:.75rem;padding:.65rem 1rem;background:#fef2f2;
                        border:1px solid #fecaca;border-radius:8px;font-size:.82rem;color:#b91c1c">
                <span style="font-weight:600">Rejection reason:</span> {{ $leave->rejection_reason }}
            </div>
            @endif

            {{-- Cancel button for pending --}}
            @if($leave->status === 'pending')
            <div style="margin-top:.75rem;display:flex;justify-content:flex-end">
                <form method="POST" action="{{ route('doctor.leave.cancel', $leave->id) }}"
                    onsubmit="return confirm('Cancel this leave request?')">
                    @csrf @method('DELETE')
                    <button type="submit"
                        style="padding:.4rem 1rem;border:1px solid #fecaca;border-radius:8px;
                               background:#fef2f2;color:#b91c1c;font-size:.8rem;font-weight:600;
                               cursor:pointer">
                        Cancel Request
                    </button>
                </form>
            </div>
            @endif

            {{-- Approved info --}}
            @if($leave->status === 'approved')
            <div style="margin-top:.75rem;padding:.65rem 1rem;background:#f0fdf4;
                        border:1px solid #bbf7d0;border-radius:8px;font-size:.82rem;color:#15803d">
                <span style="font-weight:600">✅ Approved</span> — Your status has been set to On Leave.
            </div>
            @endif

            <div style="margin-top:.65rem;font-size:.75rem;color:var(--text-muted)">
                Submitted {{ $leave->created_at->diffForHumans() }}
            </div>
        </div>

        @empty
        <div style="text-align:center;padding:3rem;color:var(--text-muted)">
            <div style="font-size:42px;margin-bottom:.75rem">🏖️</div>
            <div style="font-size:.95rem;font-weight:600;color:var(--text);margin-bottom:.35rem">
                No leave requests yet
            </div>
            <div style="font-size:.875rem">
                Submit a leave request using the form.
            </div>
        </div>
        @endforelse
    </div>

    {{-- ── Request Form ── --}}
    <div style="display:flex;flex-direction:column;gap:1rem">

        {{-- Current status card --}}
        <div class="chart-card" style="margin-bottom:0">
            <div class="card-title" style="margin-bottom:1rem">Current Status</div>
            <div style="display:flex;align-items:center;gap:.75rem;padding:.85rem;
                        background:var(--bg);border-radius:10px">
                <div style="width:40px;height:40px;border-radius:50%;background:var(--green);color:#fff;
                            display:flex;align-items:center;justify-content:center;font-size:.9rem;font-weight:700">
                    {{ strtoupper(substr($doctor->first_name,0,1).substr($doctor->last_name,0,1)) }}
                </div>
                <div>
                    <div style="font-size:.875rem;font-weight:700;color:var(--text)">
                        Dr. {{ $doctor->first_name }} {{ $doctor->last_name }}
                    </div>
                    @if($doctor->status === 'available')
                    <span class="badge badge-green">● Available</span>
                    @elseif($doctor->status === 'onleave')
                    <span class="badge badge-amber">● On Leave</span>
                    @else
                    <span class="badge badge-red">● Unavailable</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- New request form --}}
        <div class="chart-card" style="margin-bottom:0">
            <div class="card-title" style="margin-bottom:.35rem">New Leave Request</div>
            <div class="card-subtitle" style="margin-bottom:1.25rem">
                Submit a request for time off
            </div>

            @php
            $hasPending = $leaveRequests->where('status', 'pending')->count() > 0;
            @endphp

            @if($hasPending)
            <div style="background:#fffbeb;border:1px solid #fde68a;border-radius:10px;
                        padding:.85rem 1rem;font-size:.83rem;color:#92400e;margin-bottom:1rem">
                ⚠️ You already have a pending leave request. Cancel it first to submit a new one.
            </div>
            @endif

            <form method="POST" action="{{ route('doctor.leave.store') }}">
                @csrf

                {{-- From date --}}
                <div style="margin-bottom:1rem">
                    <label style="display:block;font-size:.83rem;font-weight:600;
                                  color:var(--text-muted);margin-bottom:.45rem">
                        From Date <span style="color:#ef4444">*</span>
                    </label>
                    <input type="date" name="from_date"
                        value="{{ old('from_date') }}"
                        min="{{ now()->addDay()->toDateString() }}"
                        required
                        {{ $hasPending ? 'disabled' : '' }}
                        style="width:100%;padding:.6rem .9rem;border:1px solid var(--border);
                               border-radius:10px;background:var(--bg);color:var(--text);
                               font-size:.875rem;outline:none;box-sizing:border-box"
                        onfocus="this.style.borderColor='var(--green)'"
                        onblur="this.style.borderColor='var(--border)'">
                    @error('from_date')
                    <div style="font-size:.76rem;color:#ef4444;margin-top:.3rem">{{ $message }}</div>
                    @enderror
                </div>

                {{-- To date --}}
                <div style="margin-bottom:1rem">
                    <label style="display:block;font-size:.83rem;font-weight:600;
                                  color:var(--text-muted);margin-bottom:.45rem">
                        To Date <span style="color:#ef4444">*</span>
                    </label>
                    <input type="date" name="to_date"
                        value="{{ old('to_date') }}"
                        min="{{ now()->addDay()->toDateString() }}"
                        required
                        {{ $hasPending ? 'disabled' : '' }}
                        style="width:100%;padding:.6rem .9rem;border:1px solid var(--border);
                               border-radius:10px;background:var(--bg);color:var(--text);
                               font-size:.875rem;outline:none;box-sizing:border-box"
                        onfocus="this.style.borderColor='var(--green)'"
                        onblur="this.style.borderColor='var(--border)'">
                    @error('to_date')
                    <div style="font-size:.76rem;color:#ef4444;margin-top:.3rem">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Reason --}}
                <div style="margin-bottom:1.25rem">
                    <label style="display:block;font-size:.83rem;font-weight:600;
                                  color:var(--text-muted);margin-bottom:.45rem">
                        Reason <span style="color:#ef4444">*</span>
                    </label>
                    <textarea name="reason" rows="3" required
                        {{ $hasPending ? 'disabled' : '' }}
                        placeholder="Explain your reason for leave..."
                        style="width:100%;padding:.7rem .9rem;border:1px solid var(--border);
                               border-radius:10px;background:var(--bg);color:var(--text);
                               font-size:.875rem;outline:none;resize:vertical;
                               font-family:inherit;box-sizing:border-box"
                        onfocus="this.style.borderColor='var(--green)'"
                        onblur="this.style.borderColor='var(--border)'">{{ old('reason') }}</textarea>
                    @error('reason')
                    <div style="font-size:.76rem;color:#ef4444;margin-top:.3rem">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit"
                    {{ $hasPending ? 'disabled' : '' }}
                    class="btn-primary"
                    style="width:100%;justify-content:center;
                           opacity:{{ $hasPending ? '.5' : '1' }};
                           cursor:{{ $hasPending ? 'not-allowed' : 'pointer' }}">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:15px;height:15px">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    Submit Leave Request
                </button>
            </form>
        </div>

    </div>
</div>

@endsection