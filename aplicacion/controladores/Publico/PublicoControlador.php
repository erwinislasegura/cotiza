<?php

namespace Aplicacion\Controladores\Publico;

use Aplicacion\Nucleo\Controlador;
use Aplicacion\Modelos\Plan;

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
