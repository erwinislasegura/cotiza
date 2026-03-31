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
  <nav class="nav flex-column small gap-1">
    <a class="nav-link d-flex align-items-center gap-2 <?= $coincideRuta('/app/panel', $rutaActual) ? 'active' : '' ?>" href="<?= e(url('/app/panel')) ?>"><i class="bi bi-house-door"></i><span>Inicio</span></a>
    <span class="text-uppercase text-muted fw-semibold px-2 pt-2">Flujo de inventario</span>

    <a class="nav-link d-flex align-items-center gap-2 <?= $coincideRuta('/app/inventario/proveedores', $rutaActual) ? 'active' : '' ?>" href="<?= e(url('/app/inventario/proveedores')) ?>"><i class="bi bi-building-add"></i><span>Proveedores</span></a>
    <a class="nav-link d-flex align-items-center gap-2 <?= $coincideRuta('/app/inventario/ordenes-compra', $rutaActual) ? 'active' : '' ?>" href="<?= e(url('/app/inventario/ordenes-compra')) ?>"><i class="bi bi-file-earmark-text"></i><span>Órdenes de compra</span></a>
    <a class="nav-link d-flex align-items-center gap-2 <?= $coincideRuta('/app/inventario/recepciones', $rutaActual) ? 'active' : '' ?>" href="<?= e(url('/app/inventario/recepciones')) ?>"><i class="bi bi-truck"></i><span>Recepciones inventario</span></a>
    <button class="nav-link btn text-start d-flex align-items-center gap-2 <?= $esProductos ? 'active' : '' ?>" type="button" data-bs-toggle="collapse" data-bs-target="#submenuProductos" aria-expanded="<?= $esProductos ? 'true' : 'false' ?>">
      <i class="bi bi-box-seam"></i><span>Servicios / Productos</span><i class="bi ms-auto <?= $esProductos ? 'bi-chevron-up' : 'bi-chevron-down' ?>"></i>
    </button>
    <div class="collapse <?= $esProductos ? 'show' : '' ?>" id="submenuProductos">
      <a class="nav-link submenu <?= $coincideRuta('/app/productos', $rutaActual) && !$coincideRuta('/app/productos/carga-masiva', $rutaActual) ? 'active' : '' ?>" href="<?= e(url('/app/productos')) ?>">Listado productos</a>
      <a class="nav-link submenu <?= $coincideRuta('/app/productos/carga-masiva', $rutaActual) ? 'active' : '' ?>" href="<?= e(url('/app/productos/carga-masiva')) ?>">Carga masiva</a>
      <a class="nav-link submenu <?= $coincideRuta('/app/categorias', $rutaActual) ? 'active' : '' ?>" href="<?= e(url('/app/categorias')) ?>">Categorías</a>
      <a class="nav-link submenu <?= $coincideRuta('/app/listas-precios', $rutaActual) ? 'active' : '' ?>" href="<?= e(url('/app/listas-precios')) ?>">Listas de precios</a>
    </div>
    <a class="nav-link d-flex align-items-center gap-2 <?= $coincideRuta('/app/inventario/ajustes', $rutaActual) ? 'active' : '' ?>" href="<?= e(url('/app/inventario/ajustes')) ?>"><i class="bi bi-sliders"></i><span>Ajustes inventario</span></a>
    <a class="nav-link d-flex align-items-center gap-2 <?= $coincideRuta('/app/inventario/movimientos', $rutaActual) ? 'active' : '' ?>" href="<?= e(url('/app/inventario/movimientos')) ?>"><i class="bi bi-arrow-left-right"></i><span>Movimientos inventario</span></a>

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

    <span class="text-uppercase text-muted fw-semibold px-2 pt-2">Gestión comercial</span>
    <a class="nav-link d-flex align-items-center gap-2 <?= $coincideRuta('/app/clientes', $rutaActual) ? 'active' : '' ?>" href="<?= e(url('/app/clientes')) ?>"><i class="bi bi-building"></i><span>Clientes</span></a>
    <a class="nav-link d-flex align-items-center gap-2 <?= $coincideRuta('/app/contactos', $rutaActual) ? 'active' : '' ?>" href="<?= e(url('/app/contactos')) ?>"><i class="bi bi-person-lines-fill"></i><span>Contactos</span></a>
    <a class="nav-link d-flex align-items-center gap-2 <?= $coincideRuta('/app/vendedores', $rutaActual) ? 'active' : '' ?>" href="<?= e(url('/app/vendedores')) ?>"><i class="bi bi-person-badge"></i><span>Vendedores</span></a>
    <a class="nav-link d-flex align-items-center gap-2 <?= $coincideRuta('/app/cotizaciones', $rutaActual) ? 'active' : '' ?>" href="<?= e(url('/app/cotizaciones')) ?>"><i class="bi bi-file-earmark-text"></i><span>Cotizaciones</span></a>
    <a class="nav-link d-flex align-items-center gap-2 <?= $coincideRuta('/app/seguimiento', $rutaActual) ? 'active' : '' ?>" href="<?= e(url('/app/seguimiento')) ?>"><i class="bi bi-graph-up-arrow"></i><span>Seguimiento comercial</span></a>
    <a class="nav-link d-flex align-items-center gap-2 <?= $coincideRuta('/app/aprobaciones', $rutaActual) ? 'active' : '' ?>" href="<?= e(url('/app/aprobaciones')) ?>"><i class="bi bi-check2-square"></i><span>Aprobaciones</span></a>
    <a class="nav-link d-flex align-items-center gap-2 <?= $coincideRuta('/app/documentos', $rutaActual) ? 'active' : '' ?>" href="<?= e(url('/app/documentos')) ?>"><i class="bi bi-code-square"></i><span>Plantilla correo cotización</span></a>

    <span class="text-uppercase text-muted fw-semibold px-2 pt-2">Configuración</span>
    <a class="nav-link d-flex align-items-center gap-2 <?= $coincideRuta('/app/configuracion/envio-oc-html', $rutaActual) ? 'active' : '' ?>" href="<?= e(url('/app/configuracion/envio-oc-html')) ?>"><i class="bi bi-envelope"></i><span>Envío OC HTML</span></a>
    <a class="nav-link d-flex align-items-center gap-2 <?= $coincideRuta('/app/configuracion', $rutaActual) ? 'active' : '' ?>" href="<?= e(url('/app/configuracion')) ?>"><i class="bi bi-gear"></i><span>Configuración empresa</span></a>
    <a class="nav-link d-flex align-items-center gap-2 <?= $coincideRuta('/app/configuracion/correos-stock', $rutaActual) ? 'active' : '' ?>" href="<?= e(url('/app/configuracion/correos-stock')) ?>"><i class="bi bi-envelope-paper"></i><span>Correos de stock</span></a>
    <a class="nav-link d-flex align-items-center gap-2 <?= $coincideRuta('/app/usuarios', $rutaActual) ? 'active' : '' ?>" href="<?= e(url('/app/usuarios')) ?>"><i class="bi bi-people"></i><span>Usuarios y permisos</span></a>
    <a class="nav-link d-flex align-items-center gap-2 <?= $coincideRuta('/app/notificaciones', $rutaActual) ? 'active' : '' ?>" href="<?= e(url('/app/notificaciones')) ?>"><i class="bi bi-bell"></i><span>Notificaciones</span></a>
    <a class="nav-link d-flex align-items-center gap-2 <?= $coincideRuta('/app/historial', $rutaActual) ? 'active' : '' ?>" href="<?= e(url('/app/historial')) ?>"><i class="bi bi-clock-history"></i><span>Historial / actividad</span></a>
  </nav>
</aside>
