<?php

namespace Aplicacion\Modelos;

use Aplicacion\Nucleo\Modelo;
use Throwable;

class Inventario extends Modelo
{
    public function listarProveedores(int $empresaId): array
    {
        $stmt = $this->db->prepare('SELECT * FROM proveedores_inventario WHERE empresa_id = :empresa_id ORDER BY nombre ASC');
        $stmt->execute(['empresa_id' => $empresaId]);
        return $stmt->fetchAll();
    }

    public function crearProveedor(int $empresaId, array $data): int
    {
        $stmt = $this->db->prepare('INSERT INTO proveedores_inventario (empresa_id,nombre,identificador_fiscal,contacto,correo,telefono,direccion,ciudad,observacion,estado,fecha_creacion) VALUES (:empresa_id,:nombre,:identificador_fiscal,:contacto,:correo,:telefono,:direccion,:ciudad,:observacion,:estado,NOW())');
        $stmt->execute([
            'empresa_id' => $empresaId,
            'nombre' => $data['nombre'],
            'identificador_fiscal' => $data['identificador_fiscal'] ?? null,
            'contacto' => $data['contacto'] ?? null,
            'correo' => $data['correo'] ?? null,
            'telefono' => $data['telefono'] ?? null,
            'direccion' => $data['direccion'] ?? null,
            'ciudad' => $data['ciudad'] ?? null,
            'observacion' => $data['observacion'] ?? null,
            'estado' => $data['estado'] ?? 'activo',
        ]);
        return (int) $this->db->lastInsertId();
    }


