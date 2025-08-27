@php
$empleado = \App\Models\Usuarios::find($requestData->requestor);
@endphp


@component('mail::message')
# Pending Approval Request

**Employee:** {{ $empleado->name ?? $requestData->requestor }}<br>
**Department:** {{ $requestData->department }}<br>
**Amount:** {{ number_format($requestData->amount, 2, '.', ',') }} {{ $requestData->currency }}<br>
**Justification:** {{ $requestData->justification }}

@component('mail::button', ['url' => $link, 'color' => 'success'])
Review
@endcomponent

This link is unique and can only be used by the recipient.

Thanks,<br>
{{ config('app.name') }}
@endcomponent