<?php

namespace Aplicacion\Modelos;

use Aplicacion\Nucleo\Modelo;

class Cliente extends Modelo
{
    public function listar(int $empresaId, string $buscar = ''): array
    {
        $sql = 'SELECT * FROM clientes WHERE empresa_id=:empresa_id AND fecha_eliminacion IS NULL';
        $params = ['empresa_id' => $empresaId];
        if ($buscar !== '') {
            $sql .= ' AND (nombre LIKE :buscar OR correo LIKE :buscar)';
            $params['buscar'] = "%{$buscar}%";
        }
        $sql .= ' ORDER BY id DESC';
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function contar(int $empresaId): int
    {
        $stmt = $this->db->prepare('SELECT COUNT(*) AS total FROM clientes WHERE empresa_id = :empresa_id AND fecha_eliminacion IS NULL');
        $stmt->execute(['empresa_id' => $empresaId]);
        return (int) $stmt->fetch()['total'];
    }

    public function crear(array $data): int
    {
        $sql = 'INSERT INTO clientes (empresa_id, nombre, correo, telefono, direccion, notas, estado, fecha_creacion) VALUES (:empresa_id,:nombre,:correo,:telefono,:direccion,:notas,:estado,NOW())';
        $this->db->prepare($sql)->execute($data);
        return (int) $this->db->lastInsertId();
    }
}
