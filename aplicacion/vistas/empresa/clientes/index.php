<section class="modulo-head d-flex justify-content-between align-items-center mb-3">
  <h1 class="h4 mb-0">Clientes</h1>
  <a href="<?= e(url('/app/contactos')) ?>" class="btn btn-outline-primary btn-sm">Ver contactos</a>
</section>

<div class="card mb-3">
  <div class="card-header">Nuevo cliente</div>
  <div class="card-body">
    <form method="POST" action="<?= e(url('/app/clientes')) ?>" class="row g-2">
      <?= csrf_campo() ?>
      <div class="col-md-3"><label class="form-label">Razón social</label><input name="razon_social" class="form-control" required></div>
      <div class="col-md-3"><label class="form-label">Nombre comercial</label><input name="nombre_comercial" class="form-control" required></div>
      <div class="col-md-2"><label class="form-label">RUT/ID Fiscal</label><input name="identificador_fiscal" class="form-control"></div>
      <div class="col-md-2"><label class="form-label">Giro</label><input name="giro" class="form-control"></div>
      <div class="col-md-2"><label class="form-label">Estado</label><select name="estado" class="form-select"><option value="activo">Activo</option><option value="inactivo">Inactivo</option></select></div>
      <div class="col-md-3"><label class="form-label">Correo</label><input type="email" name="correo" class="form-control"></div>
      <div class="col-md-2"><label class="form-label">Teléfono</label><input name="telefono" class="form-control"></div>
      <div class="col-md-3"><label class="form-label">Dirección</label><input name="direccion" class="form-control"></div>
      <div class="col-md-2"><label class="form-label">Ciudad</label><input name="ciudad" class="form-control"></div>
      <div class="col-md-2"><label class="form-label">Vendedor</label><select name="vendedor_id" class="form-select"><option value="">Sin asignar</option><?php foreach ($vendedores as $v): ?><option value="<?= (int) $v['id'] ?>"><?= e($v['nombre']) ?></option><?php endforeach; ?></select></div>
      <div class="col-md-12"><label class="form-label">Observaciones</label><textarea name="notas" class="form-control" rows="2"></textarea></div>
      <div class="col-12"><input type="hidden" name="nombre" value="Cliente"><button class="btn btn-primary btn-sm">Guardar cliente</button></div>
    </form>
  </div>
</div>

<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <div class="d-flex gap-2 align-items-center"><label class="small text-muted">Mostrar</label><select class="form-select form-select-sm" style="width:90px"><option>10</option><option>25</option><option>50</option></select></div>
    <form method="GET" class="d-flex gap-2"><input class="form-control form-control-sm" name="q" value="<?= e($buscar) ?>" placeholder="Buscar cliente"><button class="btn btn-outline-secondary btn-sm">Buscar</button></form>
  </div>
  <div class="table-responsive" style="overflow: visible;">
    <table class="table table-hover table-sm mb-0 tabla-admin">
      <thead><tr><th>Razón social</th><th>Comercial</th><th>Fiscal</th><th>Correo</th><th>Teléfono</th><th>Ciudad</th><th>Estado</th><th class="text-end">Acciones</th></tr></thead>
      <tbody><?php foreach($clientes as $c): ?><tr>
        <td><?= e($c['razon_social'] ?: $c['nombre']) ?></td><td><?= e($c['nombre_comercial'] ?: $c['nombre']) ?></td><td><?= e($c['identificador_fiscal'] ?? '') ?></td><td><?= e($c['correo']) ?></td><td><?= e($c['telefono']) ?></td><td><?= e($c['ciudad'] ?? '') ?></td><td><span class="badge <?= ($c['estado'] === 'activo') ? 'badge-estado-activo' : 'badge-estado-inactivo' ?>"><?= e($c['estado']) ?></span></td>
        <td class="text-end"><div class="dropdown dropup"><button class="btn btn-light btn-sm dropdown-toggle" data-bs-toggle="dropdown">Acciones</button><ul class="dropdown-menu dropdown-menu-end"><li><a class="dropdown-item" href="<?= e(url('/app/clientes/ver/' . $c['id'])) ?>">Ver</a></li><li><a class="dropdown-item" href="<?= e(url('/app/clientes/editar/' . $c['id'])) ?>">Editar</a></li><li><a class="dropdown-item" href="<?= e(url('/app/contactos')) ?>">Ver contactos</a></li><li><form method="POST" action="<?= e(url('/app/clientes/eliminar/' . $c['id'])) ?>" onsubmit="return confirm('¿Confirmas eliminar este cliente?')"><?= csrf_campo() ?><button class="dropdown-item text-danger" type="submit">Eliminar</button></form></li></ul></div></td>
      </tr><?php endforeach; ?></tbody>
    </table>
  </div>
  <div class="card-footer small text-muted d-flex justify-content-between"><span>Registros: <?= count($clientes) ?></span><span>Paginación base preparada</span></div>
</div>
