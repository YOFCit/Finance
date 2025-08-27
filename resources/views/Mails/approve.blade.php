@extends('welcome')
@section('title', 'Approve Payment Request')
@section('content')
@include('Components.Alerts.modal')

<main class="container mt-5">
  <div class="table-responsive">
    <table class="table table-bordered table-hover align-middle bg-white">
      <thead class="table-dark">
        <tr>
          <th>Expense No.</th>
          <th>Date</th>
          <th>Requestor</th>
          <th>Submitter Department</th>
          <th>Causing Department</th>
          <th>Supplier</th>
          <th>Amount</th>
          <th>Due Date</th>
          <th>Justification</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>{{ $request->expense_no }}</td>
          <td>{{ $request->request_date }}</td>
          <td>{{ $request->requestor }}</td>
          <td>{{ $request->department }}</td>
          <td>{{ $request->causing_department }}</td>
          <td>{{ $request->supplier }}</td>
          <td>${{ number_format($request->amount, 2) }}</td>
          <td>{{ $request->payment_due_date }}</td>
          <td class="text-truncate" style="max-width: 200px;">{{ $request->justification }}</td>
          <td class="text-center">
            @switch($request->status)
            @case('Approve')
            <span class="badge bg-success">✓ Approved</span>
            @break
            @case('Reject')
            <span class="badge bg-danger">✗ Rejected</span>
            @break
            @case('In Review')
            <span class="badge bg-warning text-dark">⏳ In Review</span>
            @break
            @default
            <span class="badge bg-secondary">Pending</span>
            @endswitch
          </td>
          <td class="text-center">
            @php
            $requestId = $request->id;
            $empleado = \App\Models\Usuarios::where('requestor', $request->requestor)->first();
            $requestorName = $empleado->name ?? '';
            @endphp

            @if($request->status === 'In Review')
            <button
              class="btn btn-sm btn-secondary w-100 mb-1"
              data-bs-toggle="modal"
              data-bs-target="#approveModal"
              data-action="{{ $requestId }}"
              data-current-status="In Review"
              data-expense-no="{{ $request->expense_no }}"
              data-request-date="{{ $request->request_date }}"

              data-requestor="{{ $request->requestor }}"
              data-requestor-name="{{ $requestorName }}"
              data-department="{{ $request->department }}"
              data-causing-department="{{ $request->causing_department }}"
              data-supplier="{{ $request->supplier }}"
              data-amount="{{ $request->amount }}"
              data-currency="{{ $request->currency }}"
              data-payment-due="{{ $request->payment_due_date }}"
              data-justification="{{ $request->justification }}"
              data-cause="{{ $request->cause }}"
              data-risk="{{ $request->risk }}"
              data-description="{{ $request->description }}"
              data-reason="{{ $request->reason ?? '' }}">
              Review
            </button>
            @endif

            @if($request->status === 'Approve')
            <span>✓ Approved</span>
            @elseif($request->status === 'Reject')
            <span>✗ Rejected</span>
            @endif
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</main>

{{-- Modal separado en un include --}}
<div class="modal fade" id="approveModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="bi bi-shield-check me-2"></i> Change Status
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <div class="row g-3">
          <div class="col-md-6">
            <p><strong>Expense No:</strong> <span id="modalExpenseNo"></span></p>
            <p><strong>Request Date:</strong> <span id="modalRequestDate"></span></p>
            <p><strong>Employee ID:</strong> <span id="modalRequestor"></span></p>
            <p><strong>Name:</strong> <span id="modalRequestorname"></span></p>
            <p><strong>Submitter Department:</strong> <span id="modalDepartment"></span></p>
            <p><strong>Causing Department:</strong> <span id="modalCausingDepartment"></span></p>
            <p><strong>Supplier:</strong> <span id="modalSupplier"></span></p>
          </div>
          <div class="col-md-6">
            <p><strong>Amount:</strong> $<span id="modalAmount"></span> <span id="modalCurrency"></span></p>
            <p><strong>Due Date:</strong> <span id="modalPaymentDue"></span></p>
            <p><strong>Justification:</strong> <span id="modalJustification"></span></p>
            <p><strong>Cause:</strong> <span id="modalCause"></span></p>
            <p><strong>Risk:</strong> <span id="modalRisk"></span></p>
          </div>
        </div>
        <hr>
        <p><strong>Description:</strong> <span id="modalDescription"></span></p>
        <hr>
        <div class="mb-3">
          <label for="modalReason" class="form-label"><strong>Reason (optional):</strong></label>
          <textarea class="form-control" id="modalReason" name="reason" rows="2"
            placeholder="Provide a reason (optional)"></textarea>
        </div>
        <p>Choose an action for this request:</p>
      </div>

      <div class="modal-footer d-flex justify-content-between gap-2">
        <form id="approveForm" method="POST" action="" class="flex-grow-1">
          @csrf
          @method('PATCH')
          <input type="hidden" name="status" value="Approve">
          <input type="hidden" name="reason" id="approveReason">
          <button type="submit" class="btn btn-success w-100">
            <i class="bi bi-check-circle me-1"></i> Approve
          </button>
        </form>

        <form id="rejectForm" method="POST" action="" class="flex-grow-1">
          @csrf
          @method('PATCH')
          <input type="hidden" name="status" value="Reject">
          <input type="hidden" name="reason" id="rejectReason">
          <button type="submit" class="btn btn-danger w-100">
            <i class="bi bi-x-circle me-1"></i> Reject
          </button>
        </form>
      </div>
    </div>
  </div>
</div>


@endsection
<script src="{{ asset('js/mail_approve.js') }}"></script>
<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('js/jquery.min.js') }}"></script>