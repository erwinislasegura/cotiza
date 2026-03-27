<?php
$hayClientes = !empty($clientes);
$hayProductos = !empty($productos);
$puedeGuardar = $hayClientes && $hayProductos;
?>
<h1 class="h4 mb-3">Crear cotización</h1>

<form method="POST" class="d-grid gap-3">
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
        <div class="card-header">Detalle de cotización</div>
        <div class="card-body row g-3">
            <div class="col-md-5">
                <label class="small">Producto</label>
                <div class="input-group">
                    <select class="form-select" name="producto_id" required>
                        <?php if (!$hayProductos): ?>
                            <option value="">No hay productos registrados</option>
                        <?php endif; ?>
                        <?php foreach ($productos as $p): ?>
                            <option value="<?= $p['id'] ?>"><?= e($p['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button class="btn btn-outline-primary" type="button" data-bs-toggle="modal" data-bs-target="#modalProducto">Dato fijo producto</button>
                </div>
            </div>

            <div class="col-md-2">
                <label class="small">Cantidad</label>
                <input class="form-control" name="cantidad" type="number" step="0.01" value="1">
            </div>

            <div class="col-md-2">
                <label class="small">Precio</label>
                <input class="form-control" name="precio_unitario" type="number" step="0.01" value="0">
            </div>

            <div class="col-md-3">
                <label class="small">Impuesto %</label>
                <input class="form-control" name="impuesto_item" type="number" step="0.01" value="19">
            </div>

            <div class="col-md-3">
                <label class="small">Descuento</label>
                <input class="form-control" name="descuento" type="number" step="0.01" value="0">
            </div>

            <div class="col-md-9">
                <label class="small">Descripción ítem</label>
                <input class="form-control" name="descripcion_item">
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
