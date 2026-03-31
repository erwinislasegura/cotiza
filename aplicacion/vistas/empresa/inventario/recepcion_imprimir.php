<?php
$modoPdf = ($_GET['modo'] ?? '') === 'pdf';
$total = 0.0;
foreach (($recepcion['detalles'] ?? []) as $item) {
    $total += (float) ($item['subtotal'] ?? 0);
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Recepción <?= e($recepcion['numero_documento'] ?? '') ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    body{font-family:Arial,Helvetica,sans-serif;background:#f5f7fb;margin:0;padding:20px;color:#1f2937}
    .doc{max-width:980px;margin:0 auto;background:#fff;border:1px solid #dbe3ef;border-radius:12px;overflow:hidden}
    .head{padding:22px;border-bottom:4px solid #114477}
    .head h1{margin:0;font-size:24px;color:#114477}.head small{color:#6b7280}
    .grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:10px;padding:18px 22px}
    .box{background:#f8fbff;border:1px solid #dce8f8;border-radius:8px;padding:10px}
    .box strong{display:block;color:#114477;margin-bottom:4px}
    table{width:100%;border-collapse:collapse}th,td{padding:8px;border-bottom:1px solid #e5e7eb;font-size:13px}
    thead th{background:#114477;color:#fff;text-align:left}td.right,th.right{text-align:right}
    .tot{display:flex;justify-content:flex-end;padding:16px 22px}.tot table{width:280px}
    .tot td{border:1px solid #dbe3ef}.tot .final td{background:#114477;color:#fff;font-weight:700}
    .tools{max-width:980px;margin:0 auto 10px;display:flex;gap:8px}
    @media print {.tools{display:none}body{background:#fff;padding:0}.doc{border:0;border-radius:0}}
  </style>
</head>
<body>
<?php if (!$modoPdf): ?>
<div class="tools">
  <button class="btn btn-dark btn-sm" type="button" onclick="window.print()">Imprimir / Guardar PDF</button>
  <a class="btn btn-outline-secondary btn-sm" href="<?= e(url('/app/inventario/recepciones/editar/' . (int) $recepcion['id'])) ?>">Volver</a>
</div>
<?php endif; ?>
<div class="doc">
  <div class="head"><h1><?= e($empresa['nombre_comercial'] ?? $empresa['razon_social'] ?? 'Empresa') ?></h1><small>Recepción de inventario</small></div>
  <div class="grid">
    <div class="box"><strong>Documento</strong>Tipo: <?= e($recepcion['tipo_documento'] ?? '') ?><br>Número: <?= e($recepcion['numero_documento'] ?? '') ?><br>Fecha: <?= e($recepcion['fecha_documento'] ?? '') ?></div>
    <div class="box"><strong>Proveedor</strong><?= e($recepcion['proveedor_nombre'] ?? '-') ?><br>Correo: <?= e($recepcion['proveedor_correo'] ?? '-') ?></div>
    <div class="box"><strong>Referencia</strong><?= e($recepcion['referencia_interna'] ?? '-') ?></div>
    <div class="box"><strong>Observación</strong><?= e($recepcion['observacion'] ?? '-') ?></div>
  </div>
  <div style="padding:0 22px 10px">
    <table>
      <thead><tr><th>Código</th><th>Descripción</th><th class="right">Cantidad</th><th class="right">Costo unitario</th><th class="right">Subtotal</th></tr></thead>
      <tbody><?php foreach(($recepcion['detalles'] ?? []) as $d): ?><tr><td><?= e($d['codigo'] ?? '') ?></td><td><?= e($d['nombre'] ?? '') ?></td><td class="right"><?= number_format((float)($d['cantidad'] ?? 0),2,',','.') ?></td><td class="right">$<?= number_format((float)($d['costo_unitario'] ?? 0),0,',','.') ?></td><td class="right">$<?= number_format((float)($d['subtotal'] ?? 0),0,',','.') ?></td></tr><?php endforeach; ?></tbody>
    </table>
  </div>
  <div class="tot"><table><tr class="final"><td>Total recepción</td><td class="right">$<?= number_format($total,0,',','.') ?></td></tr></table></div>
</div>
<?php if ($modoPdf): ?><script>setTimeout(()=>window.print(),300);</script><?php endif; ?>
</body>
</html>
