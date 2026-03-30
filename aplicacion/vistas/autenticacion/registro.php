<section class="container py-5">
  <div class="row justify-content-center">
    <div class="col-12 col-xl-10">
      <div class="card shadow-sm border-0">
        <div class="card-body p-4 p-lg-5">
          <h1 class="h3 mb-2">Crear cuenta empresarial</h1>
          <p class="text-secondary mb-4">
            Registra tu empresa en menos de 2 minutos. Te enviaremos acceso inmediato para iniciar la gestión comercial.
          </p>

          <form method="POST" class="row g-3">
            <?= csrf_campo() ?>

            <div class="col-12">
              <h2 class="h6 text-uppercase text-muted mb-2">Datos de la empresa</h2>
            </div>
            <div class="col-md-6">
              <label class="form-label" for="razon_social">Razón social</label>
              <input id="razon_social" class="form-control" name="razon_social" maxlength="150" required>
            </div>
            <div class="col-md-6">
              <label class="form-label" for="nombre_comercial">Nombre comercial</label>
              <input id="nombre_comercial" class="form-control" name="nombre_comercial" maxlength="150" required>
            </div>
            <div class="col-md-4">
              <label class="form-label" for="identificador_fiscal">RUT / NIT</label>
              <input id="identificador_fiscal" class="form-control" name="identificador_fiscal" maxlength="80" required>
            </div>
            <div class="col-md-4">
              <label class="form-label" for="correo_empresa">Correo corporativo</label>
              <input id="correo_empresa" type="email" class="form-control" name="correo_empresa" maxlength="150" required>
            </div>
            <div class="col-md-4">
              <label class="form-label" for="telefono">Teléfono</label>
              <input id="telefono" class="form-control" name="telefono" maxlength="60" placeholder="+57 300 123 4567">
            </div>
            <div class="col-md-5">
              <label class="form-label" for="direccion">Dirección</label>
              <input id="direccion" class="form-control" name="direccion" maxlength="200">
            </div>
            <div class="col-md-4">
              <label class="form-label" for="ciudad">Ciudad</label>
              <input id="ciudad" class="form-control" name="ciudad" maxlength="120">
            </div>
            <div class="col-md-3">
              <label class="form-label" for="pais">País</label>
              <input id="pais" class="form-control" name="pais" value="Colombia" maxlength="120">
            </div>
            <div class="col-md-6">
              <label class="form-label" for="plan_id">Plan</label>
              <select id="plan_id" class="form-select" name="plan_id" required>
                <option value="">Selecciona un plan</option>
                <?php foreach ($planes as $plan): ?>
                  <option value="<?= (int) $plan['id'] ?>" <?= ((int) ($planPreseleccionado ?? 0) === (int) $plan['id']) ? 'selected' : '' ?>>
                    <?= e($plan['nombre']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="col-12 mt-2">
              <h2 class="h6 text-uppercase text-muted mb-2">Administrador de la cuenta</h2>
            </div>
            <div class="col-md-4">
              <label class="form-label" for="nombre_admin">Nombre completo</label>
              <input id="nombre_admin" class="form-control" name="nombre_admin" maxlength="150" required>
            </div>
            <div class="col-md-4">
              <label class="form-label" for="correo_admin">Correo del administrador</label>
              <input id="correo_admin" type="email" class="form-control" name="correo_admin" maxlength="150" required>
            </div>
            <div class="col-md-4">
              <label class="form-label" for="password">Contraseña</label>
              <input id="password" type="password" class="form-control" name="password" minlength="8" required>
              <div class="form-text">Mínimo 8 caracteres.</div>
            </div>

            <div class="col-12 d-grid d-md-flex justify-content-md-end pt-2">
              <button type="submit" class="btn btn-primary px-4">Crear empresa y activar cuenta</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>
