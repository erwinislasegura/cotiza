<header class="topbar border-bottom bg-white px-3 py-2 d-flex justify-content-between align-items-center">
  <div>
    <strong class="small d-block"><?= e(usuario_actual()['nombre'] ?? 'Invitado') ?></strong>
    <span class="text-muted small">Panel comercial SaaS</span>
  </div>
  <form method="POST" action="<?= e(url('/cerrar-sesion')) ?>" class="m-0"><?= csrf_campo() ?><button class="btn btn-sm btn-outline-secondary"><i class="bi bi-box-arrow-right"></i> Salir</button></form>
</header>
