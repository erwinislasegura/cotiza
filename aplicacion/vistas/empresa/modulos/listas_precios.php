<?php require __DIR__ . '/_tabla_simple.php'; ?>

<div class="alert alert-info border-0 mb-3">
    <h2 class="h6 mb-2">¿Cómo completar una lista de precios sin errores?</h2>
    <ul class="mb-0 small">
        <li>Usa un <strong>nombre descriptivo</strong> para identificar rápido cuándo aplicar la lista.</li>
        <li>Selecciona <strong>canal de venta</strong> para separar precios de tienda física, delivery o e-commerce.</li>
        <li>Define <strong>vigencias</strong> para controlar promociones y evitar usar precios fuera de fecha.</li>
        <li>En <strong>Reglas base</strong> documenta reglas por producto (SKU/categoría) para reutilizarlas en cotizaciones.</li>
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
    <label class="form-label">Tipo de lista</label>
    <input name="tipo_lista" class="form-control" value="general" placeholder="general / mayorista / campaña">
    <div class="form-text">Ejemplo: General, Mayorista, Promoción.</div>
</div>
<div class="col-md-2">
    <label class="form-label">Canal de venta</label>
    <select name="canal_venta" class="form-select">
        <option value="">Todos</option>
        <option value="local">Local</option>
        <option value="delivery">Delivery</option>
        <option value="ecommerce">E-commerce</option>
    </select>
    <div class="form-text">Si aplica en todos los canales, deja "Todos".</div>
</div>
<div class="col-md-3">
    <label class="form-label">Estado de la lista</label>
    <select name="estado" class="form-select">
        <option value="activo">Activo</option>
        <option value="inactivo">Inactivo</option>
    </select>
</div>
<div class="col-md-2">
    <label class="form-label">Tipo de ajuste</label>
    <select name="ajuste_tipo" class="form-select">
        <option value="incremento">Incremento</option>
        <option value="descuento">Descuento</option>
    </select>
</div>
<div class="col-md-2">
    <label class="form-label">Porcentaje de ajuste (%)</label>
    <input type="number" min="0" step="0.0001" name="ajuste_porcentaje" class="form-control" value="0">
    <div class="form-text">Ejemplo: 10 = 10%.</div>
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
