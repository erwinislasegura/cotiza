<h1 class="h4 mb-3">Editar lista de precios</h1>

<div class="alert alert-info border-0 mb-3">
    <h2 class="h6 mb-2">Mantén consistencia con la configuración inicial</h2>
    <ul class="mb-0 small">
        <li>Conserva la estructura de reglas para que sea reutilizable en productos y cotizaciones.</li>
        <li>Verifica vigencias para evitar aplicar precios fuera de fecha.</li>
        <li>Usa estado <strong>activo</strong> solo en listas listas para operar.</li>
    </ul>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" class="row g-2">
            <?= csrf_campo() ?>

            <div class="col-md-3">
                <label class="form-label">Nombre</label>
                <input
                    name="nombre"
                    class="form-control"
                    required
                    placeholder="Ej: Mayorista 2026"
                    value="<?= e((string) ($registro['nombre'] ?? '')) ?>"
                >
            </div>

            <div class="col-md-2">
                <label class="form-label">Vigencia desde</label>
                <input
                    type="date"
                    name="vigencia_desde"
                    class="form-control"
                    value="<?= e((string) ($registro['vigencia_desde'] ?? '')) ?>"
                >
            </div>

            <div class="col-md-2">
                <label class="form-label">Vigencia hasta</label>
                <input
                    type="date"
                    name="vigencia_hasta"
                    class="form-control"
                    value="<?= e((string) ($registro['vigencia_hasta'] ?? '')) ?>"
                >
            </div>

            <div class="col-md-2">
                <label class="form-label">Tipo</label>
                <input
                    name="tipo_lista"
                    class="form-control"
                    placeholder="general / mayorista / campaña"
                    value="<?= e((string) ($registro['tipo_lista'] ?? 'general')) ?>"
                >
            </div>
            <div class="col-md-2">
                <label class="form-label">Canal</label>
                <select name="canal_venta" class="form-select">
                    <option value="" <?= (($registro['canal_venta'] ?? '') === '') ? 'selected' : '' ?>>Todos</option>
                    <option value="local" <?= ($registro['canal_venta'] ?? '') === 'local' ? 'selected' : '' ?>>Local</option>
                    <option value="delivery" <?= ($registro['canal_venta'] ?? '') === 'delivery' ? 'selected' : '' ?>>Delivery</option>
                    <option value="ecommerce" <?= ($registro['canal_venta'] ?? '') === 'ecommerce' ? 'selected' : '' ?>>E-commerce</option>
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label">Estado</label>
                <select name="estado" class="form-select">
                    <option value="activo" <?= ($registro['estado'] ?? 'activo') === 'activo' ? 'selected' : '' ?>>Activo</option>
                    <option value="inactivo" <?= ($registro['estado'] ?? '') === 'inactivo' ? 'selected' : '' ?>>Inactivo</option>
                </select>
            </div>

            <div class="col-md-2">
                <label class="form-label">Tipo ajuste</label>
                <select name="ajuste_tipo" class="form-select">
                    <option value="incremento" <?= ($registro['ajuste_tipo'] ?? 'incremento') === 'incremento' ? 'selected' : '' ?>>Incremento</option>
                    <option value="descuento" <?= ($registro['ajuste_tipo'] ?? '') === 'descuento' ? 'selected' : '' ?>>Descuento</option>
                </select>
            </div>

            <div class="col-md-2">
                <label class="form-label">% ajuste</label>
                <input
                    type="number"
                    min="0"
                    step="0.0001"
                    name="ajuste_porcentaje"
                    class="form-control"
                    value="<?= e((string) ($registro['ajuste_porcentaje'] ?? '0')) ?>"
                >
            </div>

            <div class="col-12">
                <label class="form-label">Reglas base (recomendado para productos y cotizaciones)</label>
                <textarea
                    name="reglas_base"
                    class="form-control"
                    rows="5"
                    placeholder="Ejemplo recomendado:
- ALCANCE: categoria=electrónica
- AJUSTE: +8% sobre precio base
- DESCUENTO: 3% por cantidad > 20
- OBS: aplicar en cotizaciones B2B"
                ><?= e((string) ($registro['reglas_base'] ?? '')) ?></textarea>
                <div class="form-text">Tip: mantén reglas claras por categoría/SKU para su uso futuro en cotizaciones.</div>
            </div>

            <div class="col-12">
                <button class="btn btn-primary btn-sm">Guardar cambios</button>
                <a class="btn btn-outline-secondary btn-sm" href="<?= e(url('/app/listas-precios')) ?>">Cancelar</a>
            </div>
        </form>
    </div>
</div>
