document.addEventListener('DOMContentLoaded', () => {
  const approveModal = document.getElementById('approveModal');
  const approveForm = document.getElementById('approveForm');
  const rejectForm = document.getElementById('rejectForm');
  const modalReason = document.getElementById('modalReason');
  const approveReason = document.getElementById('approveReason');
  const rejectReason = document.getElementById('rejectReason');

  approveModal.addEventListener('show.bs.modal', event => {
    const button = event.relatedTarget;
    const requestId = button.getAttribute('data-action');

    const expenseNo = button.getAttribute('data-expense-no') || '-';
    const requestDate = button.getAttribute('data-request-date') || '-';
    const requestor = button.getAttribute('data-requestor') || '-';
    const department = button.getAttribute('data-department') || '-';
    const causingDepartment = button.getAttribute('data-causing-department') || '-';
    const supplier = button.getAttribute('data-supplier') || '-';
    const amount = button.getAttribute('data-amount') || '0.00';
    const currency = button.getAttribute('data-currency') || '';
    const paymentDue = button.getAttribute('data-payment-due') || '-';
    const justification = button.getAttribute('data-justification') || '-';
    const cause = button.getAttribute('data-cause') || '-';
    const risk = button.getAttribute('data-risk') || '-';
    const description = button.getAttribute('data-description') || '-';
    const currentStatus = button.getAttribute('data-current-status') || 'In Review';
    const reason = button.getAttribute('data-reason') || '';

    approveModal.querySelector('#modalExpenseNo').textContent = expenseNo;
    approveModal.querySelector('#modalRequestDate').textContent = requestDate;
    approveModal.querySelector('#modalRequestor').textContent = requestor;
    approveModal.querySelector('#modalDepartment').textContent = department;
    approveModal.querySelector('#modalCausingDepartment').textContent = causingDepartment;
    approveModal.querySelector('#modalSupplier').textContent = supplier;
    approveModal.querySelector('#modalAmount').textContent = parseFloat(amount).toLocaleString('en-US', {
      minimumFractionDigits: 2,
      maximumFractionDigits: 2
    });
    approveModal.querySelector('#modalCurrency').textContent = currency;
    approveModal.querySelector('#modalPaymentDue').textContent = paymentDue;
    approveModal.querySelector('#modalJustification').textContent = justification;
    approveModal.querySelector('#modalCause').textContent = cause;
    approveModal.querySelector('#modalRisk').textContent = risk;
    approveModal.querySelector('#modalDescription').textContent = description;
    modalReason.value = reason;

    approveForm.action = `/requests/${requestId}/status`;
    rejectForm.action = `/requests/${requestId}/status`;

    if (currentStatus === 'Approve') {
      approveForm.style.display = 'none';
      rejectForm.style.display = 'flex';
    } else if (currentStatus === 'Reject') {
      approveForm.style.display = 'flex';
      rejectForm.style.display = 'none';
    } else {
      approveForm.style.display = 'flex';
      rejectForm.style.display = 'flex';
    }
  });

  approveForm.addEventListener('submit', () => {
    approveReason.value = modalReason.value;
  });
  rejectForm.addEventListener('submit', () => {
    rejectReason.value = modalReason.value;
  });
});
