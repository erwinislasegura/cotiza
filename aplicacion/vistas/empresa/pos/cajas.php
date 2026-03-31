<div class="d-flex justify-content-between align-items-center mb-3"><h1 class="h4 mb-0">Cajas / terminales</h1><a class="btn btn-outline-secondary btn-sm" href="<?= e(url('/app/punto-venta')) ?>">Volver</a></div>
<div class="card mb-3"><div class="card-body"><form class="row g-2" method="POST" action="<?= e(url('/app/punto-venta/cajas')) ?>"><?= csrf_campo() ?>
<div class="col-md-4"><input class="form-control" name="nombre" placeholder="Nombre de caja" required></div>
<div class="col-md-3"><input class="form-control" name="codigo" placeholder="Código" required></div>
<div class="col-md-3"><select class="form-select" name="estado"><option value="activa">Activa</option><option value="inactiva">Inactiva</option></select></div>
<div class="col-md-2 d-grid"><button class="btn btn-primary">Crear caja</button></div></form></div></div>
<div class="card"><div class="card-body"><table class="table table-sm"><thead><tr><th>ID</th><th>Nombre</th><th>Código</th><th>Estado</th></tr></thead><tbody><?php foreach($cajas as $caja): ?><tr><td><?= (int)$caja['id'] ?></td><td><?= e($caja['nombre']) ?></td><td><?= e($caja['codigo']) ?></td><td><?= e($caja['estado']) ?></td></tr><?php endforeach; ?></tbody></table></div></div>
