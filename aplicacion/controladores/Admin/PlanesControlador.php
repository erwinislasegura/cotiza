<?php

namespace Aplicacion\Controladores\Admin;

use Aplicacion\Nucleo\Controlador;
use Aplicacion\Modelos\Plan;

class PlanesControlador extends Controlador
{
    public function index(): void
    {
        $planes = (new Plan())->listar();
        $this->vista('admin/planes/index', compact('planes'), 'admin');
    }

    public function crear(): void
    {
        $this->vista('admin/planes/formulario', ['plan' => null], 'admin');
    }

    public function guardar(): void
    {
        validar_csrf();
        (new Plan())->crear($this->obtenerDatosFormulario());
        flash('success', 'Plan creado correctamente.');
        $this->redirigir('/admin/planes');
    }

    public function editar(int $id): void
    {
        $plan = (new Plan())->buscar($id);
        $this->vista('admin/planes/formulario', compact('plan'), 'admin');
    }

    public function actualizar(int $id): void
    {
        validar_csrf();
        (new Plan())->actualizar($id, $this->obtenerDatosFormulario());
        flash('success', 'Plan actualizado.');
        $this->redirigir('/admin/planes');
    }

    private function obtenerDatosFormulario(): array
    {
        return [
            'nombre' => trim($_POST['nombre'] ?? ''),
            'slug' => trim($_POST['slug'] ?? ''),
            'descripcion_comercial' => trim($_POST['descripcion_comercial'] ?? ''),
            'precio_mensual' => (float) ($_POST['precio_mensual'] ?? 0),
            'precio_anual' => (float) ($_POST['precio_anual'] ?? 0),
            'duracion_dias' => (int) ($_POST['duracion_dias'] ?? 30),
            'visible' => isset($_POST['visible']) ? 1 : 0,
            'destacado' => isset($_POST['destacado']) ? 1 : 0,
            'orden_visualizacion' => (int) ($_POST['orden_visualizacion'] ?? 0),
            'insignia' => trim($_POST['insignia'] ?? ''),
            'resumen_comercial' => trim($_POST['resumen_comercial'] ?? ''),
            'color_visual' => trim($_POST['color_visual'] ?? '#0d6efd'),
            'estado' => $_POST['estado'] ?? 'activo',
        ];
    }
}
