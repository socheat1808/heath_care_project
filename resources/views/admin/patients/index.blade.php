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
                {{ strtoupper(substr($patient->name, 0, 2)) }}
              </div>
              <div>
                <div class="user-name">{{ $patient->name }}</div>
                <div class="user-email">{{ $patient->email }}</div>
              </div>
            </div>
          </td>

          {{-- Phone --}}
          <td style="color:var(--text-secondary)">{{ $patient->phone ?? '—' }}</td>

          {{-- Joined --}}
          <td style="color:var(--text-secondary)">
            {{ $patient->created_at->format('d M Y') }}
          </td>

          {{-- Role --}}
          <td>
            <span class="badge badge-blue">Patient</span>
          </td>

          {{-- Status --}}
          <td>
            <span class="badge {{ $patient->status === 'approved' ? 'badge-green' : 'badge-red' }}">
              {{ $patient->status === 'approved' ? 'Active' : ucfirst($patient->status) }}
            </span>
          </td>

          {{-- Actions --}}
          <td>
            <div style="display:flex;align-items:center;gap:.4rem">
              <a href="{{ route('admin.patients.edit', $patient->id) }}"
                title="Edit"
                style="display:inline-flex;align-items:center;justify-content:center;
                       width:30px;height:30px;border-radius:7px;background:var(--bg);
                       border:1px solid var(--border);color:var(--text-muted);text-decoration:none"
                onmouseover="this.style.borderColor='var(--green)';this.style.color='var(--green)'"
                onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--text-muted)'">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:13px;height:13px">
                  <path stroke-linecap="round" stroke-linejoin="round"
                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
              </a>

              <form method="POST" action="{{ route('admin.patients.destroy', $patient->id) }}">
                @csrf @method('DELETE')
                <button type="submit"
                  title="Delete"
                  onclick="return confirm('Delete {{ addslashes($patient->name) }}?')"
                  style="display:inline-flex;align-items:center;justify-content:center;
                           width:30px;height:30px;border-radius:7px;background:var(--bg);
                           border:1px solid var(--border);color:var(--text-muted);cursor:pointer"
                  onmouseover="this.style.borderColor='#ef4444';this.style.color='#ef4444'"
                  onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--text-muted)'">
                  <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:13px;height:13px">
                    <path stroke-linecap="round" stroke-linejoin="round"
                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                  </svg>
                </button>
              </form>
              {{-- View History --}}
              <a href="{{ route('admin.patients.show', $patient->id) }}"
                title="View History"
                style="display:inline-flex;align-items:center;justify-content:center;
           width:30px;height:30px;border-radius:7px;background:var(--bg);
           border:1px solid var(--border);color:var(--text-muted);text-decoration:none"
                onmouseover="this.style.borderColor='var(--green)';this.style.color='var(--green)'"
                onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--text-muted)'">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:13px;height:13px">
                  <path stroke-linecap="round" stroke-linejoin="round"
                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round"
                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
              </a>
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