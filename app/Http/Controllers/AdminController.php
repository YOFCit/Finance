<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AdminController extends Controller
{
  public $auth_status = false;

  /** Show the admin login form */
  public function showLoginForm()
  {
    return view('Login_admin.login');
  }

  /** Handle admin login */
  public function login(Request $request)
  {
    $credentials = $request->validate([
      'email' => 'required|email',
      'password' => 'required|string',
    ]);

    if (Auth::attempt($credentials)) {
      $request->session()->regenerate();

      return redirect()->route('home')
        ->with([
          'auth_status' => true,
          'welcome_message' => 'Welcome ' . Auth::user()->name
        ]);
    }

    throw ValidationException::withMessages([
      'email' => 'Credentials do not match our records',
    ]);
  }

  /** Handle admin logout */
  public function logout(Request $request)
  {
    $name = Auth::user()->name;
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->intended('/')
      ->with([
        'auth_status' => false,
        'welcome_message' => 'Goodbye ' . $name
      ]);
  }
}
