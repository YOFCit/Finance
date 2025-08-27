<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UrgentPaymentRequest extends Model
{
  use HasFactory;

  protected $fillable = [
    'device_token',
    'request_date',
    'requestor',
    'expense_no',
    'department',
    'causing_department',
    'supplier',
    'amount',
    'currency',
    'payment_due_date',
    'description',
    'justification',
    'cause',
    'risk',
    'signature_path',
    'status',
    'access_token',
    'reason',
  ];
}
