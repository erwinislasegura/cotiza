<h1 class="h4 mb-3">Editar vendedor</h1>
<div class="card">
    <div class="card-body">
        <form method="POST" class="row g-2">
            <?= csrf_campo() ?>
            <?php require __DIR__ . '/_formulario_vendedor.php'; ?>
            <div class="col-12">
                <button class="btn btn-primary btn-sm">Guardar cambios</button>
                <a class="btn btn-outline-secondary btn-sm" href="<?= e(url('/app/vendedores')) ?>">Cancelar</a>
            </div>
        </form>
    </div>
</div>
