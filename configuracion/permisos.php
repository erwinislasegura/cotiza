<?php

return [
    'superadministrador' => ['*'],
    'administrador_empresa' => [
        'panel_empresa', 'clientes', 'productos', 'cotizaciones', 'usuarios_empresa', 'configuracion_empresa'
    ],
    'usuario_empresa' => [
        'panel_empresa', 'clientes', 'productos', 'cotizaciones'
    ],
];
