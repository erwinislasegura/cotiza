<?php
$totalCotizaciones = max(1, (int) (($resumen['aprobadas'] ?? 0) + ($resumen['rechazadas'] ?? 0) + ($resumen['pendientes'] ?? 0)));
$porcentajeAprobadas = (int) round(((int) ($resumen['aprobadas'] ?? 0) / $totalCotizaciones) * 100);
$porcentajePendientes = (int) round(((int) ($resumen['pendientes'] ?? 0) / $totalCotizaciones) * 100);
$porcentajeRechazadas = (int) round(((int) ($resumen['rechazadas'] ?? 0) / $totalCotizaciones) * 100);

$meses = [];
$conteosMes = [];
$montosMes = [];
foreach (($resumen['cotizaciones_ultimos_meses'] ?? []) as $fila) {
    $periodo = (string) ($fila['periodo'] ?? '');
    $fecha = \DateTime::createFromFormat('Y-m', $periodo);
    $meses[] = $fecha ? $fecha->format('M y') : $periodo;
    $conteosMes[] = (int) ($fila['total'] ?? 0);
    $montosMes[] = (float) ($fila['monto'] ?? 0);
}
?>

<h1 class="h4 mb-1">Inicio del cliente</h1>
<p class="text-muted mb-3">Resumen ejecutivo con métricas clave y visualización comercial.</p>

<div class="row g-2 mb-3">
  <div class="col-md-6 col-xl-3">
    <div class="card card-body">
      <div class="small text-muted">Cotizaciones del mes</div>
      <div class="h4 mb-0"><?= (int) ($resumen['cotizaciones_mes'] ?? 0) ?></div>
    </div>
  </div>
  <div class="col-md-6 col-xl-3">
    <div class="card card-body">
      <div class="small text-muted">Monto cotizado (mes)</div>
      <div class="h4 mb-0">$<?= number_format((float) ($resumen['monto_mes'] ?? 0), 2) ?></div>
    </div>
  </div>
  <div class="col-md-6 col-xl-3">
    <div class="card card-body">
      <div class="small text-muted">Tasa de aprobación</div>
      <div class="h4 mb-0"><?= $porcentajeAprobadas ?>%</div>
    </div>
  </div>
  <div class="col-md-6 col-xl-3">
    <div class="card card-body">
      <div class="small text-muted">Cotizaciones por vencer (7 días)</div>
      <div class="h4 mb-0"><?= (int) ($resumen['por_vencer'] ?? 0) ?></div>
    </div>
  </div>
</div>

<div class="row g-2 mb-3">
  <div class="col-lg-7">
    <div class="card h-100">
      <div class="card-header">Tendencia de cotizaciones (6 meses)</div>
      <div class="card-body">
        <canvas id="graficoCotizacionesMes" height="100"></canvas>
      </div>
    </div>
  </div>
  <div class="col-lg-5">
    <div class="card h-100">
      <div class="card-header">Distribución por estado</div>
      <div class="card-body">
        <div class="small mb-2">Aprobadas: <?= (int) ($resumen['aprobadas'] ?? 0) ?> (<?= $porcentajeAprobadas ?>%)</div>
        <div class="progress mb-2" role="progressbar"><div class="progress-bar bg-success" style="width: <?= $porcentajeAprobadas ?>%"></div></div>
        <div class="small mb-2">Pendientes: <?= (int) ($resumen['pendientes'] ?? 0) ?> (<?= $porcentajePendientes ?>%)</div>
        <div class="progress mb-2" role="progressbar"><div class="progress-bar bg-warning" style="width: <?= $porcentajePendientes ?>%"></div></div>
        <div class="small mb-2">Rechazadas: <?= (int) ($resumen['rechazadas'] ?? 0) ?> (<?= $porcentajeRechazadas ?>%)</div>
        <div class="progress" role="progressbar"><div class="progress-bar bg-danger" style="width: <?= $porcentajeRechazadas ?>%"></div></div>
      </div>
    </div>
  </div>
</div>

<div class="row g-2 mb-3">
  <div class="col-lg-6"><div class="card"><div class="card-header">Clientes recientes</div><div class="table-responsive"><table class="table table-sm mb-0"><thead><tr><th>Cliente</th><th>Correo</th><th>Alta</th></tr></thead><tbody><?php foreach (($resumen['clientes_recientes'] ?? []) as $c): ?><tr><td><?= e($c['nombre']) ?></td><td><?= e($c['correo']) ?></td><td><?= e($c['fecha_creacion']) ?></td></tr><?php endforeach; ?></tbody></table></div></div></div>
  <div class="col-lg-6"><div class="card"><div class="card-header">Productos/Servicios más cotizados</div><div class="table-responsive"><table class="table table-sm mb-0"><thead><tr><th>Descripción</th><th class="text-end">Cantidad</th></tr></thead><tbody><?php foreach (($resumen['productos_top'] ?? []) as $item): ?><tr><td><?= e($item['descripcion']) ?></td><td class="text-end"><?= (int) $item['total'] ?></td></tr><?php endforeach; ?></tbody></table></div></div></div>
</div>

<div class="card"><div class="card-header">Vendedores destacados</div><div class="table-responsive"><table class="table table-sm mb-0"><thead><tr><th>Vendedor</th><th class="text-end">Cotizaciones</th></tr></thead><tbody><?php foreach (($resumen['vendedores_top'] ?? []) as $v): ?><tr><td><?= e($v['nombre']) ?></td><td class="text-end"><?= (int) $v['total'] ?></td></tr><?php endforeach; ?></tbody></table></div></div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
(() => {
  const labels = <?= json_encode($meses) ?>;
  const seriesConteo = <?= json_encode($conteosMes) ?>;
  const seriesMontos = <?= json_encode($montosMes) ?>;
  const canvas = document.getElementById('graficoCotizacionesMes');
  if (!canvas || !labels.length || typeof Chart === 'undefined') return;

  new Chart(canvas, {
    type: 'bar',
    data: {
      labels,
      datasets: [
        {
          type: 'bar',
          label: 'Cotizaciones',
          data: seriesConteo,
          backgroundColor: 'rgba(13, 110, 253, 0.35)',
          borderColor: '#0d6efd',
          borderWidth: 1
        },
        {
          type: 'line',
          label: 'Monto cotizado',
          data: seriesMontos,
          yAxisID: 'y1',
          borderColor: '#198754',
          backgroundColor: 'rgba(25, 135, 84, 0.25)',
          tension: 0.3
        }
      ]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        y: { beginAtZero: true, ticks: { precision: 0 } },
        y1: {
          beginAtZero: true,
          position: 'right',
          grid: { drawOnChartArea: false }
        }
      }
    }
  });
})();
</script>
