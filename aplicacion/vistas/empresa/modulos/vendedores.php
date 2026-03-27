<?php

use Aplicacion\Servicios\ExcelExpoFormato;

require __DIR__ . '/_tabla_simple.php';

$formularioVendedor = '
<div class="col-md-4">
    <label class="form-label">Nombre completo</label>
    <input name="nombre" class="form-control" maxlength="120" required placeholder="Ej. Laura García">
</div>
<div class="col-md-4">
    <label class="form-label">Correo corporativo</label>
    <input name="correo" class="form-control" type="email" maxlength="120" placeholder="vendedor@empresa.com">
</div>
<div class="col-md-2">
    <label class="form-label">Teléfono</label>
    <input name="telefono" class="form-control" maxlength="30" placeholder="3001234567">
</div>
<div class="col-md-2">
    <label class="form-label">Comisión %</label>
    <input name="comision" class="form-control" type="number" step="0.01" min="0" max="100" value="0">
</div>
<div class="col-md-3">
    <label class="form-label">Estado</label>
    <select name="estado" class="form-select">
        <option value="activo" selected>Activo</option>
        <option value="inactivo">Inactivo</option>
    </select>
</div>
';

$accionesListado = sprintf(
    '<a href="%s" class="%s" style="%s">%s</a>',
    e(url('/app/vendedores/exportar/excel?q=' . urlencode($buscar))),
    e(ExcelExpoFormato::BOTON_CLASES),
    e(ExcelExpoFormato::BOTON_ESTILO),
    e(ExcelExpoFormato::BOTON_TEXTO)
);

render_modulo_simple(
    'Vendedores',
    '/app/vendedores',
    ['nombre', 'correo', 'telefono', 'comision', 'estado'],
    $registros,
    $formularioVendedor,
    $buscar,
    $accionesListado
);
