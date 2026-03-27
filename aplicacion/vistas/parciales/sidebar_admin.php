<?php
$rutaActual = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$items = [
    ['/admin/panel', 'Inicio', 'bi-speedometer2'],
    ['/admin/empresas', 'Empresas', 'bi-buildings'],
    ['/admin/planes', 'Planes', 'bi-award'],
    ['/admin/funcionalidades', 'Funcionalidades', 'bi-grid-3x3-gap'],
    ['/admin/suscripciones', 'Suscripciones', 'bi-card-checklist'],
    ['/admin/pagos', 'Pagos', 'bi-cash-stack'],
    ['/admin/reportes', 'Reportes globales', 'bi-bar-chart-line'],
    ['/admin/configuracion', 'Configuración general', 'bi-gear'],
];
?>
<aside class="sidebar sidebar-app p-3 border-end bg-white">
  <h6 class="text-uppercase text-muted mb-3">Superadministrador</h6>
  <nav class="nav flex-column small gap-1">
    <?php foreach ($items as [$url, $texto, $icono]): ?>
      <a class="nav-link d-flex align-items-center gap-2 <?= str_starts_with($rutaActual, $url) ? 'active' : '' ?>" href="<?= e(url($url)) ?>">
        <i class="bi <?= e($icono) ?>"></i><span><?= e($texto) ?></span>
      </a>
    <?php endforeach; ?>
  </nav>
</aside>
