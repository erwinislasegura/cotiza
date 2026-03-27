<?php

namespace Aplicacion\Controladores\Admin;

use Aplicacion\Nucleo\Controlador;
use Aplicacion\Modelos\Empresa;

class EmpresasControlador extends Controlador
{
    public function index(): void
    {
        $empresas = (new Empresa())->listar();
        $this->vista('admin/empresas/index', compact('empresas'), 'admin');
    }

    public function ver(int $id): void
    {
        $empresa = (new Empresa())->buscar($id);
        $this->vista('admin/empresas/ver', compact('empresa'), 'admin');
    }
}
