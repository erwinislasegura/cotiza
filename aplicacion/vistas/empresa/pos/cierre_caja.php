<div class="d-flex justify-content-between align-items-center mb-3"><h1 class="h4 mb-0">Cierre de caja</h1><a class="btn btn-outline-secondary btn-sm" href="<?= e(url('/app/punto-venta')) ?>">Volver al POS</a></div>

<?php if (!$apertura): ?>
  <div class="alert alert-warning">No tienes caja abierta para cerrar.</div>
<?php else: ?>
  <?php $esperado = (float) $apertura['monto_inicial'] + (float) ($resumen['total_ventas'] ?? 0); ?>
  <div class="card mb-3"><div class="card-body">
    <h2 class="h6">Arqueo básico</h2>
    <ul class="small mb-3">
      <li>Monto inicial: <strong>$ <?= number_format((float) $apertura['monto_inicial'], 2) ?></strong></li>
      <li>Ventas efectivo: <strong>$ <?= number_format((float) ($resumen['efectivo'] ?? 0), 2) ?></strong></li>
      <li>Ventas transferencia: <strong>$ <?= number_format((float) ($resumen['transferencia'] ?? 0), 2) ?></strong></li>
      <li>Ventas tarjeta: <strong>$ <?= number_format((float) ($resumen['tarjeta'] ?? 0), 2) ?></strong></li>
      <li>Total esperado: <strong>$ <?= number_format($esperado, 2) ?></strong></li>
    </ul>
    <form method="POST" class="row g-2" action="<?= e(url('/app/punto-venta/cierre-caja')) ?>"><?= csrf_campo() ?>
      <div class="col-md-4"><label class="form-label">Monto contado</label><input class="form-control" type="number" step="0.01" min="0" name="monto_contado" required></div>
      <div class="col-md-8"><label class="form-label">Observación de cierre</label><input class="form-control" name="observacion"></div>
      <div class="col-12"><button class="btn btn-danger">Cerrar caja</button></div>
    </form>
  </div></div>
<?php endif; ?>

<div class="card"><div class="card-body"><h2 class="h6">Historial de cierres</h2>
<div class="table-responsive"><table class="table table-sm"><thead><tr><th>#</th><th>Caja</th><th>Fecha cierre</th><th>Esperado</th><th>Contado</th><th>Diferencia</th></tr></thead><tbody>
<?php foreach ($historialCierres as $cierre): ?>
<tr><td><?= (int) $cierre['id'] ?></td><td><?= e($cierre['caja_nombre']) ?></td><td><?= e($cierre['fecha_cierre']) ?></td><td>$ <?= number_format((float) $cierre['monto_esperado'], 2) ?></td><td>$ <?= number_format((float) $cierre['monto_contado'], 2) ?></td><td class="<?= (float)$cierre['diferencia'] < 0 ? 'text-danger':'text-success' ?>">$ <?= number_format((float) $cierre['diferencia'], 2) ?></td></tr>
<?php endforeach; ?>
</tbody></table></div></div></div>
