<?php

namespace Aplicacion\Modelos;

use Aplicacion\Nucleo\Modelo;

class Pago extends Modelo
{
    public function listar(): array
    {
        $sql = 'SELECT p.*, e.nombre_comercial AS empresa FROM pagos p INNER JOIN empresas e ON e.id = p.empresa_id ORDER BY p.id DESC';
        return $this->db->query($sql)->fetchAll();
    }

    public function crear(array $data): int
    {
        $sql = 'INSERT INTO pagos (empresa_id,suscripcion_id,monto,moneda,metodo,estado,referencia_externa,observaciones,payload,fecha_pago,fecha_creacion) VALUES (:empresa_id,:suscripcion_id,:monto,:moneda,:metodo,:estado,:referencia_externa,:observaciones,:payload,:fecha_pago,NOW())';
        $this->db->prepare($sql)->execute($data);
        return (int) $this->db->lastInsertId();
    }
}
