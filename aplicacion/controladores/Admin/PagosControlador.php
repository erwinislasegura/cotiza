<?php

namespace Aplicacion\Controladores\Admin;

use Aplicacion\Nucleo\Controlador;
use Aplicacion\Modelos\Pago;

class PagosControlador extends Controlador
{
    public function index(): void
    {
        $pagos = (new Pago())->listar();
        $this->vista('admin/pagos/index', compact('pagos'), 'admin');
    }
}
