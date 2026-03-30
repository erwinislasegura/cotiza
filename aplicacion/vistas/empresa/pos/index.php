<?php
$aperturaTexto = $apertura ? ($apertura['caja_nombre'] . ' · Apertura #' . $apertura['id']) : 'Caja cerrada';
$esperado = $apertura ? (float) $apertura['monto_inicial'] + (float) ($resumenCierre['total_ventas'] ?? 0) : 0;
$usarDecimales = (int) ($configuracion['usar_decimales'] ?? 1) === 1;
$cantidadDecimales = (int) ($configuracion['cantidad_decimales'] ?? 2);
?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <div>
    <h1 class="h4 mb-0">Punto de venta</h1>
    <small class="text-muted">Estado de caja: <?= e($aperturaTexto) ?> · Usuario: <?= e(usuario_actual()['nombre'] ?? '') ?></small>
  </div>
  <div class="d-flex gap-2">
    <?php if ($apertura): ?>
      <button class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalCierreCaja">Cerrar caja</button>
    <?php else: ?>
      <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalAperturaCaja">Abrir caja</button>
    <?php endif; ?>
    <a class="btn btn-outline-secondary btn-sm" href="<?= e(url('/app/punto-venta/ventas')) ?>">Historial</a>
  </div>
</div>

<?php if (!$apertura): ?>
  <div class="alert alert-warning">Para vender debes abrir una caja desde el botón <strong>Abrir caja</strong>.</div>
<?php endif; ?>

<form method="GET" class="row g-2 mb-3">
  <div class="col-md-6"><input class="form-control form-control-sm" name="q" value="<?= e($buscar) ?>" placeholder="Buscar por nombre, código, SKU o barras"></div>
  <div class="col-md-4">
    <select class="form-select form-select-sm" name="categoria_id">
      <option value="">Todas las categorías</option>
      <?php foreach ($categorias as $categoria): ?>
        <option value="<?= (int) $categoria['id'] ?>" <?= (int) ($categoriaId ?? 0) === (int) $categoria['id'] ? 'selected' : '' ?>><?= e($categoria['nombre'] ?? '') ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="col-md-2 d-grid"><button class="btn btn-sm btn-outline-primary">Filtrar</button></div>
</form>

