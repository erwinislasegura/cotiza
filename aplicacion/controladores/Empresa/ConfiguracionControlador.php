<?php

namespace Aplicacion\Controladores\Empresa;

use Aplicacion\Nucleo\Controlador;

class ConfiguracionControlador extends Controlador
{
    public function index(): void
    {
        $this->vista('empresa/configuracion/index', [], 'empresa');
    }
}
