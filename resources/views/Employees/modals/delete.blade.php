<!-- MODAL ELIMINAR -->
<div class="modal fade" id="deleteModal{{ $usuario->id }}" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg-light">
      <div class="modal-header">
        <h5 class="modal-title text-danger"><i class="bi bi-exclamation-triangle me-2"></i>Confirm Delete</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body text-center">
        <p>Are you sure to delete the follow employee?</p>
        <h5 class="fw-bold text-danger">{{ $usuario->name }}</h5>
        <p class="small text-muted">This action cannot be restore</p>
      </div>
      <div class="modal-footer justify-content-center">
        <form method="POST" action="{{ route('users.destroy', $usuario->id) }}">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-danger">Yes, Delete</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </form>
      </div>
    </div>
  </div>
</div>