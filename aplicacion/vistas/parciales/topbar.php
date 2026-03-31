<header class="topbar border-bottom bg-white px-3 py-2 d-flex justify-content-between align-items-center">
  <div>
    <strong class="small d-block"><?= e(usuario_actual()['nombre'] ?? 'Invitado') ?></strong>
    <span class="text-muted small">Panel comercial SaaS</span>
    <?php $resumenPlan = resumen_plan_empresa_actual(); ?>
    <div class="small mt-1">
      <?php if (!$resumenPlan): ?>
        <span class="badge text-bg-warning">Sin suscripción vigente</span>
      <?php else: ?>
        <span class="fw-semibold"><?= e($resumenPlan['plan_nombre'] ?? 'Plan no definido') ?></span>
        <?php $dias = $resumenPlan['dias_restantes']; ?>
        <?php if ($dias === null): ?>
          <span class="badge text-bg-secondary ms-2"><?= e((string) ($resumenPlan['estado'] ?? 'sin estado')) ?></span>
        <?php elseif ((int) $dias < 0): ?>
          <span class="badge text-bg-danger ms-2">Vencida hace <?= e((string) abs((int) $dias)) ?> días</span>
        <?php else: ?>
          <span class="badge text-bg-info ms-2"><?= e((string) $dias) ?> días restantes</span>
        <?php endif; ?>
      <?php endif; ?>
    </div>
  </div>
  <div class="d-flex align-items-center gap-2">
    <?php if (!empty(usuario_actual()['id'])): ?>
      <a class="btn btn-sm btn-outline-primary" href="<?= e(url('/app/usuarios/editar/' . (int) usuario_actual()['id'])) ?>"><i class="bi bi-person-gear"></i> Mi perfil</a>
    <?php endif; ?>
    <form method="POST" action="<?= e(url('/cerrar-sesion')) ?>" class="m-0"><?= csrf_campo() ?><button class="btn btn-sm btn-outline-secondary"><i class="bi bi-box-arrow-right"></i> Salir</button></form>
  </div>
</header>
