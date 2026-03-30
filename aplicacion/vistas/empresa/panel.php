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

<section class="panel-cliente">
  <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-2 mb-3">
    <div>
      <h1 class="h4 mb-1">Panel de control comercial</h1>
      <p class="text-muted mb-0">Monitorea indicadores clave del negocio en un entorno más ejecutivo y profesional.</p>
    </div>
    <a class="btn btn-primary" href="<?= e(url('/app/cotizaciones/crear')) ?>">
      <i class="bi bi-plus-circle me-1"></i>Nueva cotización
    </a>
  </div>

  <div class="row g-3 mb-3">
    <div class="col-sm-6 col-xl-3">
      <article class="metric-card metric-card-sky">
        <div class="metric-card__icon"><i class="bi bi-file-earmark-bar-graph"></i></div>
        <div class="metric-card__meta">Cotizaciones del mes</div>
        <div class="metric-card__value"><?= (int) ($resumen['cotizaciones_mes'] ?? 0) ?></div>
      </article>
    </div>
    <div class="col-sm-6 col-xl-3">
      <article class="metric-card metric-card-red">
        <div class="metric-card__icon"><i class="bi bi-currency-dollar"></i></div>
        <div class="metric-card__meta">Monto cotizado</div>
        <div class="metric-card__value">$<?= number_format((float) ($resumen['monto_mes'] ?? 0), 2) ?></div>
      </article>
    </div>
    <div class="col-sm-6 col-xl-3">
      <article class="metric-card metric-card-green">
        <div class="metric-card__icon"><i class="bi bi-graph-up-arrow"></i></div>
        <div class="metric-card__meta">Tasa de aprobación</div>
        <div class="metric-card__value"><?= $porcentajeAprobadas ?>%</div>
      </article>
    </div>
    <div class="col-sm-6 col-xl-3">
      <article class="metric-card metric-card-amber">
        <div class="metric-card__icon"><i class="bi bi-hourglass-split"></i></div>
        <div class="metric-card__meta">Por vencer (7 días)</div>
        <div class="metric-card__value"><?= (int) ($resumen['por_vencer'] ?? 0) ?></div>
      </article>
    </div>
  </div>

  <div class="row g-3 mb-3">
    <div class="col-xl-8">
      <div class="card card-dashboard h-100">
        <div class="card-header d-flex justify-content-between align-items-center">
          <span>Tendencia de cotizaciones (últimos 6 meses)</span>
          <span class="badge text-bg-light border">Actualizado</span>
        </div>
        <div class="card-body chart-area">
          <canvas id="graficoCotizacionesMes"></canvas>
        </div>
      </div>
    </div>
    <div class="col-xl-4">
      <div class="card card-dashboard h-100">
        <div class="card-header">Objetivos por estado</div>
        <div class="card-body">
          <div class="kpi-progress mb-3">
            <div class="d-flex justify-content-between small mb-1"><span>Aprobadas</span><strong><?= $porcentajeAprobadas ?>%</strong></div>
            <div class="progress" role="progressbar"><div class="progress-bar bg-success" style="width: <?= $porcentajeAprobadas ?>%"></div></div>
          </div>
          <div class="kpi-progress mb-3">
            <div class="d-flex justify-content-between small mb-1"><span>Pendientes</span><strong><?= $porcentajePendientes ?>%</strong></div>
            <div class="progress" role="progressbar"><div class="progress-bar bg-warning" style="width: <?= $porcentajePendientes ?>%"></div></div>
          </div>
          <div class="kpi-progress mb-3">
            <div class="d-flex justify-content-between small mb-1"><span>Rechazadas</span><strong><?= $porcentajeRechazadas ?>%</strong></div>
            <div class="progress" role="progressbar"><div class="progress-bar bg-danger" style="width: <?= $porcentajeRechazadas ?>%"></div></div>
          </div>
          <hr>
          <div class="small text-muted">Distribución sobre <?= $totalCotizaciones ?> cotizaciones registradas.</div>
        </div>
      </div>
    </div>
  </div>

  <div class="row g-3 mb-3">
    <div class="col-lg-6">
      <div class="card card-dashboard h-100">
        <div class="card-header">Clientes recientes</div>
        <div class="table-responsive">
          <table class="table table-sm table-hover mb-0">
            <thead><tr><th>Cliente</th><th>Correo</th><th>Alta</th></tr></thead>
            <tbody>
            <?php if (!empty($resumen['clientes_recientes'])): ?>
              <?php foreach ($resumen['clientes_recientes'] as $c): ?>
                <tr>
                  <td class="fw-semibold"><?= e($c['nombre']) ?></td>
                  <td><?= e($c['correo']) ?></td>
                  <td><?= e($c['fecha_creacion']) ?></td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr><td colspan="3" class="text-center text-muted py-3">Sin clientes recientes.</td></tr>
            <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <div class="col-lg-6">
      <div class="card card-dashboard h-100">
        <div class="card-header">Productos/Servicios más cotizados</div>
        <div class="table-responsive">
          <table class="table table-sm table-hover mb-0">
            <thead><tr><th>Descripción</th><th class="text-end">Cantidad</th></tr></thead>
            <tbody>
            <?php if (!empty($resumen['productos_top'])): ?>
              <?php foreach ($resumen['productos_top'] as $item): ?>
                <tr>
                  <td><?= e($item['descripcion']) ?></td>
                  <td class="text-end fw-semibold"><?= (int) $item['total'] ?></td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr><td colspan="2" class="text-center text-muted py-3">Sin productos cotizados.</td></tr>
            <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div class="card card-dashboard">
    <div class="card-header">Vendedores destacados</div>
    <div class="table-responsive">
      <table class="table table-sm table-hover mb-0">
        <thead><tr><th>Vendedor</th><th class="text-end">Cotizaciones</th></tr></thead>
        <tbody>
        <?php if (!empty($resumen['vendedores_top'])): ?>
          <?php foreach ($resumen['vendedores_top'] as $v): ?>
            <tr><td><?= e($v['nombre']) ?></td><td class="text-end fw-semibold"><?= (int) $v['total'] ?></td></tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr><td colspan="2" class="text-center text-muted py-3">Sin vendedores registrados.</td></tr>
        <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</section>

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
          backgroundColor: 'rgba(58, 167, 255, 0.35)',
          borderColor: '#2f9dff',
          borderWidth: 1,
          borderRadius: 6
        },
        {
          type: 'line',
          label: 'Monto cotizado',
          data: seriesMontos,
          yAxisID: 'y1',
          borderColor: '#22b36d',
          backgroundColor: 'rgba(34, 179, 109, 0.18)',
          tension: 0.35,
          fill: true
        }
      ]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      interaction: { mode: 'index', intersect: false },
      plugins: { legend: { position: 'bottom' } },
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
