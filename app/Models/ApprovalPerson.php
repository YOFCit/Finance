<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApprovalPerson extends Model
{
  use HasFactory;
  protected $table = 'approval_person';

  protected $fillable = [
    'mail',
    'approved_by'
  ];
}
