<?php

namespace Aplicacion\Modelos;

use Aplicacion\Nucleo\Modelo;

class Empresa extends Modelo
{
    public function listar(): array
    {
        return $this->db->query('SELECT * FROM empresas WHERE fecha_eliminacion IS NULL ORDER BY id DESC')->fetchAll();
    }

    public function buscar(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM empresas WHERE id=:id');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function crear(array $data): int
    {
        $sql = 'INSERT INTO empresas (razon_social,nombre_comercial,identificador_fiscal,correo,telefono,direccion,ciudad,pais,estado,fecha_activacion,plan_id,fecha_creacion) VALUES (:razon_social,:nombre_comercial,:identificador_fiscal,:correo,:telefono,:direccion,:ciudad,:pais,:estado,:fecha_activacion,:plan_id,NOW())';
        $this->db->prepare($sql)->execute($data);
        return (int) $this->db->lastInsertId();
    }
}
