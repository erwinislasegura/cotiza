<h1 class="h5">Crear producto</h1>
<form method="POST" class="row g-2">
  <?= csrf_campo() ?>
  <div class="col-md-3"><input class="form-control" name="codigo" placeholder="Código interno" required></div>
  <div class="col-md-3"><input class="form-control" name="sku" placeholder="SKU"></div>
  <div class="col-md-3"><input class="form-control" name="codigo_barras" placeholder="Código de barras"></div>
  <div class="col-md-3"><input class="form-control" name="nombre" placeholder="Nombre" required></div>
  <div class="col-md-5"><input class="form-control" name="descripcion" placeholder="Descripción"></div>
  <div class="col-md-3">
    <select class="form-select" name="unidad">
      <?php foreach (['unidad','kg','g','lb','litro','ml','metro','cm','caja','paquete','servicio','hora'] as $unidad): ?>
        <option value="<?= e($unidad) ?>" <?= $unidad === 'unidad' ? 'selected' : '' ?>><?= e(ucfirst($unidad)) ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="col-md-2"><input class="form-control" type="number" step="0.01" min="0" name="precio" placeholder="Precio"></div>
  <div class="col-md-2"><input class="form-control" type="number" step="0.01" min="0" name="impuesto" value="19" placeholder="Impuesto"></div>
  <div class="col-md-2"><input class="form-control" type="number" step="0.01" min="0" name="stock_minimo" value="0" placeholder="Stock mínimo"></div>
  <div class="col-md-2"><input class="form-control" type="number" step="0.01" min="0" name="stock_aviso" value="0" placeholder="Stock aviso"></div>
  <div class="col-md-2"><select name="estado" class="form-select"><option value="activo">Activo</option><option value="inactivo">Inactivo</option></select></div>
  <div class="col-12"><button class="btn btn-primary btn-sm">Guardar</button></div>
</form>
