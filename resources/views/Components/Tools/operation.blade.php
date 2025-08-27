  <div class="row g-3 align-items-center mb-4 justify-content-between">
    <div class="col-md-6">
      <form method="GET" action="{{ route('home') }}">
        <div class="input-group">
          <input type="text" name="search" class="form-control" placeholder="Search..." value="{{ request('search') }}">
          <button type="submit" class="btn btn-primary">Search</button>
          @if(request('search'))
          <a href="{{ route('home') }}" class="btn btn-outline-secondary">Clear</a>
          @endif
        </div>
      </form>
    </div>
    <div class="col-md-3">
      <button class="btn btn-success w-100" data-bs-toggle="modal" data-bs-target="#createModal">
        <i class="bi bi-plus-circle me-1"></i> Add New
      </button>
    </div>
    @if($auth_status)
    <div class="col-md-3 d-flex gap-2">
      <a title="Export pdf" href="{{ route('requests.export.pdf') }}" class="btn btn-outline-danger w-100">
        <i class="bi bi-file-earmark-pdf"></i>
      </a>
      <a title="Export xls" href="{{ route('requests.export.excel') }}" class="btn btn-outline-success w-100">
        <i class="bi bi-file-earmark-excel"></i>
      </a>
    </div>
    @endif
  </div>