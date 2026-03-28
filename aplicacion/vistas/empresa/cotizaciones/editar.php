<?php
$hayClientes = !empty($clientes);
$hayProductos = !empty($productos);
$puedeGuardar = $hayClientes;
$itemsExistentes = $cotizacion['items'] ?? [];
$descuentoTipoTotal = $cotizacion['descuento_tipo'] ?? 'valor';
$descuentoTotalValor = $cotizacion['descuento_valor'] ?? $cotizacion['descuento'] ?? 0;
$listaPrecioIdSeleccionada = (int) ($listaPrecioSeleccionada['id'] ?? 0);
?>
<h1 class="h4 mb-3">Editar cotización</h1>

<form method="POST" class="d-grid gap-3" id="form-cotizacion-editar">
    <?= csrf_campo() ?>

    <div class="card">
        <div class="card-header">Datos cotización</div>
        <div class="card-body row g-3">
            <div class="col-md-3">
                <label class="small">Número</label>
                <input class="form-control" value="<?= e($cotizacion['numero']) ?>" disabled>
            </div>

            <div class="col-md-5">
                <label class="small">Cliente</label>
                <div class="input-group">
                    <select class="form-select" name="cliente_id" required>
                        <?php foreach ($clientes as $c): ?>
                            <option value="<?= $c['id'] ?>" <?= (int) $cotizacion['cliente_id'] === (int) $c['id'] ? 'selected' : '' ?>><?= e($c['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button class="btn btn-outline-primary" type="button" data-bs-toggle="modal" data-bs-target="#modalCliente">Dato fijo cliente</button>
                </div>
            </div>

            <div class="col-md-2">
                <label class="small">Estado</label>
                <select class="form-select" name="estado">
                    <?php foreach (['borrador', 'enviada', 'aprobada', 'rechazada', 'vencida', 'anulada'] as $estado): ?>
                        <option value="<?= e($estado) ?>" <?= $cotizacion['estado'] === $estado ? 'selected' : '' ?>><?= e($estado) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-2">
                <label class="small">Canal venta</label>
                <select class="form-select" name="canal_venta" id="canal_venta">
                    <option value="">General</option>
                    <option value="local">Local</option>
                    <option value="delivery">Delivery</option>
                    <option value="ecommerce">E-commerce</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="small">Lista de precios aplicada</label>
                <select class="form-select" name="lista_precio_id" id="lista_precio_id">
                    <option value="">Automática por cliente/canal</option>
                    <?php foreach (($listasPrecios ?? []) as $lista): ?>
                        <option value="<?= (int) $lista['id'] ?>" <?= $listaPrecioIdSeleccionada === (int) $lista['id'] ? 'selected' : '' ?>><?= e($lista['nombre']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-3">
                <label class="small">Fecha emisión</label>
                <input class="form-control" type="date" name="fecha_emision" value="<?= e($cotizacion['fecha_emision']) ?>">
            </div>

            <div class="col-md-3">
                <label class="small">Fecha vencimiento</label>
                <input class="form-control" type="date" name="fecha_vencimiento" value="<?= e($cotizacion['fecha_vencimiento']) ?>">
            </div>

            <div class="col-md-6">
                <label class="small">Observaciones</label>
                <input class="form-control" name="observaciones" value="<?= e($cotizacion['observaciones'] ?? '') ?>">
            </div>

            <div class="col-12">
                <label class="small">Términos</label>
                <input class="form-control" name="terminos_condiciones" value="<?= e($cotizacion['terminos_condiciones'] ?? '') ?>">
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Detalle de cotización</span>
            <small class="text-muted">Precio y descuento se recalculan con la lista del cliente/canal.</small>
            <button type="button" class="btn btn-outline-primary btn-sm" id="btn-agregar-linea">Agregar línea</button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-sm align-middle" id="tabla-items">
                    <thead>
                    <tr>
                        <th style="min-width: 220px;">Producto / Servicio</th>
                        <th style="min-width: 180px;">Descripción</th>
                        <th>Cantidad</th>
                        <th>Precio</th>
                        <th>Descuento</th>
                        <th>IVA %</th>
                        <th class="text-end">Subtotal</th>
                        <th class="text-end">IVA</th>
                        <th class="text-end">Total</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody id="cuerpo-items">
                    <?php foreach ($itemsExistentes as $item): ?>
                        <tr>
                            <td>
                                <div class="input-group input-group-sm">
                                    <select class="form-select js-producto" name="producto_id[]">
                                        <option value="">Seleccionar</option>
                                        <?php foreach ($productos as $p): ?>
                                            <option value="<?= $p['id'] ?>" <?= (int) ($item['producto_id'] ?? 0) === (int) $p['id'] ? 'selected' : '' ?>><?= e($p['nombre']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <button class="btn btn-outline-primary" type="button" data-bs-toggle="modal" data-bs-target="#modalProducto">+</button>
                                </div>
                            </td>
                            <td><input class="form-control form-control-sm" name="descripcion_item[]" value="<?= e($item['descripcion'] ?? '') ?>"></td>
                            <td><input class="form-control form-control-sm js-cantidad" type="number" step="0.01" min="0" name="cantidad[]" value="<?= e((string) ($item['cantidad'] ?? 1)) ?>"></td>
                            <td><input class="form-control form-control-sm js-precio" type="number" step="0.01" min="0" name="precio_unitario[]" value="<?= e((string) ($item['precio_unitario'] ?? 0)) ?>"></td>
                            <td>
                                <div class="input-group input-group-sm">
                                    <select class="form-select js-descuento-tipo" name="descuento_tipo_item[]">
                                        <option value="valor" <?= ($item['descuento_tipo'] ?? 'valor') === 'valor' ? 'selected' : '' ?>>$</option>
                                        <option value="porcentaje" <?= ($item['descuento_tipo'] ?? '') === 'porcentaje' ? 'selected' : '' ?>>%</option>
                                    </select>
                                    <input class="form-control js-descuento-valor" type="number" step="0.01" min="0" name="descuento_item[]" value="<?= e((string) ($item['descuento_valor'] ?? 0)) ?>">
                                </div>
                            </td>
                            <td><input class="form-control form-control-sm js-iva" type="number" step="0.01" min="0" name="impuesto_item[]" value="<?= e((string) ($item['porcentaje_impuesto'] ?? 19)) ?>"></td>
                            <td class="text-end js-subtotal">$0.00</td>
                            <td class="text-end js-iva-total">$0.00</td>
                            <td class="text-end js-total">$0.00</td>
                            <td><button type="button" class="btn btn-outline-danger btn-sm js-eliminar">×</button></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="row g-2 mt-2">
                <div class="col-md-4 ms-auto">
                    <label class="small">Descuento total</label>
                    <div class="input-group">
                        <select class="form-select" name="descuento_tipo_total" id="descuento_tipo_total">
                            <option value="valor" <?= $descuentoTipoTotal === 'valor' ? 'selected' : '' ?>>$</option>
                            <option value="porcentaje" <?= $descuentoTipoTotal === 'porcentaje' ? 'selected' : '' ?>>%</option>
                        </select>
                        <input class="form-control" type="number" step="0.01" min="0" name="descuento_total" id="descuento_total" value="<?= e((string) $descuentoTotalValor) ?>">
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-4 ms-auto">
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between"><span>Subtotal</span><strong id="resumen_subtotal">$0.00</strong></li>
                        <li class="list-group-item d-flex justify-content-between"><span>IVA</span><strong id="resumen_iva">$0.00</strong></li>
                        <li class="list-group-item d-flex justify-content-between"><span>Descuento total</span><strong id="resumen_descuento">$0.00</strong></li>
                        <li class="list-group-item d-flex justify-content-between"><span>Total</span><strong id="resumen_total">$0.00</strong></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <?php if (!$puedeGuardar): ?>
        <div class="alert alert-warning mb-0">Debes crear al menos un cliente antes de guardar cambios.</div>
    <?php endif; ?>

    <div>
        <button class="btn btn-primary btn-sm" name="accion" value="guardar"<?= $puedeGuardar ? '' : ' disabled' ?>>Guardar sin salir</button>
        <button class="btn btn-success btn-sm" name="accion" value="guardar_salir"<?= $puedeGuardar ? '' : ' disabled' ?>>Guardar y salir</button>
        <a class="btn btn-outline-dark btn-sm" href="<?= e(url('/app/cotizaciones/pdf/' . $cotizacion['id'])) ?>">Descargar PDF</a>
        <a href="<?= e(url('/app/cotizaciones')) ?>" class="btn btn-outline-secondary btn-sm">Cancelar</a>
    </div>
</form>

<template id="fila-item-template">
    <tr>
        <td>
            <div class="input-group input-group-sm">
                <select class="form-select js-producto" name="producto_id[]">
                    <option value="">Seleccionar</option>
                    <?php foreach ($productos as $p): ?>
                        <option value="<?= $p['id'] ?>"><?= e($p['nombre']) ?></option>
                    <?php endforeach; ?>
                </select>
                <button class="btn btn-outline-primary" type="button" data-bs-toggle="modal" data-bs-target="#modalProducto">+</button>
            </div>
        </td>
        <td><input class="form-control form-control-sm" name="descripcion_item[]" placeholder="Descripción"></td>
        <td><input class="form-control form-control-sm js-cantidad" type="number" step="0.01" min="0" name="cantidad[]" value="1"></td>
        <td><input class="form-control form-control-sm js-precio" type="number" step="0.01" min="0" name="precio_unitario[]" value="0"></td>
        <td>
            <div class="input-group input-group-sm">
                <select class="form-select js-descuento-tipo" name="descuento_tipo_item[]">
                    <option value="valor">$</option>
                    <option value="porcentaje">%</option>
                </select>
                <input class="form-control js-descuento-valor" type="number" step="0.01" min="0" name="descuento_item[]" value="0">
            </div>
        </td>
        <td><input class="form-control form-control-sm js-iva" type="number" step="0.01" min="0" name="impuesto_item[]" value="19"></td>
        <td class="text-end js-subtotal">$0.00</td>
        <td class="text-end js-iva-total">$0.00</td>
        <td class="text-end js-total">$0.00</td>
        <td><button type="button" class="btn btn-outline-danger btn-sm js-eliminar">×</button></td>
    </tr>
</template>

<div class="modal fade" id="modalCliente" tabindex="-1" aria-hidden="true"><div class="modal-dialog modal-lg"><div class="modal-content"><div class="modal-header"><h5 class="modal-title">Crear cliente (dato fijo)</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button></div><form method="POST" action="<?= e(url('/app/clientes/crear')) ?>"><?= csrf_campo() ?><input type="hidden" name="redirect_to" value="/app/cotizaciones/editar/<?= e((string) $cotizacion['id']) ?>"><div class="modal-body row g-2"><div class="col-md-4"><input class="form-control" name="nombre" placeholder="Nombre" required></div><div class="col-md-4"><input class="form-control" name="correo" placeholder="Correo"></div><div class="col-md-4"><input class="form-control" name="telefono" placeholder="Teléfono"></div><div class="col-md-6"><input class="form-control" name="direccion" placeholder="Dirección"></div><div class="col-md-6"><input class="form-control" name="notas" placeholder="Notas"></div></div><div class="modal-footer"><button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cerrar</button><button class="btn btn-primary btn-sm">Guardar cliente</button></div></form></div></div></div>
<div class="modal fade" id="modalProducto" tabindex="-1" aria-hidden="true"><div class="modal-dialog modal-lg"><div class="modal-content"><div class="modal-header"><h5 class="modal-title">Crear producto (dato fijo)</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button></div><form method="POST" action="<?= e(url('/app/productos/crear')) ?>"><?= csrf_campo() ?><input type="hidden" name="redirect_to" value="/app/cotizaciones/editar/<?= e((string) $cotizacion['id']) ?>"><div class="modal-body row g-2"><div class="col-md-3"><input class="form-control" name="codigo" placeholder="Código" required></div><div class="col-md-4"><input class="form-control" name="nombre" placeholder="Nombre" required></div><div class="col-md-5"><input class="form-control" name="descripcion" placeholder="Descripción"></div><div class="col-md-3"><input class="form-control" name="unidad" value="unidad"></div><div class="col-md-3"><input class="form-control" type="number" step="0.01" name="precio" placeholder="Precio"></div><div class="col-md-3"><input class="form-control" type="number" step="0.01" name="impuesto" value="19"></div><div class="col-md-3"><select name="estado" class="form-select"><option value="activo">Activo</option><option value="inactivo">Inactivo</option></select></div></div><div class="modal-footer"><button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cerrar</button><button class="btn btn-primary btn-sm">Guardar producto</button></div></form></div></div></div>

<script>
(function () {
    const cuerpo = document.getElementById('cuerpo-items');
    const template = document.getElementById('fila-item-template');
    const btnAgregar = document.getElementById('btn-agregar-linea');

    function fmt(v) { return '$' + (Math.round((v + Number.EPSILON) * 100) / 100).toFixed(2); }
    async function autocompletarPrecioDesdeLista(fila, forzar = false) {
        const selectProducto = fila.querySelector('.js-producto');
        const clienteId = document.querySelector('[name="cliente_id"]')?.value || '';
        const canal = document.getElementById('canal_venta')?.value || '';
        const listaPrecioId = document.getElementById('lista_precio_id')?.value || '';
        if (!selectProducto || !selectProducto.value || !clienteId) { return; }
        try {
            const params = new URLSearchParams({ producto_id: selectProducto.value, cliente_id: clienteId, canal: canal, lista_precio_id: listaPrecioId });
            const resp = await fetch('<?= e(url('/app/listas-precios/precio-producto')) ?>?' + params.toString(), { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            const data = await resp.json();
            if (data.ok && data.data && typeof data.data.precio_final !== 'undefined') {
                const inputPrecio = fila.querySelector('.js-precio');
                const selectDescuento = fila.querySelector('.js-descuento-tipo');
                const inputDescuento = fila.querySelector('.js-descuento-valor');
                if (inputPrecio && (forzar || parseFloat(inputPrecio.value || '0') <= 0)) {
                    const ajusteTipo = data.data.ajuste_tipo || '';
                    const ajustePorcentaje = parseFloat(data.data.ajuste_porcentaje || '0');
                    if (ajusteTipo === 'descuento' && ajustePorcentaje > 0) {
                        inputPrecio.value = String(data.data.precio_base);
                        if (selectDescuento) { selectDescuento.value = 'porcentaje'; }
                        if (inputDescuento) { inputDescuento.value = String(ajustePorcentaje); }
                    } else {
                        inputPrecio.value = String(data.data.precio_final);
                        if (forzar && selectDescuento && inputDescuento) {
                            selectDescuento.value = 'valor';
                            inputDescuento.value = '0';
                        }
                    }
                }
            }
        } catch (e) { }
    }
    function bindFila(fila) {
        fila.querySelector('.js-eliminar').addEventListener('click', () => {
            if (cuerpo.querySelectorAll('tr').length > 1) { fila.remove(); recalcular(); }
        });
        fila.querySelectorAll('input, select').forEach((c) => { c.addEventListener('input', recalcular); c.addEventListener('change', recalcular); });
        const selectProducto = fila.querySelector('.js-producto');
        if (selectProducto) {
            selectProducto.addEventListener('change', async () => {
                await autocompletarPrecioDesdeLista(fila, true);
                recalcular();
            });
        }
    }
    function recalcular() {
        let subtotal = 0; let iva = 0;
        cuerpo.querySelectorAll('tr').forEach((fila) => {
            const cantidad = parseFloat(fila.querySelector('.js-cantidad').value || '0');
            const precio = parseFloat(fila.querySelector('.js-precio').value || '0');
            const ivaPct = parseFloat(fila.querySelector('.js-iva').value || '0');
            const tipo = fila.querySelector('.js-descuento-tipo').value;
            const valor = parseFloat(fila.querySelector('.js-descuento-valor').value || '0');
            const base = Math.max(0, cantidad) * Math.max(0, precio);
            const descuento = tipo === 'porcentaje' ? base * (Math.min(Math.max(valor, 0), 100) / 100) : Math.min(Math.max(valor, 0), base);
            const sub = Math.max(0, base - descuento);
            const ivaLinea = sub * (Math.max(0, ivaPct) / 100);
            const total = sub + ivaLinea;
            fila.querySelector('.js-subtotal').textContent = fmt(sub);
            fila.querySelector('.js-iva-total').textContent = fmt(ivaLinea);
            fila.querySelector('.js-total').textContent = fmt(total);
            subtotal += sub; iva += ivaLinea;
        });
        const tipoTotal = document.getElementById('descuento_tipo_total').value;
        const valorTotal = parseFloat(document.getElementById('descuento_total').value || '0');
        const baseTotal = subtotal + iva;
        const descTotal = tipoTotal === 'porcentaje' ? baseTotal * (Math.min(Math.max(valorTotal, 0), 100) / 100) : Math.min(Math.max(valorTotal, 0), baseTotal);
        document.getElementById('resumen_subtotal').textContent = fmt(subtotal);
        document.getElementById('resumen_iva').textContent = fmt(iva);
        document.getElementById('resumen_descuento').textContent = fmt(descTotal);
        document.getElementById('resumen_total').textContent = fmt(Math.max(0, baseTotal - descTotal));
    }
    function agregarFila() { const fila = template.content.firstElementChild.cloneNode(true); bindFila(fila); cuerpo.appendChild(fila); }
    if (cuerpo.querySelectorAll('tr').length === 0) { agregarFila(); }
    cuerpo.querySelectorAll('tr').forEach(bindFila);
    btnAgregar.addEventListener('click', () => { agregarFila(); recalcular(); });
    document.getElementById('descuento_tipo_total').addEventListener('change', recalcular);
    document.getElementById('descuento_total').addEventListener('input', recalcular);
    document.querySelector('[name="cliente_id"]')?.addEventListener('change', () => { cuerpo.querySelectorAll('tr').forEach((fila) => autocompletarPrecioDesdeLista(fila, true)); recalcular(); });
    document.getElementById('canal_venta')?.addEventListener('change', () => { cuerpo.querySelectorAll('tr').forEach((fila) => autocompletarPrecioDesdeLista(fila, true)); recalcular(); });
    document.getElementById('lista_precio_id')?.addEventListener('change', () => { cuerpo.querySelectorAll('tr').forEach((fila) => autocompletarPrecioDesdeLista(fila, true)); recalcular(); });
    recalcular();
})();
</script>
