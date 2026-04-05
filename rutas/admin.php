<?php

use Aplicacion\Middlewares\AutenticadoMiddleware;
use Aplicacion\Middlewares\SuperAdminMiddleware;
use Aplicacion\Controladores\Admin\PanelAdminControlador;
use Aplicacion\Controladores\Admin\EmpresasControlador;
use Aplicacion\Controladores\Admin\AdministradoresEmpresasControlador;
use Aplicacion\Controladores\Admin\PlanesControlador;
use Aplicacion\Controladores\Admin\FuncionalidadesControlador;
use Aplicacion\Controladores\Admin\PlanFuncionalidadesControlador;
use Aplicacion\Controladores\Admin\SuscripcionesControlador;
use Aplicacion\Controladores\Admin\PagosControlador;
use Aplicacion\Controladores\Admin\ReportesControlador;
use Aplicacion\Controladores\Admin\ConfiguracionGeneralControlador;
use Aplicacion\Controladores\Admin\HistorialAdminControlador;

$mw = [AutenticadoMiddleware::class, SuperAdminMiddleware::class];

$enrutador->agregar('GET', '/admin/panel', [PanelAdminControlador::class, 'panel'], $mw);
$enrutador->agregar('GET', '/admin/empresas', [EmpresasControlador::class, 'index'], $mw);
$enrutador->agregar('GET', '/admin/empresas/ver/{id}', [EmpresasControlador::class, 'ver'], $mw);
$enrutador->agregar('POST', '/admin/empresas/estado/{id}', [EmpresasControlador::class, 'actualizarEstado'], $mw);
$enrutador->agregar('POST', '/admin/empresas/plan/{id}', [EmpresasControlador::class, 'cambiarPlan'], $mw);
$enrutador->agregar('POST', '/admin/empresas/extender-vigencia/{id}', [EmpresasControlador::class, 'extenderSuscripcion'], $mw);

$enrutador->agregar('GET', '/admin/administradores-empresa', [AdministradoresEmpresasControlador::class, 'index'], $mw);
$enrutador->agregar('POST', '/admin/administradores-empresa/actualizar/{id}', [AdministradoresEmpresasControlador::class, 'actualizar'], $mw);
$enrutador->agregar('POST', '/admin/administradores-empresa/estado/{id}', [AdministradoresEmpresasControlador::class, 'cambiarEstado'], $mw);
$enrutador->agregar('POST', '/admin/administradores-empresa/reset-password/{id}', [AdministradoresEmpresasControlador::class, 'resetearPassword'], $mw);

$enrutador->agregar('GET', '/admin/planes', [PlanesControlador::class, 'index'], $mw);
$enrutador->agregar('GET', '/admin/planes/crear', [PlanesControlador::class, 'crear'], $mw);
$enrutador->agregar('POST', '/admin/planes/crear', [PlanesControlador::class, 'guardar'], $mw);
$enrutador->agregar('GET', '/admin/planes/editar/{id}', [PlanesControlador::class, 'editar'], $mw);
$enrutador->agregar('POST', '/admin/planes/editar/{id}', [PlanesControlador::class, 'actualizar'], $mw);
$enrutador->agregar('POST', '/admin/planes/estado/{id}', [PlanesControlador::class, 'cambiarEstado'], $mw);
$enrutador->agregar('POST', '/admin/planes/eliminar/{id}', [PlanesControlador::class, 'eliminar'], $mw);

$enrutador->agregar('GET', '/admin/funcionalidades', [FuncionalidadesControlador::class, 'index'], $mw);
$enrutador->agregar('GET', '/admin/funcionalidades/crear', [FuncionalidadesControlador::class, 'crear'], $mw);
$enrutador->agregar('POST', '/admin/funcionalidades/crear', [FuncionalidadesControlador::class, 'guardar'], $mw);
$enrutador->agregar('GET', '/admin/funcionalidades/editar/{id}', [FuncionalidadesControlador::class, 'editar'], $mw);
$enrutador->agregar('POST', '/admin/funcionalidades/editar/{id}', [FuncionalidadesControlador::class, 'actualizar'], $mw);

$enrutador->agregar('GET', '/admin/plan-funcionalidades/{plan_id}', [PlanFuncionalidadesControlador::class, 'index'], $mw);
$enrutador->agregar('POST', '/admin/plan-funcionalidades/{plan_id}', [PlanFuncionalidadesControlador::class, 'guardar'], $mw);

$enrutador->agregar('GET', '/admin/suscripciones', [SuscripcionesControlador::class, 'index'], $mw);
$enrutador->agregar('POST', '/admin/suscripciones/ver/{id}', [SuscripcionesControlador::class, 'actualizarEstado'], $mw);
$enrutador->agregar('POST', '/admin/suscripciones/editar/{id}', [SuscripcionesControlador::class, 'actualizar'], $mw);

$enrutador->agregar('GET', '/admin/pagos', [PagosControlador::class, 'index'], $mw);
$enrutador->agregar('GET', '/admin/reportes', [ReportesControlador::class, 'index'], $mw);
$enrutador->agregar('GET', '/admin/configuracion', [ConfiguracionGeneralControlador::class, 'index'], $mw);
$enrutador->agregar('POST', '/admin/configuracion', [ConfiguracionGeneralControlador::class, 'guardar'], $mw);
$enrutador->agregar('GET', '/admin/historial', [HistorialAdminControlador::class, 'index'], $mw);
