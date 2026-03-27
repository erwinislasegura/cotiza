document.querySelectorAll('[data-confirmar]').forEach((el) => {
  el.addEventListener('click', (e) => {
    if (!confirm(el.dataset.confirmar || '¿Confirmas esta acción?')) {
      e.preventDefault();
    }
  });
});
