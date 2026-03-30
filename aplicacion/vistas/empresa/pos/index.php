<?php
$aperturaTexto = $apertura['caja_nombre'] . ' · Apertura #' . $apertura['id'];
?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <div>
    <h1 class="h4 mb-0">Punto de venta</h1>
    <small class="text-muted">Caja activa: <?= e($aperturaTexto) ?> · Usuario: <?= e(usuario_actual()['nombre'] ?? '') ?></small>
  </div>
  <div class="d-flex gap-2">
    <a class="btn btn-outline-secondary btn-sm" href="<?= e(url('/app/punto-venta/ventas')) ?>">Historial</a>
    <a class="btn btn-outline-secondary btn-sm" href="<?= e(url('/app/punto-venta/cierre-caja')) ?>">Cerrar caja</a>
  </div>
</div>

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
    <div class="col-lg-6">
      <div class="card h-100">
        <div class="card-body">
          <h2 class="h6">Detalle de venta</h2>
          <div class="row g-2 mb-2">
            <div class="col-6">
              <select class="form-select form-select-sm" id="selector_tipo_venta">
                <option value="rapida">Venta rápida</option>
                <option value="registrada">Cliente registrado</option>
              </select>
            </div>
            <div class="col-6">
              <select class="form-select form-select-sm" id="selector_cliente">
                <option value="">Consumidor final</option>
                <?php foreach ($clientes as $cliente): ?>
                  <option value="<?= (int) $cliente['id'] ?>"><?= e(($cliente['razon_social'] ?: $cliente['nombre_comercial'] ?: $cliente['nombre'])) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>

          <div class="table-responsive" style="max-height: 340px; overflow:auto;">
            <table class="table table-sm align-middle">
              <thead><tr><th>Producto</th><th>Cant.</th><th>P. Unit</th><th>Subt.</th><th></th></tr></thead>
              <tbody id="carrito_body"><tr><td colspan="5" class="text-muted">Sin productos agregados.</td></tr></tbody>
            </table>
          </div>

          <div class="border rounded p-2 bg-light small">
            <div class="d-flex justify-content-between"><span>Subtotal</span><strong id="txt_subtotal">0.00</strong></div>
            <div class="d-flex justify-content-between"><span>Descuento</span><strong id="txt_descuento">0.00</strong></div>
            <div class="d-flex justify-content-between"><span>Impuestos</span><strong id="txt_impuesto">0.00</strong></div>
            <div class="d-flex justify-content-between fs-5"><span>Total</span><strong id="txt_total">0.00</strong></div>
          </div>

          <div class="row g-2 mt-2">
            <div class="col-md-5"><select class="form-select form-select-sm" id="metodo_pago"><option value="efectivo">Efectivo</option><option value="transferencia">Transferencia</option><option value="tarjeta">Tarjeta</option></select></div>
            <div class="col-md-4"><input type="number" step="0.01" min="0" class="form-control form-control-sm" id="monto_pago" placeholder="Monto"></div>
            <div class="col-md-3"><button class="btn btn-outline-primary btn-sm w-100" type="button" id="agregar_pago">Agregar pago</button></div>
            <div class="col-12"><input class="form-control form-control-sm" id="referencia_pago" placeholder="Referencia (opcional)"></div>
          </div>
          <div class="small mt-2" id="listado_pagos"></div>

          <div class="row g-2 mt-2">
            <div class="col-md-6"><input class="form-control form-control-sm" type="number" step="0.01" min="0" id="monto_recibido" placeholder="Monto recibido"></div>
            <div class="col-md-6"><input class="form-control form-control-sm" id="monto_vuelto" readonly placeholder="Vuelto"></div>
          </div>

          <div class="d-flex gap-2 mt-3">
            <button class="btn btn-success" type="submit">Cobrar y finalizar</button>
            <button class="btn btn-outline-danger" type="button" id="cancelar_venta">Cancelar</button>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-6">
      <div class="card h-100">
        <div class="card-body">
          <h2 class="h6">Catálogo rápido</h2>
          <div class="row g-2">
            <?php foreach ($productos as $producto): ?>
              <div class="col-md-6">
                <button type="button" class="btn btn-light border w-100 text-start py-2 js-producto" data-id="<?= (int) $producto['id'] ?>" data-nombre="<?= e($producto['nombre']) ?>" data-codigo="<?= e($producto['codigo'] ?? '') ?>" data-precio="<?= e((string) ($producto['precio'] ?? 0)) ?>" data-impuesto="<?= e((string) ($producto['impuesto'] ?? 0)) ?>" data-stock="<?= e((string) ($producto['stock_actual'] ?? 0)) ?>">
                  <div class="fw-semibold"><?= e($producto['nombre']) ?></div>
                  <div class="small text-muted">Código: <?= e($producto['codigo'] ?? '') ?> · Stock: <?= e(number_format((float) ($producto['stock_actual'] ?? 0), 2)) ?></div>
                  <div class="text-primary fw-bold">$ <?= e(number_format((float) ($producto['precio'] ?? 0), 2)) ?></div>
                </button>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</form>

