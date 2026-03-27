<?php

namespace Aplicacion\Middlewares;

class EmpresaMiddleware
{
    public function manejar(): void
    {
        if (!tiene_rol(['administrador_empresa', 'usuario_empresa'])) {
            http_response_code(403);
            exit('Acceso restringido para usuarios de empresa.');
        }
    }
}
