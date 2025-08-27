document.addEventListener('DOMContentLoaded', () => {
  const requestorInput = document.getElementById('requestor');
  if(!requestorInput) return;

  requestorInput.addEventListener('input', () => {
    // Limitar a máximo 7 dígitos
    if(requestorInput.value.length > 7){
      requestorInput.value = requestorInput.value.slice(0,7);
    }
  });
});
