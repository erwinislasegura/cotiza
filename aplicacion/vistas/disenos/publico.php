<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>CotizaPro</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="<?= e(url('/assets/css/app.css')) ?>" rel="stylesheet">
</head>
<body class="bg-light">
<?php require __DIR__ . '/../parciales/navbar_publico.php'; ?>
<main>
  <?php if ($flash = obtener_flash()): ?>
    <div class="container pt-3"><div class="alert alert-<?= e($flash['tipo']) ?>"><?= e($flash['mensaje']) ?></div></div>
  <?php endif; ?>
  <?php require $contenido; ?>
</main>
<?php require __DIR__ . '/../parciales/footer_publico.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>window.APP_BASE_PATH = "<?= e(base_path_url()) ?>";</script>
<script src="<?= e(url('/assets/js/app.js')) ?>"></script>
</body>
</html>
