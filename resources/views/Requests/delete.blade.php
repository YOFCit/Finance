<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg-light">

      <!-- Header -->
      <div class="modal-header">
        <h5 class="modal-title text-danger" id="deleteModalLabel">
          <i class="bi bi-exclamation-triangle-fill me-2"></i>Confirm Delete
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <!-- Body -->
      <div class="modal-body text-center text-dark">
        <p class="mb-2">Are you sure you want to delete the following payment request?</p>
        <h5 class="fw-bold text-danger" id="deleteModalExpenseNo">#12345</h5>
        <p class="small text-muted mt-2">This action cannot be undone.</p>
      </div>

      <!-- Footer -->
      <div class="modal-footer justify-content-center">
        <form id="deleteForm" method="POST" action="" class="d-flex gap-2">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-danger px-4">Yes, Delete</button>
          <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Cancel</button>
        </form>
      </div>

    </div>
  </div>
</div>