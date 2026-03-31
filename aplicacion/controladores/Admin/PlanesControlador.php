<?php

namespace Aplicacion\Controladores\Admin;

use Aplicacion\Modelos\LogAdministracion;
use Aplicacion\Nucleo\Controlador;
use Aplicacion\Modelos\Plan;
use Throwable;

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
        $planId = (new Plan())->crear($this->obtenerDatosFormulario());
        (new LogAdministracion())->registrar('planes', 'crear', 'Creación de plan ID ' . $planId);
        flash('success', 'Plan creado correctamente.');
        $this->redirigir('/admin/planes');
    }

    public function editar(int $id): void
    {
        $plan = (new Plan())->buscar($id);
        if (!$plan) {
            flash('danger', 'Plan no encontrado.');
            $this->redirigir('/admin/planes');
        }
        $this->vista('admin/planes/formulario', compact('plan'), 'admin');
    }

    public function actualizar(int $id): void
    {
        validar_csrf();
        try {
            (new Plan())->actualizar($id, $this->obtenerDatosFormulario());
            (new LogAdministracion())->registrar('planes', 'editar', 'Actualización de plan ID ' . $id);
            flash('success', 'Plan actualizado.');
        } catch (Throwable $e) {
            flash('danger', 'No se pudo actualizar el plan. Verifica slug único y campos obligatorios.');
        }
        $this->redirigir('/admin/planes');
    }

    public function cambiarEstado(int $id): void
    {
        validar_csrf();
        $plan = (new Plan())->buscar($id);
        if (!$plan) {
            flash('danger', 'Plan no encontrado.');
            $this->redirigir('/admin/planes');
        }

        $nuevoEstado = $plan['estado'] === 'activo' ? 'inactivo' : 'activo';
        $data = $plan;
        $data['estado'] = $nuevoEstado;
        (new Plan())->actualizar($id, $data);
        (new LogAdministracion())->registrar('planes', 'cambiar_estado', "Plan ID {$id} pasó a {$nuevoEstado}");

        flash('success', 'Estado del plan actualizado.');
        $this->redirigir('/admin/planes');
    }

    private function obtenerDatosFormulario(): array
    {
        $precioMensual = (float) ($_POST['precio_mensual'] ?? 0);
        $descuentoAnual = (float) ($_POST['descuento_anual_pct'] ?? 0);
        $precioAnual = (float) ($_POST['precio_anual'] ?? 0);
        if ($precioAnual <= 0 && $precioMensual > 0) {
            $precioAnual = ($precioMensual * 12) * ((100 - $descuentoAnual) / 100);
        }

        return [
            'nombre' => trim($_POST['nombre'] ?? ''),
            'slug' => trim($_POST['slug'] ?? ''),
            'descripcion_comercial' => trim($_POST['descripcion_comercial'] ?? ''),
            'precio_mensual' => $precioMensual,
            'descuento_anual_pct' => $descuentoAnual,
            'precio_anual' => $precioAnual,
            'duracion_dias' => (int) ($_POST['duracion_dias'] ?? 30),
            'visible' => isset($_POST['visible']) ? 1 : 0,
            'destacado' => isset($_POST['destacado']) ? 1 : 0,
            'recomendado' => isset($_POST['recomendado']) ? 1 : 0,
            'orden_visualizacion' => (int) ($_POST['orden_visualizacion'] ?? 0),
            'insignia' => trim($_POST['insignia'] ?? ''),
            'resumen_comercial' => trim($_POST['resumen_comercial'] ?? ''),
            'color_visual' => trim($_POST['color_visual'] ?? '#0d6efd'),
            'maximo_usuarios' => (int) ($_POST['maximo_usuarios'] ?? 0),
            'usuarios_ilimitados' => isset($_POST['usuarios_ilimitados']) ? 1 : 0,
            'observaciones_internas' => trim($_POST['observaciones_internas'] ?? ''),
            'estado' => $_POST['estado'] ?? 'activo',
        ];
    }
}
