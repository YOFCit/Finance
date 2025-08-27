// public/js/toast.js
document.addEventListener('DOMContentLoaded', () => {
  const toastElList = document.querySelectorAll('.toast');
  toastElList.forEach(toastEl => {
    const toast = new bootstrap.Toast(toastEl, {
      delay: 3000,
      autohide: true
    });
    toast.show();
  });
});
