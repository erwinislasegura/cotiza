<?php

namespace Aplicacion\Modelos;

use Aplicacion\Nucleo\Modelo;
use PDO;
use Throwable;

class PuntoVenta extends Modelo
{
    public function listarCajas(int $empresaId): array
    {
        $stmt = $this->db->prepare('SELECT * FROM cajas_pos WHERE empresa_id = :empresa_id ORDER BY id DESC');
        $stmt->execute(['empresa_id' => $empresaId]);
        return $stmt->fetchAll();
    }

    public function crearCaja(array $data): int
    {
        $sql = 'INSERT INTO cajas_pos (empresa_id,nombre,codigo,estado,fecha_creacion) VALUES (:empresa_id,:nombre,:codigo,:estado,NOW())';
        $this->db->prepare($sql)->execute($data);
        return (int) $this->db->lastInsertId();
    }

    public function obtenerAperturaActiva(int $empresaId, int $usuarioId): ?array
    {
        $sql = 'SELECT a.*, c.nombre AS caja_nombre, c.codigo AS caja_codigo
            FROM aperturas_caja_pos a
            INNER JOIN cajas_pos c ON c.id = a.caja_id
            WHERE a.empresa_id = :empresa_id AND a.usuario_id = :usuario_id AND a.estado = "abierta"
            ORDER BY a.id DESC LIMIT 1';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['empresa_id' => $empresaId, 'usuario_id' => $usuarioId]);
        return $stmt->fetch() ?: null;
    }

    public function abrirCaja(array $data): int
    {
        $sql = 'INSERT INTO aperturas_caja_pos (empresa_id,caja_id,usuario_id,monto_inicial,observacion,fecha_apertura,estado) VALUES (:empresa_id,:caja_id,:usuario_id,:monto_inicial,:observacion,NOW(),"abierta")';
        $this->db->prepare($sql)->execute($data);
        return (int) $this->db->lastInsertId();
    }

    public function listarProductosPos(int $empresaId, string $buscar = '', ?int $categoriaId = null): array
    {
        $sql = 'SELECT p.id,p.codigo,p.sku,p.codigo_barras,p.nombre,p.precio,p.impuesto,p.tipo,p.estado,p.categoria_id,
                    COALESCE(p.stock_actual, 0) AS stock_actual,
                    c.nombre AS categoria
                FROM productos p
                LEFT JOIN categorias_productos c ON c.id = p.categoria_id
                WHERE p.empresa_id = :empresa_id AND p.fecha_eliminacion IS NULL AND p.estado = "activo"';
        $params = ['empresa_id' => $empresaId];
        if ($buscar !== '') {
            $sql .= ' AND (p.nombre LIKE :buscar OR p.codigo LIKE :buscar OR p.sku LIKE :buscar OR p.codigo_barras LIKE :buscar)';
            $params['buscar'] = "%{$buscar}%";
        }
        if ($categoriaId) {
            $sql .= ' AND p.categoria_id = :categoria_id';
            $params['categoria_id'] = $categoriaId;
        }
        $sql .= ' ORDER BY p.nombre ASC LIMIT 300';
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function listarClientesPos(int $empresaId): array
    {
        $sql = 'SELECT id, nombre, razon_social, nombre_comercial, identificador_fiscal FROM clientes WHERE empresa_id = :empresa_id AND fecha_eliminacion IS NULL AND estado = "activo" ORDER BY id DESC LIMIT 200';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['empresa_id' => $empresaId]);
        return $stmt->fetchAll();
    }

