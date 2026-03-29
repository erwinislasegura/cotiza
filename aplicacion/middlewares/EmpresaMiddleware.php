<?php

namespace Aplicacion\Middlewares;

class EmpresaMiddleware
{
    public function manejar(): void
    {
        if (!tiene_rol([
            'administrador_empresa',
            'vendedor',
            'administrativo',
            'contabilidad',
            'supervisor_comercial',
            'operaciones',
            'usuario_empresa',
        ])) {
            http_response_code(403);
            exit('Acceso restringido para usuarios de empresa.');
        }
    }
}
