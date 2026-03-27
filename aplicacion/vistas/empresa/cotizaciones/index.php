<div class="d-flex justify-content-between align-items-center mb-3">
  <h1 class="h4 mb-0">Cotizaciones</h1>
  <a href="<?= e(url('/app/cotizaciones/crear')) ?>" class="btn btn-primary btn-sm">Nueva cotización</a>
</div>

<div class="card">
  <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
    <div>
      <strong>Listado de cotizaciones</strong>
      <div class="small text-muted">Registros encontrados: <?= count($cotizaciones) ?></div>
    </div>
    <form method="GET" class="d-flex gap-2">
      <input class="form-control form-control-sm" name="q" value="<?= e($buscar ?? '') ?>" placeholder="Buscar por número/cliente">
      <button class="btn btn-outline-secondary btn-sm">Buscar</button>
      <?php if (($buscar ?? '') !== ''): ?>
        <a class="btn btn-outline-dark btn-sm" href="<?= e(url('/app/cotizaciones')) ?>">Limpiar</a>
      <?php endif; ?>
    </form>
  </div>
  <div class="table-responsive" style="overflow: visible;">
    <table class="table table-hover table-sm mb-0 tabla-admin">
      <thead class="table-light"><tr><th>Número</th><th>Cliente</th><th>Emisión</th><th>Vencimiento</th><th>Vendedor</th><th>Subtotal</th><th>Impuesto</th><th>Total</th><th>Estado</th><th class="text-end">Acciones</th></tr></thead>
      <tbody>
      <?php if (empty($cotizaciones)): ?>
        <tr><td colspan="10" class="text-center py-4 text-muted">No hay cotizaciones registradas con este criterio.</td></tr>
      <?php else: ?>
        <?php foreach($cotizaciones as $c): ?>
          <tr><td><?= e($c['numero']) ?></td><td><?= e($c['cliente']) ?></td><td><?= e($c['fecha_emision']) ?></td><td><?= e($c['fecha_vencimiento']) ?></td><td><?= e($c['vendedor']) ?></td><td>$<?= number_format((float)$c['subtotal'],2) ?></td><td>$<?= number_format((float)$c['impuesto'],2) ?></td><td>$<?= number_format((float)$c['total'],2) ?></td><td><?php $e = $c['estado']; ?><span class="badge <?= $e === 'aprobada' ? 'badge-estado-aprobada' : ($e === 'rechazada' ? 'badge-estado-rechazada' : 'badge-estado-pendiente') ?>"><?= e($e) ?></span></td><td class="text-end"><div class="dropdown dropup"><button class="btn btn-light btn-sm dropdown-toggle" data-bs-toggle="dropdown">Acciones</button><ul class="dropdown-menu dropdown-menu-end"><li><a class="dropdown-item" href="<?= e(url('/app/cotizaciones/ver/' . $c['id'])) ?>">Ver</a></li><li><a class="dropdown-item" href="<?= e(url('/app/cotizaciones/editar/' . $c['id'])) ?>">Editar</a></li><li><a class="dropdown-item" href="#">Duplicar</a></li><li><a class="dropdown-item" target="_blank" href="<?= e(url('/app/cotizaciones/imprimir/' . $c['id'])) ?>">Imprimir formato</a></li><li><a class="dropdown-item" href="#">Enviar</a></li><li><a class="dropdown-item" href="#">Cambiar estado</a></li><li><form method="POST" action="<?= e(url('/app/cotizaciones/eliminar/' . $c['id'])) ?>" onsubmit="return confirm('¿Confirmas eliminar esta cotización?')"><?= csrf_campo() ?><button class="dropdown-item text-danger" type="submit">Eliminar</button></form></li></ul></div></td></tr>
        <?php endforeach; ?>
      <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