<form method="POST" action="<?= e(url('/app/punto-venta/venta/guardar')) ?>" id="form_pos">
  <?= csrf_campo() ?>
  <input type="hidden" name="tipo_venta" id="tipo_venta" value="rapida">
  <input type="hidden" name="cliente_id" id="cliente_id" value="">
  <input type="hidden" name="subtotal" id="input_subtotal" value="0">
  <input type="hidden" name="descuento" id="input_descuento" value="0">
  <input type="hidden" name="impuesto" id="input_impuesto" value="0">
  <input type="hidden" name="total" id="input_total" value="0">
  <input type="hidden" name="monto_recibido" id="input_recibido" value="0">
  <input type="hidden" name="vuelto" id="input_vuelto" value="0">
  <input type="hidden" name="items_json" id="items_json" value="[]">
  <input type="hidden" name="pagos_json" id="pagos_json" value="[]">

  <div class="row g-3">
    <div class="col-lg-8">
      <div class="card h-100">
        <div class="card-body">
          <h2 class="h6">Detalle de compra</h2>
          <div class="row g-2 mb-2">
            <div class="col-6"><select class="form-select form-select-sm" id="selector_tipo_venta"><option value="rapida">Venta rápida</option><option value="registrada">Cliente registrado</option></select></div>
            <div class="col-6"><select class="form-select form-select-sm" id="selector_cliente"><option value="">Consumidor final</option><?php foreach ($clientes as $cliente): ?><option value="<?= (int) $cliente['id'] ?>"><?= e(($cliente['razon_social'] ?: $cliente['nombre_comercial'] ?: $cliente['nombre'])) ?></option><?php endforeach; ?></select></div>
          </div>

          <div class="table-responsive" style="max-height: 340px; overflow:auto;">
            <table class="table table-sm align-middle">
              <thead><tr><th>Producto</th><th>Cant.</th><th>Precio</th><th>Total línea</th><th></th></tr></thead>
              <tbody id="carrito_body"><tr><td colspan="5" class="text-muted">Sin productos agregados.</td></tr></tbody>
            </table>
          </div>

          <div class="border rounded p-2 bg-light small">
            <div class="d-flex justify-content-between"><span>Subtotal</span><strong id="txt_subtotal">$0.00</strong></div>
            <div class="d-flex justify-content-between"><span>Descuento</span><strong id="txt_descuento">$0.00</strong></div>
            <div class="d-flex justify-content-between"><span>Impuestos</span><strong id="txt_impuesto">$0.00</strong></div>
            <div class="d-flex justify-content-between fs-5"><span>Total</span><strong id="txt_total">$0.00</strong></div>
          </div>

          <div class="row g-2 mt-2">
            <div class="col-md-4"><select class="form-select form-select-sm" id="metodo_pago"><option value="efectivo">Efectivo</option><option value="transferencia">Transferencia</option><option value="tarjeta">Tarjeta</option></select></div>
            <div class="col-md-3"><input type="number" step="0.01" min="0" class="form-control form-control-sm" id="monto_pago" placeholder="Monto"></div>
            <div class="col-md-3"><input class="form-control form-control-sm" id="referencia_pago" placeholder="Referencia"></div>
            <div class="col-md-2"><button class="btn btn-outline-primary btn-sm w-100" type="button" id="agregar_pago">Agregar</button></div>
          </div>

          <div class="small mt-2" id="listado_pagos"></div>

          <div class="row g-2 mt-2">
            <div class="col-md-6">
              <label class="form-label small">Monto recibido (solo efectivo)</label>
              <input class="form-control form-control-sm" type="number" step="0.01" min="0" id="monto_recibido" placeholder="Monto recibido">
            </div>
            <div class="col-md-6">
              <label class="form-label small">Vuelto</label>
              <input class="form-control form-control-sm" id="monto_vuelto" readonly placeholder="$0.00">
            </div>
          </div>

          <div class="d-flex gap-2 mt-3">
            <button class="btn btn-success" type="submit" <?= $apertura ? '' : 'disabled' ?>>Cobrar y emitir boucher</button>
            <button class="btn btn-outline-danger" type="button" id="cancelar_venta">Cancelar</button>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-4">
      <div class="card h-100">
        <div class="card-body">
          <h2 class="h6">Productos</h2>
          <div class="d-grid gap-2" style="max-height: 560px; overflow:auto;">
            <?php foreach ($productos as $producto): ?>
              <button type="button" class="btn btn-light border text-start py-2 js-producto" <?= $apertura ? '' : 'disabled' ?> data-id="<?= (int) $producto['id'] ?>" data-nombre="<?= e($producto['nombre']) ?>" data-codigo="<?= e($producto['codigo'] ?? '') ?>" data-precio="<?= e((string) ($producto['precio'] ?? 0)) ?>" data-impuesto="<?= e((string) ($producto['impuesto'] ?? 0)) ?>" data-stock="<?= e((string) ($producto['stock_actual'] ?? 0)) ?>">
                <div class="fw-semibold small"><?= e($producto['nombre']) ?></div>
                <div class="small text-muted">Cod: <?= e($producto['codigo'] ?? '') ?> · Stock: <?= e(number_format((float) ($producto['stock_actual'] ?? 0), 2)) ?></div>
                <div class="text-primary fw-bold small">$ <?= e(number_format((float) ($producto['precio'] ?? 0), 2)) ?></div>
              </button>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</form>

<div class="modal fade" id="modalAperturaCaja" tabindex="-1" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><form method="POST" action="<?= e(url('/app/punto-venta/apertura-caja')) ?>"><?= csrf_campo() ?><div class="modal-header"><h5 class="modal-title">Apertura de caja</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body row g-2"><div class="col-12"><label class="form-label">Caja / terminal</label><select name="caja_id" class="form-select" required><option value="">Selecciona...</option><?php foreach ($cajas as $caja): ?><option value="<?= (int) $caja['id'] ?>"><?= e($caja['nombre']) ?> (<?= e($caja['codigo']) ?>)</option><?php endforeach; ?></select></div><div class="col-12"><label class="form-label">Monto inicial</label><input class="form-control" type="number" step="0.01" min="0" name="monto_inicial" required></div><div class="col-12"><label class="form-label">Observación</label><input class="form-control" name="observacion" placeholder="Opcional"></div></div><div class="modal-footer"><button class="btn btn-primary">Abrir caja</button></div></form></div></div></div>

