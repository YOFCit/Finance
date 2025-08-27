<!-- Modal -->
<div class="modal fade" id="approvalModal" tabindex="-1" aria-labelledby="approvalModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <form method="POST"
        action="{{ $approvalPerson ? route('approval-person.update', $approvalPerson->id) : route('approval-person.store') }}">
        @csrf
        @if($approvalPerson)
        @method('PUT')
        @endif

        <div class="modal-header">
          <h5 class="modal-title" id="approvalModalLabel">
            {{ $approvalPerson ? 'Edit Approval Person' : 'Create Approval Person' }}
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          <div class="mb-3">
            <label for="approved_by" class="form-label">Approved By</label>
            <input type="text" name="approved_by" id="approved_by"
              class="form-control"
              value="{{ old('approved_by', $approvalPerson->approved_by ?? '') }}" required>
          </div>

          <div class="mb-3">
            <label for="mail" class="form-label">Email</label>
            <input type="email" name="mail" id="mail"
              class="form-control"
              value="{{ old('mail', $approvalPerson->mail ?? '') }}" required>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-success">Save</button>
        </div>
      </form>

    </div>
  </div>
</div>