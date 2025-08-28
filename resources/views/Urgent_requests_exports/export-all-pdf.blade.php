<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Urgent Payment Requests PDF</title>
  <link href="{{ public_path('css/exports.css') }}" rel="stylesheet" type="text/css">
</head>

<body>
  @foreach($requests as $req)
  <div class="request-page">
    <div class="header">
      <img src="{{ public_path('logo.png') }}" alt="Logo" class="logo">
      <h3>Urgent Payment Request Form</h3>
      <p class="intro">
        This form must be completed by the requesting department and submitted to the Finance Department. All requests must be properly justified and approved by the department head.
      </p>
    </div>

    <table>
      <tr>
        <th>Request Date</th>
        <td>{{ \Carbon\Carbon::parse($req->request_date)->format('d/m/Y') }}</td>
      </tr>
      <tr>
        <th>Employee ID</th>
        <td>{{ $req->requestor }}</td>
      </tr>

      <tr>
        <th>Employee Name</th>
        <td>{{ \App\Models\Usuarios::where('requestor', $req->requestor)->first()?->name ?? 'Name not defined' }}</td>
      </tr>

      <tr>
        <th>Submitter Department</th>
        <td>{{ $req->department }}</td>
      </tr>
      <tr>
        <th>Causing Department</th>
        <td>{{ $req->causing_department }}</td>
      </tr>
      <tr>
        <th>Supplier</th>
        <td>{{ $req->supplier }}</td>
      </tr>
      <tr>
        <th>Amount</th>
        <td>${{ number_format($req->amount, 2) }}</td>
      </tr>
      <tr>
        <th>Payment Due Date</th>
        <td>{{ \Carbon\Carbon::parse($req->payment_due_date)->format('d/m/Y') }}</td>
      </tr>
      <tr>
        <th>Justification</th>
        <td>{{ $req->justification ?? 'N/A' }}</td>
      </tr>
      <tr>
        <th>Cause</th>
        <td>{{ $req->cause ?? 'N/A' }}</td>
      </tr>
      <tr>
        <th>Risk</th>
        <td>{{ $req->risk ?? 'N/A' }}</td>
      </tr>
      <tr>
        <th class="title-small">
          Detail Explanation<br>
          <small>(clearly explain the reason and attach evidence if applicable)</small>
        </th>
        <td class="description-cell">{{ $req->description ?? 'N/A' }}</td>
      </tr>
      <tr>
        <th>Reason</th>
        <td>{{ $req->reason ?? 'N/A' }}</td>
      </tr>
      <tr>
        @php
        $approver = \App\Models\ApprovalPerson::first();
        $statusText = match(strtolower($req->status)) {
        'approve', 'approved' => 'Approved by',
        'reject', 'rejected' => 'Rejected by',
        default => 'Reviewed by',
        };
        @endphp
        <th>{{ $statusText.' '.$approver?->approved_by ?? 'Approver not defined' }}</th>
        @php
        $statusMap = [
        'Approve' => 'Approved',
        'Reject' => 'Rejected',
        'In Review' => 'In Review',
        ];
        $statusText = $statusMap[$req->status] ?? $req->status;
        @endphp
        <td class="status {{ strtolower(str_replace(' ', '-', $req->status)) }}">
          {{ $statusText }}
        </td>
      </tr>
    </table>
    <p class="note">
      Note: The Finance Department reserves the right to reject requests that lack sufficient justification or proper authorization.
    </p>
  </div>
  @endforeach
</body>

</html>