<?php

namespace App\Http\Controllers;

use App\Models\Usuarios;
use Illuminate\Http\Request;
use App\Imports\UsersImport;
use App\Models\UrgentPaymentRequest;
use Maatwebsite\Excel\Facades\Excel;

class UsuariosController extends Controller
{
  /** Import users from Excel file */
  public function importExcel(Request $request)
  {
    $request->validate([
      'file' => 'required|mimes:xlsx,xls,csv'
    ]);

    Excel::import(new UsersImport, $request->file('file'));

    return redirect()->back()->with('success', 'Users imported successfully');
  }

  /** List users with pagination */
  public function index()
  {
    $usuarios = Usuarios::paginate(5);
    return view('Employees.index', compact('usuarios'));
  }


  /** Show details of a specific user */
  public function show(Usuarios $usuario)
  {
    return view('View_user.show', compact('usuario'));
  }

  /** Show form to edit a user */
  public function edit($id)
  {
    $usuario = Usuarios::findOrFail($id);
    return view('Employees.edit', compact('usuario'));
  }

  /** Update a user in the database */
  public function update(Request $request, $id)
  {
    $request->validate([
      'requestor'  => 'required|string|max:255',
      'name'       => 'required|string|max:255',
      'email'      => 'required|email|max:255',
      'department' => 'required|string|max:255',
    ]);

    $usuario = Usuarios::findOrFail($id);
    $usuario->update($request->only('requestor', 'name', 'email', 'department'));

    return redirect()->route('Employees.index')
      ->with('success', 'User updated successfully');
  }

  /** Delete a user from the database */
  public function destroy($id)
  {
    $usuario = Usuarios::findOrFail($id);
    $usuario->delete();

    return redirect()->route('Employees.index')
      ->with('success', 'User deleted successfully');
  }

  /** Create a new user */
  public function store(Request $request)
  {
    $request->validate([
      'requestor'  => 'required|string|max:255',
      'name'       => 'required|string|max:255',
      'email'      => 'required|email|max:255|unique:usuarios,email',
      'department' => 'required|string|max:255',
    ]);

    $exists = Usuarios::where('requestor', $request->requestor)->exists();

    if ($exists) {
      return redirect()->back()
        ->withErrors(['duplicate' => 'There is already a user with the same ID'])
        ->withInput();
    }

    Usuarios::create($request->only('requestor', 'name', 'email', 'department'));

    return redirect()->route('Employees.index')
      ->with('success', 'User created successfully');
  }
}
