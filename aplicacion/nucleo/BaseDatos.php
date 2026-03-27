<?php

namespace Aplicacion\Nucleo;

use PDO;
use PDOException;

class BaseDatos
{
    private static ?PDO $conexion = null;

    public static function obtener(): PDO
    {
        if (self::$conexion) {
            return self::$conexion;
        }

        $config = require __DIR__ . '/../../configuracion/base_datos.php';
        $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=%s', $config['host'], $config['puerto'], $config['nombre'], $config['charset']);

        try {
            self::$conexion = new PDO($dsn, $config['usuario'], $config['password'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (PDOException $e) {
            http_response_code(500);
            echo 'Error de conexión a base de datos.';
            exit;
        }

        return self::$conexion;
    }
}
