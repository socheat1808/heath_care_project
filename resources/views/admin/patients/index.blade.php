@extends('admin.layout')
@section('title', 'Patients')
@section('content')

<div class="page-content active" id="page-patients">

  <div class="page-header">
    <div class="page-header-left">
      <h1>Manage Patients</h1>
      <p>{{ $patients->total() }} registered patients in the system.</p>
    </div>
    <a href="{{ route('admin.patients.create') }}" class="btn btn-primary"
      style="text-decoration:none;display:inline-flex;align-items:center;gap:.4rem">
      <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:16px;height:16px">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
      </svg>
      Add Patient
    </a>
  </div>

  {{-- Flash message --}}
  @if(session('success'))
  <div style="display:flex;align-items:center;gap:.6rem;padding:.75rem 1.25rem;border-radius:10px;background:rgba(26,138,110,.1);color:var(--green);border:1px solid rgba(26,138,110,.2);font-size:.875rem;font-weight:500;margin-bottom:1.25rem">
    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:18px;height:18px;flex-shrink:0">
      <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
    </svg>
    {{ session('success') }}
  </div>
  @endif

  <div class="table-card">

    {{-- Search + Filter bar --}}
    <div class="search-bar">
      <div class="search-input-wrap">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
        </svg>
        <form method="GET" action="{{ route('admin.patients.index') }}" style="flex:1">
          <input type="text"
            name="search"
            value="{{ request('search') }}"
            placeholder="Search by name or email…"
            style="width:100%;border:none;outline:none;background:none;font-size:.875rem;font-family:inherit;color:var(--text)">
        </form>
      </div>

      <div class="status-filter">
        <a href="{{ route('admin.patients.index') }}"
          class="status-tab {{ request('status') == null ? 'active' : '' }}">All</a>
        <a href="{{ route('admin.patients.index', ['status' => 'active']) }}"
          class="status-tab {{ request('status') == 'active' ? 'active' : '' }}">Active</a>
        <a href="{{ route('admin.patients.index', ['status' => 'inactive']) }}"
          class="status-tab {{ request('status') == 'inactive' ? 'active' : '' }}">Inactive</a>
      </div>

      <button class="btn btn-outline" style="margin-left:auto;display:inline-flex;align-items:center;gap:.4rem">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:15px;height:15px">
          <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
        </svg>
        Export
      </button>
    </div>

    {{-- Table --}}
    <table class="data-table">
      <thead>
        <tr>
          <th>Patient</th>
          <th>Phone</th>
          <th>Date of Birth</th>
          <th>Department</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($patients as $patient)
        <tr>
          {{-- Name + Email --}}
          <td>
            <div class="user-cell">
              <div class="avatar av-green">
                {{ strtoupper(substr($patient->first_name ?? 'P', 0, 1) . substr($patient->last_name ?? 'P', 0, 1)) }}
              </div>
              <div>
                <div class="user-name">{{ $patient->first_name }} {{ $patient->last_name }}</div>
                <div class="user-email">{{ $patient->email }}</div>
              </div>
            </div>
          </td>

          {{-- Phone --}}
          <td style="color:var(--text-secondary)">{{ $patient->phone ?? '—' }}</td>

          {{-- Date of Birth --}}
          <td style="color:var(--text-secondary)">
            {{ $patient->date_of_birth
                ? \Carbon\Carbon::parse($patient->date_of_birth)->format('d M Y')
                : '—' }}
          </td>

          {{-- Department --}}
          <td>
            <span class="badge badge-blue">{{ $patient->department ?? 'General' }}</span>
          </td>

          {{-- Status --}}
          <td>
            <span class="badge {{ ($patient->status === 'active') ? 'badge-green' : 'badge-red' }}">
              {{ ucfirst($patient->status ?? 'active') }}
            </span>
          </td>

          {{-- Actions --}}
          <td>
            <div style="display:flex;align-items:center;gap:.4rem">

              {{-- Edit --}}
              <a href="{{ route('admin.patients.edit', $patient->PatientID) }}"
                class="action-btn"
                style="display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:7px;background:rgba(37,99,235,.1);color:#2563EB;text-decoration:none;transition:opacity .15s"
                title="Edit patient">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:15px;height:15px">
                  <path stroke-linecap="round" stroke-linejoin="round"
                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
              </a>

              {{-- Delete --}}
              <form method="POST" action="{{ route('admin.patients.destroy', $patient->PatientID) }}">
                @csrf
                @method('DELETE')
                <button type="submit"
                  title="Delete patient"
                  onclick="return confirm('Delete {{ addslashes($patient->first_name.' '.$patient->last_name) }}? This cannot be undone.')"
                  style="display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:7px;background:rgba(220,38,38,.08);color:#DC2626;border:none;cursor:pointer;transition:opacity .15s">
                  <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:15px;height:15px">
                    <path stroke-linecap="round" stroke-linejoin="round"
                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                  </svg>
                </button>
              </form>

            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="6" style="text-align:center;padding:3rem 1.5rem;color:var(--text-muted);font-size:.875rem">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.3"
              style="width:48px;height:48px;opacity:.3;display:block;margin:0 auto .75rem">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            No patients found.
            <a href="{{ route('admin.patients.create') }}" style="color:var(--green)">Add the first one →</a>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>

    {{-- Pagination --}}
    <div class="pagination">
      <span class="pagination-info">
        Showing {{ $patients->firstItem() }}–{{ $patients->lastItem() }} of {{ $patients->total() }} patients
      </span>
      <div class="pagination-btns">
        {{-- Previous --}}
        <a href="{{ $patients->previousPageUrl() }}"
          class="pg-btn {{ $patients->onFirstPage() ? 'disabled' : '' }}"
          style="text-decoration:none;{{ $patients->onFirstPage() ? 'opacity:.4;pointer-events:none' : '' }}">‹</a>

        {{-- Page numbers --}}
        @foreach($patients->getUrlRange(1, $patients->lastPage()) as $page => $url)
        <a href="{{ $url }}"
          class="pg-btn {{ $patients->currentPage() === $page ? 'active' : '' }}"
          style="text-decoration:none">{{ $page }}</a>
        @endforeach

        {{-- Next --}}
        <a href="{{ $patients->nextPageUrl() }}"
          class="pg-btn {{ !$patients->hasMorePages() ? 'disabled' : '' }}"
          style="text-decoration:none;{{ !$patients->hasMorePages() ? 'opacity:.4;pointer-events:none' : '' }}">›</a>
      </div>
    </div>

  </div>
</div>

@endsection