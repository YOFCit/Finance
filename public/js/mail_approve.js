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

    // Todos los atributos del botón con fallback si no existen
    const expenseNo = button.getAttribute('data-expense-no') || '-';
    const requestDate = button.getAttribute('data-request-date') || '-';
    const requestor = button.getAttribute('data-requestor') || '-';
    const requestorName = button.getAttribute('data-requestor-name') || '-';
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

    // Asignar valores a elementos del modal
    approveModal.querySelector('#modalExpenseNo').textContent = expenseNo;
    approveModal.querySelector('#modalRequestDate').textContent = requestDate;
    approveModal.querySelector('#modalRequestor').textContent = requestor;
    approveModal.querySelector('#modalRequestorname').textContent = requestorName;
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

    // Asignar acción de los formularios
    approveForm.action = `/requests/${requestId}/status`;
    rejectForm.action = `/requests/${requestId}/status`;

    // Mostrar/ocultar formularios según el estado
    if (currentStatus === 'Approve' || currentStatus === 'Reject') {
      approveForm.style.display = 'none';
      rejectForm.style.display = 'none';
      modalReason.disabled = true;
    } else {
      approveForm.style.display = 'flex';
      rejectForm.style.display = 'flex';
      modalReason.disabled = false;
    }
  });

  // Pasar razón al enviar cada formulario
  approveForm.addEventListener('submit', () => {
    approveReason.value = modalReason.value;
  });
  rejectForm.addEventListener('submit', () => {
    rejectReason.value = modalReason.value;
  });
});
