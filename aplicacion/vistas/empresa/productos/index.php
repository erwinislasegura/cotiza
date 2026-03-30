<section class="modulo-head d-flex justify-content-between align-items-center mb-3">
  <h1 class="h4 mb-0">Servicios / Productos</h1>
</section>

<div class="card mb-3">
  <div class="card-header">Nuevo ítem comercial</div>
  <div class="card-body">
    <?php
    $accion = url('/app/productos');
    $textoBoton = 'Guardar ítem';
    $mostrarCancelar = false;
    $mostrarModalCategoria = true;
    $modalId = 'modalNuevaCategoriaProductoIndex';
    $redirigirA = '/app/productos';
    require __DIR__ . '/_formulario.php';
    ?>
  </div>
</div>

<div class="card">
  <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
    <div>
      <strong>Listado de productos</strong>
      <div class="small text-muted">Registros encontrados: <?= count($productos) ?></div>
    </div>
    <div class="d-flex gap-2">
      <a href="<?= e(url('/app/productos/exportar/excel?q=' . urlencode($buscar))) ?>" class="<?= e(\Aplicacion\Servicios\ExcelExpoFormato::BOTON_CLASES) ?>" style="<?= e(\Aplicacion\Servicios\ExcelExpoFormato::BOTON_ESTILO) ?>"><?= e(\Aplicacion\Servicios\ExcelExpoFormato::BOTON_TEXTO) ?></a>
      <form class="d-flex gap-2" method="GET">
        <input class="form-control form-control-sm" name="q" value="<?= e($buscar) ?>" placeholder="Buscar por nombre/código/SKU">
        <button class="btn btn-outline-secondary btn-sm">Buscar</button>
        <?php if ($buscar !== ''): ?>
          <a class="btn btn-outline-dark btn-sm" href="<?= e(url('/app/productos')) ?>">Limpiar</a>
        <?php endif; ?>
      </form>
    </div>
  </div>
  <div class="table-responsive" style="overflow: visible;">
    <table class="table table-hover table-sm mb-0 tabla-admin">
      <thead class="table-light">
        <tr>
          <th>Código</th><th>SKU</th><th>Nombre</th><th>Tipo</th><th>Categoría</th><th>Precio</th><th>Stock mín.</th><th>Stock aviso</th><th>Estado</th><th class="text-end">Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($productos)): ?>
          <tr><td colspan="10" class="text-center py-4 text-muted">No hay productos registrados con este criterio.</td></tr>
        <?php else: ?>
          <?php foreach($productos as $p): ?>
            <tr>
              <td><?= e($p['codigo']) ?></td>
              <td><?= e($p['sku'] ?: '-') ?></td>
              <td><?= e($p['nombre']) ?></td>
              <td><?= e($p['tipo'] ?? 'producto') ?></td>
              <td><?= e($p['categoria'] ?? '-') ?></td>
              <td>$<?= number_format((float)$p['precio'],2) ?></td>
              <td><?= number_format((float)($p['stock_minimo'] ?? 0), 2) ?></td>
              <td><?= number_format((float)($p['stock_aviso'] ?? 0), 2) ?></td>
              <td><span class="badge <?= ($p['estado'] === 'activo') ? 'badge-estado-activo' : 'badge-estado-inactivo' ?>"><?= e($p['estado']) ?></span></td>
              <td class="text-end"><div class="dropdown dropup"><button class="btn btn-light btn-sm dropdown-toggle" data-bs-toggle="dropdown">Acciones</button><ul class="dropdown-menu dropdown-menu-end"><li><a class="dropdown-item" href="<?= e(url('/app/productos/ver/' . $p['id'])) ?>">Ver</a></li><li><a class="dropdown-item" href="<?= e(url('/app/productos/editar/' . $p['id'])) ?>">Editar</a></li><li><form method="POST" action="<?= e(url('/app/productos/eliminar/' . $p['id'])) ?>" onsubmit="return confirm('¿Confirmas eliminar este producto?')"><?= csrf_campo() ?><button class="dropdown-item text-danger" type="submit">Eliminar</button></form></li></ul></div></td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
