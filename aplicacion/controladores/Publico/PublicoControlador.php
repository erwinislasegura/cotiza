<?php

namespace Aplicacion\Controladores\Publico;

use Aplicacion\Nucleo\Controlador;
use Aplicacion\Modelos\Plan;
use Aplicacion\Servicios\ServicioCorreo;

class PublicoControlador extends Controlador
{
    public function inicio(): void
    {
        $planes = (new Plan())->listar(true);
        $this->vista('publico/inicio', ['planes' => $planes], 'publico');
    }

    public function caracteristicas(): void
    {
        $this->vista('publico/caracteristicas', [], 'publico');
    }

    public function planes(): void
    {
        $planes = (new Plan())->listar(true);
        $this->vista('publico/planes', ['planes' => $planes], 'publico');
    }

    public function contacto(): void
    {
        $this->vista('publico/contacto', [], 'publico');
    }

    public function preguntasFrecuentes(): void
    {
        $this->vista('publico/preguntas_frecuentes', [], 'publico');
    }

    public function enviarContacto(): void
    {
        validar_csrf();

        $nombre = trim($_POST['nombre'] ?? '');
        $correo = filter_var($_POST['correo'] ?? '', FILTER_VALIDATE_EMAIL);
        $mensaje = trim($_POST['mensaje'] ?? '');

        if ($nombre === '' || !$correo || $mensaje === '') {
            flash('danger', 'Completa todos los campos del formulario de contacto.');
            $this->redirigir('/contacto');
        }

        (new ServicioCorreo())->enviar(
            'ventas@cotizapro.local',
            'Nuevo lead desde landing',
            'landing_contacto',
            ['nombre' => $nombre, 'correo' => $correo, 'mensaje' => $mensaje]
        );

        flash('success', 'Gracias por escribirnos. Te contactaremos pronto.');
        $this->redirigir('/contacto');
    }

    public function contratar(string $planSlug): void
    {
        $plan = (new Plan())->buscarPorSlug($planSlug);
        if (!$plan) {
            http_response_code(404);
            require __DIR__ . '/../../vistas/errores/404.php';
            return;
        }
        $this->vista('publico/contratar', ['plan' => $plan], 'publico');
    }
}
