<?php
$hayClientes = !empty($clientes);
$hayProductos = !empty($productos);
$puedeGuardar = $hayClientes && $hayProductos;
?>
<h1 class="h4 mb-3">Crear cotización</h1>

<form method="POST" class="d-grid gap-3" id="form-cotizacion">
    <?= csrf_campo() ?>

    <div class="card">
        <div class="card-header">Datos cotización</div>
        <div class="card-body row g-3">
            <div class="col-md-3">
                <label class="small">Número</label>
                <input class="form-control" value="<?= e($siguienteNumero) ?>" disabled>
            </div>

            <div class="col-md-5">
                <label class="small">Cliente</label>
                <div class="input-group">
                    <select class="form-select" name="cliente_id" required>
                        <?php if (!$hayClientes): ?>
                            <option value="">No hay clientes registrados</option>
                        <?php endif; ?>
                        <?php foreach ($clientes as $c): ?>
                            <option value="<?= $c['id'] ?>"><?= e($c['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button class="btn btn-outline-primary" type="button" data-bs-toggle="modal" data-bs-target="#modalCliente">Dato fijo cliente</button>
                </div>
            </div>

            <div class="col-md-4">
                <label class="small">Estado</label>
                <select class="form-select" name="estado">
                    <option>borrador</option>
                    <option>enviada</option>
                    <option>aprobada</option>
                    <option>rechazada</option>
                </select>
            </div>

            <div class="col-md-3">
                <label class="small">Fecha emisión</label>
                <input class="form-control" type="date" name="fecha_emision" value="<?= date('Y-m-d') ?>">
            </div>

            <div class="col-md-3">
                <label class="small">Fecha vencimiento</label>
                <input class="form-control" type="date" name="fecha_vencimiento" value="<?= date('Y-m-d', strtotime('+15 days')) ?>">
            </div>

            <div class="col-md-6">
                <label class="small">Observaciones</label>
                <input class="form-control" name="observaciones">
            </div>

            <div class="col-12">
                <label class="small">Términos</label>
                <input class="form-control" name="terminos_condiciones">
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Detalle de cotización</span>
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
                    <tbody id="cuerpo-items"></tbody>
                </table>
            </div>

            <div class="row g-2 mt-2">
                <div class="col-md-4 ms-auto">
                    <label class="small">Descuento total</label>
                    <div class="input-group">
                        <select class="form-select" name="descuento_tipo_total" id="descuento_tipo_total">
                            <option value="valor">$</option>
                            <option value="porcentaje">%</option>
                        </select>
                        <input class="form-control" type="number" step="0.01" min="0" name="descuento_total" id="descuento_total" value="0">
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
        <div class="alert alert-warning mb-0">
            Debes crear al menos un cliente y un producto antes de guardar una cotización.
        </div>
    <?php endif; ?>

    <div>
        <button class="btn btn-primary btn-sm"<?= $puedeGuardar ? '' : ' disabled' ?>>Guardar cotización</button>
        <button class="btn btn-outline-success btn-sm" type="button" onclick="window.location.href='mailto:?subject=' + encodeURIComponent('Cotización <?= e($siguienteNumero) ?>')">Enviar por correo</button>
        <button class="btn btn-outline-dark btn-sm" type="button" onclick="window.print()">Imprimir</button>
        <a href="<?= e(url('/app/cotizaciones')) ?>" class="btn btn-outline-secondary btn-sm">Cancelar</a>
    </div>
</form>

<template id="fila-item-template">
    <tr>
        <td>
            <div class="input-group input-group-sm">
                <select class="form-select" name="producto_id[]">
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

<div class="modal fade" id="modalCliente" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Crear cliente (dato fijo)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form method="POST" action="<?= e(url('/app/clientes/crear')) ?>">
                <?= csrf_campo() ?>
                <input type="hidden" name="redirect_to" value="/app/cotizaciones/crear">
                <div class="modal-body row g-2">
                    <div class="col-md-4"><input class="form-control" name="nombre" placeholder="Nombre" required></div>
                    <div class="col-md-4"><input class="form-control" name="correo" placeholder="Correo"></div>
                    <div class="col-md-4"><input class="form-control" name="telefono" placeholder="Teléfono"></div>
                    <div class="col-md-6"><input class="form-control" name="direccion" placeholder="Dirección"></div>
                    <div class="col-md-6"><input class="form-control" name="notas" placeholder="Notas"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cerrar</button>
                    <button class="btn btn-primary btn-sm">Guardar cliente</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalProducto" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Crear producto (dato fijo)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form method="POST" action="<?= e(url('/app/productos/crear')) ?>">
                <?= csrf_campo() ?>
                <input type="hidden" name="redirect_to" value="/app/cotizaciones/crear">
                <div class="modal-body row g-2">
                    <div class="col-md-3"><input class="form-control" name="codigo" placeholder="Código" required></div>
                    <div class="col-md-4"><input class="form-control" name="nombre" placeholder="Nombre" required></div>
                    <div class="col-md-5"><input class="form-control" name="descripcion" placeholder="Descripción"></div>
                    <div class="col-md-3"><input class="form-control" name="unidad" value="unidad"></div>
                    <div class="col-md-3"><input class="form-control" type="number" step="0.01" name="precio" placeholder="Precio"></div>
                    <div class="col-md-3"><input class="form-control" type="number" step="0.01" name="impuesto" value="19"></div>
                    <div class="col-md-3">
                        <select name="estado" class="form-select">
                            <option value="activo">Activo</option>
                            <option value="inactivo">Inactivo</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cerrar</button>
                    <button class="btn btn-primary btn-sm">Guardar producto</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
(function () {
    const cuerpo = document.getElementById('cuerpo-items');
    const template = document.getElementById('fila-item-template');
    const btnAgregar = document.getElementById('btn-agregar-linea');

    function fmt(valor) {
        return '$' + (Math.round((valor + Number.EPSILON) * 100) / 100).toFixed(2);
    }

    function recalcular() {
        let subtotal = 0;
        let iva = 0;

        cuerpo.querySelectorAll('tr').forEach((fila) => {
            const cantidad = parseFloat(fila.querySelector('.js-cantidad').value || '0');
            const precio = parseFloat(fila.querySelector('.js-precio').value || '0');
            const ivaPct = parseFloat(fila.querySelector('.js-iva').value || '0');
            const descuentoTipo = fila.querySelector('.js-descuento-tipo').value;
            const descuentoValor = parseFloat(fila.querySelector('.js-descuento-valor').value || '0');

            const base = Math.max(0, cantidad) * Math.max(0, precio);
            const descuento = descuentoTipo === 'porcentaje'
                ? base * (Math.min(Math.max(descuentoValor, 0), 100) / 100)
                : Math.min(Math.max(descuentoValor, 0), base);

            const subtotalLinea = Math.max(0, base - descuento);
            const ivaLinea = subtotalLinea * (Math.max(0, ivaPct) / 100);
            const totalLinea = subtotalLinea + ivaLinea;

            fila.querySelector('.js-subtotal').textContent = fmt(subtotalLinea);
            fila.querySelector('.js-iva-total').textContent = fmt(ivaLinea);
            fila.querySelector('.js-total').textContent = fmt(totalLinea);

            subtotal += subtotalLinea;
            iva += ivaLinea;
        });

        const tipoTotal = document.getElementById('descuento_tipo_total').value;
        const valorTotal = parseFloat(document.getElementById('descuento_total').value || '0');
        const baseTotal = subtotal + iva;
        const descuentoTotal = tipoTotal === 'porcentaje'
            ? baseTotal * (Math.min(Math.max(valorTotal, 0), 100) / 100)
            : Math.min(Math.max(valorTotal, 0), baseTotal);

        document.getElementById('resumen_subtotal').textContent = fmt(subtotal);
        document.getElementById('resumen_iva').textContent = fmt(iva);
        document.getElementById('resumen_descuento').textContent = fmt(descuentoTotal);
        document.getElementById('resumen_total').textContent = fmt(Math.max(0, baseTotal - descuentoTotal));
    }

    function agregarFila() {
        const fila = template.content.firstElementChild.cloneNode(true);
        fila.querySelector('.js-eliminar').addEventListener('click', () => {
            if (cuerpo.querySelectorAll('tr').length > 1) {
                fila.remove();
                recalcular();
            }
        });
        fila.querySelectorAll('input, select').forEach((control) => {
            control.addEventListener('input', recalcular);
            control.addEventListener('change', recalcular);
        });
        cuerpo.appendChild(fila);
    }

    btnAgregar.addEventListener('click', () => {
        agregarFila();
        recalcular();
    });

    agregarFila();
    document.getElementById('descuento_tipo_total').addEventListener('change', recalcular);
    document.getElementById('descuento_total').addEventListener('input', recalcular);
    recalcular();
})();
</script>
