<h1 class="h4 mb-3">Editar producto/servicio</h1>
<div class="card"><div class="card-body"><form method="POST" class="row g-2"><?= csrf_campo() ?>
<div class="col-md-2"><label class="form-label">Tipo</label><select name="tipo" class="form-select"><option value="producto" <?= $producto['tipo']==='producto'?'selected':'' ?>>Producto</option><option value="servicio" <?= $producto['tipo']==='servicio'?'selected':'' ?>>Servicio</option></select></div>
<div class="col-md-2"><label class="form-label">Categoría</label><select name="categoria_id" class="form-select"><option value="">Sin categoría</option><?php foreach($categorias as $cat): ?><option value="<?= (int)$cat['id'] ?>" <?= (int)$producto['categoria_id']===(int)$cat['id']?'selected':'' ?>><?= e($cat['nombre']) ?></option><?php endforeach; ?></select></div>
<div class="col-md-2"><label class="form-label">Código</label><input name="codigo" class="form-control" value="<?= e($producto['codigo']) ?>"></div>
<div class="col-md-3"><label class="form-label">Nombre</label><input name="nombre" class="form-control" value="<?= e($producto['nombre']) ?>"></div>
<div class="col-md-3"><label class="form-label">Descripción</label><input name="descripcion" class="form-control" value="<?= e($producto['descripcion']) ?>"></div>
<div class="col-md-2"><label class="form-label">Unidad</label><input name="unidad" class="form-control" value="<?= e($producto['unidad']) ?>"></div>
<div class="col-md-2"><label class="form-label">Precio</label><input name="precio" class="form-control" value="<?= e((string)$producto['precio']) ?>"></div>
<div class="col-md-2"><label class="form-label">Costo</label><input name="costo" class="form-control" value="<?= e((string)$producto['costo']) ?>"></div>
<div class="col-md-2"><label class="form-label">Impuesto</label><input name="impuesto" class="form-control" value="<?= e((string)$producto['impuesto']) ?>"></div>
<div class="col-md-2"><label class="form-label">Desc. máximo</label><input name="descuento_maximo" class="form-control" value="<?= e((string)$producto['descuento_maximo']) ?>"></div>
<div class="col-md-2"><label class="form-label">Estado</label><select name="estado" class="form-select"><option value="activo" <?= $producto['estado']==='activo'?'selected':'' ?>>Activo</option><option value="inactivo" <?= $producto['estado']==='inactivo'?'selected':'' ?>>Inactivo</option></select></div>
<div class="col-12"><button class="btn btn-primary btn-sm">Guardar cambios</button> <a class="btn btn-outline-secondary btn-sm" href="<?= e(url('/app/productos')) ?>">Cancelar</a></div>
</form></div></div>
