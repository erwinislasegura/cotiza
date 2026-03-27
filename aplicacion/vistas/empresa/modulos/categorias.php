<?php

use Aplicacion\Servicios\ExcelExpoFormato;

require __DIR__ . '/_tabla_simple.php';

$formularioCategorias = <<<'HTML'
<div class="col-md-5">
    <label class="form-label">Nombre</label>
    <input name="nombre" class="form-control" maxlength="120" placeholder="Ej. Electrónica" required>
</div>
<div class="col-md-5">
    <label class="form-label">Descripción</label>
    <textarea name="descripcion" class="form-control" rows="2" maxlength="255" placeholder="Describe brevemente esta categoría"></textarea>
</div>
<div class="col-md-2">
    <label class="form-label">Estado</label>
    <select name="estado" class="form-select">
        <option value="activo">Activo</option>
        <option value="inactivo">Inactivo</option>
    </select>
</div>
<div class="col-12">
    <small class="text-muted">Usa categorías claras para facilitar la búsqueda y reportes de productos.</small>
</div>
HTML;

$accionesListado = sprintf(
    '<a href="%s" class="%s" style="%s">%s</a>',
    e(url('/app/categorias/exportar/excel?q=' . urlencode($buscar))),
    e(ExcelExpoFormato::BOTON_CLASES),
    e(ExcelExpoFormato::BOTON_ESTILO),
    e(ExcelExpoFormato::BOTON_TEXTO)
);

render_modulo_simple(
    'Categorías',
    '/app/categorias',
    ['nombre', 'descripcion', 'estado'],
    $registros,
    $formularioCategorias,
    $buscar,
    $accionesListado
);
