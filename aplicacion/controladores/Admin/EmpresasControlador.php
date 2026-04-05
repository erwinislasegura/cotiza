<?php

namespace Aplicacion\Controladores\Admin;

use Aplicacion\Modelos\Empresa;
use Aplicacion\Modelos\LogAdministracion;
use Aplicacion\Modelos\Plan;
use Aplicacion\Modelos\Suscripcion;
use Aplicacion\Modelos\Usuario;
use Aplicacion\Nucleo\BaseDatos;
use Aplicacion\Nucleo\Controlador;

class EmpresasControlador extends Controlador
{
    public function index(): void
    {
        $filtros = [
            'busqueda' => trim($_GET['q'] ?? ''),
            'estado' => $_GET['estado'] ?? '',
            'plan_id' => $_GET['plan_id'] ?? '',
        ];

        $empresas = (new Empresa())->listar($filtros);
        $planes = (new Plan())->listar();
        $this->vista('admin/empresas/index', compact('empresas', 'planes', 'filtros'), 'admin');
    }

    public function ver(int $id): void
    {
        $empresa = (new Empresa())->buscarDetalleAdmin($id);
        if (!$empresa) {
            flash('danger', 'Empresa no encontrada.');
            $this->redirigir('/admin/empresas');
        }

        $admins = (new Usuario())->listarAdministradoresEmpresa(['empresa_id' => $id]);
        $historial = BaseDatos::obtener()->prepare('SELECT hs.*, u.nombre AS admin_nombre FROM historial_suscripciones hs LEFT JOIN suscripciones s ON s.id = hs.suscripcion_id LEFT JOIN usuarios u ON u.id = s.empresa_id WHERE s.empresa_id = :empresa_id ORDER BY hs.id DESC LIMIT 20');
        $historial->execute(['empresa_id' => $id]);
        $historial = $historial->fetchAll();

        $planes = (new Plan())->listarActivos();
        $this->vista('admin/empresas/ver', compact('empresa', 'admins', 'historial', 'planes'), 'admin');
    }

    public function actualizarEstado(int $id): void
    {
        validar_csrf();
        $estado = $_POST['estado'] ?? 'activa';
        try {
            (new Empresa())->actualizarEstado($id, $estado);
            (new LogAdministracion())->registrar('empresas', 'cambiar_estado', 'Cambio de estado a ' . $estado, $id);
            flash('success', 'Estado de empresa actualizado.');
        } catch (\Throwable $e) {
            flash('danger', 'No se pudo actualizar el estado de la empresa.');
        }
        $this->redirigir('/admin/empresas/ver/' . $id);
    }

    public function cambiarPlan(int $id): void
    {
        validar_csrf();
        $planId = (int) ($_POST['plan_id'] ?? 0);
        $observaciones = trim($_POST['observaciones_internas'] ?? '');
        try {
            (new Empresa())->actualizarPlanYObservacion($id, $planId, $observaciones);
            (new LogAdministracion())->registrar('empresas', 'cambiar_plan', 'Plan asignado ID ' . $planId, $id);
            flash('success', 'Plan de empresa actualizado.');
        } catch (\Throwable $e) {
            flash('danger', 'No se pudo actualizar el plan de la empresa.');
        }
        $this->redirigir('/admin/empresas/ver/' . $id);
    }

    public function extenderSuscripcion(int $id): void
    {
        validar_csrf();
        $dias = max(1, (int) ($_POST['dias'] ?? 30));
        $suscripcionModelo = new Suscripcion();
        $actual = $suscripcionModelo->obtenerUltimaPorEmpresa($id);
        if (!$actual) {
            flash('danger', 'La empresa no tiene suscripción para extender.');
            $this->redirigir('/admin/empresas/ver/' . $id);
        }

        try {
            $hoy = date('Y-m-d');
            $base = ((string) ($actual['fecha_vencimiento'] ?? '') > $hoy) ? (string) $actual['fecha_vencimiento'] : $hoy;
            $nuevaFecha = date('Y-m-d', strtotime($base . ' +' . $dias . ' day'));

            $suscripcionModelo->actualizar((int) $actual['id'], [
                'empresa_id' => (int) ($actual['empresa_id'] ?? $id),
                'plan_id' => (int) $actual['plan_id'],
                'estado' => 'activa',
                'fecha_inicio' => $actual['fecha_inicio'],
                'fecha_vencimiento' => $nuevaFecha,
                'observaciones' => trim((string) (($actual['observaciones'] ?? '') . ' | Extensión admin: +' . $dias . ' días')),
            ]);
            (new LogAdministracion())->registrar('suscripciones', 'extender_vigencia', 'Extensión de ' . $dias . ' días', $id);
            flash('success', 'Vigencia extendida hasta ' . $nuevaFecha . '.');
        } catch (\Throwable $e) {
            flash('danger', 'No se pudo extender la vigencia de la suscripción.');
        }

        $this->redirigir('/admin/empresas/ver/' . $id);
    }
}
