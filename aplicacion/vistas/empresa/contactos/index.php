<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <h1 class="h4 mb-0">Contactos</h1>
    <div class="d-flex gap-2">
        <a href="<?= e(url('/app/contactos/exportar/excel?q=' . urlencode($buscar))) ?>" class="btn btn-success btn-sm">Exportar Excel</a>
        <a href="<?= e(url('/app/clientes')) ?>" class="btn btn-outline-primary btn-sm">Volver a clientes</a>
    </div>
</div>

<div class="card mb-3">
    <div class="card-header">Nuevo contacto</div>
    <div class="card-body">
        <?php if ($clientes === []): ?>
            <div class="alert alert-warning mb-0">
                No hay clientes registrados activos para asociar contactos.
                <a href="<?= e(url('/app/clientes/crear')) ?>" class="alert-link">Registrar cliente</a>.
            </div>
        <?php else: ?>
            <form method="POST" action="<?= e(url('/app/contactos')) ?>" class="row g-3">
                <?= csrf_campo() ?>
                <div class="col-md-4">
                    <label class="form-label">Cliente registrado</label>
                    <select name="cliente_id" class="form-select" required>
                        <option value="">Selecciona un cliente</option>
                        <?php foreach ($clientes as $c): ?>
                            <option value="<?= (int) $c['id'] ?>"><?= e($c['nombre_comercial'] ?: $c['razon_social'] ?: $c['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Nombre del contacto</label>
                    <input name="nombre" class="form-control" required maxlength="120" placeholder="Ej: María Pérez">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Cargo</label>
                    <input name="cargo" class="form-control" maxlength="120" placeholder="Ej: Jefe de compras">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Correo</label>
                    <input type="email" name="correo" class="form-control" maxlength="150" placeholder="correo@empresa.com">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Teléfono</label>
                    <input name="telefono" class="form-control" maxlength="30" placeholder="Ej: +51 1 555 1234">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Celular</label>
                    <input name="celular" class="form-control" maxlength="30" placeholder="Ej: +51 999 888 777">
                </div>
                <div class="col-md-8">
                    <label class="form-label">Observaciones</label>
                    <input name="observaciones" class="form-control" maxlength="255" placeholder="Detalles relevantes del contacto">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="es_principal" name="es_principal" value="1">
                        <label class="form-check-label" for="es_principal">Marcar como contacto principal</label>
                    </div>
                </div>
                <div class="col-12">
                    <button class="btn btn-primary btn-sm">Guardar contacto</button>
                </div>
            </form>
        <?php endif; ?>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
        <strong>Listado de contactos registrados</strong>
        <form class="d-flex gap-2" method="GET" action="<?= e(url('/app/contactos')) ?>">
            <input class="form-control form-control-sm" name="q" value="<?= e($buscar) ?>" placeholder="Buscar por cliente, nombre o correo">
            <button class="btn btn-outline-secondary btn-sm">Buscar</button>
        </form>
    </div>
    <div class="table-responsive" style="overflow: visible;">
        <table class="table table-sm table-hover mb-0 tabla-admin">
            <thead>
                <tr>
                    <th>Cliente</th>
                    <th>Nombre</th>
                    <th>Cargo</th>
                    <th>Correo</th>
                    <th>Teléfono</th>
                    <th>Celular</th>
                    <th>Principal</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($contactos === []): ?>
                    <tr>
                        <td colspan="8" class="text-center text-muted py-3">No se encontraron contactos registrados.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($contactos as $r): ?>
                        <tr>
                            <td><?= e($r['cliente_nombre'] ?: $r['cliente_razon_social'] ?: ('#' . (int) $r['cliente_id'])) ?></td>
                            <td><?= e($r['nombre']) ?></td>
                            <td><?= e($r['cargo']) ?></td>
                            <td><?= e($r['correo']) ?></td>
                            <td><?= e($r['telefono']) ?></td>
                            <td><?= e($r['celular']) ?></td>
                            <td><?= !empty($r['es_principal']) ? 'Sí' : 'No' ?></td>
                            <td class="text-end">
                                <div class="dropdown dropup">
                                    <button class="btn btn-light btn-sm dropdown-toggle" data-bs-toggle="dropdown">Acciones</button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="<?= e(url('/app/contactos/ver/' . $r['id'])) ?>">Ver</a></li>
                                        <li><a class="dropdown-item" href="<?= e(url('/app/contactos/editar/' . $r['id'])) ?>">Editar</a></li>
                                        <li>
                                            <form method="POST" action="<?= e(url('/app/contactos/eliminar/' . $r['id'])) ?>" onsubmit="return confirm('¿Eliminar contacto?')">
                                                <?= csrf_campo() ?>
                                                <button type="submit" class="dropdown-item text-danger">Eliminar</button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
