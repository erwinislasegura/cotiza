<?php

use Aplicacion\Middlewares\AutenticadoMiddleware;
use Aplicacion\Middlewares\SuperAdminMiddleware;
use Aplicacion\Controladores\Admin\PanelAdminControlador;
use Aplicacion\Controladores\Admin\EmpresasControlador;
use Aplicacion\Controladores\Admin\PlanesControlador;
use Aplicacion\Controladores\Admin\FuncionalidadesControlador;
use Aplicacion\Controladores\Admin\PlanFuncionalidadesControlador;
use Aplicacion\Controladores\Admin\SuscripcionesControlador;
use Aplicacion\Controladores\Admin\PagosControlador;
use Aplicacion\Controladores\Admin\ReportesControlador;

$mw = [AutenticadoMiddleware::class, SuperAdminMiddleware::class];

$enrutador->agregar('GET', '/admin/panel', [PanelAdminControlador::class, 'panel'], $mw);
$enrutador->agregar('GET', '/admin/empresas', [EmpresasControlador::class, 'index'], $mw);
$enrutador->agregar('GET', '/admin/empresas/ver/{id}', [EmpresasControlador::class, 'ver'], $mw);

$enrutador->agregar('GET', '/admin/planes', [PlanesControlador::class, 'index'], $mw);
$enrutador->agregar('GET', '/admin/planes/crear', [PlanesControlador::class, 'crear'], $mw);
$enrutador->agregar('POST', '/admin/planes/crear', [PlanesControlador::class, 'guardar'], $mw);
$enrutador->agregar('GET', '/admin/planes/editar/{id}', [PlanesControlador::class, 'editar'], $mw);
$enrutador->agregar('POST', '/admin/planes/editar/{id}', [PlanesControlador::class, 'actualizar'], $mw);

$enrutador->agregar('GET', '/admin/funcionalidades', [FuncionalidadesControlador::class, 'index'], $mw);
$enrutador->agregar('GET', '/admin/funcionalidades/crear', [FuncionalidadesControlador::class, 'crear'], $mw);
$enrutador->agregar('POST', '/admin/funcionalidades/crear', [FuncionalidadesControlador::class, 'guardar'], $mw);
$enrutador->agregar('GET', '/admin/funcionalidades/editar/{id}', [FuncionalidadesControlador::class, 'editar'], $mw);
$enrutador->agregar('POST', '/admin/funcionalidades/editar/{id}', [FuncionalidadesControlador::class, 'actualizar'], $mw);

$enrutador->agregar('GET', '/admin/plan-funcionalidades/{plan_id}', [PlanFuncionalidadesControlador::class, 'index'], $mw);
$enrutador->agregar('POST', '/admin/plan-funcionalidades/{plan_id}', [PlanFuncionalidadesControlador::class, 'guardar'], $mw);

$enrutador->agregar('GET', '/admin/suscripciones', [SuscripcionesControlador::class, 'index'], $mw);
$enrutador->agregar('POST', '/admin/suscripciones/ver/{id}', [SuscripcionesControlador::class, 'actualizarEstado'], $mw);

$enrutador->agregar('GET', '/admin/pagos', [PagosControlador::class, 'index'], $mw);
$enrutador->agregar('GET', '/admin/reportes', [ReportesControlador::class, 'index'], $mw);
$enrutador->agregar('GET', '/admin/configuracion', fn() => print 'Configuración general', $mw);