<div class="modal fade" id="modalCierreCaja" tabindex="-1" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><form method="POST" action="<?= e(url('/app/punto-venta/cierre-caja')) ?>"><?= csrf_campo() ?><div class="modal-header"><h5 class="modal-title">Cierre de caja</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body"><?php if ($apertura): ?><ul class="small"><li>Monto inicial: <strong>$ <?= number_format((float) $apertura['monto_inicial'], 2) ?></strong></li><li>Ventas efectivo: <strong>$ <?= number_format((float) ($resumenCierre['efectivo'] ?? 0), 2) ?></strong></li><li>Ventas transferencia: <strong>$ <?= number_format((float) ($resumenCierre['transferencia'] ?? 0), 2) ?></strong></li><li>Ventas tarjeta: <strong>$ <?= number_format((float) ($resumenCierre['tarjeta'] ?? 0), 2) ?></strong></li><li>Total esperado: <strong>$ <?= number_format((float) $esperado, 2) ?></strong></li></ul><label class="form-label">Monto contado</label><input class="form-control mb-2" type="number" step="0.01" min="0" name="monto_contado" required><label class="form-label">Observación de cierre</label><input class="form-control" name="observacion"><?php else: ?><p class="text-muted">No hay caja abierta.</p><?php endif; ?></div><div class="modal-footer"><button class="btn btn-danger" <?= $apertura ? '' : 'disabled' ?>>Cerrar caja</button></div></form></div></div></div>

