<!-- MODAL EDITAR -->
<div class="modal fade" id="editModal{{ $usuario->id }}" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" action="{{ route('users.update', $usuario->id) }}">
        @csrf
        @method('PUT')
        <div class="modal-header">
          <h5 class="modal-title"><i class="bi bi-pencil me-2"></i> Edit User</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="requestor" class="form-label">Employee id</label>
            <input type="text" name="requestor" class="form-control" value="{{ $usuario->requestor }}" required pattern="\d{7}" maxlength="7" minlength="7" inputmode="numeric" required
              placeholder="Enter 7-digit ID">
          </div>
          <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" name="name" class="form-control" value="{{ $usuario->name }}" required>
          </div>
          <div class="mb-3">
            <label for="email{{ $usuario->id }}" class="form-label">Email</label>
            <input type="email" name="email" class="form-control" id="email{{ $usuario->id }}" value="{{ $usuario->email }}" required>
          </div>
          <div class="mb-3">
            <label for="department" class="form-label">Department</label>
            <select name="department" class="form-select" required>
              <option value="" disabled {{ old('department', $usuario->department) == '' ? 'selected' : '' }}>Select a department</option>
              <option value="Finance&Accounting" {{ old('department', $usuario->department) == 'Finance&Accounting' ? 'selected' : '' }}>Finance & Accounting</option>
              <option value="HR&GA" {{ old('department', $usuario->department) == 'HR&GA' ? 'selected' : '' }}>HR & GA</option>
              <option value="Supply Chain Department" {{ old('department', $usuario->department) == 'Supply Chain Department' ? 'selected' : '' }}>Supply Chain Department</option>
              <option value="Production Department" {{ old('department', $usuario->department) == 'Production Department' ? 'selected' : '' }}>Production Department</option>
              <option value="Quality Department" {{ old('department', $usuario->department) == 'Quality Department' ? 'selected' : '' }}>Quality Department</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Update</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>