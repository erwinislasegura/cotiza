<h1 class="h5 mb-3">Pagos</h1>
<div class="card">
  <div class="card-header"><strong>Listado de pagos</strong><div class="small text-muted">Registros encontrados: <?= count($pagos) ?></div></div>
  <div class="table-responsive" style="overflow: visible;"><table class="table table-sm table-hover mb-0 tabla-admin"><thead class="table-light"><tr><th>Empresa</th><th>Monto</th><th>Método</th><th>Estado</th><th>Fecha</th></tr></thead><tbody><?php if (empty($pagos)): ?><tr><td colspan="5" class="text-center py-4 text-muted">No hay pagos registrados.</td></tr><?php else: foreach($pagos as $p): ?><tr><td><?= e($p['empresa']) ?></td><td>$<?= number_format((float)$p['monto'],2) ?> <?= e($p['moneda']) ?></td><td><?= e($p['metodo']) ?></td><td><span class="badge text-bg-light"><?= e($p['estado']) ?></span></td><td><?= e($p['fecha_pago']) ?></td></tr><?php endforeach; endif; ?></tbody></table></div>
</div>
