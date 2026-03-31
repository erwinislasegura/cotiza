<?php

namespace Aplicacion\Controladores\Admin;

use Aplicacion\Nucleo\Controlador;
use Aplicacion\Modelos\Funcionalidad;
use Throwable;

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
        try {
            (new Funcionalidad())->crear($this->datos());
            flash('success', 'Funcionalidad creada.');
        } catch (Throwable $e) {
            flash('danger', 'No se pudo crear la funcionalidad. Verifica código interno único.');
        }
        $this->redirigir('/admin/funcionalidades');
    }

    public function editar(int $id): void
    {
        $funcionalidad = (new Funcionalidad())->buscar($id);
        if (!$funcionalidad) {
            flash('danger', 'Funcionalidad no encontrada.');
            $this->redirigir('/admin/funcionalidades');
        }
        $this->vista('admin/funcionalidades/formulario', compact('funcionalidad'), 'admin');
    }

    public function actualizar(int $id): void
    {
        validar_csrf();
        try {
            (new Funcionalidad())->actualizar($id, $this->datos());
            flash('success', 'Funcionalidad actualizada.');
        } catch (Throwable $e) {
            flash('danger', 'No se pudo actualizar la funcionalidad. Verifica código interno único.');
        }
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
