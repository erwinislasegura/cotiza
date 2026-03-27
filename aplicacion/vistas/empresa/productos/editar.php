<h1 class="h4 mb-3">Editar producto/servicio</h1>
<div class="card">
  <div class="card-body">
    <form method="POST" class="row g-2">
      <?= csrf_campo() ?>
      <div class="col-md-2"><label class="form-label">Tipo</label><select name="tipo" class="form-select"><option value="producto" <?= $producto['tipo']==='producto'?'selected':'' ?>>Producto</option><option value="servicio" <?= $producto['tipo']==='servicio'?'selected':'' ?>>Servicio</option></select></div>
      <div class="col-md-3"><label class="form-label">Categoría</label><select name="categoria_id" class="form-select"><option value="">Sin categoría</option><?php foreach($categorias as $cat): ?><option value="<?= (int)$cat['id'] ?>" <?= (int)$producto['categoria_id']===(int)$cat['id']?'selected':'' ?>><?= e($cat['nombre']) ?></option><?php endforeach; ?></select></div>
      <div class="col-md-2"><label class="form-label">Código interno</label><input name="codigo" class="form-control" value="<?= e($producto['codigo']) ?>"></div>
      <div class="col-md-2"><label class="form-label">SKU</label><input name="sku" class="form-control" value="<?= e($producto['sku'] ?? '') ?>"></div>
      <div class="col-md-3"><label class="form-label">Código de barras</label><input name="codigo_barras" class="form-control" value="<?= e($producto['codigo_barras'] ?? '') ?>"></div>
      <div class="col-md-3"><label class="form-label">Nombre</label><input name="nombre" class="form-control" value="<?= e($producto['nombre']) ?>"></div>
      <div class="col-md-3"><label class="form-label">Descripción</label><input name="descripcion" class="form-control" value="<?= e($producto['descripcion']) ?>"></div>
      <div class="col-md-2">
        <label class="form-label">Unidad</label>
        <select name="unidad" class="form-select">
          <?php foreach (['unidad','kg','g','lb','litro','ml','metro','cm','caja','paquete','servicio','hora'] as $unidad): ?>
            <option value="<?= e($unidad) ?>" <?= ($producto['unidad'] ?? '') === $unidad ? 'selected' : '' ?>><?= e(ucfirst($unidad)) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-2"><label class="form-label">Precio</label><input type="number" step="0.01" min="0" name="precio" class="form-control" value="<?= e((string)$producto['precio']) ?>"></div>
      <div class="col-md-2"><label class="form-label">Costo</label><input type="number" step="0.01" min="0" name="costo" class="form-control" value="<?= e((string)$producto['costo']) ?>"></div>
      <div class="col-md-2"><label class="form-label">Impuesto</label><input type="number" step="0.01" min="0" name="impuesto" class="form-control" value="<?= e((string)$producto['impuesto']) ?>"></div>
      <div class="col-md-2"><label class="form-label">Desc. máximo</label><input type="number" step="0.01" min="0" name="descuento_maximo" class="form-control" value="<?= e((string)$producto['descuento_maximo']) ?>"></div>
      <div class="col-md-2"><label class="form-label">Stock mínimo</label><input type="number" step="0.01" min="0" name="stock_minimo" class="form-control" value="<?= e((string)($producto['stock_minimo'] ?? 0)) ?>"></div>
      <div class="col-md-2"><label class="form-label">Stock de aviso</label><input type="number" step="0.01" min="0" name="stock_aviso" class="form-control" value="<?= e((string)($producto['stock_aviso'] ?? 0)) ?>"></div>
      <div class="col-md-2"><label class="form-label">Estado</label><select name="estado" class="form-select"><option value="activo" <?= $producto['estado']==='activo'?'selected':'' ?>>Activo</option><option value="inactivo" <?= $producto['estado']==='inactivo'?'selected':'' ?>>Inactivo</option></select></div>
      <div class="col-12"><button class="btn btn-primary btn-sm">Guardar cambios</button> <a class="btn btn-outline-secondary btn-sm" href="<?= e(url('/app/productos')) ?>">Cancelar</a></div>
    </form>
  </div>
</div>
