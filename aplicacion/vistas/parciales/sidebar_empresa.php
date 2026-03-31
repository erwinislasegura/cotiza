<?php
$rutaActual = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$base = base_path_url();
if ($base !== '' && str_starts_with($rutaActual, $base . '/')) {
    $rutaActual = substr($rutaActual, strlen($base));
}

$coincideRuta = static function (string $rutaMenu, string $rutaActual): bool {
    return $rutaActual === $rutaMenu || str_starts_with($rutaActual, $rutaMenu . '/');
};

$esProductos = $coincideRuta('/app/productos', $rutaActual)
    || $coincideRuta('/app/categorias', $rutaActual)
    || $coincideRuta('/app/listas-precios', $rutaActual);

$esPos = $coincideRuta('/app/punto-venta', $rutaActual);
?>
<aside class="sidebar sidebar-app p-3 border-end bg-white">
  <h6 class="sidebar-app__titulo text-uppercase mb-3">Mi Empresa</h6>
  <nav class="nav flex-column small gap-2">
    <a class="nav-link d-flex gap-2 <?= $coincideRuta('/app/panel', $rutaActual) ? 'active' : '' ?>" href="<?= e(url('/app/panel')) ?>">
      <i class="bi bi-house-door mt-1"></i>
      <span class="d-flex flex-column">
        <span>Inicio</span>
        <small class="text-muted">Resumen general de tu operación.</small>
      </span>
    </a>
    <div class="pt-2 border-top">
      <div class="text-uppercase text-muted fw-semibold px-2">Flujo de inventario</div>
      <small class="text-muted px-2 d-block mb-1">Desde el ingreso de productos hasta su salida.</small>
    </div>

    <a class="nav-link d-flex gap-2 <?= $coincideRuta('/app/inventario/proveedores', $rutaActual) ? 'active' : '' ?>" href="<?= e(url('/app/inventario/proveedores')) ?>">
      <i class="bi bi-building-add mt-1"></i>
      <span class="d-flex flex-column"><span>Proveedores</span><small class="text-muted">Base de proveedores y datos de compra.</small></span>
    </a>
    <a class="nav-link d-flex gap-2 <?= $coincideRuta('/app/inventario/ordenes-compra', $rutaActual) ? 'active' : '' ?>" href="<?= e(url('/app/inventario/ordenes-compra')) ?>">
      <i class="bi bi-file-earmark-text mt-1"></i>
      <span class="d-flex flex-column"><span>Órdenes de compra</span><small class="text-muted">Solicitudes y seguimiento de abastecimiento.</small></span>
    </a>
    <a class="nav-link d-flex gap-2 <?= $coincideRuta('/app/inventario/recepciones', $rutaActual) ? 'active' : '' ?>" href="<?= e(url('/app/inventario/recepciones')) ?>">
      <i class="bi bi-truck mt-1"></i>
      <span class="d-flex flex-column"><span>Recepciones inventario</span><small class="text-muted">Registro de ingresos al stock.</small></span>
    </a>
    <button class="nav-link btn text-start d-flex align-items-center gap-2 <?= $esProductos ? 'active' : '' ?>" type="button" data-bs-toggle="collapse" data-bs-target="#submenuProductos" aria-expanded="<?= $esProductos ? 'true' : 'false' ?>">
      <i class="bi bi-box-seam"></i><span>Servicios / Productos</span><i class="bi ms-auto <?= $esProductos ? 'bi-chevron-up' : 'bi-chevron-down' ?>"></i>
    </button>
    <div class="collapse <?= $esProductos ? 'show' : '' ?>" id="submenuProductos">
      <a class="nav-link submenu <?= $coincideRuta('/app/productos', $rutaActual) && !$coincideRuta('/app/productos/carga-masiva', $rutaActual) ? 'active' : '' ?>" href="<?= e(url('/app/productos')) ?>">Listado productos</a>
      <a class="nav-link submenu <?= $coincideRuta('/app/productos/carga-masiva', $rutaActual) ? 'active' : '' ?>" href="<?= e(url('/app/productos/carga-masiva')) ?>">Carga masiva</a>
      <a class="nav-link submenu <?= $coincideRuta('/app/categorias', $rutaActual) ? 'active' : '' ?>" href="<?= e(url('/app/categorias')) ?>">Categorías</a>
      <a class="nav-link submenu <?= $coincideRuta('/app/listas-precios', $rutaActual) ? 'active' : '' ?>" href="<?= e(url('/app/listas-precios')) ?>">Listas de precios</a>
    </div>
    <a class="nav-link d-flex gap-2 <?= $coincideRuta('/app/inventario/ajustes', $rutaActual) ? 'active' : '' ?>" href="<?= e(url('/app/inventario/ajustes')) ?>">
      <i class="bi bi-sliders mt-1"></i>
      <span class="d-flex flex-column"><span>Ajustes inventario</span><small class="text-muted">Correcciones y regularizaciones de stock.</small></span>
    </a>
    <a class="nav-link d-flex gap-2 <?= $coincideRuta('/app/inventario/movimientos', $rutaActual) ? 'active' : '' ?>" href="<?= e(url('/app/inventario/movimientos')) ?>">
      <i class="bi bi-arrow-left-right mt-1"></i>
      <span class="d-flex flex-column"><span>Movimientos inventario</span><small class="text-muted">Trazabilidad completa de entradas y salidas.</small></span>
    </a>

    <button class="nav-link btn text-start d-flex align-items-center gap-2 <?= $esPos ? 'active' : '' ?>" type="button" data-bs-toggle="collapse" data-bs-target="#submenuPos" aria-expanded="<?= $esPos ? 'true' : 'false' ?>">
      <i class="bi bi-cart-check"></i><span>Punto de venta</span><i class="bi ms-auto <?= $esPos ? 'bi-chevron-up' : 'bi-chevron-down' ?>"></i>
    </button>
    <div class="collapse <?= $esPos ? 'show' : '' ?>" id="submenuPos">
      <a class="nav-link submenu <?= $coincideRuta('/app/punto-venta', $rutaActual) && !$coincideRuta('/app/punto-venta/ventas', $rutaActual) && !$coincideRuta('/app/punto-venta/movimientos', $rutaActual) && !$coincideRuta('/app/punto-venta/cajas', $rutaActual) && !$coincideRuta('/app/punto-venta/configuracion', $rutaActual) ? 'active' : '' ?>" href="<?= e(url('/app/punto-venta')) ?>">Nueva venta</a>
      <a class="nav-link submenu <?= $coincideRuta('/app/punto-venta/ventas', $rutaActual) ? 'active' : '' ?>" href="<?= e(url('/app/punto-venta/ventas')) ?>">Historial POS</a>
      <a class="nav-link submenu <?= $coincideRuta('/app/punto-venta/movimientos', $rutaActual) ? 'active' : '' ?>" href="<?= e(url('/app/punto-venta/movimientos')) ?>">Movimientos de caja</a>
      <a class="nav-link submenu <?= $coincideRuta('/app/punto-venta/cajas', $rutaActual) ? 'active' : '' ?>" href="<?= e(url('/app/punto-venta/cajas')) ?>">Cajas / terminales</a>
      <a class="nav-link submenu <?= $coincideRuta('/app/punto-venta/configuracion', $rutaActual) ? 'active' : '' ?>" href="<?= e(url('/app/punto-venta/configuracion')) ?>">Configuración POS</a>
    </div>

    <div class="pt-2 border-top">
      <div class="text-uppercase text-muted fw-semibold px-2">Gestión comercial</div>
      <small class="text-muted px-2 d-block mb-1">Relación con clientes y seguimiento de oportunidades.</small>
    </div>
    <a class="nav-link d-flex gap-2 <?= $coincideRuta('/app/clientes', $rutaActual) ? 'active' : '' ?>" href="<?= e(url('/app/clientes')) ?>"><i class="bi bi-building mt-1"></i><span class="d-flex flex-column"><span>Clientes</span><small class="text-muted">Empresas y cuentas comerciales.</small></span></a>
    <a class="nav-link d-flex gap-2 <?= $coincideRuta('/app/contactos', $rutaActual) ? 'active' : '' ?>" href="<?= e(url('/app/contactos')) ?>"><i class="bi bi-person-lines-fill mt-1"></i><span class="d-flex flex-column"><span>Contactos</span><small class="text-muted">Personas asociadas a cada cliente.</small></span></a>
    <a class="nav-link d-flex gap-2 <?= $coincideRuta('/app/vendedores', $rutaActual) ? 'active' : '' ?>" href="<?= e(url('/app/vendedores')) ?>"><i class="bi bi-person-badge mt-1"></i><span class="d-flex flex-column"><span>Vendedores</span><small class="text-muted">Equipo comercial y responsables.</small></span></a>
    <a class="nav-link d-flex gap-2 <?= $coincideRuta('/app/cotizaciones', $rutaActual) ? 'active' : '' ?>" href="<?= e(url('/app/cotizaciones')) ?>"><i class="bi bi-file-earmark-text mt-1"></i><span class="d-flex flex-column"><span>Cotizaciones</span><small class="text-muted">Creación y administración de propuestas.</small></span></a>
    <a class="nav-link d-flex gap-2 <?= $coincideRuta('/app/seguimiento', $rutaActual) ? 'active' : '' ?>" href="<?= e(url('/app/seguimiento')) ?>"><i class="bi bi-graph-up-arrow mt-1"></i><span class="d-flex flex-column"><span>Seguimiento comercial</span><small class="text-muted">Estado del embudo y actividades.</small></span></a>
    <a class="nav-link d-flex gap-2 <?= $coincideRuta('/app/aprobaciones', $rutaActual) ? 'active' : '' ?>" href="<?= e(url('/app/aprobaciones')) ?>"><i class="bi bi-check2-square mt-1"></i><span class="d-flex flex-column"><span>Aprobaciones</span><small class="text-muted">Validaciones internas y flujos de autorización.</small></span></a>

    <div class="pt-2 border-top">
      <div class="text-uppercase text-muted fw-semibold px-2">Configuración</div>
      <small class="text-muted px-2 d-block mb-1">Ajustes del sistema y administración interna.</small>
    </div>
    <a class="nav-link d-flex gap-2 <?= $coincideRuta('/app/documentos', $rutaActual) ? 'active' : '' ?>" href="<?= e(url('/app/documentos')) ?>"><i class="bi bi-code-square mt-1"></i><span class="d-flex flex-column"><span>Plantilla correo cotización</span><small class="text-muted">Define formato de envío de cotizaciones.</small></span></a>
    <a class="nav-link d-flex gap-2 <?= $coincideRuta('/app/configuracion/envio-oc-html', $rutaActual) ? 'active' : '' ?>" href="<?= e(url('/app/configuracion/envio-oc-html')) ?>"><i class="bi bi-envelope mt-1"></i><span class="d-flex flex-column"><span>Envío OC HTML</span><small class="text-muted">Configuración de correos para órdenes.</small></span></a>
    <a class="nav-link d-flex gap-2 <?= $coincideRuta('/app/configuracion', $rutaActual) ? 'active' : '' ?>" href="<?= e(url('/app/configuracion')) ?>"><i class="bi bi-gear mt-1"></i><span class="d-flex flex-column"><span>Configuración empresa</span><small class="text-muted">Parámetros generales del negocio.</small></span></a>
    <a class="nav-link d-flex gap-2 <?= $coincideRuta('/app/configuracion/correos-stock', $rutaActual) ? 'active' : '' ?>" href="<?= e(url('/app/configuracion/correos-stock')) ?>"><i class="bi bi-envelope-paper mt-1"></i><span class="d-flex flex-column"><span>Correos de stock</span><small class="text-muted">Alertas automáticas de inventario.</small></span></a>
    <a class="nav-link d-flex gap-2 <?= $coincideRuta('/app/usuarios', $rutaActual) ? 'active' : '' ?>" href="<?= e(url('/app/usuarios')) ?>"><i class="bi bi-people mt-1"></i><span class="d-flex flex-column"><span>Usuarios y permisos</span><small class="text-muted">Control de accesos y roles.</small></span></a>
    <a class="nav-link d-flex gap-2 <?= $coincideRuta('/app/notificaciones', $rutaActual) ? 'active' : '' ?>" href="<?= e(url('/app/notificaciones')) ?>"><i class="bi bi-bell mt-1"></i><span class="d-flex flex-column"><span>Notificaciones</span><small class="text-muted">Preferencias de avisos y alertas.</small></span></a>
    <a class="nav-link d-flex gap-2 <?= $coincideRuta('/app/historial', $rutaActual) ? 'active' : '' ?>" href="<?= e(url('/app/historial')) ?>"><i class="bi bi-clock-history mt-1"></i><span class="d-flex flex-column"><span>Historial / actividad</span><small class="text-muted">Registro de acciones recientes.</small></span></a>
  </nav>
</aside>
