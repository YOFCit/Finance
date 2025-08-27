<div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title text-success" id="approveModalLabel">
          <i class="bi me-2" id="approveModalIcon"></i>
          <span id="approveModalTitle">Approve Request</span>
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body" style="max-height: 60vh; overflow-y: auto;">
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label fw-bold">Request Date</label>
            <p class="form-control-plaintext" id="modalRequestDate"></p>
          </div>
          <div class="col-md-6">
            <label class="form-label fw-bold">Employee</label>
            <p class="form-control-plaintext" id="modalRequestor"></p>
          </div>
          <div class="col-md-6">
            <label class="form-label fw-bold">Submitter Department</label>
            <p class="form-control-plaintext" id="modalDepartment"></p>
          </div>
          <div class="col-md-6">
            <label class="form-label fw-bold">Causing Department</label>
            <p class="form-control-plaintext" id="modalCausingDepartment"></p>
          </div>
          <div class="col-md-6">
            <label class="form-label fw-bold">Expense Number</label>
            <p class="form-control-plaintext" id="modalExpenseNo"></p>
          </div>
          <div class="col-md-6">
            <label class="form-label fw-bold">Supplier</label>
            <p class="form-control-plaintext" id="modalSupplier"></p>
          </div>
          <div class="col-md-6">
            <label class="form-label fw-bold">Amount</label>
            <p class="form-control-plaintext" id="modalAmount"></p>
          </div>
          <div class="col-md-6">
            <label class="form-label fw-bold">Payment Due Date</label>
            <p class="form-control-plaintext" id="modalPaymentDue"></p>
          </div>
          <div class="col-md-6">
            <label class="form-label fw-bold">Justification</label>
            <p class="form-control-plaintext" id="modalJustification"></p>
          </div>
          <div class="col-md-6">
            <label class="form-label fw-bold">Cause</label>
            <p class="form-control-plaintext" id="modalCause"></p>
          </div>
          <div class="col-12">
            <label class="form-label fw-bold">Risk</label>
            <p class="form-control-plaintext" id="modalRisk"></p>
          </div>
          <div class="col-12">
            <label class="form-label fw-bold">Detail Explanation</label>
            <pre id="modalDescription"
              class="mb-0 p-2 border rounded"
              style="white-space: pre-wrap; word-wrap: break-word; font-family: inherit; max-height: 100px; overflow-y: auto;">
  </pre>
          </div>
        </div>
      </div>

      <div class="modal-footer d-flex justify-content-between gap-2">
        <!-- Botón Aprobar -->
        <form id="approveForm" method="POST" action="" class="flex-grow-1">
          @csrf
          @method('PATCH')
          <input type="hidden" name="status" value="Approve">
          <button type="submit" class="btn btn-success w-100">
            <i class="bi bi-check-circle me-1"></i> Approve
          </button>
        </form>

        <!-- Botón Rechazar -->
        <form id="rejectForm" method="POST" action="" class="flex-grow-1">
          @csrf
          @method('PATCH')
          <input type="hidden" name="status" value="Reject">
          <button type="submit" class="btn btn-danger w-100">
            <i class="bi bi-x-circle me-1"></i> Reject
          </button>
        </form>
      </div>


    </div>
  </div>
</div>