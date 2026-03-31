<h1 class="h4 mb-3">Proveedores</h1>
<div class="card mb-3"><div class="card-header">Nuevo proveedor</div><div class="card-body">
<form method="POST" action="<?= e(url('/app/inventario/proveedores')) ?>" class="row g-2"><?= csrf_campo() ?>
<div class="col-md-4"><label class="form-label">Nombre proveedor</label><input name="nombre" class="form-control" required></div>
<div class="col-md-2"><label class="form-label">RUT/NIT</label><input name="identificador_fiscal" class="form-control"></div>
<div class="col-md-3"><label class="form-label">Contacto</label><input name="contacto" class="form-control"></div>
<div class="col-md-3"><label class="form-label">Correo</label><input type="email" name="correo" class="form-control"></div>
<div class="col-md-3"><label class="form-label">Teléfono</label><input name="telefono" class="form-control"></div>
<div class="col-md-3"><label class="form-label">Ciudad</label><input name="ciudad" class="form-control"></div>
<div class="col-md-4"><label class="form-label">Dirección</label><input name="direccion" class="form-control"></div>
<div class="col-md-2"><label class="form-label">Estado</label><select name="estado" class="form-select"><option value="activo">Activo</option><option value="inactivo">Inactivo</option></select></div>
<div class="col-12"><label class="form-label">Observación</label><input name="observacion" class="form-control"></div>
<div class="col-12"><button class="btn btn-primary btn-sm">Guardar proveedor</button></div>
</form></div></div>

<div class="card"><div class="card-header">Listado de proveedores</div><div class="table-responsive"><table class="table table-sm mb-0"><thead><tr><th>Nombre</th><th>RUT/NIT</th><th>Contacto</th><th>Correo</th><th>Teléfono</th><th>Ciudad</th><th>Estado</th></tr></thead><tbody>
<?php if(empty($proveedores)): ?><tr><td colspan="7" class="text-center text-muted py-3">No hay proveedores registrados.</td></tr><?php else: foreach($proveedores as $p): ?><tr><td><?= e($p['nombre']) ?></td><td><?= e($p['identificador_fiscal'] ?? '-') ?></td><td><?= e($p['contacto'] ?? '-') ?></td><td><?= e($p['correo'] ?? '-') ?></td><td><?= e($p['telefono'] ?? '-') ?></td><td><?= e($p['ciudad'] ?? '-') ?></td><td><?= e($p['estado'] ?? 'activo') ?></td></tr><?php endforeach; endif; ?>
</tbody></table></div></div>
