<h1 class="h4 mb-3">Ver cotización</h1>

<style>
    #tabla-items-ver {
        table-layout: fixed;
        width: 100%;
    }

    #tabla-items-ver th,
    #tabla-items-ver td {
        vertical-align: middle;
        font-size: 0.82rem;
    }

    #tabla-items-ver .col-producto { width: 30%; }
    #tabla-items-ver .col-cantidad,
    #tabla-items-ver .col-precio,
    #tabla-items-ver .col-descuento,
    #tabla-items-ver .col-iva { width: 9%; }

    #tabla-items-ver .js-detalle-producto {
        font-size: 0.70rem;
        color: #6c757d;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    #tabla-items-ver .valor-linea {
        white-space: nowrap;
        font-variant-numeric: tabular-nums;
        font-weight: 600;
    }
</style>

<div class="card mb-3">
    <div class="card-body">
        <div class="row">
            <div class="col-md-4"><strong>Número:</strong> <?= e($cotizacion['numero']) ?></div>
            <div class="col-md-4"><strong>Cliente:</strong> <?= e($cotizacion['cliente']) ?></div>
            <div class="col-md-4"><strong>Estado:</strong> <?= e($cotizacion['estado']) ?></div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">Detalle de cotización</div>
    <div class="table-responsive">
        <table class="table table-sm mb-0" id="tabla-items-ver">
            <thead>
            <tr>
                <th class="col-producto">Producto / Servicio</th>
                <th class="col-cantidad">Cantidad</th>
                <th class="col-precio">Precio</th>
                <th class="col-descuento">Descuento</th>
                <th class="col-iva">IVA %</th>
                <th class="text-end">Subtotal</th>
                <th class="text-end">IVA</th>
                <th class="text-end">Total</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach (($cotizacion['items'] ?? []) as $it): ?>
                <?php
                $cantidad = (float) ($it['cantidad'] ?? 0);
                $precio = (float) ($it['precio_unitario'] ?? 0);
                $descuento = (float) ($it['descuento_monto'] ?? 0);
                $subtotal = (float) ($it['subtotal'] ?? max(0, ($cantidad * $precio) - $descuento));
                $total = (float) ($it['total'] ?? 0);
                $ivaLinea = max(0, $total - $subtotal);
                $detalle = trim((string) ($it['descripcion'] ?? ''));
                $producto = trim((string) ($it['producto_nombre'] ?? 'Producto / servicio'));
                ?>
                <tr>
                    <td>
                        <div><?= e($producto) ?></div>
                        <div class="js-detalle-producto" title="<?= e($detalle) ?>">
                            <?= e($detalle) ?>
                        </div>
                    </td>
                    <td><?= e(number_format($cantidad, 2, '.', '')) ?></td>
                    <td>$<?= e(number_format($precio, 2, '.', '')) ?></td>
                    <td>$<?= e(number_format($descuento, 2, '.', '')) ?></td>
                    <td><?= e(number_format((float) ($it['porcentaje_impuesto'] ?? 0), 2, '.', '')) ?>%</td>
                    <td class="text-end valor-linea">$<?= e(number_format($subtotal, 2, '.', '')) ?></td>
                    <td class="text-end valor-linea">$<?= e(number_format($ivaLinea, 2, '.', '')) ?></td>
                    <td class="text-end valor-linea">$<?= e(number_format($total, 2, '.', '')) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">
    <a class="btn btn-outline-secondary btn-sm" href="<?= e(url('/app/cotizaciones')) ?>">Volver</a>
    <a class="btn btn-primary btn-sm" href="<?= e(url('/app/cotizaciones/editar/' . $cotizacion['id'])) ?>">Editar</a>
    <a class="btn btn-outline-dark btn-sm" href="<?= e(url('/app/cotizaciones/pdf/' . $cotizacion['id'])) ?>">Descargar PDF</a>
</div>
