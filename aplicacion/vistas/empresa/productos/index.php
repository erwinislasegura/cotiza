<section class="modulo-head d-flex justify-content-between align-items-center mb-3">
  <h1 class="h4 mb-0">Servicios / Productos</h1>
  <a href="<?= e(url('/app/categorias')) ?>" class="btn btn-outline-primary btn-sm">Gestionar categorías</a>
</section>

<div class="card mb-3">
  <div class="card-header">Nuevo ítem comercial</div>
  <div class="card-body">
    <form method="POST" action="<?= e(url('/app/productos')) ?>" class="row g-2">
      <?= csrf_campo() ?>
      <div class="col-md-2">
        <label class="form-label">Tipo</label>
        <select name="tipo" class="form-select">
          <option value="producto">Producto</option>
          <option value="servicio">Servicio</option>
        </select>
      </div>
      <div class="col-md-3">
        <div class="d-flex justify-content-between align-items-center mb-1">
          <label class="form-label mb-0">Categoría</label>
          <button type="button" class="btn btn-link btn-sm p-0" data-bs-toggle="modal" data-bs-target="#modalNuevaCategoria">+ Nueva</button>
        </div>
        <select name="categoria_id" class="form-select">
          <option value="">Sin categoría</option>
          <?php foreach($categorias as $cat): ?>
            <option value="<?= (int)$cat['id'] ?>"><?= e($cat['nombre']) ?></option>
          <?php endforeach; ?>
        </select>
        <div class="form-text">Agrupa productos para encontrarlos más rápido.</div>
      </div>
      <div class="col-md-2">
        <label class="form-label">Código interno</label>
        <input name="codigo" class="form-control" required>
        <div class="form-text">Código corto para tu operación diaria.</div>
      </div>
      <div class="col-md-2">
        <label class="form-label">SKU</label>
        <input name="sku" class="form-control" placeholder="SKU-0001">
      </div>
      <div class="col-md-3">
        <label class="form-label">Código de barras</label>
        <input name="codigo_barras" class="form-control" placeholder="EAN/UPC">
      </div>
      <div class="col-md-3">
        <label class="form-label">Nombre</label>
        <input name="nombre" class="form-control" required>
      </div>
      <div class="col-md-3">
        <label class="form-label">Descripción</label>
        <input name="descripcion" class="form-control">
      </div>
      <div class="col-md-2">
        <label class="form-label">Unidad de medida</label>
        <select name="unidad" class="form-select">
          <?php foreach (['unidad','kg','g','lb','litro','ml','metro','cm','caja','paquete','servicio','hora'] as $unidad): ?>
            <option value="<?= e($unidad) ?>" <?= $unidad === 'unidad' ? 'selected' : '' ?>><?= e(ucfirst($unidad)) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-2">
        <label class="form-label">Precio</label>
        <input type="number" step="0.01" min="0" name="precio" class="form-control">
      </div>
      <div class="col-md-2">
        <label class="form-label">Costo</label>
        <input type="number" step="0.01" min="0" name="costo" class="form-control">
      </div>
      <div class="col-md-2">
        <label class="form-label">Impuesto %</label>
        <input type="number" step="0.01" min="0" name="impuesto" class="form-control" value="19">
      </div>
      <div class="col-md-2">
        <label class="form-label">Desc. máximo %</label>
        <input type="number" step="0.01" min="0" name="descuento_maximo" class="form-control" value="0">
      </div>
      <div class="col-md-2">
        <label class="form-label">Stock mínimo</label>
        <input type="number" step="0.01" min="0" name="stock_minimo" class="form-control" value="0">
        <div class="form-text">Nivel base para reposición.</div>
      </div>
      <div class="col-md-2">
        <label class="form-label">Stock de aviso</label>
        <input type="number" step="0.01" min="0" name="stock_aviso" class="form-control" value="0">
        <div class="form-text">Recibir alerta antes del mínimo.</div>
      </div>
      <div class="col-md-2">
        <label class="form-label">Estado</label>
        <select name="estado" class="form-select">
          <option value="activo">Activo</option>
          <option value="inactivo">Inactivo</option>
        </select>
      </div>
      <div class="col-12">
        <button class="btn btn-primary btn-sm">Guardar ítem</button>
      </div>
    </form>
  </div>
