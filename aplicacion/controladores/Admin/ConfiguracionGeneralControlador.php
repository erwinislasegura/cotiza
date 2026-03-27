<?php

namespace Aplicacion\Controladores\Admin;

use Aplicacion\Nucleo\Controlador;

class ConfiguracionGeneralControlador extends Controlador
{
    public function index(): void
    {
        $this->vista('admin/configuracion/index', [], 'admin');
    }
}
