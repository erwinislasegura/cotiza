<?php

use Aplicacion\Controladores\Publico\PublicoControlador;

$enrutador->agregar('GET', '/', [PublicoControlador::class, 'inicio']);
$enrutador->agregar('GET', '/caracteristicas', [PublicoControlador::class, 'caracteristicas']);
$enrutador->agregar('GET', '/planes', [PublicoControlador::class, 'planes']);
$enrutador->agregar('GET', '/contacto', [PublicoControlador::class, 'contacto']);
$enrutador->agregar('GET', '/preguntas-frecuentes', [PublicoControlador::class, 'preguntasFrecuentes']);
$enrutador->agregar('POST', '/contacto', [PublicoControlador::class, 'enviarContacto']);
$enrutador->agregar('GET', '/contratar/{plan}', [PublicoControlador::class, 'contratar']);
$enrutador->agregar('GET', '/cotizacion/publica/{token}', [PublicoControlador::class, 'verCotizacionPublica']);
$enrutador->agregar('POST', '/cotizacion/publica/{token}/decision', [PublicoControlador::class, 'registrarDecisionCotizacion']);