</div>

<div class="modal fade" id="modalNuevaCategoria" tabindex="-1" aria-labelledby="modalNuevaCategoriaLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" action="<?= e(url('/app/categorias')) ?>">
        <?= csrf_campo() ?>
        <input type="hidden" name="redirect_to" value="/app/productos">
        <div class="modal-header">
          <h5 class="modal-title" id="modalNuevaCategoriaLabel">Nueva categoría</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body row g-2">
          <div class="col-12">
            <label class="form-label">Nombre</label>
            <input name="nombre" class="form-control" required>
          </div>
          <div class="col-12">
            <label class="form-label">Descripción</label>
            <input name="descripcion" class="form-control">
          </div>
          <div class="col-12">
            <label class="form-label">Estado</label>
            <select name="estado" class="form-select">
              <option value="activo">Activo</option>
              <option value="inactivo">Inactivo</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary btn-sm">Guardar categoría</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="card">
  <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
    <div>
      <strong>Listado de productos</strong>
      <div class="small text-muted">Registros encontrados: <?= count($productos) ?></div>
    </div>
    <form class="d-flex gap-2" method="GET">
      <input class="form-control form-control-sm" name="q" value="<?= e($buscar) ?>" placeholder="Buscar por nombre/código/SKU">
      <button class="btn btn-outline-secondary btn-sm">Buscar</button>
      <?php if ($buscar !== ''): ?>
        <a class="btn btn-outline-dark btn-sm" href="<?= e(url('/app/productos')) ?>">Limpiar</a>
      <?php endif; ?>
    </form>
  </div>
  <div class="table-responsive" style="overflow: visible;">
    <table class="table table-hover table-sm mb-0 tabla-admin">
      <thead class="table-light">
        <tr>
          <th>Código</th><th>Nombre</th><th>Tipo</th><th>Categoría</th><th>Precio</th><th>Impuesto</th><th>Estado</th><th class="text-end">Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($productos)): ?>
          <tr><td colspan="8" class="text-center py-4 text-muted">No hay productos registrados con este criterio.</td></tr>
        <?php else: ?>
          <?php foreach($productos as $p): ?>
            <tr>
              <td><?= e($p['codigo']) ?></td>
              <td><?= e($p['nombre']) ?></td>
              <td><?= e($p['tipo'] ?? 'producto') ?></td>
              <td><?= e($p['categoria'] ?? '-') ?></td>
              <td>$<?= number_format((float)$p['precio'],2) ?></td>
              <td><?= e((string)$p['impuesto']) ?>%</td>
              <td><span class="badge <?= ($p['estado'] === 'activo') ? 'badge-estado-activo' : 'badge-estado-inactivo' ?>"><?= e($p['estado']) ?></span></td>
              <td class="text-end"><div class="dropdown dropup"><button class="btn btn-light btn-sm dropdown-toggle" data-bs-toggle="dropdown">Acciones</button><ul class="dropdown-menu dropdown-menu-end"><li><a class="dropdown-item" href="<?= e(url('/app/productos/ver/' . $p['id'])) ?>">Ver</a></li><li><a class="dropdown-item" href="<?= e(url('/app/productos/editar/' . $p['id'])) ?>">Editar</a></li><li><form method="POST" action="<?= e(url('/app/productos/eliminar/' . $p['id'])) ?>" onsubmit="return confirm('¿Confirmas eliminar este producto?')"><?= csrf_campo() ?><button class="dropdown-item text-danger" type="submit">Eliminar</button></form></li></ul></div></td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
