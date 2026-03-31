<?php

namespace Aplicacion\Servicios;

use Aplicacion\Modelos\Empresa;
use Aplicacion\Modelos\LogCorreo;

class ServicioCorreo
{
    public function enviar(string $destinatario, string $asunto, string $plantilla, array $datos = []): bool
    {
        $log = new LogCorreo();
        $log->registrar([
            'destinatario' => $destinatario,
            'asunto' => $asunto,
            'plantilla' => $plantilla,
            'payload' => json_encode($datos, JSON_UNESCAPED_UNICODE),
            'estado' => 'enviado',
        ]);
        return true;
    }

    public function enviarConEmpresa(int $empresaId, string $destinatario, string $asunto, string $plantilla, array $datos = []): bool
    {
        $empresa = (new Empresa())->buscar($empresaId) ?: [];
        $smtpEmpresa = [
            'host' => (string) ($empresa['imap_host'] ?? ''),
            'puerto' => (string) ($empresa['imap_port'] ?? ''),
            'usuario' => (string) ($empresa['imap_usuario'] ?? ''),
            'encryption' => (string) ($empresa['imap_encryption'] ?? ''),
            'remitente_correo' => trim((string) ($empresa['imap_remitente_correo'] ?? '')) !== ''
                ? trim((string) ($empresa['imap_remitente_correo'] ?? ''))
                : trim((string) ($empresa['correo'] ?? '')),
            'remitente_nombre' => trim((string) ($empresa['imap_remitente_nombre'] ?? '')) !== ''
                ? trim((string) ($empresa['imap_remitente_nombre'] ?? ''))
                : trim((string) ($empresa['nombre_comercial'] ?? '')),
        ];

        $datos['smtp_empresa'] = $smtpEmpresa;
        $datos['empresa_id'] = $empresaId;

        return $this->enviar($destinatario, $asunto, $plantilla, $datos);
    }
}
