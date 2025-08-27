<!-- MODAL CREAR -->
<div class="modal fade" id="createModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" action="{{ route('users.store') }}">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title"><i class="bi bi-person-plus me-2"></i> New User</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="requestor" class="form-label">Employee ID</label>
            <input type="text" name="requestor" class="form-control"
              required
              pattern="\d{7}"
              maxlength="7"
              inputmode="numeric"
              placeholder="Enter 7-digit ID"
              oninput="this.value = this.value.replace(/\D/g,'').slice(0,7)">
          </div>
          <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" name="name" class="form-control" required placeholder="Enter Employee Name">
          </div>
          <div class="mb-3">
            <label for="department" class="form-label">Department</label>
            <select name="department" class="form-select" required>
              <option value="" disabled selected>Select a department</option>
              <option value="Finance&Accounting">Finance & Accounting</option>
              <option value="HR&GA">HR & GA</option>
              <option value="Supply Chain Department">Supply Chain Department</option>
              <option value="Production Department">Production Department</option>
              <option value="Quality Department">Quality Department</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" class="form-control" id="email" value="{{ old('email') }}" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Save</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>