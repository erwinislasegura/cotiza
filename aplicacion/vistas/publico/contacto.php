<div class="container py-5">
    <h1 class="h3">Contacto comercial</h1>
    <p class="text-secondary small">Cuéntanos sobre tu empresa y te ayudamos a elegir el plan ideal.</p>

    <form class="row g-2 mt-3" method="POST" action="<?= e(url('/contacto')) ?>">
        <?= csrf_campo() ?>
        <div class="col-md-6">
            <input class="form-control" name="nombre" placeholder="Nombre" required>
        </div>
        <div class="col-md-6">
            <input class="form-control" type="email" name="correo" placeholder="Correo" required>
        </div>
        <div class="col-12">
            <textarea class="form-control" name="mensaje" rows="4" placeholder="Cuéntanos sobre tu empresa" required></textarea>
        </div>
        <div class="col-12 d-flex gap-2">
            <button class="btn btn-primary btn-sm" type="submit">Solicitar información</button>
            <a href="<?= e(url('/planes')) ?>" class="btn btn-outline-primary btn-sm">Ver planes</a>
        </div>
    </form>
</div>