<script>
(() => {
  const carrito = [];
  const pagos = [];
  const body = document.getElementById('carrito_body');
  const impuestoDefault = Number('<?= e((string) ($configuracion['impuesto_por_defecto'] ?? 0)) ?>') || 0;
  const usarDecimales = Number('<?= $usarDecimales ? '1' : '0' ?>') === 1;
  const decRaw = parseInt('<?= e((string) $cantidadDecimales) ?>', 10);
  const decimales = usarDecimales ? (Number.isFinite(decRaw) ? Math.min(6, Math.max(0, decRaw)) : 2) : 0;
  const money = new Intl.NumberFormat('es-CL', { style: 'currency', currency: 'USD', minimumFractionDigits: 2 });

  function fmtNum(n) { return Number(n || 0).toFixed(decimales); }
  function fmtMoney(n) { return money.format(Number(n || 0)); }
  function pasoCantidad() { return usarDecimales ? '0.01' : '1'; }

  function totalPagadoEfectivo() {
    return pagos.filter((p) => p.metodo_pago === 'efectivo').reduce((acc, p) => acc + Number(p.monto || 0), 0);
  }

  function calcularTotales() {
    let subtotal = 0;
    let impuesto = 0;
    let descuento = 0;

    carrito.forEach((i) => {
      subtotal += i.cantidad * i.precio;
      impuesto += (i.cantidad * i.precio - i.descuento) * (i.impuestoPct / 100);
      descuento += i.descuento;
    });

    const total = subtotal - descuento + impuesto;

    document.getElementById('txt_subtotal').textContent = fmtMoney(subtotal);
    document.getElementById('txt_descuento').textContent = fmtMoney(descuento);
    document.getElementById('txt_impuesto').textContent = fmtMoney(impuesto);
    document.getElementById('txt_total').textContent = fmtMoney(total);

    document.getElementById('input_subtotal').value = subtotal.toFixed(2);
    document.getElementById('input_descuento').value = descuento.toFixed(2);
    document.getElementById('input_impuesto').value = impuesto.toFixed(2);
    document.getElementById('input_total').value = total.toFixed(2);

    const recibido = Number(document.getElementById('monto_recibido').value || 0);
    const tienePagoEfectivo = totalPagadoEfectivo() > 0;
    const vuelto = tienePagoEfectivo ? Math.max(0, recibido - total) : 0;

    document.getElementById('monto_vuelto').value = fmtMoney(vuelto);
    document.getElementById('input_recibido').value = recibido.toFixed(2);
    document.getElementById('input_vuelto').value = vuelto.toFixed(2);

    document.getElementById('items_json').value = JSON.stringify(carrito.map((i) => ({
      producto_id: i.id,
      codigo_producto: i.codigo,
      nombre_producto: i.nombre,
      cantidad: Number(i.cantidad),
      precio_unitario: Number(i.precio),
      descuento: Number(i.descuento),
      impuesto: ((i.cantidad * i.precio - i.descuento) * (i.impuestoPct / 100)),
      subtotal: i.cantidad * i.precio,
      total: (i.cantidad * i.precio - i.descuento) + ((i.cantidad * i.precio - i.descuento) * (i.impuestoPct / 100)),
    })));
  }

  function pintarPagos() {
    const div = document.getElementById('listado_pagos');
    if (!pagos.length) {
      div.innerHTML = '<span class="text-muted">Sin pagos registrados.</span>';
      document.getElementById('pagos_json').value = '[]';
      calcularTotales();
      return;
    }

    div.innerHTML = pagos.map((p, idx) => `
      <div class="d-flex justify-content-between border rounded p-1 mb-1">
        <span>${p.metodo_pago}${p.referencia ? ' - ' + p.referencia : ''}</span>
        <span>
          <strong>${fmtMoney(p.monto)}</strong>
          <button type="button" class="btn btn-link btn-sm text-danger p-0 ms-2" data-pago-idx="${idx}">Quitar</button>
        </span>
      </div>`).join('');

    document.getElementById('pagos_json').value = JSON.stringify(pagos);
    calcularTotales();
  }

  function render() {
    if (!carrito.length) {
      body.innerHTML = '<tr><td colspan="5" class="text-muted">Sin productos agregados.</td></tr>';
      calcularTotales();
      return;
    }

    body.innerHTML = '';
    carrito.forEach((item, idx) => {
      const row = document.createElement('tr');
      const linea = item.cantidad * item.precio;
      row.innerHTML = `<td><div class="fw-semibold">${item.nombre}</div><small class="text-muted">${item.codigo}</small></td>
        <td><input class="form-control form-control-sm" type="number" min="1" step="${pasoCantidad()}" value="${fmtNum(item.cantidad)}" data-idx="${idx}" data-tipo="cantidad"></td>
        <td><input class="form-control form-control-sm" type="number" min="0" step="0.01" value="${Number(item.precio).toFixed(2)}" data-idx="${idx}" data-tipo="precio"></td>
        <td>${fmtMoney(linea)}</td>
        <td><button type="button" class="btn btn-sm btn-outline-danger" data-idx="${idx}" data-tipo="eliminar">✕</button></td>`;
      body.appendChild(row);
    });

    calcularTotales();
  }

  document.querySelectorAll('.js-producto').forEach((btn) => {
    btn.addEventListener('click', () => {
      const id = Number(btn.dataset.id);
      const existing = carrito.find((i) => i.id === id);
      if (existing) {
        existing.cantidad += 1;
      } else {
        carrito.push({
          id,
          nombre: btn.dataset.nombre,
          codigo: btn.dataset.codigo,
          precio: Number(btn.dataset.precio || 0),
          impuestoPct: Number(btn.dataset.impuesto || impuestoDefault || 0),
          descuento: 0,
          cantidad: 1,
          stock: Number(btn.dataset.stock || 0),
        });
      }
      render();
    });
  });

  body.addEventListener('input', (e) => {
    const el = e.target;
    const idx = Number(el.dataset.idx);
    if (Number.isNaN(idx) || !carrito[idx]) return;
    if (el.dataset.tipo === 'cantidad') carrito[idx].cantidad = Math.max(1, Number(el.value || 1));
    if (el.dataset.tipo === 'precio') carrito[idx].precio = Math.max(0, Number(el.value || 0));
    calcularTotales();
  });

  body.addEventListener('click', (e) => {
    const idx = Number(e.target.dataset.idx);
    if (e.target.dataset.tipo === 'eliminar' && !Number.isNaN(idx)) {
      carrito.splice(idx, 1);
      render();
    }
  });

  document.getElementById('agregar_pago').addEventListener('click', () => {
    const monto = Number(document.getElementById('monto_pago').value || 0);
    if (monto <= 0) {
      alert('Ingresa un monto de pago mayor a 0.');
      return;
    }

    pagos.push({
      metodo_pago: document.getElementById('metodo_pago').value,
      monto,
      referencia: document.getElementById('referencia_pago').value.trim(),
    });

    document.getElementById('monto_pago').value = '';
    document.getElementById('referencia_pago').value = '';
    pintarPagos();
  });

  document.getElementById('listado_pagos').addEventListener('click', (e) => {
    const idx = Number(e.target.dataset.pagoIdx);
    if (Number.isNaN(idx)) return;
    pagos.splice(idx, 1);
    pintarPagos();
  });

  document.getElementById('monto_recibido').addEventListener('input', calcularTotales);
  document.getElementById('selector_tipo_venta').addEventListener('change', (e) => { document.getElementById('tipo_venta').value = e.target.value; });
  document.getElementById('selector_cliente').addEventListener('change', (e) => { document.getElementById('cliente_id').value = e.target.value; });

  document.getElementById('cancelar_venta').addEventListener('click', () => {
    carrito.splice(0, carrito.length);
    pagos.splice(0, pagos.length);
    document.getElementById('monto_recibido').value = '';
    render();
    pintarPagos();
  });

  document.getElementById('form_pos').addEventListener('submit', (e) => {
    calcularTotales();
    if (!carrito.length) {
      e.preventDefault();
      alert('Debes cargar productos para finalizar la venta.');
      return;
    }
    if (!pagos.length) {
      e.preventDefault();
      alert('Debes registrar al menos un pago.');
      return;
    }
    document.getElementById('pagos_json').value = JSON.stringify(pagos);
  });

  render();
  pintarPagos();
})();
</script>
