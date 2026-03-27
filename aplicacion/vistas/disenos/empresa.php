<!doctype html><html lang="es"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>App - CotizaPro</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"><link href="/assets/css/app.css" rel="stylesheet"></head>
<body>
<div class="d-flex">
  <?php require __DIR__ . '/../parciales/sidebar_empresa.php'; ?>
  <div class="flex-grow-1">
    <?php require __DIR__ . '/../parciales/topbar.php'; ?>
    <div class="container-fluid py-3"><?php if ($flash = obtener_flash()): ?><div class="alert alert-<?= e($flash['tipo']) ?>"><?= e($flash['mensaje']) ?></div><?php endif; require $contenido; ?></div>
  </div>
</div>
<script src="/assets/js/app.js"></script>
</body></html>
