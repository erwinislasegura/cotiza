<section class="modulo-head d-flex justify-content-between align-items-center mb-3">
  <h1 class="h4 mb-0">Servicios / Productos</h1>
  <a href="<?= e(url('/app/categorias')) ?>" class="btn btn-outline-primary btn-sm">Gestionar categorías</a>
</section>
<div class="card mb-3"><div class="card-header">Nuevo ítem comercial</div><div class="card-body">
<form method="POST" action="<?= e(url('/app/productos')) ?>" class="row g-2"><?= csrf_campo() ?>
<div class="col-md-2"><label class="form-label">Tipo</label><select name="tipo" class="form-select"><option value="producto">Producto</option><option value="servicio">Servicio</option></select></div>
<div class="col-md-2"><label class="form-label">Categoría</label><select name="categoria_id" class="form-select"><option value="">Sin categoría</option><?php foreach($categorias as $cat): ?><option value="<?= (int)$cat['id'] ?>"><?= e($cat['nombre']) ?></option><?php endforeach; ?></select></div>
<div class="col-md-2"><label class="form-label">Código</label><input name="codigo" class="form-control" required></div>
<div class="col-md-3"><label class="form-label">Nombre</label><input name="nombre" class="form-control" required></div>
<div class="col-md-3"><label class="form-label">Descripción</label><input name="descripcion" class="form-control"></div>
<div class="col-md-2"><label class="form-label">Unidad</label><input name="unidad" class="form-control" value="unidad"></div>
<div class="col-md-2"><label class="form-label">Precio</label><input type="number" step="0.01" name="precio" class="form-control"></div>
<div class="col-md-2"><label class="form-label">Costo</label><input type="number" step="0.01" name="costo" class="form-control"></div>
<div class="col-md-2"><label class="form-label">Impuesto %</label><input type="number" step="0.01" name="impuesto" class="form-control" value="19"></div>
<div class="col-md-2"><label class="form-label">Desc. máximo %</label><input type="number" step="0.01" name="descuento_maximo" class="form-control" value="0"></div>
<div class="col-md-2"><label class="form-label">Estado</label><select name="estado" class="form-select"><option value="activo">Activo</option><option value="inactivo">Inactivo</option></select></div>
<div class="col-12"><button class="btn btn-primary btn-sm">Guardar ítem</button></div>
</form></div></div>

<div class="card"><div class="card-header d-flex justify-content-between align-items-center"><div class="d-flex gap-2 align-items-center"><label class="small text-muted">Mostrar</label><select class="form-select form-select-sm" style="width:90px"><option>10</option><option>25</option><option>50</option></select></div><form class="d-flex gap-2" method="GET"><input class="form-control form-control-sm" name="q" value="<?= e($buscar) ?>" placeholder="Buscar por nombre/código"><button class="btn btn-outline-secondary btn-sm">Buscar</button></form></div>
<div class="table-responsive"><table class="table table-hover table-sm mb-0 tabla-admin"><thead><tr><th>Código</th><th>Nombre</th><th>Tipo</th><th>Categoría</th><th>Precio</th><th>Impuesto</th><th>Estado</th><th class="text-end">Acciones</th></tr></thead><tbody><?php foreach($productos as $p): ?><tr><td><?= e($p['codigo']) ?></td><td><?= e($p['nombre']) ?></td><td><?= e($p['tipo'] ?? 'producto') ?></td><td><?= e($p['categoria'] ?? '-') ?></td><td>$<?= number_format((float)$p['precio'],2) ?></td><td><?= e((string)$p['impuesto']) ?>%</td><td><span class="badge <?= ($p['estado'] === 'activo') ? 'badge-estado-activo' : 'badge-estado-inactivo' ?>"><?= e($p['estado']) ?></span></td><td class="text-end"><div class="dropdown"><button class="btn btn-light btn-sm dropdown-toggle" data-bs-toggle="dropdown">Acciones</button><ul class="dropdown-menu dropdown-menu-end"><li><a class="dropdown-item" href="<?= e(url('/app/productos/ver/' . $p['id'])) ?>">Ver</a></li><li><a class="dropdown-item" href="<?= e(url('/app/productos/editar/' . $p['id'])) ?>">Editar</a></li><li><form method="POST" action="<?= e(url('/app/productos/eliminar/' . $p['id'])) ?>" onsubmit="return confirm('¿Confirmas eliminar este producto?')"><?= csrf_campo() ?><button class="dropdown-item text-danger" type="submit">Eliminar</button></form></li></ul></div></td></tr><?php endforeach; ?></tbody></table></div><div class="card-footer small text-muted">Registros: <?= count($productos) ?></div></div>
