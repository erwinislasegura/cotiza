<?php

namespace Aplicacion\Servicios;

use Aplicacion\Modelos\LogCorreo;

class ServicioCorreo
{
    public function enviar(string $destinatario, string $asunto, string $plantilla, array $datos = []): bool
    {
        // Implementación mock desacoplada para SMTP real.
        $log = new LogCorreo();
        $log->registrar([
            'destinatario' => $destinatario,
            'asunto' => $asunto,
            'plantilla' => $plantilla,
            'payload' => json_encode($datos, JSON_UNESCAPED_UNICODE),
            'estado' => 'enviado',
        ]);
        return true;
    }
}
