<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>CotizaPro | Sistema de cotizaciones para empresas</title>
  <meta name="description" content="CotizaPro es un sistema de cotizaciones para empresas que ayuda a vender más con procesos comerciales ordenados, seguimiento de oportunidades y planes escalables.">
  <meta name="keywords" content="sistema de cotizaciones, software de cotizaciones, cotizaciones para empresas, control de cotizaciones, planes de software comercial">
  <meta name="robots" content="index,follow">
  <meta name="theme-color" content="#0d6efd">
  <meta property="og:type" content="website">
  <meta property="og:title" content="CotizaPro | Sistema de cotizaciones para empresas">
  <meta property="og:description" content="Organiza cotizaciones, clientes y ventas en una plataforma profesional para equipos comerciales.">
  <meta property="og:url" content="<?= e((isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . ($_SERVER['REQUEST_URI'] ?? '/')) ?>">
  <meta property="og:site_name" content="CotizaPro">
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="CotizaPro | Sistema de cotizaciones para empresas">
  <meta name="twitter:description" content="Software de cotizaciones enfocado en vender más y mantener control comercial.">
  <link rel="canonical" href="<?= e((isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . ($_SERVER['REQUEST_URI'] ?? '/')) ?>">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="<?= e(url('/assets/css/app.css')) ?>" rel="stylesheet">
</head>
<body class="bg-light public-page">
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
