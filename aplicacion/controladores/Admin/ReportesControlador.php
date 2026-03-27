<?php

namespace Aplicacion\Controladores\Admin;

use Aplicacion\Nucleo\Controlador;
use Aplicacion\Nucleo\BaseDatos;

class ReportesControlador extends Controlador
{
    public function index(): void
    {
        $db = BaseDatos::obtener();
        $reportes = [
            'empresas_por_plan' => $db->query('SELECT p.nombre, COUNT(e.id) total FROM empresas e INNER JOIN planes p ON p.id=e.plan_id GROUP BY p.nombre')->fetchAll(),
            'suscripciones_activas' => (int)$db->query("SELECT COUNT(*) total FROM suscripciones WHERE estado='activa'")->fetch()['total'],
            'cuentas_por_vencer' => (int)$db->query("SELECT COUNT(*) total FROM suscripciones WHERE fecha_vencimiento BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY)")->fetch()['total'],
            'cuentas_vencidas' => (int)$db->query("SELECT COUNT(*) total FROM suscripciones WHERE fecha_vencimiento < CURDATE()")->fetch()['total'],
            'ingresos_registrados' => (float)$db->query("SELECT COALESCE(SUM(monto),0) total FROM pagos WHERE estado='aprobado'")->fetch()['total'],
            'cotizaciones_por_estado' => $db->query('SELECT estado, COUNT(*) total FROM cotizaciones GROUP BY estado')->fetchAll(),
        ];
        $this->vista('admin/reportes/index', compact('reportes'), 'admin');
    }
}
