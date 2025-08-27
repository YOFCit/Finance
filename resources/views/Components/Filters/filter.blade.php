<div class="mb-3 d-flex justify-content-end">
  <div class="dropdown">
    <button class="btn btn-sm dropdown-toggle shadow-none" type="button" id="filterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
      <i class="bi bi-funnel-fill me-1"></i> Filter
    </button>
    <div class="dropdown-menu dropdown-menu-end p-3" style="min-width: 250px;">
      <form method="GET" action="{{ route('home') }}">
        <div class="mb-2">
          <label class="form-label small">Status</label>
          <select name="status" class="form-select form-select-sm">
            <option value="">All Status</option>
            <option value="Approve" {{ request('status') == 'Approve' ? 'selected' : '' }}>Approved</option>
            <option value="Reject" {{ request('status') == 'Reject' ? 'selected' : '' }}>Rejected</option>
            <option value="In Review" {{ request('status') == 'In Review' ? 'selected' : '' }}>In Review</option>
          </select>
        </div>
        <div class="mb-2">
          <label class="form-label small">Name</label>
          <input type="text" name="name" class="form-control form-control-sm" placeholder="Name" value="{{ request('name') }}">
        </div>
        <div class="mb-2">
          <label class="form-label small">Employee ID</label>
          <input type="text" name="employee" class="form-control form-control-sm" placeholder="ID" value="{{ request('employee') }}">
        </div>
        <div class="mb-2">
          <label class="form-label small">From Date</label>
          <input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}">
        </div>
        <div class="mb-2">
          <label class="form-label small">To Date</label>
          <input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}">
        </div>
        <div class="d-flex justify-content-between mt-2">
          <button type="submit" class="btn btn-sm btn-primary">Apply</button>
          <a href="{{ route('home') }}" class="btn btn-sm btn-outline-secondary">Clear</a>
        </div>
      </form>
    </div>
  </div>
</div>