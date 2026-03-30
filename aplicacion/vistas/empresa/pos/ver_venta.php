<?php $autoImprimir = isset($_GET['imprimir']) && $_GET['imprimir'] === '1'; ?>
<div class="d-flex justify-content-between align-items-center mb-3 no-print">
  <h1 class="h5 mb-0">Boucher de pago <?= e($venta['numero_venta']) ?></h1>
  <div>
    <a class="btn btn-outline-secondary btn-sm" href="<?= e(url('/app/punto-venta/ventas')) ?>">Volver</a>
    <button class="btn btn-primary btn-sm" onclick="window.print()">Imprimir</button>
  </div>
</div>

<div class="card mx-auto" style="max-width: 420px;">
  <div class="card-body small" id="boucher_pago">
    <div class="text-center mb-2">
      <strong>COMPROBANTE POS</strong><br>
      <span><?= e($venta['numero_venta']) ?></span><br>
      <span><?= e($venta['fecha_venta']) ?></span>
    </div>
    <div>Caja: <strong><?= e($venta['caja_nombre']) ?></strong></div>
    <div>Cajero: <strong><?= e($venta['cajero'] ?? '') ?></strong></div>
    <div>Cliente: <strong><?= e($venta['cliente_nombre']) ?></strong></div>
    <hr>
    <?php foreach ($venta['items'] as $item): ?>
      <div class="d-flex justify-content-between">
        <span><?= e($item['nombre_producto']) ?> x <?= number_format((float) $item['cantidad'], 2) ?></span>
        <strong>$ <?= number_format((float) $item['total'], 2) ?></strong>
      </div>
    <?php endforeach; ?>
    <hr>
    <div class="d-flex justify-content-between"><span>Subtotal</span><strong>$ <?= number_format((float) $venta['subtotal'], 2) ?></strong></div>
    <div class="d-flex justify-content-between"><span>Descuento</span><strong>$ <?= number_format((float) $venta['descuento'], 2) ?></strong></div>
    <div class="d-flex justify-content-between"><span>Impuesto</span><strong>$ <?= number_format((float) $venta['impuesto'], 2) ?></strong></div>
    <div class="d-flex justify-content-between fs-6"><span>Total</span><strong>$ <?= number_format((float) $venta['total'], 2) ?></strong></div>
    <hr>
    <?php foreach ($venta['pagos'] as $pago): ?>
      <div class="d-flex justify-content-between"><span><?= e(ucfirst($pago['metodo_pago'])) ?></span><strong>$ <?= number_format((float) $pago['monto'], 2) ?></strong></div>
    <?php endforeach; ?>
    <div class="d-flex justify-content-between"><span>Monto recibido</span><strong>$ <?= number_format((float) $venta['monto_recibido'], 2) ?></strong></div>
    <div class="d-flex justify-content-between"><span>Vuelto</span><strong>$ <?= number_format((float) $venta['vuelto'], 2) ?></strong></div>
    <div class="text-center mt-3">Gracias por su compra</div>
  </div>
</div>

<style>
@media print {
  .no-print { display: none !important; }
  body { background: #fff; }
  #boucher_pago { font-size: 12px; }
}
</style>

<?php if ($autoImprimir): ?>
<script>
  window.addEventListener('load', () => window.print());
</script>
<?php endif; ?>
