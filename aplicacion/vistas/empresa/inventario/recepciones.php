<h1 class="h4 mb-3">Recepciones de inventario</h1>
<div class="card mb-3"><div class="card-header">Nueva recepción desde proveedor</div><div class="card-body">
<form method="POST" action="<?= e(url('/app/inventario/recepciones')) ?>" class="row g-2"><?= csrf_campo() ?>
<div class="col-md-3"><label class="form-label">Proveedor</label><select name="proveedor_id" class="form-select"><option value="0">Seleccionar...</option><?php foreach($proveedores as $pr): ?><option value="<?= (int)$pr['id'] ?>"><?= e($pr['nombre']) ?></option><?php endforeach; ?></select><div class="form-text">Si no existe, ingrésalo abajo.</div></div>
<div class="col-md-3"><label class="form-label">Proveedor nuevo (opcional)</label><input name="proveedor_nuevo" class="form-control" placeholder="Nombre proveedor"></div>
<div class="col-md-2"><label class="form-label">Tipo documento</label><select name="tipo_documento" class="form-select"><option value="guia_despacho">Guía de despacho</option><option value="factura">Factura</option></select></div>
<div class="col-md-2"><label class="form-label">N° documento</label><input name="numero_documento" class="form-control" required></div>
<div class="col-md-2"><label class="form-label">Fecha documento</label><input type="date" name="fecha_documento" class="form-control" value="<?= e(date('Y-m-d')) ?>" required></div>
<div class="col-md-3"><label class="form-label">Referencia interna</label><input name="referencia_interna" class="form-control"></div>
<div class="col-md-9"><label class="form-label">Observación</label><input name="observacion" class="form-control"></div>
<div class="col-12"><div class="table-responsive"><table class="table table-sm"><thead><tr><th>Producto</th><th>Cantidad</th><th>Costo unitario</th></tr></thead><tbody>
<?php for($i=0;$i<5;$i++): ?><tr><td><select name="producto_id[]" class="form-select form-select-sm"><option value="">Seleccionar...</option><?php foreach($productos as $p): ?><option value="<?= (int)$p['id'] ?>"><?= e($p['codigo'] . ' · ' . $p['nombre']) ?></option><?php endforeach; ?></select></td><td><input type="number" step="0.01" min="0" name="cantidad[]" class="form-control form-control-sm"></td><td><input type="number" step="0.01" min="0" name="costo_unitario[]" class="form-control form-control-sm"></td></tr><?php endfor; ?>
</tbody></table></div></div>
<div class="col-12"><button class="btn btn-primary btn-sm">Guardar recepción</button></div>
</form></div></div>

<div class="card"><div class="card-header">Historial de recepciones</div><div class="table-responsive"><table class="table table-sm mb-0"><thead><tr><th>Fecha</th><th>Proveedor</th><th>Documento</th><th>Número</th><th>Usuario</th><th class="text-end">Acción</th></tr></thead><tbody>
<?php if(empty($recepciones)): ?><tr><td colspan="6" class="text-center text-muted py-3">Sin recepciones registradas.</td></tr><?php else: foreach($recepciones as $r): ?><tr><td><?= e($r['fecha_creacion']) ?></td><td><?= e($r['proveedor_nombre'] ?? 'Sin proveedor') ?></td><td><?= e($r['tipo_documento']) ?></td><td><?= e($r['numero_documento']) ?></td><td><?= e($r['usuario_nombre'] ?? '-') ?></td><td class="text-end"><a class="btn btn-outline-secondary btn-sm" href="<?= e(url('/app/inventario/recepciones/ver/' . $r['id'])) ?>">Ver detalle</a></td></tr><?php endforeach; endif; ?>
</tbody></table></div></div>
