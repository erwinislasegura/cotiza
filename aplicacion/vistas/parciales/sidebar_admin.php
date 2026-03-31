<?php
$rutaActual = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$items = [
    ['/admin/panel', 'Inicio', 'bi-house-door'],
    ['/admin/panel', 'Dashboard', 'bi-speedometer2'],
    ['/admin/empresas', 'Empresas', 'bi-buildings'],
    ['/admin/administradores-empresa', 'Administradores de empresas', 'bi-people'],
    ['/admin/planes', 'Planes', 'bi-award'],
    ['/admin/funcionalidades', 'Funciones de planes', 'bi-grid-3x3-gap'],
    ['/admin/suscripciones', 'Suscripciones', 'bi-card-checklist'],
    ['/admin/pagos', 'Pagos', 'bi-cash-stack'],
    ['/admin/reportes', 'Reportes', 'bi-bar-chart-line'],
    ['/admin/configuracion', 'Configuración general', 'bi-gear'],
    ['/admin/administradores-empresa', 'Seguridad / credenciales', 'bi-shield-lock'],
    ['/admin/historial', 'Historial / actividad', 'bi-clock-history'],
];
?>
<aside class="sidebar sidebar-app sidebar-admin p-3 border-end">
  <div class="sidebar-app__titulo mb-3">Centro de control SaaS</div>
  <nav class="nav flex-column small gap-1">
    <?php foreach ($items as [$url, $texto, $icono]): ?>
      <a class="nav-link d-flex align-items-center gap-2 <?= str_starts_with($rutaActual, $url) ? 'active' : '' ?>" href="<?= e(url($url)) ?>">
        <i class="bi <?= e($icono) ?>"></i><span><?= e($texto) ?></span>
      </a>
    <?php endforeach; ?>
  </nav>
</aside>
