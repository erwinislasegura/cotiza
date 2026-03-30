<?php
$hayClientes = !empty($clientes);
$hayProductos = !empty($productos);
$puedeGuardar = $hayClientes && $hayProductos;
?>
<h1 class="h4 mb-3">Crear cotización</h1>

<style>
    #tabla-items th,
    #tabla-items td {
        vertical-align: middle;
    }

    #tabla-items .js-subtotal,
    #tabla-items .js-iva-total,
    #tabla-items .js-total {
        white-space: nowrap;
        font-variant-numeric: tabular-nums;
        min-width: 120px;
    }

    #tabla-items .col-producto {
        min-width: 240px;
    }


    #tabla-items .col-ajuste {
        min-width: 250px;
    }

    #tabla-items .col-cantidad,
    #tabla-items .col-precio,
    #tabla-items .col-descuento,
    #tabla-items .col-iva {
        min-width: 130px;
    }

    #tabla-items .js-descuento-valor,
    #tabla-items .js-iva {
        min-width: 88px;
    }
    #tabla-items .input-group {
        flex-wrap: nowrap;
    }

    #tabla-items .js-detalle-producto {
        font-size: 0.78rem;
        color: #6c757d;
        margin-top: 4px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>

<div class="alert alert-info info-modulo mb-3">
    <div class="fw-semibold mb-1">Guía rápida para crear cotizaciones</div>
    <ul class="mb-0 small ps-3">
        <li>Selecciona cliente y lista de precios para aplicar ajustes comerciales correctos.</li>
        <li>Completa detalle de productos con cantidades y descuentos para evitar reprocesos.</li>
        <li>Usa observaciones y términos para dejar condiciones claras antes del envío.</li>
    </ul>
</div>

