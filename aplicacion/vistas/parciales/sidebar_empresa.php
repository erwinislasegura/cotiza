<?php
$rutaActual = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$base = base_path_url();
if ($base !== '' && str_starts_with($rutaActual, $base . '/')) {
    $rutaActual = substr($rutaActual, strlen($base));
}

$items = [
    ['url' => '/app/panel', 'texto' => 'Inicio', 'icono' => 'bi-house-door'],
    ['url' => '/app/clientes', 'texto' => 'Clientes', 'icono' => 'bi-building'],
    ['url' => '/app/contactos', 'texto' => 'Contactos', 'icono' => 'bi-person-lines-fill'],
    ['url' => '/app/vendedores', 'texto' => 'Vendedores', 'icono' => 'bi-person-badge'],
    ['url' => '/app/productos', 'texto' => 'Servicios / Productos', 'icono' => 'bi-box-seam'],
    ['url' => '/app/productos/carga-masiva', 'texto' => 'Carga masiva', 'icono' => 'bi-upload', 'submenu' => true],
    ['url' => '/app/categorias', 'texto' => 'Categorías', 'icono' => 'bi-tags'],
    ['url' => '/app/listas-precios', 'texto' => 'Listas de precios', 'icono' => 'bi-list-ul'],
    ['url' => '/app/cotizaciones', 'texto' => 'Cotizaciones', 'icono' => 'bi-file-earmark-text'],
    ['url' => '/app/seguimiento', 'texto' => 'Seguimiento comercial', 'icono' => 'bi-graph-up-arrow'],
    ['url' => '/app/aprobaciones', 'texto' => 'Aprobaciones', 'icono' => 'bi-check2-square'],
    ['url' => '/app/documentos', 'texto' => 'Plantilla correo cotización', 'icono' => 'bi-code-square'],
    ['url' => '/app/configuracion', 'texto' => 'Configuración empresa', 'icono' => 'bi-gear'],
    ['url' => '/app/usuarios', 'texto' => 'Usuarios y permisos', 'icono' => 'bi-people'],
    ['url' => '/app/notificaciones', 'texto' => 'Notificaciones', 'icono' => 'bi-bell'],
    ['url' => '/app/historial', 'texto' => 'Historial / actividad', 'icono' => 'bi-clock-history'],
];

$coincideRuta = static function (string $rutaMenu, string $rutaActual): bool {
    return $rutaActual === $rutaMenu || str_starts_with($rutaActual, $rutaMenu . '/');
};

$activaUrl = '';
$activaLongitud = 0;
foreach ($items as $item) {
    $ruta = (string) $item['url'];
    if ($coincideRuta($ruta, $rutaActual) && strlen($ruta) > $activaLongitud) {
        $activaUrl = $ruta;
        $activaLongitud = strlen($ruta);
    }
}
?>
<aside class="sidebar sidebar-app p-3 border-end bg-white">
  <h6 class="sidebar-app__titulo text-uppercase mb-3">Mi Empresa</h6>
  <nav class="nav flex-column small gap-1">
    <?php foreach ($items as $item):
      $ruta = (string) $item['url'];
      $activo = $activaUrl === $ruta;
      $esSubmenu = (bool) ($item['submenu'] ?? false);
    ?>
      <a class="nav-link d-flex align-items-center gap-2 <?= $activo ? 'active' : '' ?> <?= $esSubmenu ? 'submenu' : '' ?>" href="<?= e(url($ruta)) ?>" title="<?= e($item['texto']) ?>">
        <i class="bi <?= e($item['icono']) ?>"></i><span><?= e($item['texto']) ?></span>
      </a>
    <?php endforeach; ?>
  </nav>
</aside>
