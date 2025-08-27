// public/js/payment-requests.js
document.addEventListener('DOMContentLoaded', function() {

  // ======= VARIABLES =======
  const createModal = document.getElementById('createModal');
  const form = document.getElementById('requestForm');
  const modalLabel = document.getElementById('createModalLabel');
  const canvas = document.getElementById('signaturePad');
  const clearBtn = document.getElementById('clearSignatureBtn');
  const toggleInput = document.getElementById('toggleInput');
  const amountInput = document.getElementById('amount');
  const currencySelect = document.getElementById('currency');
  const currencySymbol = document.getElementById('currencySymbol');
  const requestIdInput = document.getElementById('requestId');
  let signaturePad = null;

  const symbols = {
    MXN: 'MX$',
    USD: '$',
    CNY: '¥'
  };

  // ======= INIT SIGNATURE PAD =======
  const initSignaturePad = () => {
    if (!canvas) return;
    const ratio = Math.max(window.devicePixelRatio || 1, 1);
    canvas.width = canvas.offsetWidth * ratio;
    canvas.height = canvas.offsetHeight * ratio;
    canvas.getContext('2d').scale(ratio, ratio);
    signaturePad = new SignaturePad(canvas, {
      penColor: '#212529',
      backgroundColor: 'rgba(255, 255, 255, 0)'
    });
  };

  // ======= TOGGLE DIGITAL SIGNATURE =======
  if (toggleInput) {
    toggleInput.addEventListener('change', () => {
      const target = document.getElementById('targetInput');
      target.classList.toggle('d-none', !toggleInput.checked);
      if (toggleInput.checked) initSignaturePad();
      else signaturePad?.clear();
    });
  }

  // ======= CLEAR SIGNATURE =======
  clearBtn?.addEventListener('click', () => signaturePad?.clear());

  // ======= FORM SUBMIT =======
  form?.addEventListener('submit', (e) => {
    document.getElementById('signature').value = (signaturePad && !signaturePad.isEmpty()) ? signaturePad.toDataURL() : '';
  });

  // ======= RESET MODAL =======
  createModal?.addEventListener('hidden.bs.modal', () => {
    modalLabel.textContent = 'New Payment Authorization';
    form.reset();
    form.action = "{{ route('requests.store') }}";
    requestIdInput.value = '';
    form.querySelector('input[name="_method"]')?.remove();
    signaturePad?.clear();
    currencySymbol.textContent = symbols[currencySelect.value] || '$';
  });

  // ======= EDIT BUTTONS =======
  document.querySelectorAll('.edit-btn').forEach(btn => {
    btn.addEventListener('click', function() {
      // Cambiar título del modal
      modalLabel.textContent = 'Edit Payment Authorization';

      // Acción del formulario
      const id = this.dataset.id;
      form.action = `/requests/${id}`;

      // Método PUT
      let methodInput = form.querySelector('input[name="_method"]');
      if (!methodInput) {
        methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        form.appendChild(methodInput);
      }
      methodInput.value = 'PUT';

      // Campos generales (opcionales)
      const campos = ['request_date', 'department', 'expense_no', 'supplier', 'amount', 'payment_due_date', 'justification', 'cause', 'risk', 'description'];
      campos.forEach(f => {
        const el = document.getElementById(f);
        if (el && this.dataset[f] !== undefined) el.value = this.dataset[f];
      });

      // Solo cargamos el Employee ID
      const requestorInput = document.getElementById('requestor');
      if (requestorInput) requestorInput.value = this.dataset.requestor;

      // Limpiar firma si aplica
      signaturePad?.clear();
      if (toggleInput?.checked) initSignaturePad();

      console.log('Requestor ID cargado:', this.dataset.requestor); // depuración
    });
  });

  // ======= DELETE MODAL =======
  const deleteModal = document.getElementById('deleteModal');
  deleteModal?.addEventListener('show.bs.modal', event => {
    const button = event.relatedTarget;
    const url = button.getAttribute('data-delete-url');
    const expenseNo = button.getAttribute('data-expense-no');

    const modalForm = document.getElementById('deleteForm');
    modalForm.action = url;

    const modalExpenseNo = document.getElementById('deleteModalExpenseNo');
    modalExpenseNo.textContent = `#${expenseNo}`;
  });

  // ======= AMOUNT INPUT FORMATTING =======
  amountInput?.addEventListener('input', function() {
    let value = this.value.replace(/,/g, '');
    value = value.replace(/[^\d.]/g, '');
    this.value = value ? Number(value).toLocaleString('en-US') : '';
  });

  // ======= CURRENCY SYMBOL UPDATE =======
  const updateCurrencySymbol = () => {
    currencySymbol.textContent = symbols[currencySelect.value] || '$';
  };
  currencySelect?.addEventListener('change', updateCurrencySymbol);
  updateCurrencySymbol();

  // ======= NO CHINESE CHARACTERS =======
  const textInputs = form.querySelectorAll('input[type="text"], textarea');
  textInputs.forEach(input => {
    input.addEventListener('input', () => {
      input.value = input.value.replace(/[\u4E00-\u9FFF]/g, '');
    });
  });

// ======= APPROVE/REJECT MODAL =======
const approveModal = document.getElementById('approveModal');

if (approveModal) {
  approveModal.addEventListener('show.bs.modal', function(event) {
    const button = event.relatedTarget;
    const status = button.getAttribute('data-status') || 'In Review';
    const id = button.getAttribute('data-id');

    // Elementos del modal
    const modalTitle = document.getElementById('approveModalTitle');
    const modalIcon = document.getElementById('approveModalIcon');

    // Llenar campos del modal
    document.getElementById('modalRequestDate').textContent = button.getAttribute('data-request-date');
    document.getElementById('modalExpenseNo').textContent = `#${button.getAttribute('data-expense-no')}`;
    document.getElementById('modalRequestor').textContent = button.getAttribute('data-requestor');
    document.getElementById('modalDepartment').textContent = button.getAttribute('data-department');
    document.getElementById('modalCausingDepartment').textContent = button.getAttribute('data-causing_department');
    document.getElementById('modalSupplier').textContent = button.getAttribute('data-supplier');
    document.getElementById('modalAmount').textContent = `${button.getAttribute('data-currency')} ${parseFloat(button.getAttribute('data-amount')).toFixed(2)}`;
    document.getElementById('modalPaymentDue').textContent = button.getAttribute('data-payment-due');
    document.getElementById('modalJustification').textContent = button.getAttribute('data-justification');
    document.getElementById('modalCause').textContent = button.getAttribute('data-cause');
    document.getElementById('modalRisk').textContent = button.getAttribute('data-risk');
    document.getElementById('modalDescription').textContent = button.getAttribute('data-description');

    const approveForm = document.getElementById('approveForm');
    const rejectForm = document.getElementById('rejectForm');

    approveForm.action = `/requests/${id}/status`;
    rejectForm.action = `/requests/${id}/status`;

    // Ajustar botones según estado actual
    switch (status) {
      case 'Approve':
        approveForm.querySelector('input[name="status"]').value = 'In Review';
        approveForm.querySelector('button').innerHTML = '<i class="bi bi-hourglass-split me-1"></i> In Review';
        rejectForm.querySelector('input[name="status"]').value = 'Reject';
        rejectForm.querySelector('button').innerHTML = '<i class="bi bi-x-circle me-1"></i> Reject';
        modalTitle.textContent = 'Approved - Choose Action';
        modalIcon.className = 'bi bi-check-circle-fill me-2 text-success';
        break;

      case 'Reject':
        approveForm.querySelector('input[name="status"]').value = 'Approve';
        approveForm.querySelector('button').innerHTML = '<i class="bi bi-check-circle me-1"></i> Approve';
        rejectForm.querySelector('input[name="status"]').value = 'In Review';
        rejectForm.querySelector('button').innerHTML = '<i class="bi bi-hourglass-split me-1"></i> In Review';
        modalTitle.textContent = 'Rejected - Choose Action';
        modalIcon.className = 'bi bi-x-circle-fill me-2 text-danger';
        break;

      default: // In Review
        approveForm.querySelector('input[name="status"]').value = 'Approve';
        approveForm.querySelector('button').innerHTML = '<i class="bi bi-check-circle me-1"></i> Approve';
        rejectForm.querySelector('input[name="status"]').value = 'Reject';
        rejectForm.querySelector('button').innerHTML = '<i class="bi bi-x-circle me-1"></i> Reject';
        modalTitle.textContent = 'In Review - Choose Action';
        modalIcon.className = 'bi bi-hourglass-split me-2 text-secondary';
        break;
    }
  });
}



// ======= EMPLOYEE autofill =======
  $(document).ready(function() {
      function fetchEmployee(employeeId) {
          employeeId = String(employeeId).trim();
          if (employeeId && employeeId.length === 7) {
              $.ajax({
                  url: '/obtain-name',
                  type: 'GET',
                  data: { id: employeeId },
                  success: function(response) {
                      console.log('[fetchEmployee] Respuesta del servidor:', response)
                      if (response && response.nombre !== 'No encontrado') {
                          $('#name').val(response.nombre);

                          if (response.departamento) {
                              $('#department').val(response.departamento);
                          } else {
                              $('#department').val('');
                          }
                      } else {
                          $('#name').val('No encontrado');
                          $('#department').val('');
                      }
                  },
                  error: function(xhr) {
                      $('#name').val('No encontrado');
                      $('#department').val('');
                  }
              });
          } else {
              $('#name').val('');
              $('#department').val('');
          }
      }
      $('#requestor').on('change', function() {
          let val = $(this).val().trim();
          fetchEmployee(val);
      });
      $('#createModal').on('show.bs.modal', function (event) {
          let button = $(event.relatedTarget); 
          let requestor = button.data('requestor');

          if (requestor) {
              $('#requestor').val(requestor);
              fetchEmployee(requestor); 
          }
      });
      let initialId = $('#requestor').val().trim();
      if (initialId) {
          fetchEmployee(initialId);
      }
  });
});

document.addEventListener('DOMContentLoaded', function() {
  const causingDept = document.getElementById('causing_department');
  const createModal = document.getElementById('createModal');
  if (createModal && causingDept) {
    createModal.addEventListener('show.bs.modal', (event) => {
      const button = event.relatedTarget;
      const isEditing = button?.dataset?.id;
      if (!isEditing) {
        causingDept.value = '';
      }
    });
  }
});
