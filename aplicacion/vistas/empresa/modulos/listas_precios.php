<?php require __DIR__ . '/_tabla_simple.php'; ?>

<div class="alert alert-info border-0 mb-3">
    <h2 class="h6 mb-2">¿Cómo usar listas de precios para productos y futuras cotizaciones?</h2>
    <ul class="mb-0 small">
        <li>Crea una lista por escenario comercial: <strong>General</strong>, <strong>Mayorista</strong>, <strong>Campaña</strong>, etc.</li>
        <li>Define vigencias para controlar promociones y evitar usar precios fuera de fecha.</li>
        <li>En <strong>Reglas base</strong> documenta reglas aplicables a productos (SKU/categoría) y que luego puedan trasladarse a cotizaciones.</li>
    </ul>
</div>

<?php
$formulario = '
<div class="col-md-3">
    <label class="form-label">Nombre</label>
    <input name="nombre" class="form-control" required placeholder="Ej: Mayorista 2026">
</div>
<div class="col-md-2">
    <label class="form-label">Vigencia desde</label>
    <input type="date" name="vigencia_desde" class="form-control">
</div>
<div class="col-md-2">
    <label class="form-label">Vigencia hasta</label>
    <input type="date" name="vigencia_hasta" class="form-control">
</div>
<div class="col-md-2">
    <label class="form-label">Tipo</label>
    <input name="tipo_lista" class="form-control" value="general" placeholder="general / mayorista / campaña">
</div>
<div class="col-md-2">
    <label class="form-label">Canal</label>
    <select name="canal_venta" class="form-select">
        <option value="">Todos</option>
        <option value="local">Local</option>
        <option value="delivery">Delivery</option>
        <option value="ecommerce">E-commerce</option>
    </select>
</div>
<div class="col-md-3">
    <label class="form-label">Estado</label>
    <select name="estado" class="form-select">
        <option value="activo">Activo</option>
        <option value="inactivo">Inactivo</option>
    </select>
</div>
<div class="col-md-2">
    <label class="form-label">Tipo ajuste</label>
    <select name="ajuste_tipo" class="form-select">
        <option value="incremento">Incremento</option>
        <option value="descuento">Descuento</option>
    </select>
</div>
<div class="col-md-2">
    <label class="form-label">% ajuste</label>
    <input type="number" min="0" step="0.0001" name="ajuste_porcentaje" class="form-control" value="0">
</div>
<div class="col-12">
    <label class="form-label">Reglas base (recomendado para productos y cotizaciones)</label>
    <textarea name="reglas_base" class="form-control" rows="5" placeholder="Ejemplo recomendado:\n- ALCANCE: categoria=electrónica\n- AJUSTE: +8% sobre precio base\n- DESCUENTO: 3% por cantidad > 20\n- OBS: aplicar en cotizaciones B2B"></textarea>
    <div class="form-text">Tip: escribe reglas claras por categoría/SKU para reutilizarlas en futuros cálculos de cotización.</div>
</div>';

render_modulo_simple(
    'Listas de precios',
    '/app/listas-precios',
    ['nombre', 'vigencia_desde', 'vigencia_hasta', 'tipo_lista', 'canal_venta', 'ajuste_tipo', 'ajuste_porcentaje', 'estado'],
    $registros,
    $formulario,
    $buscar,
    '',
    'Listas de precios configuradas'
);
?>
