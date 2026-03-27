<?php

namespace Aplicacion\Modelos;

use Aplicacion\Nucleo\Modelo;

class GestionComercial extends Modelo
{
    private array $tablasPermitidas = [
        'contactos_cliente',
        'vendedores',
        'categorias_productos',
        'listas_precios',
        'seguimientos_comerciales',
        'aprobaciones_cotizacion',
        'documentos_plantillas',
        'notificaciones_empresa',
        'historial_actividad',
    ];

    public function listarTablaEmpresa(string $tabla, int $empresaId, string $buscar = '', int $limite = 10): array
    {
        $permitidas = [
            'vendedores' => ['nombre', 'correo'],
            'categorias_productos' => ['nombre', 'descripcion'],
            'listas_precios' => ['nombre', 'tipo_lista'],
            'seguimientos_comerciales' => ['proxima_accion', 'estado_comercial'],
            'aprobaciones_cotizacion' => ['motivo', 'estado'],
            'documentos_plantillas' => ['nombre', 'tipo_documento'],
            'notificaciones_empresa' => ['titulo', 'tipo'],
            'historial_actividad' => ['modulo', 'accion'],
            'contactos_cliente' => ['nombre', 'correo'],
        ];

        if (!isset($permitidas[$tabla])) {
            return [];
        }

        $sql = "SELECT * FROM {$tabla} WHERE empresa_id = :empresa_id";
        $params = ['empresa_id' => $empresaId];

        if ($buscar !== '') {
            $condiciones = [];
            foreach ($permitidas[$tabla] as $indice => $campo) {
                $llave = "buscar_{$indice}";
                $condiciones[] = "{$campo} LIKE :{$llave}";
                $params[$llave] = "%{$buscar}%";
            }
            $sql .= ' AND (' . implode(' OR ', $condiciones) . ')';
        }

        if ($tabla === 'historial_actividad') {
            $sql .= ' ORDER BY fecha_creacion DESC';
        } else {
            $sql .= ' ORDER BY id DESC';
        }
        $sql .= ' LIMIT :limite';

        $stmt = $this->db->prepare($sql);
        foreach ($params as $k => $v) {
            $stmt->bindValue(':' . $k, $v);
        }
        $stmt->bindValue(':limite', $limite, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function crear(string $tabla, array $data): int
    {
        if (!in_array($tabla, $this->tablasPermitidas, true)) {
            throw new \InvalidArgumentException('Tabla no permitida para escritura.');
        }
        $campos = array_keys($data);
        $columns = implode(',', $campos);
        $binds = ':' . implode(',:', $campos);
        $sql = "INSERT INTO {$tabla} ({$columns}) VALUES ({$binds})";
        $this->db->prepare($sql)->execute($data);
        return (int) $this->db->lastInsertId();
    }

    public function obtenerPorId(string $tabla, int $empresaId, int $id): ?array
    {
        if (!in_array($tabla, $this->tablasPermitidas, true)) {
            return null;
        }
        $stmt = $this->db->prepare("SELECT * FROM {$tabla} WHERE empresa_id = :empresa_id AND id = :id LIMIT 1");
        $stmt->execute(['empresa_id' => $empresaId, 'id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function eliminar(string $tabla, int $empresaId, int $id): void
    {
        if (!in_array($tabla, $this->tablasPermitidas, true)) {
            return;
        }
        $stmt = $this->db->prepare("DELETE FROM {$tabla} WHERE empresa_id = :empresa_id AND id = :id");
        $stmt->execute(['empresa_id' => $empresaId, 'id' => $id]);
    }

    public function actualizarDinamico(string $tabla, int $empresaId, int $id, array $data): void
    {
        if (!in_array($tabla, $this->tablasPermitidas, true)) {
            return;
        }

        $actual = $this->obtenerPorId($tabla, $empresaId, $id);
        if (!$actual) {
            return;
        }

        $permitidos = array_diff(array_keys($actual), ['id', 'empresa_id', 'fecha_creacion']);
        $asignaciones = [];
        $params = ['empresa_id' => $empresaId, 'id' => $id];

        foreach ($permitidos as $campo) {
            if (array_key_exists($campo, $data)) {
                $asignaciones[] = "{$campo} = :{$campo}";
                $params[$campo] = $data[$campo] === '' ? null : $data[$campo];
            }
        }

        if ($asignaciones === []) {
            return;
        }

        $sql = "UPDATE {$tabla} SET " . implode(', ', $asignaciones) . " WHERE empresa_id = :empresa_id AND id = :id";
        $this->db->prepare($sql)->execute($params);
    }

    public function estadisticasInicio(int $empresaId): array
    {
        $queries = [
            'cotizaciones_mes' => "SELECT COUNT(*) total FROM cotizaciones WHERE empresa_id=:empresa_id AND fecha_eliminacion IS NULL AND MONTH(fecha_emision)=MONTH(CURDATE()) AND YEAR(fecha_emision)=YEAR(CURDATE())",
            'aprobadas' => "SELECT COUNT(*) total FROM cotizaciones WHERE empresa_id=:empresa_id AND fecha_eliminacion IS NULL AND estado='aprobada'",
            'rechazadas' => "SELECT COUNT(*) total FROM cotizaciones WHERE empresa_id=:empresa_id AND fecha_eliminacion IS NULL AND estado='rechazada'",
            'por_vencer' => "SELECT COUNT(*) total FROM cotizaciones WHERE empresa_id=:empresa_id AND fecha_eliminacion IS NULL AND fecha_vencimiento BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY)",
            'clientes_recientes' => "SELECT nombre, correo, fecha_creacion FROM clientes WHERE empresa_id=:empresa_id AND fecha_eliminacion IS NULL ORDER BY id DESC LIMIT 5",
            'productos_top' => "SELECT descripcion, COUNT(*) total FROM items_cotizacion ic INNER JOIN cotizaciones c ON c.id = ic.cotizacion_id WHERE c.empresa_id=:empresa_id GROUP BY descripcion ORDER BY total DESC LIMIT 5",
            'vendedores_top' => "SELECT u.nombre, COUNT(*) total FROM cotizaciones c INNER JOIN usuarios u ON u.id=c.usuario_id WHERE c.empresa_id=:empresa_id GROUP BY u.nombre ORDER BY total DESC LIMIT 5",
        ];

        $salida = [];
        foreach ($queries as $clave => $sql) {
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['empresa_id' => $empresaId]);
            if (str_contains($clave, 'recientes') || str_contains($clave, 'top')) {
                $salida[$clave] = $stmt->fetchAll();
            } else {
                $salida[$clave] = (int) ($stmt->fetch()['total'] ?? 0);
            }
        }

        return $salida;
    }
}
