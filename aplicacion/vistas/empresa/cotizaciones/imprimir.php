<?php
$empresaNombre = trim((string) (($empresa['nombre_comercial'] ?? '') !== '' ? $empresa['nombre_comercial'] : ($empresa['razon_social'] ?? '')));
$clienteNombre = trim((string) (($cotizacion['cliente_razon_social'] ?? '') !== '' ? $cotizacion['cliente_razon_social'] : ($cotizacion['cliente'] ?? '')));
?>
<style>
@media print {
    .no-print { display: none !important; }
    body { background: #fff !important; }
}
.cotizacion-print { max-width: 980px; margin: 0 auto; background: #fff; padding: 24px; border: 1px solid #dee2e6; }
.encabezado { border-bottom: 2px solid #0d6efd; margin-bottom: 16px; padding-bottom: 12px; }
.meta-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; margin-bottom: 16px; }
.bloque { border: 1px solid #dee2e6; border-radius: 8px; padding: 12px; }
.tabla-detalle th, .tabla-detalle td { font-size: 13px; }
.resumen { margin-left: auto; width: 320px; }
</style>

<div class="no-print mb-3 d-flex gap-2">
    <button class="btn btn-dark btn-sm" type="button" onclick="window.print()">Imprimir</button>
    <a class="btn btn-outline-secondary btn-sm" href="<?= e(url('/app/cotizaciones/ver/' . $cotizacion['id'])) ?>">Volver</a>
    <form method="GET" class="d-flex gap-2 align-items-center" action="<?= e(url('/app/cotizaciones/imprimir/' . $cotizacion['id'])) ?>">
        <select class="form-select form-select-sm" name="lista_precio_id">
            <option value="">Lista automática por cliente</option>
            <?php foreach (($listasPrecios ?? []) as $lista): ?>
                <option value="<?= (int) $lista['id'] ?>" <?= (int) (($listaAplicada['id'] ?? 0)) === (int) $lista['id'] ? 'selected' : '' ?>><?= e($lista['nombre']) ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit" class="btn btn-outline-primary btn-sm">Aplicar lista</button>
    </form>
</div>

<div class="cotizacion-print">
    <div class="encabezado d-flex justify-content-between align-items-start">
        <div>
            <h2 class="h4 mb-1"><?= e($empresaNombre) ?></h2>
            <div class="small text-muted">RUC/NIT: <?= e($empresa['identificador_fiscal'] ?? '') ?></div>
            <div class="small text-muted">Correo: <?= e($empresa['correo'] ?? '') ?> · Tel: <?= e($empresa['telefono'] ?? '') ?></div>
            <div class="small text-muted">Dirección: <?= e($empresa['direccion'] ?? '') ?> <?= e($empresa['ciudad'] ?? '') ?> <?= e($empresa['pais'] ?? '') ?></div>
        </div>
        <div class="text-end">
            <h1 class="h3 mb-0">COTIZACIÓN</h1>
            <div><strong><?= e($cotizacion['numero']) ?></strong></div>
            <div class="small text-muted">Emisión: <?= e($cotizacion['fecha_emision']) ?></div>
            <div class="small text-muted">Vence: <?= e($cotizacion['fecha_vencimiento']) ?></div>
            <div class="small text-muted">Estado: <?= e($cotizacion['estado']) ?></div>
            <div class="small text-muted">Lista aplicada: <strong><?= e($listaAplicada['nombre'] ?? 'Sin lista activa') ?></strong></div>
        </div>
    </div>

    <div class="meta-grid">
        <div class="bloque">
            <div class="small text-muted">Cliente</div>
            <div><strong><?= e($clienteNombre) ?></strong></div>
            <div class="small">RUC/NIT: <?= e($cotizacion['cliente_identificador_fiscal'] ?? '') ?></div>
            <div class="small">Correo: <?= e($cotizacion['cliente_correo'] ?? '') ?></div>
            <div class="small">Tel: <?= e($cotizacion['cliente_telefono'] ?? '') ?></div>
        </div>
        <div class="bloque">
            <div class="small text-muted">Dirección cliente</div>
            <div class="small"><?= e($cotizacion['cliente_direccion'] ?? '') ?></div>
            <div class="small"><?= e($cotizacion['cliente_ciudad'] ?? '') ?></div>
        </div>
        <div class="bloque">
            <div class="small text-muted">Asesor comercial</div>
            <div><strong><?= e($cotizacion['vendedor'] ?? '') ?></strong></div>
            <div class="small">Observaciones:</div>
            <div class="small"><?= e($cotizacion['observaciones'] ?? '') ?></div>
        </div>
    </div>

    <table class="table table-bordered tabla-detalle">
        <thead class="table-light">
        <tr>
            <th>Descripción</th>
            <th class="text-end">Cant.</th>
            <th class="text-end">Precio Unit.</th>
            <th class="text-end">Desc.</th>
            <th class="text-end">IVA</th>
            <th class="text-end">Total</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach (($cotizacion['items'] ?? []) as $item): ?>
            <tr>
                <td><?= e($item['descripcion'] ?? '') ?></td>
                <td class="text-end"><?= number_format((float) ($item['cantidad'] ?? 0), 2) ?></td>
                <td class="text-end">$<?= number_format((float) ($item['precio_unitario'] ?? 0), 2) ?></td>
                <td class="text-end">
                    <?php if (($item['descuento_tipo'] ?? 'valor') === 'porcentaje'): ?>
                        <?= number_format((float) ($item['descuento_valor'] ?? 0), 2) ?>%
                    <?php else: ?>
                        $<?= number_format((float) ($item['descuento_monto'] ?? 0), 2) ?>
                    <?php endif; ?>
                </td>
                <td class="text-end">$<?= number_format(((float) ($item['total'] ?? 0) - (float) ($item['subtotal'] ?? 0)), 2) ?></td>
                <td class="text-end">$<?= number_format((float) ($item['total'] ?? 0), 2) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <table class="table table-sm resumen">
        <tr><th>Subtotal</th><td class="text-end">$<?= number_format((float) ($cotizacion['subtotal'] ?? 0), 2) ?></td></tr>
        <tr><th>Impuesto</th><td class="text-end">$<?= number_format((float) ($cotizacion['impuesto'] ?? 0), 2) ?></td></tr>
        <tr><th>Descuento</th><td class="text-end">$<?= number_format((float) ($cotizacion['descuento'] ?? 0), 2) ?></td></tr>
        <tr class="table-light"><th>Total</th><td class="text-end"><strong>$<?= number_format((float) ($cotizacion['total'] ?? 0), 2) ?></strong></td></tr>
    </table>

    <div class="mt-3 small">
        <strong>Términos y condiciones:</strong><br>
        <?= nl2br(e($cotizacion['terminos_condiciones'] ?? '')) ?>
    </div>
</div>
