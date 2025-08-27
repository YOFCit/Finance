<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DefaultUserSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run()
  {
    User::create([
      'name' => 'Admin',
      'email' => 'finanzas@yofc.com',
      'password' => Hash::make('finanzas123'),
    ]);
    User::factory(5)->create();
  }
}
