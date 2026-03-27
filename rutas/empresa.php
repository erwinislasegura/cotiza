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
$enrutador->agregar('GET', '/app/clientes/exportar/excel', [ClientesControlador::class, 'exportarExcel'], $mwEmpresa);
$enrutador->agregar('POST', '/app/clientes', [ClientesControlador::class, 'guardar'], $mwEmpresa);
$enrutador->agregar('GET', '/app/clientes/crear', [ClientesControlador::class, 'crear'], $mwEmpresa);
$enrutador->agregar('POST', '/app/clientes/crear', [ClientesControlador::class, 'guardar'], $mwEmpresa);
$enrutador->agregar('GET', '/app/clientes/ver/{id}', [ClientesControlador::class, 'ver'], $mwEmpresa);
$enrutador->agregar('GET', '/app/clientes/editar/{id}', [ClientesControlador::class, 'editar'], $mwEmpresa);
$enrutador->agregar('POST', '/app/clientes/editar/{id}', [ClientesControlador::class, 'actualizar'], $mwEmpresa);
$enrutador->agregar('POST', '/app/clientes/eliminar/{id}', [ClientesControlador::class, 'eliminar'], $mwEmpresa);

$enrutador->agregar('GET', '/app/productos', [ProductosControlador::class, 'index'], $mwEmpresa);
$enrutador->agregar('POST', '/app/productos', [ProductosControlador::class, 'guardar'], $mwEmpresa);
$enrutador->agregar('GET', '/app/productos/crear', [ProductosControlador::class, 'crear'], $mwEmpresa);
$enrutador->agregar('POST', '/app/productos/crear', [ProductosControlador::class, 'guardar'], $mwEmpresa);
$enrutador->agregar('GET', '/app/productos/ver/{id}', [ProductosControlador::class, 'ver'], $mwEmpresa);
$enrutador->agregar('GET', '/app/productos/editar/{id}', [ProductosControlador::class, 'editar'], $mwEmpresa);
$enrutador->agregar('POST', '/app/productos/editar/{id}', [ProductosControlador::class, 'actualizar'], $mwEmpresa);
$enrutador->agregar('POST', '/app/productos/eliminar/{id}', [ProductosControlador::class, 'eliminar'], $mwEmpresa);

$enrutador->agregar('GET', '/app/cotizaciones', [CotizacionesControlador::class, 'index'], $mwEmpresa);
$enrutador->agregar('GET', '/app/cotizaciones/crear', [CotizacionesControlador::class, 'crear'], $mwEmpresa);
$enrutador->agregar('POST', '/app/cotizaciones/crear', [CotizacionesControlador::class, 'guardar'], $mwEmpresa);
$enrutador->agregar('GET', '/app/cotizaciones/editar/{id}', [CotizacionesControlador::class, 'editar'], $mwEmpresa);
$enrutador->agregar('POST', '/app/cotizaciones/editar/{id}', [CotizacionesControlador::class, 'actualizar'], $mwEmpresa);
$enrutador->agregar('GET', '/app/cotizaciones/ver/{id}', [CotizacionesControlador::class, 'ver'], $mwEmpresa);
$enrutador->agregar('POST', '/app/cotizaciones/eliminar/{id}', [CotizacionesControlador::class, 'eliminar'], $mwEmpresa);

$enrutador->agregar('GET', '/app/configuracion', [ConfiguracionControlador::class, 'index'], $mwEmpresa);
$enrutador->agregar('GET', '/app/usuarios', [UsuariosControlador::class, 'index'], $mwEmpresa);
$enrutador->agregar('POST', '/app/usuarios', [UsuariosControlador::class, 'guardar'], $mwEmpresa);
$enrutador->agregar('GET', '/app/usuarios/ver/{id}', [UsuariosControlador::class, 'ver'], $mwEmpresa);
$enrutador->agregar('GET', '/app/usuarios/editar/{id}', [UsuariosControlador::class, 'editar'], $mwEmpresa);
$enrutador->agregar('POST', '/app/usuarios/editar/{id}', [UsuariosControlador::class, 'actualizar'], $mwEmpresa);

$enrutador->agregar('GET', '/app/contactos', [GestionComercialControlador::class, 'contactos'], $mwEmpresa);
$enrutador->agregar('POST', '/app/contactos', [GestionComercialControlador::class, 'guardarContacto'], $mwEmpresa);

