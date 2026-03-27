<h1 class="h4 mb-3">Inicio</h1>
<div class="row g-2 mb-3">
  <div class="col-md-3"><div class="card card-body small">Cotizaciones del mes: <strong><?= (int) ($resumen['cotizaciones_mes'] ?? 0) ?></strong></div></div>
  <div class="col-md-3"><div class="card card-body small">Cotizaciones aprobadas: <strong><?= (int) ($resumen['aprobadas'] ?? 0) ?></strong></div></div>
  <div class="col-md-3"><div class="card card-body small">Cotizaciones rechazadas: <strong><?= (int) ($resumen['rechazadas'] ?? 0) ?></strong></div></div>
  <div class="col-md-3"><div class="card card-body small">Cotizaciones por vencer: <strong><?= (int) ($resumen['por_vencer'] ?? 0) ?></strong></div></div>
</div>
<div class="row g-2 mb-3">
  <div class="col-lg-6"><div class="card"><div class="card-header">Clientes recientes</div><div class="table-responsive"><table class="table table-sm mb-0"><thead><tr><th>Cliente</th><th>Correo</th><th>Alta</th></tr></thead><tbody><?php foreach(($resumen['clientes_recientes'] ?? []) as $c): ?><tr><td><?= e($c['nombre']) ?></td><td><?= e($c['correo']) ?></td><td><?= e($c['fecha_creacion']) ?></td></tr><?php endforeach; ?></tbody></table></div></div></div>
  <div class="col-lg-6"><div class="card"><div class="card-header">Productos/Servicios más cotizados</div><div class="table-responsive"><table class="table table-sm mb-0"><thead><tr><th>Descripción</th><th class="text-end">Cantidad</th></tr></thead><tbody><?php foreach(($resumen['productos_top'] ?? []) as $item): ?><tr><td><?= e($item['descripcion']) ?></td><td class="text-end"><?= (int) $item['total'] ?></td></tr><?php endforeach; ?></tbody></table></div></div></div>
</div>
<div class="card"><div class="card-header">Vendedores destacados</div><div class="table-responsive"><table class="table table-sm mb-0"><thead><tr><th>Vendedor</th><th class="text-end">Cotizaciones</th></tr></thead><tbody><?php foreach(($resumen['vendedores_top'] ?? []) as $v): ?><tr><td><?= e($v['nombre']) ?></td><td class="text-end"><?= (int) $v['total'] ?></td></tr><?php endforeach; ?></tbody></table></div></div>
