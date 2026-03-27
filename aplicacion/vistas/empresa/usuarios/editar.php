<h1 class="h4 mb-3">Editar usuario</h1>
<div class="card"><div class="card-body"><form method="POST" class="row g-2"><?= csrf_campo() ?>
<div class="col-md-4"><label class="form-label">Nombre</label><input name="nombre" class="form-control" value="<?= e($usuario['nombre']) ?>"></div>
<div class="col-md-4"><label class="form-label">Correo</label><input name="correo" class="form-control" value="<?= e($usuario['correo']) ?>"></div>
<div class="col-md-2"><label class="form-label">Rol</label><select name="rol_id" class="form-select"><?php foreach($roles as $rol): ?><option value="<?= (int)$rol['id'] ?>" <?= (int)$usuario['rol_id']===(int)$rol['id']?'selected':'' ?>><?= e($rol['nombre']) ?></option><?php endforeach; ?></select></div>
<div class="col-md-2"><label class="form-label">Estado</label><select name="estado" class="form-select"><option value="activo" <?= $usuario['estado']==='activo'?'selected':'' ?>>Activo</option><option value="inactivo" <?= $usuario['estado']==='inactivo'?'selected':'' ?>>Inactivo</option></select></div>
<div class="col-12"><button class="btn btn-primary btn-sm">Guardar cambios</button> <a class="btn btn-outline-secondary btn-sm" href="<?= e(url('/app/usuarios')) ?>">Cancelar</a></div>
</form></div></div>
