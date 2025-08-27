@extends('welcome')
@section('title', 'Corporate Payment Authorization')
@section('content')
<!-- PRINCIPAL CONTENT -->
<div class="container mt-5">
  <!-- TOOLS (SEARCH, CREATE, EXPORT) -->
  @include('Components.Tools.operation')
  @include('Components.Filters.filter')
  @if($requests->isEmpty())
  <div class="alert alert-info text-center py-4" role="alert">
    <i class="bi bi-info-circle-fill fs-4"></i>
    <h4 class="mt-2">No Payment Requests Found</h4>
    <p class="mb-0">Initiate a new payment authorization request by clicking the button above</p>
  </div>
  @else
  <div class="table-responsive">
    <table class="table table-bordered table-hover align-middle bg-white">
      <thead class="table-dark">
        <tr>
          <th style="position: sticky; left: 0; background-color: #212529; z-index: 2;">Expense No.</th>
          <th>Date</th>
          <th>Requestor</th>
          <th>Submitter Department</th>
          <th>Causing Department</th>
          <th>Supplier</th>
          <th>Amount</th>
          <th>Due Date</th>
          <th>Justification</th>
          <th>Status</th>
          @if($requests->contains(fn($req) => $req->status === 'Approve'))
          <th>Actions</th>
          @endif
        </tr>
      </thead>
      <tbody>
        @foreach ($requests as $req)
        @php
        $status = $req->status ?? 'In Review';
        @endphp
        <tr>
          <td style="position: sticky; left: 0; background-color: #fff; z-index: 1;">
            {{ $req->expense_no ?? '-' }}
          </td>
          <td>{{ $req->request_date ?? '-' }}</td>
          <td>{{ $req->requestor ?? '-' }}</td>
          <td>{{ $req->department ?? '-' }}</td>
          <td>{{ $req->causing_department ?? '-' }}</td>
          <td>{{ $req->supplier ?? '-' }}</td>
          <td>${{ number_format($req->amount ?? 0, 2) }}</td>
          <td>{{ $req->payment_due_date ?? '-' }}</td>
          <td class="text-truncate" style="max-width: 200px;">{{ $req->justification ?? '-' }}</td>
          <td class="text-center align-middle" style="min-width: 140px;">
            @if($auth_status)
            <button class="btn 
                    @switch($status)
                      @case('Approve') btn-success @break
                      @case('Reject') btn-danger @break
                      @default btn-outline-secondary @endswitch
                    btn-sm w-100"
              data-bs-toggle="modal"
              data-bs-target="#approveModal"
              data-id="{{ $req->id }}"
              data-expense-no="{{ $req->expense_no ?? '-' }}"
              data-requestor="{{ $req->requestor ?? '-' }}"
              data-name="{{ $req->name ?? '-' }}"
              data-department="{{ $req->department ?? '-' }}"
              data-causing_department="{{ $req->causing_department ?? '-' }}"
              data-supplier="{{ $req->supplier ?? '-' }}"
              data-amount="{{ $req->amount ?? 0 }}"
              data-currency="{{ $req->currency ?? '-' }}"
              data-payment-due="{{ $req->payment_due_date ?? '-' }}"
              data-justification="{{ $req->justification ?? '-' }}"
              data-cause="{{ $req->cause ?? '-' }}"
              data-risk="{{ $req->risk ?? '-' }}"
              data-description="{{ $req->description ?? '-' }}"
              data-request-date="{{ $req->request_date ?? '-' }}"
              data-status="{{ $status }}"
              title="{{ $status }}">
              @switch($status)
              @case('Approve') Approved @break
              @case('Reject') Rejected @break
              @default In Review @endswitch
            </button>
            @else
            @switch($status)
            @case('Approve') <span class="badge bg-success d-block">Approved</span> @break
            @case('Reject') <span class="badge bg-danger d-block">Rejected</span> @break
            @default <span class="badge bg-secondary d-block">In Review</span> @endswitch
            @endif
          </td>

          @if($req->status === 'Approve')
          <td>
            <div class="btn-group w-100" role="group">
              <a title="Export pdf" href="{{ route('urgent-requests.export.single', $req->id) }}" class="btn btn-outline-secondary px-3">
                <i class="bi bi-file-earmark-pdf"></i>
              </a>
            </div>
          </td>
          @endif
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  <!-- PAGINATE ELEMENT -->
  @include('Components.Paginated.element')
  @endif
</div>

<!-- MODALS -->
@include('Requests.create_edit')
@include('Requests.delete')
@include('Requests.approve')

<script src="{{ asset('js/id7numbers.js') }}"></script>
@endsection