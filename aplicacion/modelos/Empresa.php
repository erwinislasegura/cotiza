<?php

namespace Aplicacion\Modelos;

use Aplicacion\Nucleo\Modelo;

class Empresa extends Modelo
{
    public function listar(array $filtros = []): array
    {
        $sql = 'SELECT e.*, p.nombre AS plan_nombre,
                (SELECT COUNT(*) FROM usuarios u WHERE u.empresa_id = e.id AND u.fecha_eliminacion IS NULL) AS total_usuarios,
                (SELECT u.ultimo_acceso FROM usuarios u INNER JOIN roles r ON r.id = u.rol_id WHERE u.empresa_id = e.id AND r.codigo = "administrador_empresa" AND u.fecha_eliminacion IS NULL ORDER BY u.id ASC LIMIT 1) AS ultimo_acceso_admin
            FROM empresas e
            LEFT JOIN planes p ON p.id = e.plan_id
            WHERE e.fecha_eliminacion IS NULL';

        $params = [];
        if (!empty($filtros['busqueda'])) {
            $sql .= ' AND (e.nombre_comercial LIKE :q OR e.razon_social LIKE :q OR e.correo LIKE :q OR e.identificador_fiscal LIKE :q)';
            $params['q'] = '%' . $filtros['busqueda'] . '%';
        }
        if (!empty($filtros['estado'])) {
            $sql .= ' AND e.estado = :estado';
            $params['estado'] = $filtros['estado'];
        }
        if (!empty($filtros['plan_id'])) {
            $sql .= ' AND e.plan_id = :plan_id';
            $params['plan_id'] = (int) $filtros['plan_id'];
        }

        $sql .= ' ORDER BY e.id DESC';
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function buscar(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM empresas WHERE id=:id');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function buscarDetalleAdmin(int $id): ?array
    {
        $sql = 'SELECT e.*, p.nombre AS plan_nombre, p.precio_mensual AS plan_precio_mensual,
                s.id AS suscripcion_id, s.estado AS suscripcion_estado, s.fecha_inicio, s.fecha_vencimiento,
                DATEDIFF(s.fecha_vencimiento, CURDATE()) AS dias_restantes
            FROM empresas e
            LEFT JOIN planes p ON p.id = e.plan_id
            LEFT JOIN suscripciones s ON s.id = (
                SELECT sx.id FROM suscripciones sx WHERE sx.empresa_id = e.id AND sx.fecha_eliminacion IS NULL ORDER BY sx.id DESC LIMIT 1
            )
            WHERE e.id = :id LIMIT 1';

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function crear(array $data): int
    {
        $sql = 'INSERT INTO empresas (razon_social,nombre_comercial,identificador_fiscal,correo,telefono,direccion,ciudad,pais,estado,fecha_activacion,plan_id,fecha_creacion) VALUES (:razon_social,:nombre_comercial,:identificador_fiscal,:correo,:telefono,:direccion,:ciudad,:pais,:estado,:fecha_activacion,:plan_id,NOW())';
        $this->db->prepare($sql)->execute($data);
        return (int) $this->db->lastInsertId();
    }

    public function actualizarEstado(int $empresaId, string $estado): void
    {
        $this->db->prepare('UPDATE empresas SET estado = :estado, fecha_actualizacion = NOW() WHERE id = :id')->execute([
            'id' => $empresaId,
            'estado' => $estado,
        ]);
    }

    public function actualizarPlanYObservacion(int $empresaId, int $planId, string $observaciones): void
    {
        $this->db->prepare('UPDATE empresas SET plan_id = :plan_id, observaciones_internas = :observaciones, fecha_actualizacion = NOW() WHERE id = :id')->execute([
            'id' => $empresaId,
            'plan_id' => $planId,
            'observaciones' => $observaciones,
        ]);
    }

    public function existePorIdentificadorFiscal(string $identificadorFiscal): bool
    {
        $stmt = $this->db->prepare('SELECT id FROM empresas WHERE identificador_fiscal = :identificador_fiscal AND fecha_eliminacion IS NULL LIMIT 1');
        $stmt->execute(['identificador_fiscal' => $identificadorFiscal]);
        return (bool) $stmt->fetchColumn();
    }

    public function obtenerConfiguracion(int $empresaId): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM empresas WHERE id = :id AND fecha_eliminacion IS NULL');
        $stmt->execute(['id' => $empresaId]);
        return $stmt->fetch() ?: null;
    }

    public function actualizarConfiguracion(int $empresaId, array $data): void
    {
        $sql = 'UPDATE empresas
            SET
                razon_social = :razon_social,
                nombre_comercial = :nombre_comercial,
                identificador_fiscal = :identificador_fiscal,
                correo = :correo,
                telefono = :telefono,
                direccion = :direccion,
                ciudad = :ciudad,
                pais = :pais,
                logo = :logo,
                imap_host = :imap_host,
                imap_port = :imap_port,
                imap_encryption = :imap_encryption,
                imap_usuario = :imap_usuario,
                imap_password = :imap_password,
                imap_remitente_correo = :imap_remitente_correo,
                imap_remitente_nombre = :imap_remitente_nombre,
                fecha_actualizacion = NOW()
            WHERE id = :empresa_id AND fecha_eliminacion IS NULL';

        $this->db->prepare($sql)->execute([
            'empresa_id' => $empresaId,
            'razon_social' => $data['razon_social'],
            'nombre_comercial' => $data['nombre_comercial'],
            'identificador_fiscal' => $data['identificador_fiscal'],
            'correo' => $data['correo'],
            'telefono' => $data['telefono'],
            'direccion' => $data['direccion'],
            'ciudad' => $data['ciudad'],
            'pais' => $data['pais'],
            'logo' => $data['logo'],
            'imap_host' => $data['imap_host'],
            'imap_port' => $data['imap_port'],
            'imap_encryption' => $data['imap_encryption'],
            'imap_usuario' => $data['imap_usuario'],
            'imap_password' => $data['imap_password'],
            'imap_remitente_correo' => $data['imap_remitente_correo'],
            'imap_remitente_nombre' => $data['imap_remitente_nombre'],
        ]);
    }
}
