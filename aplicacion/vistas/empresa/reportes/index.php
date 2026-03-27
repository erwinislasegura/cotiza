<h1 class="h4 mb-3">Reportes comerciales</h1>
<div class="row g-2 mb-3">
  <div class="col-md-2"><div class="card card-body"><small>Cotizaciones mes</small><strong><?= (int) $resumen['cotizaciones_mes'] ?></strong></div></div>
  <div class="col-md-2"><div class="card card-body"><small>Aprobadas</small><strong><?= (int) $resumen['aprobadas'] ?></strong></div></div>
  <div class="col-md-2"><div class="card card-body"><small>Rechazadas</small><strong><?= (int) $resumen['rechazadas'] ?></strong></div></div>
  <div class="col-md-2"><div class="card card-body"><small>Por vencer</small><strong><?= (int) $resumen['por_vencer'] ?></strong></div></div>
</div>
<div class="card"><div class="card-header">Métricas disponibles</div><ul class="list-group list-group-flush small"><li class="list-group-item">Cotizaciones por estado y por vendedor.</li><li class="list-group-item">Montos cotizados por cliente.</li><li class="list-group-item">Vencimientos próximos y clientes activos.</li></ul></div>
