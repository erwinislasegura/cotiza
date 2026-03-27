<?php

use Aplicacion\Servicios\ExcelExpoFormato;

require __DIR__ . '/_tabla_simple.php';

$vendedor = null;
ob_start();
require __DIR__ . '/_formulario_vendedor.php';
$formularioVendedor = (string) ob_get_clean();

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
    ['nombre', 'correo', 'telefono', 'comision', 'estado', 'usuario_nombre'],
    $registros,
    $formularioVendedor,
    $buscar,
    $accionesListado
);
