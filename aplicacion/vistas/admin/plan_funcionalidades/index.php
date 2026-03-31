<h1 class="h5 mb-3">Funcionalidades del plan: <?= e($plan['nombre'] ?? 'Plan') ?></h1>
<form method="POST">
  <?= csrf_campo() ?>
  <div class="table-responsive">
    <table class="table table-sm tabla-admin align-middle">
      <thead>
        <tr>
          <th>Nombre</th>
          <th>Código</th>
          <th>Descripción comercial</th>
          <th>Activo</th>
          <th>Límite</th>
          <th>Ilimitado</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($funcionalidades as $f): ?>
          <?php $a = $mapa[$f['id']] ?? null; ?>
          <tr>
            <td class="fw-semibold"><?= e($f['nombre']) ?></td>
            <td><code><?= e($f['codigo_interno']) ?></code></td>
            <td class="small text-muted"><?= e($f['descripcion'] ?: 'Sin descripción comercial') ?></td>
            <td><input type="checkbox" name="funcionalidades[<?= $f['id'] ?>][activo]" <?= ($a['activo'] ?? 0) ? 'checked' : '' ?>></td>
            <td><input class="form-control form-control-sm" type="number" min="0" name="funcionalidades[<?= $f['id'] ?>][valor_numerico]" value="<?= e((string) ($a['valor_numerico'] ?? 0)) ?>"></td>
            <td><input type="checkbox" name="funcionalidades[<?= $f['id'] ?>][es_ilimitado]" <?= ($a['es_ilimitado'] ?? 0) ? 'checked' : '' ?>></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <button class="btn btn-primary btn-sm">Guardar asignaciones</button>
</form>
