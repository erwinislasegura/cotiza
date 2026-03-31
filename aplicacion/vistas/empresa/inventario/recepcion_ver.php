<h1 class="h4 mb-3">Detalle recepción #<?= (int)$recepcion['id'] ?></h1>
<div class="card mb-3"><div class="card-body row g-2">
<div class="col-md-3"><strong>Proveedor:</strong><div><?= e($recepcion['proveedor_nombre'] ?? '-') ?></div></div>
<div class="col-md-3"><strong>Documento:</strong><div><?= e($recepcion['tipo_documento']) ?> #<?= e($recepcion['numero_documento']) ?></div></div>
<div class="col-md-2"><strong>Fecha doc:</strong><div><?= e($recepcion['fecha_documento']) ?></div></div>
<div class="col-md-2"><strong>Usuario:</strong><div><?= e($recepcion['usuario_nombre'] ?? '-') ?></div></div>
<div class="col-md-2"><strong>Registro:</strong><div><?= e($recepcion['fecha_creacion']) ?></div></div>
<div class="col-12"><strong>Referencia:</strong> <?= e($recepcion['referencia_interna'] ?? '-') ?></div>
<div class="col-12"><strong>Observación:</strong> <?= e($recepcion['observacion'] ?? '-') ?></div>
</div></div>
<div class="card"><div class="card-header">Productos recepcionados</div><div class="table-responsive"><table class="table table-sm mb-0"><thead><tr><th>Producto</th><th>Cantidad</th><th>Costo</th><th>Subtotal</th></tr></thead><tbody>
<?php foreach($recepcion['detalles'] as $d): ?><tr><td><?= e(($d['codigo'] ?? '') . ' · ' . ($d['nombre'] ?? '')) ?></td><td><?= number_format((float)$d['cantidad'],2) ?></td><td>$<?= number_format((float)$d['costo_unitario'],2) ?></td><td>$<?= number_format((float)$d['subtotal'],2) ?></td></tr><?php endforeach; ?>
</tbody></table></div></div>
