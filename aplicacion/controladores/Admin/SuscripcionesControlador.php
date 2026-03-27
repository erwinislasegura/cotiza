<?php

namespace Aplicacion\Controladores\Admin;

use Aplicacion\Nucleo\Controlador;
use Aplicacion\Modelos\Suscripcion;

class SuscripcionesControlador extends Controlador
{
    public function index(): void
    {
        $estado = $_GET['estado'] ?? '';
        $suscripciones = (new Suscripcion())->listar($estado);
        $this->vista('admin/suscripciones/index', compact('suscripciones', 'estado'), 'admin');
    }

    public function actualizarEstado(int $id): void
    {
        validar_csrf();
        (new Suscripcion())->actualizarEstado($id, $_POST['estado'] ?? 'activa', trim($_POST['observaciones'] ?? ''));
        flash('success', 'Estado de suscripción actualizado.');
        $this->redirigir('/admin/suscripciones');
    }
}
