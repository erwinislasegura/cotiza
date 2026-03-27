<?php

namespace Aplicacion\Modelos;

use Aplicacion\Nucleo\Modelo;

class Cotizacion extends Modelo
{
    public function listar(int $empresaId): array
    {
        $sql = 'SELECT c.*, cl.nombre AS cliente FROM cotizaciones c INNER JOIN clientes cl ON cl.id = c.cliente_id WHERE c.empresa_id = :empresa_id AND c.fecha_eliminacion IS NULL ORDER BY c.id DESC';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['empresa_id' => $empresaId]);
        return $stmt->fetchAll();
    }

    public function contarMes(int $empresaId): int
    {
        $stmt = $this->db->prepare('SELECT COUNT(*) total FROM cotizaciones WHERE empresa_id=:empresa_id AND MONTH(fecha_emision)=MONTH(CURDATE()) AND YEAR(fecha_emision)=YEAR(CURDATE()) AND fecha_eliminacion IS NULL');
        $stmt->execute(['empresa_id' => $empresaId]);
        return (int) $stmt->fetch()['total'];
    }

    public function siguienteNumero(int $empresaId): string
    {
        $stmt = $this->db->prepare('SELECT COALESCE(MAX(consecutivo),0)+1 AS siguiente FROM cotizaciones WHERE empresa_id=:empresa_id');
        $stmt->execute(['empresa_id' => $empresaId]);
        $num = (int) $stmt->fetch()['siguiente'];
        return 'COT-' . str_pad((string) $empresaId, 3, '0', STR_PAD_LEFT) . '-' . str_pad((string) $num, 6, '0', STR_PAD_LEFT);
    }

    public function crearConItems(array $cotizacion, array $items): int
    {
        $this->db->beginTransaction();
        try {
            $sql = 'INSERT INTO cotizaciones (empresa_id, cliente_id, usuario_id, numero, consecutivo, estado, subtotal, descuento, impuesto, total, observaciones, terminos_condiciones, fecha_emision, fecha_vencimiento, fecha_creacion) VALUES (:empresa_id,:cliente_id,:usuario_id,:numero,:consecutivo,:estado,:subtotal,:descuento,:impuesto,:total,:observaciones,:terminos_condiciones,:fecha_emision,:fecha_vencimiento,NOW())';
            $this->db->prepare($sql)->execute($cotizacion);
            $cotizacionId = (int) $this->db->lastInsertId();

            $sqlItem = 'INSERT INTO items_cotizacion (cotizacion_id, producto_id, descripcion, cantidad, precio_unitario, porcentaje_impuesto, subtotal, total, fecha_creacion) VALUES (:cotizacion_id,:producto_id,:descripcion,:cantidad,:precio_unitario,:porcentaje_impuesto,:subtotal,:total,NOW())';
            $stmtItem = $this->db->prepare($sqlItem);
            foreach ($items as $item) {
                $item['cotizacion_id'] = $cotizacionId;
                $stmtItem->execute($item);
            }

            $this->db->prepare('INSERT INTO historial_estados_cotizacion (cotizacion_id, estado, observaciones, usuario_id, fecha_creacion) VALUES (:cotizacion_id,:estado,:observaciones,:usuario_id,NOW())')->execute([
                'cotizacion_id' => $cotizacionId,
                'estado' => $cotizacion['estado'],
                'observaciones' => 'Creación inicial',
                'usuario_id' => $cotizacion['usuario_id'],
            ]);

            $this->db->commit();
            return $cotizacionId;
        } catch (\Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
}
