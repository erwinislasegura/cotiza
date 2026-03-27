<?php

namespace Aplicacion\Modelos;

use Aplicacion\Nucleo\Modelo;

class Usuario extends Modelo
{
    public function buscarPorCorreo(string $correo): ?array
    {
        $sql = 'SELECT u.*, r.codigo AS rol_codigo FROM usuarios u INNER JOIN roles r ON r.id = u.rol_id WHERE u.correo = :correo AND u.fecha_eliminacion IS NULL LIMIT 1';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['correo' => $correo]);
        return $stmt->fetch() ?: null;
    }

    public function listarPorEmpresa(int $empresaId): array
    {
        $stmt = $this->db->prepare('SELECT u.id, u.nombre, u.correo, u.estado, r.nombre AS rol FROM usuarios u INNER JOIN roles r ON r.id = u.rol_id WHERE u.empresa_id = :empresa_id AND u.fecha_eliminacion IS NULL ORDER BY u.id DESC');
        $stmt->execute(['empresa_id' => $empresaId]);
        return $stmt->fetchAll();
    }

    public function crear(array $data): int
    {
        $sql = 'INSERT INTO usuarios (empresa_id, rol_id, nombre, correo, password, estado, fecha_creacion) VALUES (:empresa_id, :rol_id, :nombre, :correo, :password, :estado, NOW())';
        $stmt = $this->db->prepare($sql);
        $stmt->execute($data);
        return (int) $this->db->lastInsertId();
    }

    public function listarRolesEmpresa(): array
    {
        return $this->db->query("SELECT id, nombre FROM roles WHERE codigo IN ('admin_empresa','vendedor','visor') ORDER BY nombre")->fetchAll();
    }
}
