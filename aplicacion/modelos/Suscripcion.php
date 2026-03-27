<?php

namespace Aplicacion\Modelos;

use Aplicacion\Nucleo\Modelo;

class Suscripcion extends Modelo
{
    public function listar(string $estado = ''): array
    {
        $sql = 'SELECT s.*, e.nombre_comercial AS empresa, p.nombre AS plan, DATEDIFF(s.fecha_vencimiento, CURDATE()) AS dias_restantes FROM suscripciones s INNER JOIN empresas e ON e.id=s.empresa_id INNER JOIN planes p ON p.id=s.plan_id WHERE s.fecha_eliminacion IS NULL';
        $params = [];
        if ($estado !== '') {
            $sql .= ' AND s.estado = :estado';
            $params['estado'] = $estado;
        }
        $sql .= ' ORDER BY s.fecha_vencimiento ASC';
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function crear(array $data): int
    {
        $sql = 'INSERT INTO suscripciones (empresa_id, plan_id, estado, fecha_inicio, fecha_vencimiento, observaciones, renovacion_automatica, fecha_creacion) VALUES (:empresa_id,:plan_id,:estado,:fecha_inicio,:fecha_vencimiento,:observaciones,:renovacion_automatica,NOW())';
        $this->db->prepare($sql)->execute($data);
        return (int) $this->db->lastInsertId();
    }

    public function actualizarEstado(int $id, string $estado, string $observaciones): void
    {
        $this->db->prepare('UPDATE suscripciones SET estado=:estado, observaciones=:observaciones, fecha_actualizacion=NOW() WHERE id=:id')->execute(['id' => $id, 'estado' => $estado, 'observaciones' => $observaciones]);
        $this->db->prepare('INSERT INTO historial_suscripciones (suscripcion_id, accion, observaciones, fecha_creacion) VALUES (:suscripcion_id,:accion,:observaciones,NOW())')->execute(['suscripcion_id' => $id, 'accion' => 'actualizacion_estado', 'observaciones' => $observaciones]);
    }
}