$enrutador->agregar('GET', '/app/contactos/ver/{id}', fn($id) => (new GestionComercialControlador())->verRegistro('contactos', (int) $id), $mwEmpresa);
$enrutador->agregar('GET', '/app/contactos/editar/{id}', fn($id) => (new GestionComercialControlador())->editarRegistro('contactos', (int) $id), $mwEmpresa);
$enrutador->agregar('POST', '/app/contactos/editar/{id}', fn($id) => (new GestionComercialControlador())->actualizarRegistro('contactos', (int) $id), $mwEmpresa);
$enrutador->agregar('POST', '/app/contactos/eliminar/{id}', fn($id) => (new GestionComercialControlador())->eliminarRegistro('contactos', (int) $id), $mwEmpresa);

$enrutador->agregar('GET', '/app/vendedores', fn() => (new GestionComercialControlador())->moduloBase('vendedores'), $mwEmpresa);
$enrutador->agregar('POST', '/app/vendedores', fn() => (new GestionComercialControlador())->guardarModuloBase('vendedores'), $mwEmpresa);
$enrutador->agregar('GET', '/app/vendedores/ver/{id}', fn($id) => (new GestionComercialControlador())->verRegistro('vendedores', (int) $id), $mwEmpresa);
$enrutador->agregar('GET', '/app/vendedores/editar/{id}', fn($id) => (new GestionComercialControlador())->editarRegistro('vendedores', (int) $id), $mwEmpresa);
$enrutador->agregar('POST', '/app/vendedores/editar/{id}', fn($id) => (new GestionComercialControlador())->actualizarRegistro('vendedores', (int) $id), $mwEmpresa);
$enrutador->agregar('POST', '/app/vendedores/eliminar/{id}', fn($id) => (new GestionComercialControlador())->eliminarRegistro('vendedores', (int) $id), $mwEmpresa);
$enrutador->agregar('GET', '/app/categorias', fn() => (new GestionComercialControlador())->moduloBase('categorias'), $mwEmpresa);
$enrutador->agregar('POST', '/app/categorias', fn() => (new GestionComercialControlador())->guardarModuloBase('categorias'), $mwEmpresa);
$enrutador->agregar('GET', '/app/categorias/ver/{id}', fn($id) => (new GestionComercialControlador())->verRegistro('categorias', (int) $id), $mwEmpresa);
$enrutador->agregar('GET', '/app/categorias/editar/{id}', fn($id) => (new GestionComercialControlador())->editarRegistro('categorias', (int) $id), $mwEmpresa);
$enrutador->agregar('POST', '/app/categorias/editar/{id}', fn($id) => (new GestionComercialControlador())->actualizarRegistro('categorias', (int) $id), $mwEmpresa);
$enrutador->agregar('POST', '/app/categorias/eliminar/{id}', fn($id) => (new GestionComercialControlador())->eliminarRegistro('categorias', (int) $id), $mwEmpresa);
$enrutador->agregar('GET', '/app/listas-precios', fn() => (new GestionComercialControlador())->moduloBase('listas-precios'), $mwEmpresa);
$enrutador->agregar('POST', '/app/listas-precios', fn() => (new GestionComercialControlador())->guardarModuloBase('listas-precios'), $mwEmpresa);
$enrutador->agregar('GET', '/app/listas-precios/ver/{id}', fn($id) => (new GestionComercialControlador())->verRegistro('listas-precios', (int) $id), $mwEmpresa);
$enrutador->agregar('GET', '/app/listas-precios/editar/{id}', fn($id) => (new GestionComercialControlador())->editarRegistro('listas-precios', (int) $id), $mwEmpresa);
$enrutador->agregar('POST', '/app/listas-precios/editar/{id}', fn($id) => (new GestionComercialControlador())->actualizarRegistro('listas-precios', (int) $id), $mwEmpresa);
$enrutador->agregar('POST', '/app/listas-precios/eliminar/{id}', fn($id) => (new GestionComercialControlador())->eliminarRegistro('listas-precios', (int) $id), $mwEmpresa);
$enrutador->agregar('GET', '/app/seguimiento', fn() => (new GestionComercialControlador())->moduloBase('seguimiento'), $mwEmpresa);
$enrutador->agregar('POST', '/app/seguimiento', fn() => (new GestionComercialControlador())->guardarModuloBase('seguimiento'), $mwEmpresa);
$enrutador->agregar('GET', '/app/seguimiento/ver/{id}', fn($id) => (new GestionComercialControlador())->verRegistro('seguimiento', (int) $id), $mwEmpresa);
$enrutador->agregar('GET', '/app/seguimiento/editar/{id}', fn($id) => (new GestionComercialControlador())->editarRegistro('seguimiento', (int) $id), $mwEmpresa);
$enrutador->agregar('POST', '/app/seguimiento/editar/{id}', fn($id) => (new GestionComercialControlador())->actualizarRegistro('seguimiento', (int) $id), $mwEmpresa);
$enrutador->agregar('POST', '/app/seguimiento/eliminar/{id}', fn($id) => (new GestionComercialControlador())->eliminarRegistro('seguimiento', (int) $id), $mwEmpresa);
$enrutador->agregar('GET', '/app/aprobaciones', fn() => (new GestionComercialControlador())->moduloBase('aprobaciones'), $mwEmpresa);
$enrutador->agregar('POST', '/app/aprobaciones', fn() => (new GestionComercialControlador())->guardarModuloBase('aprobaciones'), $mwEmpresa);
$enrutador->agregar('GET', '/app/aprobaciones/ver/{id}', fn($id) => (new GestionComercialControlador())->verRegistro('aprobaciones', (int) $id), $mwEmpresa);
$enrutador->agregar('GET', '/app/aprobaciones/editar/{id}', fn($id) => (new GestionComercialControlador())->editarRegistro('aprobaciones', (int) $id), $mwEmpresa);
$enrutador->agregar('POST', '/app/aprobaciones/editar/{id}', fn($id) => (new GestionComercialControlador())->actualizarRegistro('aprobaciones', (int) $id), $mwEmpresa);
$enrutador->agregar('POST', '/app/aprobaciones/eliminar/{id}', fn($id) => (new GestionComercialControlador())->eliminarRegistro('aprobaciones', (int) $id), $mwEmpresa);
$enrutador->agregar('GET', '/app/documentos', fn() => (new GestionComercialControlador())->moduloBase('documentos'), $mwEmpresa);
$enrutador->agregar('POST', '/app/documentos', fn() => (new GestionComercialControlador())->guardarModuloBase('documentos'), $mwEmpresa);
$enrutador->agregar('GET', '/app/documentos/ver/{id}', fn($id) => (new GestionComercialControlador())->verRegistro('documentos', (int) $id), $mwEmpresa);
$enrutador->agregar('GET', '/app/documentos/editar/{id}', fn($id) => (new GestionComercialControlador())->editarRegistro('documentos', (int) $id), $mwEmpresa);
$enrutador->agregar('POST', '/app/documentos/editar/{id}', fn($id) => (new GestionComercialControlador())->actualizarRegistro('documentos', (int) $id), $mwEmpresa);
$enrutador->agregar('POST', '/app/documentos/eliminar/{id}', fn($id) => (new GestionComercialControlador())->eliminarRegistro('documentos', (int) $id), $mwEmpresa);
$enrutador->agregar('GET', '/app/notificaciones', fn() => (new GestionComercialControlador())->moduloBase('notificaciones'), $mwEmpresa);
$enrutador->agregar('POST', '/app/notificaciones', fn() => (new GestionComercialControlador())->guardarModuloBase('notificaciones'), $mwEmpresa);
$enrutador->agregar('GET', '/app/notificaciones/ver/{id}', fn($id) => (new GestionComercialControlador())->verRegistro('notificaciones', (int) $id), $mwEmpresa);
$enrutador->agregar('GET', '/app/notificaciones/editar/{id}', fn($id) => (new GestionComercialControlador())->editarRegistro('notificaciones', (int) $id), $mwEmpresa);
$enrutador->agregar('POST', '/app/notificaciones/editar/{id}', fn($id) => (new GestionComercialControlador())->actualizarRegistro('notificaciones', (int) $id), $mwEmpresa);
$enrutador->agregar('POST', '/app/notificaciones/eliminar/{id}', fn($id) => (new GestionComercialControlador())->eliminarRegistro('notificaciones', (int) $id), $mwEmpresa);
$enrutador->agregar('GET', '/app/historial', fn() => (new GestionComercialControlador())->moduloBase('historial'), $mwEmpresa);
$enrutador->agregar('POST', '/app/historial', fn() => (new GestionComercialControlador())->guardarModuloBase('historial'), $mwEmpresa);
$enrutador->agregar('GET', '/app/historial/ver/{id}', fn($id) => (new GestionComercialControlador())->verRegistro('historial', (int) $id), $mwEmpresa);
$enrutador->agregar('GET', '/app/historial/editar/{id}', fn($id) => (new GestionComercialControlador())->editarRegistro('historial', (int) $id), $mwEmpresa);
$enrutador->agregar('POST', '/app/historial/editar/{id}', fn($id) => (new GestionComercialControlador())->actualizarRegistro('historial', (int) $id), $mwEmpresa);
$enrutador->agregar('POST', '/app/historial/eliminar/{id}', fn($id) => (new GestionComercialControlador())->eliminarRegistro('historial', (int) $id), $mwEmpresa);
$enrutador->agregar('GET', '/app/reportes', [GestionComercialControlador::class, 'reportes'], $mwEmpresa);
