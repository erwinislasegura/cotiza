<h1 class="h4 mb-3">Configuración POS</h1>
<div class="card"><div class="card-body"><form method="POST" class="row g-2" action="<?= e(url('/app/punto-venta/configuracion')) ?>"><?= csrf_campo() ?>
<div class="col-md-4"><label class="form-label">Impuesto por defecto (%)</label><input class="form-control" type="number" step="0.01" min="0" name="impuesto_por_defecto" value="<?= e((string) ($configuracion['impuesto_por_defecto'] ?? 0)) ?>"></div>
<div class="col-md-4 d-flex align-items-end"><div class="form-check"><input class="form-check-input" type="checkbox" name="permitir_venta_sin_stock" id="permitir_stock" <?= !empty($configuracion['permitir_venta_sin_stock']) ? 'checked' : '' ?>><label class="form-check-label" for="permitir_stock">Permitir venta sin stock</label></div></div>
<div class="col-md-4 d-flex align-items-end"><button class="btn btn-primary">Guardar configuración</button></div>
</form></div></div>
