<section class="modulo-head d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
  <h1 class="h4 mb-0">Proveedores</h1>
</section>

<div class="alert alert-info info-modulo mb-3">
  <div class="fw-semibold mb-1">Información clave para gestionar proveedores</div>
  <ul class="mb-0 small ps-3">
    <li>Registra razón social y datos fiscales para mantener trazabilidad documental.</li>
    <li>Mantén contacto, correo y teléfono actualizados para recepciones y reposición.</li>
    <li>Usa observaciones para indicar condiciones de entrega o pago.</li>
  </ul>
</div>

<div class="card mb-3">
  <div class="card-header d-flex justify-content-between align-items-center">
    <span>Nuevo proveedor</span>
    <span class="small text-muted">Completa la información principal para registrarlo rápido.</span>
  </div>
  <div class="card-body">
    <form method="POST" action="<?= e(url('/app/inventario/proveedores')) ?>" class="row g-3">
      <?= csrf_campo() ?>
      <div class="col-md-4">
        <label class="form-label">Razón social</label>
        <input name="razon_social" class="form-control" required maxlength="180" placeholder="Ej: Proveedora Industrial SpA">
      </div>
      <div class="col-md-4">
        <label class="form-label">Nombre comercial</label>
        <input name="nombre_comercial" class="form-control" maxlength="180" placeholder="Ej: Proveedora Industrial">
      </div>
      <div class="col-md-4">
        <label class="form-label">Nombre de contacto</label>
        <input name="nombre_contacto" class="form-control" maxlength="140" placeholder="Ej: Laura Pérez">
      </div>

      <div class="col-md-3">
        <label class="form-label">RUT/NIT</label>
        <input name="identificador_fiscal" class="form-control" maxlength="80" placeholder="Ej: 76.123.456-7">
      </div>
      <div class="col-md-3">
        <label class="form-label">Correo</label>
        <input type="email" name="correo" class="form-control" maxlength="160" placeholder="proveedor@empresa.com">
      </div>
      <div class="col-md-3">
        <label class="form-label">Teléfono</label>
        <input name="telefono" class="form-control" maxlength="80" placeholder="+56 9 1234 5678">
      </div>
      <div class="col-md-3">
        <label class="form-label">Ciudad</label>
        <input name="ciudad" class="form-control" maxlength="120" placeholder="Santiago">
      </div>

      <div class="col-md-9">
        <label class="form-label">Dirección</label>
        <input name="direccion" class="form-control" maxlength="200" placeholder="Calle, número, comuna">
      </div>
      <div class="col-md-3">
        <label class="form-label">Estado</label>
        <select name="estado" class="form-select"><option value="activo">Activo</option><option value="inactivo">Inactivo</option></select>
      </div>

      <div class="col-12">
        <label class="form-label">Observación</label>
        <textarea name="observacion" class="form-control" rows="2" placeholder="Notas internas del proveedor"></textarea>
      </div>

      <div class="col-12 d-flex justify-content-end">
        <button class="btn btn-primary btn-sm px-4">Guardar proveedor</button>
      </div>
    </form>
  </div>
</div>

<div class="card">
  <div class="card-header">
    <strong>Listado de proveedores</strong>
    <div class="small text-muted">Registros encontrados: <?= count($proveedores) ?></div>
  </div>
  <div class="table-responsive" style="overflow: visible;">
    <table class="table table-hover align-middle table-sm mb-0 tabla-admin">
      <thead class="table-light">
        <tr><th>Razón social</th><th>Fiscal</th><th>Contacto</th><th>Correo</th><th>Teléfono</th><th>Ciudad</th><th>Estado</th></tr>
      </thead>
      <tbody>
      <?php if(empty($proveedores)): ?>
        <tr><td colspan="7" class="text-center py-4 text-muted">No hay proveedores registrados.</td></tr>
      <?php else: foreach($proveedores as $p): ?>
        <tr>
          <td><?= e($p['nombre']) ?></td>
          <td><?= e($p['identificador_fiscal'] ?? '') ?></td>
          <td><?= e($p['contacto'] ?? '') ?></td>
          <td><?= e($p['correo'] ?? '') ?></td>
          <td><?= e($p['telefono'] ?? '') ?></td>
          <td><?= e($p['ciudad'] ?? '') ?></td>
          <td><span class="badge <?= ($p['estado'] ?? 'activo') === 'activo' ? 'badge-estado-activo' : 'badge-estado-inactivo' ?>"><?= e($p['estado'] ?? 'activo') ?></span></td>
        </tr>
      <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>
