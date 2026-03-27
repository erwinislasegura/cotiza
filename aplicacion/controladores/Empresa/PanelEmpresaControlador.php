<?php

namespace Aplicacion\Controladores\Empresa;

use Aplicacion\Nucleo\Controlador;
use Aplicacion\Modelos\Cliente;
use Aplicacion\Modelos\Producto;
use Aplicacion\Modelos\Cotizacion;
use Aplicacion\Modelos\Plan;

class PanelEmpresaControlador extends Controlador
{
    public function panel(): void
    {
        $empresaId = empresa_actual_id();
        $clienteModel = new Cliente();
        $productoModel = new Producto();
        $cotizacionModel = new Cotizacion();
        $planEmpresa = (new Plan())->obtenerPlanActivoEmpresa($empresaId);

        $resumen = [
            'total_clientes' => $clienteModel->contar($empresaId),
            'total_productos' => $productoModel->contar($empresaId),
            'total_cotizaciones' => count($cotizacionModel->listar($empresaId)),
            'plan_actual' => $planEmpresa['plan_id'] ?? null,
            'fecha_vencimiento' => $planEmpresa['fecha_vencimiento'] ?? null,
        ];

        $cotizaciones = $cotizacionModel->listar($empresaId);
        $this->vista('empresa/panel', compact('resumen', 'cotizaciones'), 'empresa');
    }
}
