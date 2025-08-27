<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UrgentPaymentRequestController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UsuariosController;
use App\Exports\UrgentPaymentRequestExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\ApprovalPersonController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Home (request form)
Route::get('/', [UrgentPaymentRequestController::class, 'index'])->name('home');

// Admin login
Route::get('/admin', [AdminController::class, 'showLoginForm'])
  ->name('admin.login')
  ->middleware('guest:admin');
Route::post('/admin/login', [AdminController::class, 'login'])->name('admin.login.post');
Route::post('/admin/logout', [AdminController::class, 'logout'])->name('admin.logout');

// Public urgent payment requests
Route::post('/requests', [UrgentPaymentRequestController::class, 'store'])->name('requests.store');
Route::get('/urgent-requests/{id}/pdf', [UrgentPaymentRequestController::class, 'exportSinglePDF'])
  ->name('urgent-requests.export.single');

// Approval by token
Route::get('/requests/approve/{token}', [UrgentPaymentRequestController::class, 'approveurl'])->name('request.approve');
Route::get('/requests/approveurl/{token}', [UrgentPaymentRequestController::class, 'approveurl'])->name('approve.url');

// Fetch requestor name dynamically (AJAX)
Route::get('/obtain-name', [UrgentPaymentRequestController::class, 'getName']);

// Show request details
Route::get('/requests/{id}', [UrgentPaymentRequestController::class, 'show'])->name('requests.show');
Route::patch('/requests/{id}/approval', [UrgentPaymentRequestController::class, 'updateApprovalStatus'])->name('requests.updateApprovalStatus');
Route::patch('/requests/{id}/status', [UrgentPaymentRequestController::class, 'updateStatus'])->name('requests.updateStatus');

/*
|--------------------------------------------------------------------------
| Protected Routes (require login)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

  // Export routes
  Route::get('/requests/export/excel', function () {
    return Excel::download(new UrgentPaymentRequestExport, 'urgent_requests.xlsx');
  })->name('requests.export.excel');
  Route::get('/requests/export/pdf', [UrgentPaymentRequestController::class, 'exportAllPDF'])->name('requests.export.pdf');

  // Urgent Payment Requests (CRUD)
  Route::get('/requests/{id}/edit', [UrgentPaymentRequestController::class, 'edit'])->name('requests.edit');
  Route::put('/requests/{id}', [UrgentPaymentRequestController::class, 'update'])->name('requests.update');
  Route::delete('/requests/{urgentPaymentRequest}', [UrgentPaymentRequestController::class, 'destroy'])->name('requests.destroy');

  // Users (CRUD)
  Route::get('/users', [UsuariosController::class, 'index'])->name('users.index');
  Route::post('/users', [UsuariosController::class, 'store'])->name('users.store');
  Route::post('/users/import', [UsuariosController::class, 'importExcel'])->name('users.import');
  Route::get('/users/{user}', [UsuariosController::class, 'show'])->name('users.show');
  Route::get('/users/{user}/edit', [UsuariosController::class, 'edit'])->name('users.edit');
  Route::put('/users/{user}', [UsuariosController::class, 'update'])->name('users.update');
  Route::delete('/users/{user}', [UsuariosController::class, 'destroy'])->name('users.destroy');

  //Approval requestor
  Route::get('/approval-person', [ApprovalPersonController::class, 'showModal'])->name('approval-person.modal');
  Route::post('/approval-person', [ApprovalPersonController::class, 'store'])->name('approval-person.store');
  Route::put('/approval-person/{id}', [ApprovalPersonController::class, 'update'])->name('approval-person.update');
});
