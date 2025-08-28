@extends('welcome')
@section('title', 'Employees')
@section('content')
<div class="container mt-5">
  <!-- CREATE / IMPORT BUTTONS -->
  <div class="mb-3 text-end">
    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createModal" title="Create">
      <i class="bi bi-plus-circle me-1"></i> New Employee
    </button>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#importModal" title="Import Excel">
      <i class="bi bi-file-earmark-arrow-up me-1"></i> Import Users .xls
    </button>
  </div>

  <!-- USERS TABLE -->
  @if($usuarios->isEmpty())
  <div class="alert alert-info text-center py-4">
    <i class="bi bi-info-circle-fill fs-4"></i>
    <h4 class="mt-2">No users registered</h4>
  </div>
  @else
  <div class="table-responsive">
    <table class="table table-bordered table-hover align-middle bg-white">
      <thead class="table-dark">
        <tr>
          <th style="position: sticky; left: 0; background-color: #212529; z-index: 2;"> EmployeeID</th>
          <th>Name</th>
          <th>Email</th>
          <th>Department</th>
          <th class="text-center">Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($usuarios as $usuario)
        <tr>
          <td style="position: sticky; left: 0; background-color: #fff; z-index: 1;">{{ $usuario->requestor }}</td>
          <td>{{ $usuario->name }}</td>
          <td>{{ $usuario->email }}</td>
          <td>{{ $usuario->department }}</td>
          <td class="text-center">
            <div class="btn-group">
              <button type="button" class="btn btn-outline-primary" title="Edit"
                data-bs-toggle="modal" data-bs-target="#editModal{{ $usuario->id }}">
                <i class="bi bi-pencil"></i>
              </button>
              <button type="button" class="btn btn-outline-danger" title="Delete"
                data-bs-toggle="modal" data-bs-target="#deleteModal{{ $usuario->id }}">
                <i class="bi bi-trash"></i>
              </button>
            </div>
          </td>
        </tr>
        @include('Employees.modals.edit')
        @include('Employees.modals.delete')
        @endforeach
      </tbody>
    </table>
    <!-- PAGINATE ELEMENT -->
    @include('Components.Paginated.element_u')
    <!-- ###################################### -->
  </div>
  @endif
  @include('Employees.modals.create')
  @include('Employees.modals.import')
</div>
@endsection