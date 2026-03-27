<?php

namespace Aplicacion\Nucleo;

class App
{
    public static function iniciar(): void
    {
        CargadorEntorno::cargar(__DIR__ . '/../../.env');
        $config = require __DIR__ . '/../../configuracion/aplicacion.php';
        date_default_timezone_set($config['zona_horaria']);

        require_once __DIR__ . '/../ayudantes/funciones.php';
        iniciar_sesion_segura($config['sesion_nombre']);

        $enrutador = new Enrutador();
        require __DIR__ . '/../../rutas/web.php';
        require __DIR__ . '/../../rutas/autenticacion.php';
        require __DIR__ . '/../../rutas/admin.php';
        require __DIR__ . '/../../rutas/empresa.php';

        $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
        $enrutador->despachar($_SERVER['REQUEST_METHOD'] ?? 'GET', $uri);
    }
}
