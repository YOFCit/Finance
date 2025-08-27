@php
$empleado = \App\Models\Usuarios::where('requestor', $requestModel->requestor)->first();
@endphp

<x-mail::message>
  # Status of your payment request

  Hello {{ $empleado->name ?? 'Employee' }},

  Your request with ID **#{{ $requestModel->expense_no }}** has changed status:

  - **New status:** {{ $requestModel->status }}

  - **Reason:** {{ $requestModel->reason }}

  <x-mail::button :url="route('requests.show', $requestModel->id)">
    View request
  </x-mail::button>

  You can click on that link to review it and download/print the receipt.

  Thank you,<br>
  {{ config('app.name') }}
</x-mail::message>