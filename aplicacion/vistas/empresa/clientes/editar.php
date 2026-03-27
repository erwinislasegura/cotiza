<h1 class="h4 mb-3">Editar cliente</h1>
<div class="card"><div class="card-body"><form method="POST" class="row g-2"><?= csrf_campo() ?>
<div class="col-md-3"><label class="form-label">Razón social</label><input name="razon_social" class="form-control" value="<?= e($cliente['razon_social']) ?>"></div>
<div class="col-md-3"><label class="form-label">Nombre comercial</label><input name="nombre_comercial" class="form-control" value="<?= e($cliente['nombre_comercial']) ?>"></div>
<div class="col-md-2"><label class="form-label">ID fiscal</label><input name="identificador_fiscal" class="form-control" value="<?= e($cliente['identificador_fiscal']) ?>"></div>
<div class="col-md-2"><label class="form-label">Giro</label><input name="giro" class="form-control" value="<?= e($cliente['giro']) ?>"></div>
<div class="col-md-2"><label class="form-label">Estado</label><select name="estado" class="form-select"><option value="activo" <?= $cliente['estado']==='activo'?'selected':'' ?>>Activo</option><option value="inactivo" <?= $cliente['estado']==='inactivo'?'selected':'' ?>>Inactivo</option></select></div>
<div class="col-md-3"><label class="form-label">Correo</label><input name="correo" class="form-control" value="<?= e($cliente['correo']) ?>"></div>
<div class="col-md-2"><label class="form-label">Teléfono</label><input name="telefono" class="form-control" value="<?= e($cliente['telefono']) ?>"></div>
<div class="col-md-3"><label class="form-label">Dirección</label><input name="direccion" class="form-control" value="<?= e($cliente['direccion']) ?>"></div>
<div class="col-md-2"><label class="form-label">Ciudad</label><input name="ciudad" class="form-control" value="<?= e($cliente['ciudad']) ?>"></div>
<div class="col-md-2"><label class="form-label">Vendedor</label><select name="vendedor_id" class="form-select"><option value="">Sin asignar</option><?php foreach($vendedores as $v): ?><option value="<?= (int)$v['id'] ?>" <?= (int)$cliente['vendedor_id']===(int)$v['id'] ? 'selected' : '' ?>><?= e($v['nombre']) ?></option><?php endforeach; ?></select></div>
<div class="col-md-12"><label class="form-label">Observaciones</label><textarea name="notas" class="form-control" rows="2"><?= e($cliente['notas']) ?></textarea></div>
<div class="col-12"><button class="btn btn-primary btn-sm">Guardar cambios</button> <a class="btn btn-outline-secondary btn-sm" href="<?= e(url('/app/clientes')) ?>">Cancelar</a></div>
</form></div></div>
