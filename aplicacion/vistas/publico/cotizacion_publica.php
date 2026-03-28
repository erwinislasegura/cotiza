<?php
$flash = obtener_flash();
$estado = (string) ($cotizacion['estado'] ?? 'borrador');
$badge = $estado === 'aprobada' ? 'success' : ($estado === 'rechazada' ? 'danger' : 'warning');
$puedeDecidir = in_array($estado, ['enviada', 'borrador'], true);
?>
<section class="py-4">
  <div class="container" style="max-width: 980px;">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h1 class="h4 mb-0">Cotización pública <?= e($cotizacion['numero'] ?? '') ?></h1>
      <span class="badge text-bg-<?= e($badge) ?> text-uppercase"><?= e($estado) ?></span>
    </div>

    <?php if ($flash): ?>
      <div class="alert alert-<?= e($flash['tipo']) ?>"><?= e($flash['mensaje']) ?></div>
    <?php endif; ?>

    <div class="card mb-3">
      <div class="card-body row g-3">
        <div class="col-md-6"><strong>Cliente:</strong> <?= e($cotizacion['cliente'] ?? '') ?></div>
        <div class="col-md-3"><strong>Emisión:</strong> <?= e($cotizacion['fecha_emision'] ?? '') ?></div>
        <div class="col-md-3"><strong>Vencimiento:</strong> <?= e($cotizacion['fecha_vencimiento'] ?? '') ?></div>
        <div class="col-md-6"><strong>Vendedor:</strong> <?= e($cotizacion['vendedor'] ?? '') ?></div>
        <div class="col-md-6"><strong>Correo de contacto:</strong> <?= e($cotizacion['cliente_correo'] ?? '') ?></div>
      </div>
    </div>

    <div class="card mb-3">
      <div class="card-header">Detalle del producto / servicio</div>
      <div class="table-responsive">
        <table class="table table-sm mb-0">
          <thead>
          <tr>
            <th>Producto</th>
            <th>Detalle</th>
            <th class="text-end">Cantidad</th>
            <th class="text-end">Precio</th>
            <th class="text-end">Total</th>
          </tr>
          </thead>
          <tbody>
          <?php foreach (($cotizacion['items'] ?? []) as $item): ?>
            <?php $detalle = trim((string) ($item['descripcion'] ?? '')) !== '' ? (string) $item['descripcion'] : ((string) ($item['producto_descripcion'] ?? '') !== '' ? (string) $item['producto_descripcion'] : (string) ($item['producto_nombre'] ?? 'Ítem')); ?>
            <tr>
              <td><?= e((string) ($item['producto_nombre'] ?? $item['codigo'] ?? 'Ítem')) ?></td>
              <td><?= e($detalle) ?></td>
              <td class="text-end"><?= e(number_format((float) ($item['cantidad'] ?? 0), 2)) ?></td>
              <td class="text-end">$<?= e(number_format((float) ($item['precio_unitario'] ?? 0), 2)) ?></td>
              <td class="text-end">$<?= e(number_format((float) ($item['total'] ?? 0), 2)) ?></td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>

    <div class="card mb-3">
      <div class="card-body">
        <div class="d-flex justify-content-between"><span>Subtotal</span><strong>$<?= e(number_format((float) ($cotizacion['subtotal'] ?? 0), 2)) ?></strong></div>
        <div class="d-flex justify-content-between"><span>Descuento</span><strong>$<?= e(number_format((float) ($cotizacion['descuento'] ?? 0), 2)) ?></strong></div>
        <div class="d-flex justify-content-between"><span>IVA</span><strong>$<?= e(number_format((float) ($cotizacion['impuesto'] ?? 0), 2)) ?></strong></div>
        <hr>
        <div class="d-flex justify-content-between h5 mb-0"><span>Total</span><strong>$<?= e(number_format((float) ($cotizacion['total'] ?? 0), 2)) ?></strong></div>
      </div>
    </div>

    <?php if ($puedeDecidir): ?>
      <div class="d-flex gap-2">
        <form method="POST" action="<?= e(url('/cotizacion/publica/' . $token . '/decision')) ?>">
          <input type="hidden" name="decision" value="aprobada">
          <button class="btn btn-success">Aceptar cotización</button>
        </form>
        <form method="POST" action="<?= e(url('/cotizacion/publica/' . $token . '/decision')) ?>" onsubmit="return confirm('¿Confirmas rechazar esta cotización?');">
          <input type="hidden" name="decision" value="rechazada">
          <button class="btn btn-outline-danger">Rechazar cotización</button>
        </form>
      </div>
    <?php endif; ?>
  </div>
</section>
