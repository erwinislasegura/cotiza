(function () {
  const base = (window.APP_BASE_PATH || '').replace(/\/$/, '');

  function normalizarInterna(url) {
    if (!url || !url.startsWith('/')) return url;
    if (url.startsWith('//')) return url;
    if (!base) return url;
    if (url === base || url.startsWith(base + '/')) return url;
    return base + url;
  }

  document.querySelectorAll('a[href]').forEach((el) => {
    const href = el.getAttribute('href');
    if (href && href.startsWith('/')) {
      el.setAttribute('href', normalizarInterna(href));
    }
  });

  document.querySelectorAll('form[action]').forEach((el) => {
    const action = el.getAttribute('action');
    if (action && action.startsWith('/')) {
      el.setAttribute('action', normalizarInterna(action));
    }
  });

  document.querySelectorAll('[data-confirmar]').forEach((el) => {
    el.addEventListener('click', (e) => {
      if (!confirm(el.dataset.confirmar || '¿Confirmas esta acción?')) {
        e.preventDefault();
      }
    });
  });
})();
