<?php

namespace Aplicacion\Controladores\Empresa;

use Aplicacion\Nucleo\Controlador;
use Aplicacion\Modelos\Usuario;

class UsuariosControlador extends Controlador
{
    public function index(): void
    {
        $usuarios = (new Usuario())->listarPorEmpresa(empresa_actual_id());
        $this->vista('empresa/usuarios/index', compact('usuarios'), 'empresa');
    }
}
