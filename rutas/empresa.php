<?php

use Aplicacion\Middlewares\AutenticadoMiddleware;
use Aplicacion\Middlewares\EmpresaMiddleware;
use Aplicacion\Controladores\Empresa\PanelEmpresaControlador;
use Aplicacion\Controladores\Empresa\ClientesControlador;
use Aplicacion\Controladores\Empresa\ProductosControlador;
use Aplicacion\Controladores\Empresa\CotizacionesControlador;
use Aplicacion\Controladores\Empresa\ConfiguracionControlador;
use Aplicacion\Controladores\Empresa\UsuariosControlador;
use Aplicacion\Controladores\Empresa\GestionComercialControlador;

$mwEmpresa = [AutenticadoMiddleware::class, EmpresaMiddleware::class];

$enrutador->agregar('GET', '/app/panel', [GestionComercialControlador::class, 'inicio'], $mwEmpresa);
$enrutador->agregar('GET', '/app/clientes', [ClientesControlador::class, 'index'], $mwEmpresa);
$enrutador->agregar('POST', '/app/clientes', [ClientesControlador::class, 'guardar'], $mwEmpresa);
$enrutador->agregar('GET', '/app/clientes/crear', [ClientesControlador::class, 'crear'], $mwEmpresa);
$enrutador->agregar('POST', '/app/clientes/crear', [ClientesControlador::class, 'guardar'], $mwEmpresa);
$enrutador->agregar('GET', '/app/clientes/editar/{id}', fn() => print 'Pendiente edición de clientes', $mwEmpresa);

$enrutador->agregar('GET', '/app/productos', [ProductosControlador::class, 'index'], $mwEmpresa);
$enrutador->agregar('POST', '/app/productos', [ProductosControlador::class, 'guardar'], $mwEmpresa);
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
$enrutador->agregar('POST', '/app/usuarios', [UsuariosControlador::class, 'guardar'], $mwEmpresa);

$enrutador->agregar('GET', '/app/contactos', [GestionComercialControlador::class, 'contactos'], $mwEmpresa);
$enrutador->agregar('POST', '/app/contactos', [GestionComercialControlador::class, 'guardarContacto'], $mwEmpresa);
$enrutador->agregar('GET', '/app/vendedores', fn() => (new GestionComercialControlador())->moduloBase('vendedores'), $mwEmpresa);
$enrutador->agregar('POST', '/app/vendedores', fn() => (new GestionComercialControlador())->guardarModuloBase('vendedores'), $mwEmpresa);
$enrutador->agregar('GET', '/app/categorias', fn() => (new GestionComercialControlador())->moduloBase('categorias'), $mwEmpresa);
$enrutador->agregar('POST', '/app/categorias', fn() => (new GestionComercialControlador())->guardarModuloBase('categorias'), $mwEmpresa);
$enrutador->agregar('GET', '/app/listas-precios', fn() => (new GestionComercialControlador())->moduloBase('listas-precios'), $mwEmpresa);
$enrutador->agregar('POST', '/app/listas-precios', fn() => (new GestionComercialControlador())->guardarModuloBase('listas-precios'), $mwEmpresa);
$enrutador->agregar('GET', '/app/seguimiento', fn() => (new GestionComercialControlador())->moduloBase('seguimiento'), $mwEmpresa);
$enrutador->agregar('POST', '/app/seguimiento', fn() => (new GestionComercialControlador())->guardarModuloBase('seguimiento'), $mwEmpresa);
$enrutador->agregar('GET', '/app/aprobaciones', fn() => (new GestionComercialControlador())->moduloBase('aprobaciones'), $mwEmpresa);
$enrutador->agregar('POST', '/app/aprobaciones', fn() => (new GestionComercialControlador())->guardarModuloBase('aprobaciones'), $mwEmpresa);
$enrutador->agregar('GET', '/app/documentos', fn() => (new GestionComercialControlador())->moduloBase('documentos'), $mwEmpresa);
$enrutador->agregar('POST', '/app/documentos', fn() => (new GestionComercialControlador())->guardarModuloBase('documentos'), $mwEmpresa);
$enrutador->agregar('GET', '/app/notificaciones', fn() => (new GestionComercialControlador())->moduloBase('notificaciones'), $mwEmpresa);
$enrutador->agregar('POST', '/app/notificaciones', fn() => (new GestionComercialControlador())->guardarModuloBase('notificaciones'), $mwEmpresa);
$enrutador->agregar('GET', '/app/historial', fn() => (new GestionComercialControlador())->moduloBase('historial'), $mwEmpresa);
$enrutador->agregar('POST', '/app/historial', fn() => (new GestionComercialControlador())->guardarModuloBase('historial'), $mwEmpresa);
$enrutador->agregar('GET', '/app/reportes', [GestionComercialControlador::class, 'reportes'], $mwEmpresa);