    public function obtenerProveedor(int $empresaId, int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM proveedores_inventario WHERE empresa_id=:empresa_id AND id=:id LIMIT 1');
        $stmt->execute(['empresa_id' => $empresaId, 'id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function listarProductos(int $empresaId): array
    {
        $stmt = $this->db->prepare('SELECT id,codigo,nombre,COALESCE(stock_actual,0) AS stock_actual,COALESCE(stock_minimo,0) AS stock_minimo,COALESCE(stock_critico,0) AS stock_critico FROM productos WHERE empresa_id=:empresa_id AND fecha_eliminacion IS NULL ORDER BY nombre ASC');
        $stmt->execute(['empresa_id' => $empresaId]);
        return $stmt->fetchAll();
    }

    public function crearRecepcion(array $cabecera, array $detalles): int
    {
        $this->db->beginTransaction();
        try {
            $stmtCab = $this->db->prepare('INSERT INTO recepciones_inventario (empresa_id,proveedor_id,tipo_documento,numero_documento,fecha_documento,referencia_interna,observacion,usuario_id,fecha_creacion) VALUES (:empresa_id,:proveedor_id,:tipo_documento,:numero_documento,:fecha_documento,:referencia_interna,:observacion,:usuario_id,NOW())');
            $stmtCab->execute($cabecera);
            $recepcionId = (int) $this->db->lastInsertId();

            $stmtDet = $this->db->prepare('INSERT INTO recepciones_inventario_detalle (recepcion_id,producto_id,cantidad,costo_unitario,subtotal) VALUES (:recepcion_id,:producto_id,:cantidad,:costo_unitario,:subtotal)');
            $stmtProd = $this->db->prepare('SELECT nombre,COALESCE(stock_actual,0) AS stock_actual FROM productos WHERE id=:producto_id AND empresa_id=:empresa_id AND fecha_eliminacion IS NULL LIMIT 1 FOR UPDATE');
            $stmtUpd = $this->db->prepare('UPDATE productos SET stock_actual = :stock_actual, fecha_actualizacion = NOW() WHERE id=:producto_id AND empresa_id=:empresa_id');
            $stmtMov = $this->db->prepare('INSERT INTO movimientos_inventario (empresa_id,producto_id,tipo_movimiento,modulo_origen,documento_origen,referencia_id,entrada,salida,saldo_resultante,observacion,usuario_id,fecha_creacion) VALUES (:empresa_id,:producto_id,:tipo_movimiento,:modulo_origen,:documento_origen,:referencia_id,:entrada,:salida,:saldo_resultante,:observacion,:usuario_id,NOW())');

            $stocks = [];
            foreach ($detalles as $detalle) {
                $stmtDet->execute([
                    'recepcion_id' => $recepcionId,
                    'producto_id' => $detalle['producto_id'],
                    'cantidad' => $detalle['cantidad'],
                    'costo_unitario' => $detalle['costo_unitario'],
                    'subtotal' => $detalle['subtotal'],
                ]);

                $stmtProd->execute([
                    'producto_id' => $detalle['producto_id'],
                    'empresa_id' => $cabecera['empresa_id'],
                ]);
                $producto = $stmtProd->fetch();
                if (!$producto) {
                    throw new \RuntimeException('Producto inválido en la recepción.');
                }

                $stockAnterior = (float) $producto['stock_actual'];
                $stockNuevo = $stockAnterior + (float) $detalle['cantidad'];
                $stmtUpd->execute([
                    'stock_actual' => $stockNuevo,
                    'producto_id' => $detalle['producto_id'],
                    'empresa_id' => $cabecera['empresa_id'],
                ]);

                $stmtMov->execute([
                    'empresa_id' => $cabecera['empresa_id'],
                    'producto_id' => $detalle['producto_id'],
                    'tipo_movimiento' => 'recepcion_proveedor',
                    'modulo_origen' => 'recepciones_inventario',
                    'documento_origen' => $cabecera['tipo_documento'] . ' #' . $cabecera['numero_documento'],
                    'referencia_id' => $recepcionId,
                    'entrada' => $detalle['cantidad'],
                    'salida' => 0,
                    'saldo_resultante' => $stockNuevo,
                    'observacion' => $cabecera['observacion'],
                    'usuario_id' => $cabecera['usuario_id'],
                ]);

                $stocks[] = [
                    'producto_id' => (int) $detalle['producto_id'],
                    'stock_anterior' => $stockAnterior,
                    'stock_actual' => $stockNuevo,
                ];
            }

            $this->db->commit();
            return $recepcionId;
        } catch (Throwable $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            throw $e;
        }
    }

    public function crearAjuste(array $data, bool $permitirNegativo = false): int
    {
        $this->db->beginTransaction();
        try {
            $stmtProd = $this->db->prepare('SELECT COALESCE(stock_actual,0) AS stock_actual FROM productos WHERE id=:producto_id AND empresa_id=:empresa_id AND fecha_eliminacion IS NULL LIMIT 1 FOR UPDATE');
            $stmtProd->execute(['producto_id' => $data['producto_id'], 'empresa_id' => $data['empresa_id']]);
            $producto = $stmtProd->fetch();
            if (!$producto) {
                throw new \RuntimeException('Producto no encontrado para ajuste.');
            }

            $stockAnterior = (float) $producto['stock_actual'];
            $cantidad = (float) $data['cantidad'];
            $entrada = $data['tipo_ajuste'] === 'entrada' ? $cantidad : 0;
            $salida = $data['tipo_ajuste'] === 'salida' ? $cantidad : 0;
            $stockNuevo = $stockAnterior + $entrada - $salida;

            if (!$permitirNegativo && $stockNuevo < 0) {
                throw new \RuntimeException('El ajuste deja el stock en negativo y no está permitido.');
            }

            $this->db->prepare('INSERT INTO ajustes_inventario (empresa_id,producto_id,tipo_ajuste,cantidad,motivo,observacion,usuario_id,fecha_creacion) VALUES (:empresa_id,:producto_id,:tipo_ajuste,:cantidad,:motivo,:observacion,:usuario_id,NOW())')
                ->execute($data);
            $ajusteId = (int) $this->db->lastInsertId();

            $this->db->prepare('UPDATE productos SET stock_actual=:stock_actual, fecha_actualizacion=NOW() WHERE id=:producto_id AND empresa_id=:empresa_id')
                ->execute([
                    'stock_actual' => $stockNuevo,
                    'producto_id' => $data['producto_id'],
                    'empresa_id' => $data['empresa_id'],
                ]);

            $this->db->prepare('INSERT INTO movimientos_inventario (empresa_id,producto_id,tipo_movimiento,modulo_origen,documento_origen,referencia_id,entrada,salida,saldo_resultante,observacion,usuario_id,fecha_creacion) VALUES (:empresa_id,:producto_id,:tipo_movimiento,:modulo_origen,:documento_origen,:referencia_id,:entrada,:salida,:saldo_resultante,:observacion,:usuario_id,NOW())')
                ->execute([
                    'empresa_id' => $data['empresa_id'],
                    'producto_id' => $data['producto_id'],
                    'tipo_movimiento' => $data['tipo_ajuste'] === 'entrada' ? 'ajuste_entrada' : 'ajuste_salida',
                    'modulo_origen' => 'ajustes_inventario',
                    'documento_origen' => 'ajuste #' . $ajusteId,
                    'referencia_id' => $ajusteId,
                    'entrada' => $entrada,
                    'salida' => $salida,
                    'saldo_resultante' => $stockNuevo,
                    'observacion' => trim($data['motivo'] . ' ' . $data['observacion']),
                    'usuario_id' => $data['usuario_id'],
                ]);

            $this->db->commit();
            return $ajusteId;
        } catch (Throwable $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            throw $e;
        }
    }

    public function listarRecepciones(int $empresaId): array
    {
        $stmt = $this->db->prepare('SELECT r.*, p.nombre AS proveedor_nombre, u.nombre AS usuario_nombre
            FROM recepciones_inventario r
            LEFT JOIN proveedores_inventario p ON p.id = r.proveedor_id
            LEFT JOIN usuarios u ON u.id = r.usuario_id
            WHERE r.empresa_id = :empresa_id
            ORDER BY r.id DESC LIMIT 300');
        $stmt->execute(['empresa_id' => $empresaId]);
        return $stmt->fetchAll();
    }

    public function obtenerRecepcion(int $empresaId, int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT r.*, p.nombre AS proveedor_nombre, u.nombre AS usuario_nombre
            FROM recepciones_inventario r
            LEFT JOIN proveedores_inventario p ON p.id = r.proveedor_id
            LEFT JOIN usuarios u ON u.id = r.usuario_id
            WHERE r.empresa_id = :empresa_id AND r.id = :id LIMIT 1');
        $stmt->execute(['empresa_id' => $empresaId, 'id' => $id]);
        $recepcion = $stmt->fetch();
        if (!$recepcion) {
            return null;
        }

        $stmtDet = $this->db->prepare('SELECT d.*, pr.codigo, pr.nombre
            FROM recepciones_inventario_detalle d
            INNER JOIN productos pr ON pr.id = d.producto_id
            WHERE d.recepcion_id = :recepcion_id ORDER BY d.id ASC');
        $stmtDet->execute(['recepcion_id' => $id]);
        $recepcion['detalles'] = $stmtDet->fetchAll();

        return $recepcion;
    }

    public function listarAjustes(int $empresaId, array $filtros = []): array
    {
        $sql = 'SELECT a.*, pr.codigo, pr.nombre AS producto_nombre, u.nombre AS usuario_nombre
            FROM ajustes_inventario a
            INNER JOIN productos pr ON pr.id = a.producto_id
            LEFT JOIN usuarios u ON u.id = a.usuario_id
            WHERE a.empresa_id = :empresa_id';
        $params = ['empresa_id' => $empresaId];

        if (!empty($filtros['producto_id'])) {
            $sql .= ' AND a.producto_id = :producto_id';
            $params['producto_id'] = (int) $filtros['producto_id'];
        }
        if (!empty($filtros['tipo_ajuste'])) {
            $sql .= ' AND a.tipo_ajuste = :tipo_ajuste';
            $params['tipo_ajuste'] = $filtros['tipo_ajuste'];
        }
        if (!empty($filtros['fecha_desde'])) {
            $sql .= ' AND DATE(a.fecha_creacion) >= :fecha_desde';
            $params['fecha_desde'] = $filtros['fecha_desde'];
        }
        if (!empty($filtros['fecha_hasta'])) {
            $sql .= ' AND DATE(a.fecha_creacion) <= :fecha_hasta';
            $params['fecha_hasta'] = $filtros['fecha_hasta'];
        }

        $sql .= ' ORDER BY a.id DESC LIMIT 400';
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function obtenerAjuste(int $empresaId, int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT a.*, pr.codigo, pr.nombre AS producto_nombre, pr.stock_actual, u.nombre AS usuario_nombre
            FROM ajustes_inventario a
            INNER JOIN productos pr ON pr.id = a.producto_id
            LEFT JOIN usuarios u ON u.id = a.usuario_id
            WHERE a.empresa_id = :empresa_id AND a.id = :id LIMIT 1');
        $stmt->execute(['empresa_id' => $empresaId, 'id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function listarMovimientos(int $empresaId, ?int $productoId = null): array
    {
        $sql = 'SELECT m.*, p.codigo, p.nombre AS producto_nombre, u.nombre AS usuario_nombre
            FROM movimientos_inventario m
            INNER JOIN productos p ON p.id = m.producto_id
            LEFT JOIN usuarios u ON u.id = m.usuario_id
            WHERE m.empresa_id = :empresa_id';
        $params = ['empresa_id' => $empresaId];
        if ($productoId) {
            $sql .= ' AND m.producto_id = :producto_id';
            $params['producto_id'] = $productoId;
        }
        $sql .= ' ORDER BY m.id DESC LIMIT 500';
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function obtenerAjustePermitirNegativo(int $empresaId): bool
    {
        $stmt = $this->db->prepare('SELECT valor FROM configuraciones_empresa WHERE empresa_id=:empresa_id AND clave = "inventario_permitir_stock_negativo" LIMIT 1');
        $stmt->execute(['empresa_id' => $empresaId]);
        return ((string) $stmt->fetchColumn()) === '1';
    }
}
