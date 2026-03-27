<?php

namespace Aplicacion\Controladores\Admin;

use Aplicacion\Nucleo\Controlador;
use Aplicacion\Modelos\Funcionalidad;

class FuncionalidadesControlador extends Controlador
{
    public function index(): void
    {
        $funcionalidades = (new Funcionalidad())->listar();
        $this->vista('admin/funcionalidades/index', compact('funcionalidades'), 'admin');
    }

    public function crear(): void
    {
        $this->vista('admin/funcionalidades/formulario', ['funcionalidad' => null], 'admin');
    }

    public function guardar(): void
    {
        validar_csrf();
        (new Funcionalidad())->crear($this->datos());
        flash('success', 'Funcionalidad creada.');
        $this->redirigir('/admin/funcionalidades');
    }

    public function editar(int $id): void
    {
        $funcionalidad = (new Funcionalidad())->buscar($id);
        $this->vista('admin/funcionalidades/formulario', compact('funcionalidad'), 'admin');
    }

    public function actualizar(int $id): void
    {
        validar_csrf();
        (new Funcionalidad())->actualizar($id, $this->datos());
        flash('success', 'Funcionalidad actualizada.');
        $this->redirigir('/admin/funcionalidades');
    }

    private function datos(): array
    {
        return [
            'nombre' => trim($_POST['nombre'] ?? ''),
            'codigo_interno' => trim($_POST['codigo_interno'] ?? ''),
            'descripcion' => trim($_POST['descripcion'] ?? ''),
            'tipo_valor' => $_POST['tipo_valor'] ?? 'booleano',
            'estado' => $_POST['estado'] ?? 'activo',
        ];
    }
}