    public function asegurarClienteRapido(int $empresaId): int
    {
        $stmt = $this->db->prepare('SELECT id FROM clientes WHERE empresa_id = :empresa_id AND nombre = :nombre AND fecha_eliminacion IS NULL LIMIT 1');
        $stmt->execute(['empresa_id' => $empresaId, 'nombre' => 'Consumidor final']);
        $cliente = $stmt->fetch();
        if ($cliente) {
            return (int) $cliente['id'];
        }

        $stmt = $this->db->prepare('INSERT INTO clientes (empresa_id,nombre,razon_social,estado,fecha_creacion) VALUES (:empresa_id,:nombre,:razon_social,"activo",NOW())');
        $stmt->execute([
            'empresa_id' => $empresaId,
            'nombre' => 'Consumidor final',
            'razon_social' => 'Venta rápida POS',
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function registrarVenta(array $ventaData, array $items, array $pagos, bool $permitirSinStock = false): int
    {
        $this->db->beginTransaction();
        try {
            $this->db->prepare('INSERT INTO ventas_pos (empresa_id,caja_id,apertura_caja_id,cliente_id,usuario_id,tipo_venta,subtotal,descuento,impuesto,total,estado,numero_venta,fecha_venta,observaciones,monto_recibido,vuelto) VALUES (:empresa_id,:caja_id,:apertura_caja_id,:cliente_id,:usuario_id,:tipo_venta,:subtotal,:descuento,:impuesto,:total,"pagada",:numero_venta,NOW(),:observaciones,:monto_recibido,:vuelto)')
                ->execute($ventaData);

            $ventaId = (int) $this->db->lastInsertId();

            $stmtItem = $this->db->prepare('INSERT INTO items_venta_pos (venta_pos_id,producto_id,codigo_producto,nombre_producto,cantidad,precio_unitario,descuento,impuesto,subtotal,total) VALUES (:venta_pos_id,:producto_id,:codigo_producto,:nombre_producto,:cantidad,:precio_unitario,:descuento,:impuesto,:subtotal,:total)');
            $stmtStock = $this->db->prepare('SELECT nombre, COALESCE(stock_actual, 0) AS stock_actual FROM productos WHERE id = :id AND empresa_id = :empresa_id AND fecha_eliminacion IS NULL LIMIT 1 FOR UPDATE');
            $stmtUpdStock = $this->db->prepare('UPDATE productos SET stock_actual = GREATEST(0, COALESCE(stock_actual, 0) - :cantidad), fecha_actualizacion = NOW() WHERE id = :id AND empresa_id = :empresa_id');
            $stmtMovInv = $this->db->prepare('INSERT INTO movimientos_inventario_pos (empresa_id,venta_pos_id,producto_id,tipo_movimiento,cantidad,stock_anterior,stock_actual,usuario_id,fecha_movimiento) VALUES (:empresa_id,:venta_pos_id,:producto_id,"salida_venta",:cantidad,:stock_anterior,:stock_actual,:usuario_id,NOW())');

            foreach ($items as $item) {
                $stmtItem->execute([
                    'venta_pos_id' => $ventaId,
                    'producto_id' => $item['producto_id'],
                    'codigo_producto' => $item['codigo_producto'],
                    'nombre_producto' => $item['nombre_producto'],
                    'cantidad' => $item['cantidad'],
                    'precio_unitario' => $item['precio_unitario'],
                    'descuento' => $item['descuento'],
                    'impuesto' => $item['impuesto'],
                    'subtotal' => $item['subtotal'],
                    'total' => $item['total'],
                ]);

                $stmtStock->execute(['id' => $item['producto_id'], 'empresa_id' => $ventaData['empresa_id']]);
                $stock = $stmtStock->fetch();
                if (!$stock) {
                    throw new \RuntimeException('Producto no encontrado para descontar stock.');
                }
                $stockAnterior = (float) $stock['stock_actual'];
                $cantidad = (float) $item['cantidad'];
                if (!$permitirSinStock && $stockAnterior < $cantidad) {
                    throw new \RuntimeException('Stock insuficiente para ' . $stock['nombre']);
                }

                $stmtUpdStock->execute([
                    'id' => $item['producto_id'],
                    'empresa_id' => $ventaData['empresa_id'],
                    'cantidad' => $cantidad,
                ]);

                $stockNuevo = max(0, $stockAnterior - $cantidad);
                $stmtMovInv->execute([
                    'empresa_id' => $ventaData['empresa_id'],
                    'venta_pos_id' => $ventaId,
                    'producto_id' => $item['producto_id'],
                    'cantidad' => $cantidad,
                    'stock_anterior' => $stockAnterior,
                    'stock_actual' => $stockNuevo,
                    'usuario_id' => $ventaData['usuario_id'],
                ]);
            }

            $stmtPago = $this->db->prepare('INSERT INTO pagos_venta_pos (venta_pos_id,metodo_pago,monto,referencia,fecha_pago) VALUES (:venta_pos_id,:metodo_pago,:monto,:referencia,NOW())');
            foreach ($pagos as $pago) {
                $stmtPago->execute([
                    'venta_pos_id' => $ventaId,
                    'metodo_pago' => $pago['metodo_pago'],
                    'monto' => $pago['monto'],
                    'referencia' => $pago['referencia'],
                ]);
            }

            $stmtMovCaja = $this->db->prepare('INSERT INTO movimientos_caja_pos (empresa_id,caja_id,apertura_caja_id,tipo_movimiento,concepto,monto,usuario_id,fecha_movimiento,venta_pos_id) VALUES (:empresa_id,:caja_id,:apertura_caja_id,"ingreso_venta",:concepto,:monto,:usuario_id,NOW(),:venta_pos_id)');
            $stmtMovCaja->execute([
                'empresa_id' => $ventaData['empresa_id'],
                'caja_id' => $ventaData['caja_id'],
                'apertura_caja_id' => $ventaData['apertura_caja_id'],
                'concepto' => 'Venta POS ' . $ventaData['numero_venta'],
                'monto' => $ventaData['total'],
                'usuario_id' => $ventaData['usuario_id'],
                'venta_pos_id' => $ventaId,
            ]);

            $this->db->commit();
            return $ventaId;
        } catch (Throwable $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            throw $e;
        }
    }

    public function listarVentas(int $empresaId): array
    {
        $sql = 'SELECT v.*, c.nombre AS caja_nombre, COALESCE(NULLIF(cl.razon_social, ""), NULLIF(cl.nombre_comercial, ""), cl.nombre, "Venta rápida") AS cliente_nombre,
                    u.nombre AS cajero
                FROM ventas_pos v
                INNER JOIN cajas_pos c ON c.id = v.caja_id
                LEFT JOIN clientes cl ON cl.id = v.cliente_id
                LEFT JOIN usuarios u ON u.id = v.usuario_id
                WHERE v.empresa_id = :empresa_id
                ORDER BY v.id DESC LIMIT 200';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['empresa_id' => $empresaId]);
        return $stmt->fetchAll();
    }

    public function obtenerVenta(int $empresaId, int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT v.*, c.nombre AS caja_nombre, u.nombre AS cajero,
            COALESCE(NULLIF(cl.razon_social, ""), NULLIF(cl.nombre_comercial, ""), cl.nombre, "Venta rápida") AS cliente_nombre
            FROM ventas_pos v
            INNER JOIN cajas_pos c ON c.id = v.caja_id
            LEFT JOIN clientes cl ON cl.id = v.cliente_id
            LEFT JOIN usuarios u ON u.id = v.usuario_id
            WHERE v.empresa_id=:empresa_id AND v.id=:id LIMIT 1');
        $stmt->execute(['empresa_id' => $empresaId, 'id' => $id]);
        $venta = $stmt->fetch();
        if (!$venta) {
            return null;
        }

        $stmtItems = $this->db->prepare('SELECT * FROM items_venta_pos WHERE venta_pos_id = :venta_pos_id ORDER BY id ASC');
        $stmtItems->execute(['venta_pos_id' => $id]);
        $venta['items'] = $stmtItems->fetchAll();

        $stmtPagos = $this->db->prepare('SELECT * FROM pagos_venta_pos WHERE venta_pos_id = :venta_pos_id ORDER BY id ASC');
        $stmtPagos->execute(['venta_pos_id' => $id]);
        $venta['pagos'] = $stmtPagos->fetchAll();

        return $venta;
    }

    public function resumenCierre(int $empresaId, int $aperturaId): array
    {
        $stmt = $this->db->prepare('SELECT 
            COALESCE(SUM(CASE WHEN p.metodo_pago = "efectivo" THEN p.monto ELSE 0 END),0) AS efectivo,
            COALESCE(SUM(CASE WHEN p.metodo_pago = "transferencia" THEN p.monto ELSE 0 END),0) AS transferencia,
            COALESCE(SUM(CASE WHEN p.metodo_pago = "tarjeta" THEN p.monto ELSE 0 END),0) AS tarjeta,
            COALESCE(SUM(p.monto),0) AS total_ventas
            FROM ventas_pos v
            INNER JOIN pagos_venta_pos p ON p.venta_pos_id = v.id
            WHERE v.empresa_id = :empresa_id AND v.apertura_caja_id = :apertura_id AND v.estado = "pagada"');
        $stmt->execute(['empresa_id' => $empresaId, 'apertura_id' => $aperturaId]);
        return $stmt->fetch() ?: [];
    }

    public function cerrarCaja(array $data): int
    {
        $stmt = $this->db->prepare('INSERT INTO cierres_caja_pos (empresa_id,apertura_caja_id,usuario_id,monto_esperado,monto_contado,diferencia,observacion,fecha_cierre,monto_efectivo,monto_transferencia,monto_tarjeta,monto_inicial) VALUES (:empresa_id,:apertura_caja_id,:usuario_id,:monto_esperado,:monto_contado,:diferencia,:observacion,NOW(),:monto_efectivo,:monto_transferencia,:monto_tarjeta,:monto_inicial)');
        $stmt->execute($data);
        $cierreId = (int) $this->db->lastInsertId();

        $this->db->prepare('UPDATE aperturas_caja_pos SET estado = "cerrada" WHERE id = :id AND empresa_id = :empresa_id')
            ->execute(['id' => $data['apertura_caja_id'], 'empresa_id' => $data['empresa_id']]);

        return $cierreId;
    }

    public function listarMovimientosCaja(int $empresaId): array
    {
        $stmt = $this->db->prepare('SELECT m.*, c.nombre AS caja_nombre, u.nombre AS usuario_nombre FROM movimientos_caja_pos m INNER JOIN cajas_pos c ON c.id = m.caja_id LEFT JOIN usuarios u ON u.id = m.usuario_id WHERE m.empresa_id = :empresa_id ORDER BY m.id DESC LIMIT 200');
        $stmt->execute(['empresa_id' => $empresaId]);
        return $stmt->fetchAll();
    }

    public function listarHistorialCierres(int $empresaId): array
    {
        $stmt = $this->db->prepare('SELECT c.*, a.fecha_apertura, cj.nombre AS caja_nombre, u.nombre AS usuario_cierre FROM cierres_caja_pos c INNER JOIN aperturas_caja_pos a ON a.id = c.apertura_caja_id INNER JOIN cajas_pos cj ON cj.id = a.caja_id LEFT JOIN usuarios u ON u.id = c.usuario_id WHERE c.empresa_id = :empresa_id ORDER BY c.id DESC LIMIT 100');
        $stmt->execute(['empresa_id' => $empresaId]);
        return $stmt->fetchAll();
    }

    public function obtenerConfiguracion(int $empresaId): array
    {
        $stmt = $this->db->prepare('SELECT permitir_venta_sin_stock, impuesto_por_defecto FROM configuracion_pos WHERE empresa_id = :empresa_id LIMIT 1');
        $stmt->execute(['empresa_id' => $empresaId]);
        return $stmt->fetch() ?: ['permitir_venta_sin_stock' => 0, 'impuesto_por_defecto' => 0];
    }

    public function guardarConfiguracion(int $empresaId, array $data): void
    {
        $stmt = $this->db->prepare('INSERT INTO configuracion_pos (empresa_id,permitir_venta_sin_stock,impuesto_por_defecto,fecha_actualizacion) VALUES (:empresa_id,:permitir_venta_sin_stock,:impuesto_por_defecto,NOW()) ON DUPLICATE KEY UPDATE permitir_venta_sin_stock = VALUES(permitir_venta_sin_stock), impuesto_por_defecto = VALUES(impuesto_por_defecto), fecha_actualizacion = NOW()');
        $stmt->execute([
            'empresa_id' => $empresaId,
            'permitir_venta_sin_stock' => $data['permitir_venta_sin_stock'],
            'impuesto_por_defecto' => $data['impuesto_por_defecto'],
        ]);
    }

    public function siguienteNumeroVenta(int $empresaId): string
    {
        $stmt = $this->db->prepare('SELECT COUNT(*) AS total FROM ventas_pos WHERE empresa_id = :empresa_id');
        $stmt->execute(['empresa_id' => $empresaId]);
        $n = (int) ($stmt->fetch()['total'] ?? 0) + 1;
        return 'POS-' . str_pad((string) $empresaId, 3, '0', STR_PAD_LEFT) . '-' . str_pad((string) $n, 8, '0', STR_PAD_LEFT);
    }
}
