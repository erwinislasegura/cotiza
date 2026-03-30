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
