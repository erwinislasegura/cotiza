<?php

return [
    'host' => $_ENV['DB_HOST'] ?? '127.0.0.1',
    'puerto' => $_ENV['DB_PUERTO'] ?? '3306',
    'nombre' => $_ENV['DB_NOMBRE'] ?? 'cotiza_saas',
    'usuario' => $_ENV['DB_USUARIO'] ?? 'root',
    'password' => $_ENV['DB_PASSWORD'] ?? '',
    'charset' => $_ENV['DB_CHARSET'] ?? 'utf8mb4',
];