<form method="POST" class="d-grid gap-3" id="form-cotizacion">
    <?= csrf_campo() ?>
    <input type="hidden" name="token_publico" id="token_publico" value="<?= e($tokenPrevisualizacion ?? '') ?>">

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
                    <select class="form-select" name="cliente_id" id="cliente_id" required>
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
            <div class="col-12">
                <div class="border rounded p-2 bg-light" id="resumen_cliente">
                    <div class="small text-muted">Selecciona un cliente para ver su información.</div>
                </div>
            </div>

            <div class="col-md-2">
                <label class="small">Estado</label>
                <select class="form-select" name="estado">
                    <option>borrador</option>
                    <option>enviada</option>
                    <option>aprobada</option>
                    <option>rechazada</option>
                </select>
            </div>

            <div class="col-md-4">
                <label class="small">Lista de precios aplicada</label>
                <select class="form-select" name="lista_precio_id" id="lista_precio_id">
                    <option value="">No aplicar lista</option>
                    <?php foreach (($listasPrecios ?? []) as $lista): ?>
                        <option value="<?= (int) $lista['id'] ?>"><?= e($lista['nombre']) ?></option>
                    <?php endforeach; ?>
                </select>
                <div class="form-text" id="indicador_lista_estado">Selecciona cliente y lista para aplicar ajustes.</div>
            </div>

            <div class="col-md-3">
                <label class="small">Fecha emisión</label>
                <input class="form-control" type="date" name="fecha_emision" value="<?= date('Y-m-d') ?>">
            </div>

            <div class="col-md-3">
                <label class="small">Fecha vencimiento</label>
                <input class="form-control" type="date" name="fecha_vencimiento" value="<?= date('Y-m-d', strtotime('+15 days')) ?>">
            </div>

            <div class="col-12">
                <?php $linkAprobacion = url('/cotizacion/publica/' . ($tokenPrevisualizacion ?? '')); ?>
                <label class="small text-muted">Enlace generado para cliente (previsualización)</label>
                <div class="input-group input-group-sm">
                    <input type="text" readonly class="form-control" id="link_aprobacion" value="<?= e($linkAprobacion) ?>">
                    <button class="btn btn-outline-secondary" type="button" id="copiar_link_aprobacion">Copiar</button>
                </div>
                <div class="form-text">Puedes compartirlo cuando la cotización quede guardada.</div>
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
            <small class="text-muted">El precio y descuento se aplican según la lista seleccionada (o sin lista).</small>
            <button type="button" class="btn btn-outline-primary btn-sm" id="btn-agregar-linea">Agregar línea</button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-sm align-middle" id="tabla-items">
                    <thead>
                    <tr>
                        <th class="col-producto">Producto / Servicio</th>
                        <th class="col-cantidad">Cantidad</th>
                        <th class="col-precio">Precio</th>
                        <th class="col-ajuste">Lista / ajuste</th>
                        <th class="col-descuento">Descuento</th>
                        <th class="col-iva">IVA %</th>
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
        <button class="btn btn-primary btn-sm" name="accion" value="guardar"<?= $puedeGuardar ? '' : ' disabled' ?>>Guardar sin salir</button>
        <button class="btn btn-success btn-sm" name="accion" value="guardar_salir"<?= $puedeGuardar ? '' : ' disabled' ?>>Guardar y salir</button>
        <button class="btn btn-outline-success btn-sm" type="button" onclick="window.location.href='mailto:?subject=' + encodeURIComponent('Cotización <?= e($siguienteNumero) ?>')">Enviar por correo</button>
        <button class="btn btn-outline-dark btn-sm" type="button" onclick="alert('Guarda la cotización para descargar el PDF.')">Descargar PDF</button>
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
                        <option value="<?= $p['id'] ?>" data-nombre="<?= e($p['nombre']) ?>" data-descripcion="<?= e($p['descripcion'] ?? '') ?>"><?= e($p['nombre']) ?></option>
                    <?php endforeach; ?>
                </select>
                <button class="btn btn-outline-primary" type="button" data-bs-toggle="modal" data-bs-target="#modalProducto">+</button>
            </div>
            <div class="js-detalle-producto" aria-live="polite" title="Selecciona un producto para ver su detalle.">Selecciona un producto para ver su detalle.</div>
            <input type="hidden" class="js-descripcion-item" name="descripcion_item[]" value="">
        </td>
        <td><input class="form-control form-control-sm js-cantidad" type="number" step="0.01" min="0" name="cantidad[]" value="1.00"></td>
        <td><input class="form-control form-control-sm js-precio" type="number" step="0.01" min="0" name="precio_unitario[]" value="0.00"></td>
        <td class="small text-muted js-lista-ajuste">Sin validar lista</td>
        <td>
            <div class="input-group input-group-sm">
                <select class="form-select js-descuento-tipo" name="descuento_tipo_item[]">
                    <option value="valor">$</option>
                    <option value="porcentaje">%</option>
                </select>
                <input class="form-control js-descuento-valor" type="number" step="0.01" min="0" name="descuento_item[]" value="0.00">
            </div>
        </td>
        <td><input class="form-control form-control-sm js-iva" type="number" step="0.01" min="0" name="impuesto_item[]" value="19.00"></td>
        <td class="text-end js-subtotal fw-semibold">$0.00</td>
        <td class="text-end js-iva-total fw-semibold">$0.00</td>
        <td class="text-end js-total fw-semibold">$0.00</td>
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
    const selectCliente = document.querySelector('[name="cliente_id"]');
    const selectLista = document.getElementById('lista_precio_id');
    const todasLasListas = <?= json_encode($listasPrecios ?? [], JSON_UNESCAPED_UNICODE) ?>;
    const btnCopiarLink = document.getElementById('copiar_link_aprobacion');
    const inputLinkAprobacion = document.getElementById('link_aprobacion');

    if (btnCopiarLink && inputLinkAprobacion) {
        btnCopiarLink.addEventListener('click', async function () {
            try {
                await navigator.clipboard.writeText(inputLinkAprobacion.value);
                btnCopiarLink.textContent = 'Copiado';
                setTimeout(function () { btnCopiarLink.textContent = 'Copiar'; }, 1200);
            } catch (error) {
                inputLinkAprobacion.select();
                document.execCommand('copy');
            }
        });
    }
    const listasPorCliente = <?= json_encode($listasPreciosPorCliente ?? [], JSON_UNESCAPED_UNICODE) ?>;
    const clientes = <?= json_encode($clientes ?? [], JSON_UNESCAPED_UNICODE) ?>;

    function fmt(valor) {
        return '$' + (Math.round((valor + Number.EPSILON) * 100) / 100).toFixed(2);
    }

    function normalizarDosDecimales(input, minimo = 0) {
        if (!input) { return; }
        const valor = parseFloat(input.value || '0');
        const saneado = Number.isFinite(valor) ? Math.max(minimo, valor) : minimo;
        input.value = saneado.toFixed(2);
    }

    function aplicarFormatoFila(fila) {
        normalizarDosDecimales(fila.querySelector('.js-cantidad'));
        normalizarDosDecimales(fila.querySelector('.js-precio'));
        normalizarDosDecimales(fila.querySelector('.js-descuento-valor'));
        normalizarDosDecimales(fila.querySelector('.js-iva'));
    }

    function esc(valor) {
        return String(valor ?? '').replace(/[&<>"']/g, (c) => ({
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        }[c] || c));
    }

    function actualizarIndicadorLista() {
        const indicador = document.getElementById('indicador_lista_estado');
        if (!indicador) { return; }
        const filas = Array.from(cuerpo.querySelectorAll('tr'));
        const aplicadas = filas.filter((fila) => fila.dataset.listaAplicada === 'si').length;
        if (!selectLista || !selectLista.value) {
            indicador.innerHTML = '<span style="color:#6c757d;">Sin lista de precios aplicada.</span>';
            return;
        }
        if (aplicadas > 0) {
            indicador.innerHTML = `<span style="color:#3f8f62;">Se aplica descuento/lista en ${aplicadas} de ${filas.length} líneas.</span>`;
            return;
        }
        indicador.innerHTML = '<span style="color:#b94a48;">No aplica lista de precios en las líneas actuales.</span>';
    }

    function renderInfoLista(fila, data = null) {
        const celda = fila.querySelector('.js-lista-ajuste');
        if (!celda) { return; }
        if (!data) {
            fila.dataset.listaAplicada = 'no';
            celda.innerHTML = '<span style="color:#b94a48;">No aplica lista.</span>';
            actualizarIndicadorLista();
            return;
        }

        const nombreLista = data.lista_precio_nombre || 'Lista automática';
        const porcentaje = parseFloat(data.ajuste_porcentaje || '0');
        const tipo = data.ajuste_tipo === 'descuento' ? 'descuento' : 'incremento';
        const precioBase = parseFloat(data.precio_base || '0');
        const precioFinal = parseFloat(data.precio_final || '0');
        const montoAjuste = Math.abs(precioFinal - precioBase);

        if (!data.lista_precio_id) {
            fila.dataset.listaAplicada = 'no';
            celda.innerHTML = '<span style="color:#b94a48;">Sin lista para este cliente.</span>';
            actualizarIndicadorLista();
            return;
        }

        if (porcentaje <= 0) {
            fila.dataset.listaAplicada = 'si';
            celda.innerHTML = `<span class="badge text-bg-success mb-1">${nombreLista}</span><div style="color:#3f8f62;">Lista detectada y aplicada (sin ajuste porcentual).</div>`;
            actualizarIndicadorLista();
            return;
        }

        const esDescuento = tipo === 'descuento';
        const colorSuave = esDescuento ? 'style="color:#3f8f62;"' : '';
        const signo = esDescuento ? '-' : '+';
        const etiqueta = esDescuento ? 'Descuento por lista' : 'Incremento por lista';
        fila.dataset.listaAplicada = 'si';
        celda.innerHTML = `<span class="badge ${esDescuento ? 'text-bg-success' : 'text-bg-warning'} mb-1">${nombreLista}</span><div ${colorSuave}><strong>${etiqueta}</strong>: ${signo}${porcentaje.toFixed(2)}% (${signo}${fmt(montoAjuste)})</div><div>Base ${fmt(precioBase)} → Final ${fmt(precioFinal)}</div>`;
        actualizarIndicadorLista();
    }

    async function autocompletarPrecioDesdeLista(fila, forzar = false) {
        const selectProducto = fila.querySelector('.js-producto');
        const clienteId = selectCliente?.value || '';
        const listaPrecioId = selectLista?.value || '';

        if (!selectProducto || !selectProducto.value || !clienteId) {
            renderInfoLista(fila, null);
            return;
        }

        try {
            const params = new URLSearchParams({
                producto_id: selectProducto.value,
                cliente_id: clienteId,
                lista_precio_id: listaPrecioId
            });
            const resp = await fetch('<?= e(url('/app/listas-precios/precio-producto')) ?>?' + params.toString(), {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            const data = await resp.json();
            if (data.ok && data.data && typeof data.data.precio_final !== 'undefined') {
                renderInfoLista(fila, data.data);
                const inputPrecio = fila.querySelector('.js-precio');
                const selectDescuento = fila.querySelector('.js-descuento-tipo');
                const inputDescuento = fila.querySelector('.js-descuento-valor');
                if (inputPrecio && (forzar || parseFloat(inputPrecio.value || '0') <= 0)) {
                    const ajusteTipo = data.data.ajuste_tipo || '';
                    const ajustePorcentaje = parseFloat(data.data.ajuste_porcentaje || '0');
                    if (ajusteTipo === 'descuento' && ajustePorcentaje > 0) {
                        inputPrecio.value = Number(data.data.precio_base || 0).toFixed(2);
                        if (selectDescuento) {
                            selectDescuento.value = 'porcentaje';
                        }
                        if (inputDescuento) {
                            inputDescuento.value = Number(ajustePorcentaje).toFixed(2);
                        }
                    } else {
                        inputPrecio.value = Number(data.data.precio_final || 0).toFixed(2);
                        if (forzar && selectDescuento && inputDescuento) {
                            selectDescuento.value = 'valor';
                            inputDescuento.value = '0.00';
                        }
                    }
                }
            } else {
                renderInfoLista(fila, null);
            }
        } catch (e) {
            // Ignorar para no interrumpir la cotización manual
            renderInfoLista(fila, null);
        }
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
        actualizarIndicadorLista();
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

        fila.querySelectorAll('.js-cantidad, .js-precio, .js-descuento-valor, .js-iva').forEach((input) => {
            input.addEventListener('blur', () => {
                normalizarDosDecimales(input);
                recalcular();
            });
        });

        const selectProducto = fila.querySelector('.js-producto');
        const inputDescripcion = fila.querySelector('.js-descripcion-item');
        const textoDetalle = fila.querySelector('.js-detalle-producto');
        if (selectProducto) {
            selectProducto.addEventListener('change', async () => {
                const opcion = selectProducto.options[selectProducto.selectedIndex];
                const detalleProducto = opcion?.dataset?.descripcion || opcion?.dataset?.nombre || '';
                if (inputDescripcion) {
                    inputDescripcion.value = detalleProducto;
                }
                if (textoDetalle) {
                    const detalleVisible = detalleProducto || 'Sin detalle';
                    textoDetalle.textContent = detalleVisible;
                    textoDetalle.title = detalleVisible;
                }
                await autocompletarPrecioDesdeLista(fila, true);
                recalcular();
            });
        }
        aplicarFormatoFila(fila);
        cuerpo.appendChild(fila);
    }

    function renderResumenCliente() {
        const contenedor = document.getElementById('resumen_cliente');
        if (!contenedor) { return; }

        const clienteId = parseInt(selectCliente?.value || '0', 10);
        const cliente = clientes.find((c) => parseInt(c.id || 0, 10) === clienteId);
        if (!cliente) {
            contenedor.innerHTML = '<div class="small text-muted">Selecciona un cliente para ver su información.</div>';
            return;
        }

        const razon = (cliente.razon_social || cliente.nombre || '').trim();
        const nombreComercial = (cliente.nombre_comercial || '').trim();
        const correo = (cliente.correo || '').trim() || '—';
        const telefono = (cliente.telefono || '').trim() || '—';
        const ciudad = (cliente.ciudad || '').trim() || '—';
        const direccion = (cliente.direccion || '').trim() || '—';

        contenedor.innerHTML = `
            <div class="row g-2 small">
                <div class="col-md-4"><strong>Cliente:</strong> ${esc(razon || '—')}</div>
                <div class="col-md-4"><strong>Nombre comercial:</strong> ${esc(nombreComercial || '—')}</div>
                <div class="col-md-4"><strong>Correo:</strong> ${esc(correo)}</div>
                <div class="col-md-4"><strong>Teléfono:</strong> ${esc(telefono)}</div>
                <div class="col-md-4"><strong>Ciudad:</strong> ${esc(ciudad)}</div>
                <div class="col-md-4"><strong>Dirección:</strong> ${esc(direccion)}</div>
            </div>`;
    }

    function actualizarOpcionesListaCliente() {
        if (!selectLista) { return; }
        const clienteId = parseInt(selectCliente?.value || '0', 10);
        const permitidas = new Set((listasPorCliente[String(clienteId)] || []).map((id) => parseInt(id, 10)));
        const valorActual = selectLista.value;

        selectLista.innerHTML = '';
        const opcionNinguna = document.createElement('option');
        opcionNinguna.value = '';
        opcionNinguna.textContent = 'No aplicar lista';
        selectLista.appendChild(opcionNinguna);

        todasLasListas.forEach((lista) => {
            const idLista = parseInt(lista.id || 0, 10);
            if (!permitidas.has(idLista)) { return; }
            const option = document.createElement('option');
            option.value = String(idLista);
            option.textContent = String(lista.nombre || ('Lista #' + idLista));
            selectLista.appendChild(option);
        });

        if (valorActual !== '' && permitidas.has(parseInt(valorActual, 10))) {
            selectLista.value = valorActual;
        } else {
            selectLista.value = '';
        }
    }

    async function aplicarListaATodasLineas(forzar = true) {
        const filas = Array.from(cuerpo.querySelectorAll('tr'));
        await Promise.all(filas.map((fila) => autocompletarPrecioDesdeLista(fila, forzar)));
        recalcular();
        actualizarIndicadorLista();
    }

    btnAgregar.addEventListener('click', () => {
        agregarFila();
        recalcular();
    });

    agregarFila();
    document.getElementById('descuento_tipo_total').addEventListener('change', recalcular);
    document.getElementById('descuento_total').addEventListener('input', recalcular);
    document.getElementById('descuento_total').addEventListener('blur', function () {
        normalizarDosDecimales(this);
        recalcular();
    });
    actualizarOpcionesListaCliente();
    renderResumenCliente();
    document.querySelector('[name="cliente_id"]')?.addEventListener('change', () => {
        renderResumenCliente();
        actualizarOpcionesListaCliente();
        aplicarListaATodasLineas(true);
    });
    document.getElementById('lista_precio_id')?.addEventListener('change', () => { aplicarListaATodasLineas(true); });
    aplicarListaATodasLineas(true);
})();
</script>
