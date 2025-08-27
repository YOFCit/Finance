<!-- Authorization Modal -->
<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="createModalLabel">
          <i class="bi bi-file-earmark-text me-2"></i> New Payment Authorization
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form id="requestForm" method="POST" enctype="multipart/form-data" action="{{ route('requests.store') }}">
        @csrf
        <input type="hidden" name="id" id="requestId" />

        <div class="modal-body" style="max-height: 60vh; overflow-y: auto;">

          <!-- Disclaimer Note -->
          <div class="alert alert-warning py-2 px-3 mb-3 border-start border-4 border-warning rounded-3">
            <h6 class="mb-1 fw-bold text-dark" style="font-size: 0.9rem;">
              <i class="bi bi-exclamation-triangle-fill me-1"></i> Important Notice
            </h6>
            <p class="small mb-0 text-muted" style="font-size: 0.78rem; line-height: 1.3;">
              The Finance Department reserves the right to reject requests lacking sufficient justification.
              All payments are subject to company policies and budget availability.
            </p>
          </div>

          <div class="row g-3">
            <!-- Request Date -->
            <div class="col-md-6">
              <label for="request_date" class="form-label">Request Date</label>
              <input type="date" class="form-control" id="request_date" name="request_date" required
                value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" readonly />
            </div>
            <!-- Employee ID -->
            <div class="col-md-6">
              <label for="requestor" class="form-label">Employee ID</label>
              <input type="number" class="form-control" id="requestor" name="requestor"
                placeholder="Enter 7-digit ID" required />
            </div>

            <!-- Employee Name -->
            <div class="col-md-6">
              <label for="name" class="form-label">Employee Name</label>
              <input type="text" class="form-control" id="name" name="name" readonly />
            </div>

            <!-- Department -->
            <div class="col-md-6">
              <label for="department" class="form-label">Submitter Department</label>
              <input type="text" class="form-control" id="department" name="department" readonly />
            </div>

            <div class="mb-3">
              <label for="causing_department" class="form-label">Causing Department</label>
              <select id="causing_department" name="causing_department" class="form-select" required>
                <option value="" disabled {{ old('causing_department', $req->causing_department ?? '') == '' ? 'selected' : '' }}>Select a department</option>
                <option value="Finance&Accounting" {{ old('causing_department', $req->causing_department ?? '') == 'Finance&Accounting' ? 'selected' : '' }}>Finance & Accounting</option>
                <option value="HR&GA" {{ old('causing_department', $req->causing_department ?? '') == 'HR&GA' ? 'selected' : '' }}>HR & GA</option>
                <option value="Supply Chain Department" {{ old('causing_department', $req->causing_department ?? '') == 'Supply Chain Department' ? 'selected' : '' }}>Supply Chain Department</option>
                <option value="Production Department" {{ old('causing_department', $req->causing_department ?? '') == 'Production Department' ? 'selected' : '' }}>Production Department</option>
                <option value="Quality Department" {{ old('causing_department', $req->causing_department ?? '') == 'Quality Department' ? 'selected' : '' }}>Quality Department</option>
              </select>
            </div>

            <!-- Expense Number -->
            <div class="col-md-6">
              <label for="expense_no" class="form-label">Expense Number</label>
              <input type="text" class="form-control text-uppercase" id="expense_no" name="expense_no"
                required maxlength="13" minlength="13" placeholder="Character limit 13..." />
            </div>

            <!-- Supplier -->
            <div class="col-md-6">
              <label for="supplier" class="form-label">Supplier</label>
              <input type="text" class="form-control" id="supplier" name="supplier" required placeholder="Supplier..." />
            </div>

            <!-- Amount + Currency -->
            <div class="col-md-6">
              <label for="amount" class="form-label">Amount</label>
              <div class="input-group">
                <span class="input-group-text" id="currencySymbol">$</span>
                <input type="text" class="form-control" id="amount" name="amount" placeholder="0.00" required />
                <select class="form-select" id="currency" name="currency" required>
                  <option value="MXN" selected>MXN</option>
                  <option value="USD">USD</option>
                  <option value="CNY">CNY</option>
                </select>
              </div>
            </div>
            <!-- Payment Due Date -->
            <div class="col-md-6">
              <label for="payment_due_date" class="form-label">Payment Due Date</label>
              <input type="date" class="form-control" id="payment_due_date" name="payment_due_date" required />
            </div>

            <script>
              // Obtener la fecha de hoy en formato YYYY-MM-DD
              const today = new Date().toISOString().split('T')[0];
              document.getElementById('payment_due_date').setAttribute('min', today);
            </script>

            <!-- Justification -->
            <div class="col-12">
              <label for="justification" class="form-label">Justification</label>
              <select id="justification" name="justification" class="form-select" required>
                <option value="" disabled selected>-- Select Justification --</option>
                <option value="Shortage of raw materials">Shortage of raw materials</option>
                <option value="Essential services">Essential services</option>
                <option value="Tax obligations / penalties">Tax obligations / penalties</option>
                <option value="Invoices past due or nearing maturity, mentioned in contract">
                  Invoices past due or nearing maturity, mentioned in contract
                </option>
                <option value="Administrative error">Administrative error</option>
                <option value="Logistics or transportation failure requiring additional expense">
                  Logistics or transportation failure requiring additional expense
                </option>
                <option value="Others">Others</option>
              </select>
            </div>

            <!-- Cause -->
            <div class="col-12">
              <label for="cause" class="form-label">Cause</label>
              <select id="cause" name="cause" class="form-select" required>
                <option value="" disabled selected>-- Select Cause --</option>
                <option value="Lack of planning by the requesting department">Lack of planning by the requesting department</option>
                <option value="Unexpected or unplanned purchase">Unexpected or unplanned purchase</option>
                <option value="Machinery or equipment failure">Machinery or equipment failure</option>
                <option value="Delay in document collection">Delay in document collection</option>
                <option value="Others">Others</option>
              </select>
            </div>

            <!-- Risk -->
            <div class="col-12">
              <label for="risk" class="form-label">Risk</label>
              <select id="risk" name="risk" class="form-select" required>
                <option value="" disabled selected>-- Select Risk --</option>
                <option value="Fine or penalty">Fine or penalty</option>
                <option value="Production interruption">Production interruption</option>
                <option value="Non-compliance of contractually agreed payment terms">Non-compliance of contractually agreed payment terms</option>
                <option value="Service suspension">Service suspension</option>
                <option value="Delivery delay">Delivery delay</option>
                <option value="Loss of early payment discount">Loss of early payment discount</option>
                <option value="Others">Others</option>
              </select>
            </div>

            <!-- Detail Explanation -->
            <div class="col-12">
              <label for="description" class="form-label">Detail Explanation</label>
              <textarea id="description" name="description" rows="3" class="form-control" required placeholder="Description..."></textarea>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="submit" id="submitBtn" class="btn btn-primary">
            <i class="bi bi-save me-1"></i> Save Request
          </button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>