<script>
(() => {
  const carrito = [];
  const pagos = [];

  const body = document.getElementById('carrito_body');
  const impuestoDefault = Number('<?= e((string) ($configuracion['impuesto_por_defecto'] ?? 0)) ?>');

  function fmt(n) { return Number(n || 0).toFixed(2); }

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
    document.getElementById('txt_subtotal').textContent = fmt(subtotal);
    document.getElementById('txt_descuento').textContent = fmt(descuento);
    document.getElementById('txt_impuesto').textContent = fmt(impuesto);
    document.getElementById('txt_total').textContent = fmt(total);

    document.getElementById('input_subtotal').value = fmt(subtotal);
    document.getElementById('input_descuento').value = fmt(descuento);
    document.getElementById('input_impuesto').value = fmt(impuesto);
    document.getElementById('input_total').value = fmt(total);

    const recibido = Number(document.getElementById('monto_recibido').value || 0);
    const vuelto = Math.max(0, recibido - total);
    document.getElementById('monto_vuelto').value = fmt(vuelto);
    document.getElementById('input_recibido').value = fmt(recibido);
    document.getElementById('input_vuelto').value = fmt(vuelto);

    document.getElementById('items_json').value = JSON.stringify(carrito.map((i) => ({
      producto_id: i.id,
      codigo_producto: i.codigo,
      nombre_producto: i.nombre,
      cantidad: i.cantidad,
      precio_unitario: i.precio,
      descuento: i.descuento,
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
      return;
    }
    div.innerHTML = pagos.map((p, idx) => `<div class="d-flex justify-content-between border rounded p-1 mb-1"><span>${p.metodo_pago} ${p.referencia ? '- ' + p.referencia : ''}</span><strong>$ ${fmt(p.monto)}</strong></div>`).join('');
    document.getElementById('pagos_json').value = JSON.stringify(pagos);
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
      const subtotal = item.cantidad * item.precio;
      row.innerHTML = `<td><div class="fw-semibold">${item.nombre}</div><small class="text-muted">${item.codigo}</small></td>
        <td><input class="form-control form-control-sm" type="number" min="1" step="1" value="${item.cantidad}" data-idx="${idx}" data-tipo="cantidad"></td>
        <td><input class="form-control form-control-sm" type="number" min="0" step="0.01" value="${item.precio}" data-idx="${idx}" data-tipo="precio"></td>
        <td>$ ${fmt(subtotal)}</td>
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
    pagos.push({
      metodo_pago: document.getElementById('metodo_pago').value,
      monto: Number(document.getElementById('monto_pago').value || 0),
      referencia: document.getElementById('referencia_pago').value.trim(),
    });
    document.getElementById('monto_pago').value = '';
    document.getElementById('referencia_pago').value = '';
    pintarPagos();
  });

  document.getElementById('monto_recibido').addEventListener('input', calcularTotales);

  document.getElementById('selector_tipo_venta').addEventListener('change', (e) => {
    document.getElementById('tipo_venta').value = e.target.value;
  });

  document.getElementById('selector_cliente').addEventListener('change', (e) => {
    document.getElementById('cliente_id').value = e.target.value;
  });

  document.getElementById('cancelar_venta').addEventListener('click', () => {
    carrito.splice(0, carrito.length);
    pagos.splice(0, pagos.length);
    render();
    pintarPagos();
  });

  document.getElementById('form_pos').addEventListener('submit', (e) => {
    calcularTotales();
    if (!carrito.length || !pagos.length) {
      e.preventDefault();
      alert('Debes cargar productos y pagos para finalizar la venta.');
    }
  });

  render();
  pintarPagos();
})();
</script>
