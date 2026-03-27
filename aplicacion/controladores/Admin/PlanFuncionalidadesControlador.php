<?php

namespace Aplicacion\Controladores\Admin;

use Aplicacion\Nucleo\Controlador;
use Aplicacion\Modelos\Plan;
use Aplicacion\Modelos\Funcionalidad;
use Aplicacion\Modelos\PlanFuncionalidad;

class PlanFuncionalidadesControlador extends Controlador
{
    public function index(int $planId): void
    {
        $plan = (new Plan())->buscar($planId);
        $funcionalidades = (new Funcionalidad())->listar();
        $asignadas = (new PlanFuncionalidad())->listarPorPlan($planId);
        $mapa = [];
        foreach ($asignadas as $fila) {
            $mapa[$fila['funcionalidad_id']] = $fila;
        }
        $this->vista('admin/plan_funcionalidades/index', compact('plan', 'funcionalidades', 'mapa'), 'admin');
    }

    public function guardar(int $planId): void
    {
        validar_csrf();
        $modelo = new PlanFuncionalidad();
        foreach ($_POST['funcionalidades'] ?? [] as $funcionalidadId => $valor) {
            $modelo->guardarAsignacion($planId, (int)$funcionalidadId, [
                'activo' => isset($valor['activo']) ? 1 : 0,
                'valor_numerico' => (int)($valor['valor_numerico'] ?? 0),
                'es_ilimitado' => isset($valor['es_ilimitado']) ? 1 : 0,
            ]);
        }
        flash('success', 'Asignaciones guardadas.');
        $this->redirigir('/admin/plan-funcionalidades/' . $planId);
    }
}
