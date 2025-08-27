<?php

namespace App\Imports;

use App\Models\Usuarios;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements ToModel, WithHeadingRow
{
  public function model(array $row)
  {
    $existingUser = Usuarios::where('requestor', $row['employee_id'])
      ->orWhere('email', $row['email'])
      ->first();

    if ($existingUser) {
      return null;
    }

    return new Usuarios([
      'requestor'  => $row['employee_id'],
      'name'       => $row['name'],
      'email'      => $row['email'],
      'department' => $row['department']
    ]);
  }
}
