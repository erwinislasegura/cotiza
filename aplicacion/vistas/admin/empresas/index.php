<h1 class="h5 mb-3">Empresas</h1>
<form class="row g-2 mb-3">
  <div class="col-md-4"><input class="form-control" name="q" placeholder="Buscar por nombre, correo o fiscal" value="<?= e($filtros['busqueda']) ?>"></div>
  <div class="col-md-3"><select class="form-select" name="plan_id"><option value="">Todos los planes</option><?php foreach($planes as $p): ?><option value="<?= $p['id'] ?>" <?= (string)$filtros['plan_id']===(string)$p['id']?'selected':'' ?>><?= e($p['nombre']) ?></option><?php endforeach; ?></select></div>
  <div class="col-md-3"><select class="form-select" name="estado"><option value="">Todos los estados</option><?php foreach(['activa','suspendida','vencida','cancelada'] as $est): ?><option value="<?= $est ?>" <?= $filtros['estado']===$est?'selected':'' ?>><?= $est ?></option><?php endforeach; ?></select></div>
  <div class="col-md-2"><button class="btn btn-outline-primary w-100">Filtrar</button></div>
</form>

<div class="card"><div class="card-header">Listado de empresas</div><div class="table-responsive" style="overflow: visible;"><table class="table table-sm table-hover mb-0 tabla-admin"><thead><tr><th>Empresa</th><th>Plan</th><th>Estado</th><th>Usuarios</th><th>Último acceso</th><th class="text-end">Acciones</th></tr></thead><tbody>
<?php foreach($empresas as $e): ?>
<tr>
  <td><div class="fw-semibold"><?= e($e['nombre_comercial']) ?></div><div class="small text-muted"><?= e($e['correo']) ?></div></td>
  <td><?= e($e['plan_nombre'] ?? 'Sin plan') ?></td>
  <td><span class="badge text-bg-light"><?= e($e['estado']) ?></span></td>
  <td><?= (int) $e['total_usuarios'] ?></td>
  <td><?= e($e['ultimo_acceso_admin'] ?: '-') ?></td>
  <td class="text-end"><div class="dropdown dropup"><button class="btn btn-sm btn-light dropdown-toggle" data-bs-toggle="dropdown">Acciones</button><ul class="dropdown-menu dropdown-menu-end"><li><a class="dropdown-item" href="/admin/empresas/ver/<?= $e['id'] ?>">Ver</a></li><li><a class="dropdown-item" href="/admin/administradores-empresa?q=<?= urlencode($e['correo']) ?>">Resetear contraseña</a></li></ul></div></td>
</tr>
<?php endforeach; ?>
</tbody></table></div></div>
