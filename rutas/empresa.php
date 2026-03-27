<?php

use Aplicacion\Middlewares\AutenticadoMiddleware;
use Aplicacion\Middlewares\EmpresaMiddleware;
use Aplicacion\Controladores\Empresa\PanelEmpresaControlador;
use Aplicacion\Controladores\Empresa\ClientesControlador;
use Aplicacion\Controladores\Empresa\ProductosControlador;
use Aplicacion\Controladores\Empresa\CotizacionesControlador;
use Aplicacion\Controladores\Empresa\ConfiguracionControlador;
use Aplicacion\Controladores\Empresa\UsuariosControlador;

$mwEmpresa = [AutenticadoMiddleware::class, EmpresaMiddleware::class];

$enrutador->agregar('GET', '/app/panel', [PanelEmpresaControlador::class, 'panel'], $mwEmpresa);
$enrutador->agregar('GET', '/app/clientes', [ClientesControlador::class, 'index'], $mwEmpresa);
$enrutador->agregar('GET', '/app/clientes/crear', [ClientesControlador::class, 'crear'], $mwEmpresa);
$enrutador->agregar('POST', '/app/clientes/crear', [ClientesControlador::class, 'guardar'], $mwEmpresa);
$enrutador->agregar('GET', '/app/clientes/editar/{id}', fn() => print 'Pendiente edición de clientes', $mwEmpresa);

$enrutador->agregar('GET', '/app/productos', [ProductosControlador::class, 'index'], $mwEmpresa);
$enrutador->agregar('GET', '/app/productos/crear', [ProductosControlador::class, 'crear'], $mwEmpresa);
$enrutador->agregar('POST', '/app/productos/crear', [ProductosControlador::class, 'guardar'], $mwEmpresa);
$enrutador->agregar('GET', '/app/productos/editar/{id}', fn() => print 'Pendiente edición de productos', $mwEmpresa);

$enrutador->agregar('GET', '/app/cotizaciones', [CotizacionesControlador::class, 'index'], $mwEmpresa);
$enrutador->agregar('GET', '/app/cotizaciones/crear', [CotizacionesControlador::class, 'crear'], $mwEmpresa);
$enrutador->agregar('POST', '/app/cotizaciones/crear', [CotizacionesControlador::class, 'guardar'], $mwEmpresa);
$enrutador->agregar('GET', '/app/cotizaciones/editar/{id}', fn() => print 'Pendiente edición de cotizaciones', $mwEmpresa);
$enrutador->agregar('GET', '/app/cotizaciones/ver/{id}', fn() => print 'Pendiente vista detallada', $mwEmpresa);

$enrutador->agregar('GET', '/app/configuracion', [ConfiguracionControlador::class, 'index'], $mwEmpresa);
$enrutador->agregar('GET', '/app/usuarios', [UsuariosControlador::class, 'index'], $mwEmpresa);
