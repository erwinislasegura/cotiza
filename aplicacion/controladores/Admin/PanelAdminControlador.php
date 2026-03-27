<?php

namespace Aplicacion\Controladores\Admin;

use Aplicacion\Nucleo\Controlador;
use Aplicacion\Modelos\Empresa;
use Aplicacion\Modelos\Suscripcion;
use Aplicacion\Modelos\Pago;

class PanelAdminControlador extends Controlador
{
    public function panel(): void
    {
        $empresas = (new Empresa())->listar();
        $suscripciones = (new Suscripcion())->listar();
        $pagos = (new Pago())->listar();

        $resumen = [
            'empresas_activas' => count(array_filter($empresas, fn($e) => $e['estado'] === 'activa')),
            'suscripciones_por_vencer' => count(array_filter($suscripciones, fn($s) => $s['dias_restantes'] <= 7)),
            'ingresos_registrados' => array_sum(array_map(fn($p) => (float) $p['monto'], array_filter($pagos, fn($p) => $p['estado'] === 'aprobado'))),
            'empresas_suspendidas' => count(array_filter($empresas, fn($e) => $e['estado'] === 'suspendida')),
        ];

        $this->vista('admin/panel', compact('resumen', 'suscripciones', 'empresas'), 'admin');
    }
}
