<?php

namespace Aplicacion\Modelos;

use Aplicacion\Nucleo\Modelo;

class Producto extends Modelo
{
    public function listar(int $empresaId, string $buscar = ''): array
    {
        $sql = 'SELECT p.*, c.nombre AS categoria FROM productos p LEFT JOIN categorias_productos c ON c.id = p.categoria_id WHERE p.empresa_id=:empresa_id AND p.fecha_eliminacion IS NULL';
        $params = ['empresa_id' => $empresaId];
        if ($buscar !== '') {
            $sql .= ' AND (p.nombre LIKE :buscar OR p.codigo LIKE :buscar)';
            $params['buscar'] = "%{$buscar}%";
        }
        $sql .= ' ORDER BY p.id DESC';
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function contar(int $empresaId): int
    {
        $stmt = $this->db->prepare('SELECT COUNT(*) AS total FROM productos WHERE empresa_id = :empresa_id AND fecha_eliminacion IS NULL');
        $stmt->execute(['empresa_id' => $empresaId]);
        return (int) $stmt->fetch()['total'];
    }

    public function crear(array $data): int
    {
        $sql = 'INSERT INTO productos (empresa_id,categoria_id,tipo,codigo,nombre,descripcion,unidad,precio,costo,impuesto,descuento_maximo,estado,fecha_creacion) VALUES (:empresa_id,:categoria_id,:tipo,:codigo,:nombre,:descripcion,:unidad,:precio,:costo,:impuesto,:descuento_maximo,:estado,NOW())';
        $this->db->prepare($sql)->execute($data);
        return (int) $this->db->lastInsertId();
    }
}
