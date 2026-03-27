<h1 class="h5 mb-3">Empresas</h1>

<div class="card">
  <div class="card-header">
    <strong>Listado de empresas</strong>
    <div class="small text-muted">Registros encontrados: <?= count($empresas) ?></div>
  </div>
  <div class="table-responsive" style="overflow: visible;">
    <table class="table table-sm table-hover mb-0 tabla-admin">
      <thead class="table-light"><tr><th>Nombre comercial</th><th>Correo</th><th>Estado</th><th>Plan</th><th class="text-end">Acciones</th></tr></thead>
      <tbody>
        <?php if (empty($empresas)): ?>
          <tr><td colspan="5" class="text-center py-4 text-muted">No hay empresas registradas.</td></tr>
        <?php else: foreach($empresas as $e): ?>
          <tr><td><?= e($e['nombre_comercial']) ?></td><td><?= e($e['correo']) ?></td><td><span class="badge text-bg-light"><?= e($e['estado']) ?></span></td><td><?= e((string)$e['plan_id']) ?></td><td class="text-end"><a href="/admin/empresas/ver/<?= $e['id'] ?>" class="btn btn-sm btn-outline-secondary">Ver</a></td></tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>
