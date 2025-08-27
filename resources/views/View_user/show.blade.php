@extends('welcome')
@section('title', 'Payment Request Details')
@section('content')
<div class="container mt-5 flex-grow-1">
  <div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
      <h5 class="mb-0">Request #{{ $requestModel->expense_no }}</h5>
    </div>
    <div class="card-body">
      @php
      $empleado = \App\Models\Usuarios::where('requestor', $requestModel->requestor)->first();
      @endphp

      <p><strong>Requester:</strong> {{ $empleado->name ?? 'Not found' }}</p>
      <p><strong>Email:</strong> {{ $empleado->email ?? 'Not found' }}</p>
      <p><strong>Department:</strong> {{ $empleado->department ?? 'Not found' }}</p>
      <p><strong>Causing Department:</strong> {{ $requestModel->causing_department ?? 'Not found' }}</p>
      <p><strong>Amount:</strong> ${{ number_format($requestModel->amount, 2) }}</p>
      <p><strong>Supplier:</strong> {{ $requestModel->supplier ?? 'Not defined' }}</p>
      <p><strong>Payment Date:</strong> {{ $requestModel->payment_due_date ?? 'Not defined' }}</p>
      <p><strong>Justification:</strong> {{ $requestModel->justification ?? 'Not defined' }}</p>
      <p><strong>Description:</strong> {{ $requestModel->description ?? 'Not defined' }}</p>
      <p><strong>Status:</strong>
        @switch($requestModel->status)
        @case('Approve') <span class="badge bg-success">Approved</span> @break
        @case('Reject') <span class="badge bg-danger">Rejected</span> @break
        @default <span class="badge bg-secondary">In Review</span>
        @endswitch
      </p>
      <p><strong>Reason:</strong> {{ $requestModel->reason ?? 'Not defined' }}</p>

      <div class="d-flex flex-wrap gap-2 mt-3">
        @if($requestModel->status === 'Approve')
        <a href="{{ route('urgent-requests.export.single', $requestModel->id) }}"
          class="btn btn-outline-secondary d-flex align-items-center gap-1">
          <i class="bi bi-file-earmark-pdf"></i> Export to PDF
        </a>
        @endif

        <a href="{{ route('home') }}" class="btn btn-outline-secondary d-flex align-items-center gap-1">
          <i class="bi bi-arrow-left"></i> Go back
        </a>
      </div>
    </div>
  </div>
</div>

<footer class="footer text-center mt-auto py-3 bg-light">
  <div class="container">
    <small>&copy; 2025 The Finance Department. All rights reserved.</small>
  </div>
</footer>
@endsection