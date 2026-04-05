<h1 class="h4 mb-3">Configuración general</h1>

<div class="card">
  <div class="card-header">Parámetros globales del SaaS</div>
  <div class="card-body">
    <form method="POST" action="<?= e(url('/admin/configuracion')) ?>" class="row g-2">
      <?= csrf_campo() ?>

      <div class="col-md-3">
        <label class="form-label">Nombre del sistema</label>
        <input class="form-control" name="nombre_plataforma" value="<?= e($config['nombre_plataforma'] ?? 'CotizaPro') ?>" required>
      </div>

      <div class="col-md-3">
        <label class="form-label">Correo soporte</label>
        <input class="form-control" type="email" name="correo_soporte" value="<?= e($config['correo_soporte'] ?? '') ?>" placeholder="soporte@dominio.com">
      </div>

      <div class="col-md-2">
        <label class="form-label">Moneda por defecto</label>
        <select class="form-select" name="moneda_defecto">
          <?php $moneda = (string) ($config['moneda_defecto'] ?? 'CLP'); ?>
          <option value="CLP" <?= $moneda === 'CLP' ? 'selected' : '' ?>>CLP</option>
          <option value="USD" <?= $moneda === 'USD' ? 'selected' : '' ?>>USD</option>
          <option value="EUR" <?= $moneda === 'EUR' ? 'selected' : '' ?>>EUR</option>
        </select>
      </div>

      <div class="col-md-2">
        <label class="form-label">Zona horaria</label>
        <input class="form-control" name="zona_horaria" value="<?= e($config['zona_horaria'] ?? 'America/Santiago') ?>" placeholder="America/Santiago">
      </div>

      <div class="col-md-2">
        <label class="form-label">Estado</label>
        <?php $estado = (string) ($config['estado_plataforma'] ?? 'activo'); ?>
        <select class="form-select" name="estado_plataforma">
          <option value="activo" <?= $estado === 'activo' ? 'selected' : '' ?>>activo</option>
          <option value="mantenimiento" <?= $estado === 'mantenimiento' ? 'selected' : '' ?>>mantenimiento</option>
        </select>
      </div>

      <div class="col-12">
        <button class="btn btn-primary btn-sm">Guardar cambios</button>
      </div>
    </form>
  </div>
</div>
