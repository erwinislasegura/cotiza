<h1 class="h4 mb-3">Editar cotización</h1>
<div class="card"><div class="card-body"><form method="POST" class="row g-2"><?= csrf_campo() ?>
<div class="col-md-3"><label class="form-label">Estado</label><select name="estado" class="form-select"><?php foreach(['borrador','enviada','aprobada','rechazada','vencida','anulada'] as $estado): ?><option value="<?= e($estado) ?>" <?= $cotizacion['estado']===$estado?'selected':'' ?>><?= e($estado) ?></option><?php endforeach; ?></select></div>
<div class="col-md-3"><label class="form-label">Fecha vencimiento</label><input type="date" name="fecha_vencimiento" class="form-control" value="<?= e($cotizacion['fecha_vencimiento']) ?>"></div>
<div class="col-md-6"><label class="form-label">Observaciones</label><input name="observaciones" class="form-control" value="<?= e($cotizacion['observaciones']) ?>"></div>
<div class="col-md-12"><label class="form-label">Términos y condiciones</label><textarea name="terminos_condiciones" class="form-control" rows="3"><?= e($cotizacion['terminos_condiciones']) ?></textarea></div>
<div class="col-12"><button class="btn btn-primary btn-sm">Guardar cambios</button> <a class="btn btn-outline-secondary btn-sm" href="<?= e(url('/app/cotizaciones')) ?>">Cancelar</a></div>
</form></div></div>
