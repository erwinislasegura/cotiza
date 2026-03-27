<?php

declare(strict_types=1);

spl_autoload_register(function (string $clase): void {
    $prefijo = 'Aplicacion\\';
    if (!str_starts_with($clase, $prefijo)) {
        return;
    }

    $relativa = str_replace('\\', '/', substr($clase, strlen($prefijo)));
    $ruta = __DIR__ . '/../aplicacion/' . $relativa . '.php';
    if (is_file($ruta)) {
        require_once $ruta;
    }
});

require_once __DIR__ . '/../aplicacion/nucleo/App.php';

\Aplicacion\Nucleo\App::iniciar();
