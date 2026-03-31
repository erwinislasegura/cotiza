<h1 class="h5 mb-3">Dashboard administrativo</h1>

<div class="row g-2 mb-3">
  <?php $kpis = [
    ['Total empresas', $resumen['empresas_total']],
    ['Empresas activas', $resumen['empresas_activas']],
    ['Suspendidas', $resumen['empresas_suspendidas']],
    ['Vencidas', $resumen['empresas_vencidas']],
    ['Por vencer', $resumen['empresas_por_vencer']],
    ['Planes activos', $resumen['planes_activos']],
    ['Usuarios de empresas', $resumen['total_usuarios_empresas']],
    ['Ingreso mensual estimado', '$' . number_format($resumen['ingresos_mensuales_estimados'], 0, ',', '.')],
    ['Ingreso anual estimado', '$' . number_format($resumen['ingresos_anuales_estimados'], 0, ',', '.')],
  ]; ?>
  <?php foreach ($kpis as [$label, $valor]): ?>
    <div class="col-md-4 col-lg-3"><div class="admin-kpi"><div class="label"><?= e($label) ?></div><div class="value"><?= e((string) $valor) ?></div></div></div>
  <?php endforeach; ?>
</div>

<?php if (!empty($alertas)): ?><div class="alert alert-warning small"><strong>Alertas:</strong><ul class="mb-0"><?php foreach ($alertas as $a): ?><li><?= e($a) ?></li><?php endforeach; ?></ul></div><?php endif; ?>

<div class="row g-3">
  <div class="col-lg-6">
    <div class="card"><div class="card-header">Últimas empresas registradas</div><div class="table-responsive"><table class="table table-sm tabla-admin mb-0"><thead><tr><th>Empresa</th><th>Correo</th><th>Estado</th><th>Fecha</th></tr></thead><tbody><?php foreach($ultimasEmpresas as $e): ?><tr><td><?= e($e['nombre_comercial']) ?></td><td><?= e($e['correo']) ?></td><td><?= e($e['estado']) ?></td><td><?= e(substr($e['fecha_creacion'],0,10)) ?></td></tr><?php endforeach; ?></tbody></table></div></div>
  </div>
  <div class="col-lg-6">
    <div class="card"><div class="card-header">Suscripciones recientes</div><div class="table-responsive"><table class="table table-sm tabla-admin mb-0"><thead><tr><th>Empresa</th><th>Plan</th><th>Estado</th><th>Movimiento</th></tr></thead><tbody><?php foreach($ultimasSuscripciones as $s): ?><tr><td><?= e($s['empresa']) ?></td><td><?= e($s['plan']) ?></td><td><?= e($s['estado']) ?></td><td><?= e(substr($s['fecha_movimiento'],0,16)) ?></td></tr><?php endforeach; ?></tbody></table></div></div>
  </div>
  <div class="col-lg-6">
    <div class="card"><div class="card-header">Empresas por plan</div><div class="card-body small"><?php foreach($empresasPorPlan as $r): ?><div class="d-flex justify-content-between border-bottom py-1"><span><?= e($r['nombre']) ?></span><strong><?= e((string)$r['total']) ?></strong></div><?php endforeach; ?></div></div>
  </div>
  <div class="col-lg-6">
    <div class="card"><div class="card-header">Próximos vencimientos</div><div class="table-responsive"><table class="table table-sm tabla-admin mb-0"><thead><tr><th>Empresa</th><th>Plan</th><th>Vence</th><th>Días</th></tr></thead><tbody><?php foreach($proximosVencimientos as $v): ?><tr><td><?= e($v['empresa']) ?></td><td><?= e($v['plan']) ?></td><td><?= e($v['fecha_vencimiento']) ?></td><td><?= e((string)$v['dias_restantes']) ?></td></tr><?php endforeach; ?></tbody></table></div></div>
  </div>
</div>

<div class="mt-3 d-flex gap-2">
  <a href="<?= e(url('/admin/planes')) ?>" class="btn btn-sm btn-outline-primary">Gestionar planes</a>
  <a href="<?= e(url('/admin/empresas')) ?>" class="btn btn-sm btn-outline-primary">Gestionar empresas</a>
  <a href="<?= e(url('/admin/suscripciones')) ?>" class="btn btn-sm btn-outline-primary">Gestionar suscripciones</a>
</div>
