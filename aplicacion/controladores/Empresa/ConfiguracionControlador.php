<?php

namespace Aplicacion\Controladores\Empresa;

use Aplicacion\Nucleo\Controlador;
use Aplicacion\Modelos\Empresa;

class ConfiguracionControlador extends Controlador
{
    public function index(): void
    {
        $empresaId = empresa_actual_id();
        $empresa = (new Empresa())->obtenerConfiguracion($empresaId);

        if (!$empresa) {
            flash('danger', 'No se encontró la configuración de la empresa.');
            $this->redirigir('/app/panel');
        }

        $this->vista('empresa/configuracion/index', compact('empresa'), 'empresa');
    }

    public function guardar(): void
    {
        validar_csrf();
        $empresaId = empresa_actual_id();
        $modelo = new Empresa();
        $empresa = $modelo->obtenerConfiguracion($empresaId);

        if (!$empresa) {
            flash('danger', 'No se encontró la empresa para actualizar.');
            $this->redirigir('/app/configuracion');
        }

        $logoActual = (string) ($empresa['logo'] ?? '');
        $logoNuevo = $this->guardarLogoEmpresa($logoActual);

        $imapPasswordActual = (string) ($empresa['imap_password'] ?? '');
        $imapPassword = trim((string) ($_POST['imap_password'] ?? ''));
        if ($imapPassword === '') {
            $imapPassword = $imapPasswordActual;
        }

        $modelo->actualizarConfiguracion($empresaId, [
            'razon_social' => trim((string) ($_POST['razon_social'] ?? '')),
            'nombre_comercial' => trim((string) ($_POST['nombre_comercial'] ?? '')),
            'identificador_fiscal' => trim((string) ($_POST['identificador_fiscal'] ?? '')),
            'correo' => trim((string) ($_POST['correo'] ?? '')),
            'telefono' => trim((string) ($_POST['telefono'] ?? '')),
            'direccion' => trim((string) ($_POST['direccion'] ?? '')),
            'ciudad' => trim((string) ($_POST['ciudad'] ?? '')),
            'pais' => trim((string) ($_POST['pais'] ?? '')),
            'logo' => $logoNuevo,
            'imap_host' => trim((string) ($_POST['imap_host'] ?? '')),
            'imap_port' => (int) ($_POST['imap_port'] ?? 0) ?: null,
            'imap_encryption' => trim((string) ($_POST['imap_encryption'] ?? 'tls')),
            'imap_usuario' => trim((string) ($_POST['imap_usuario'] ?? '')),
            'imap_password' => $imapPassword,
            'imap_remitente_correo' => trim((string) ($_POST['imap_remitente_correo'] ?? '')),
            'imap_remitente_nombre' => trim((string) ($_POST['imap_remitente_nombre'] ?? '')),
        ]);

        flash('success', 'Configuración actualizada correctamente.');
        $this->redirigir('/app/configuracion');
    }

    private function guardarLogoEmpresa(string $logoActual): string
    {
        if (!isset($_FILES['logo']) || (int) ($_FILES['logo']['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            return $logoActual;
        }

        if ((int) ($_FILES['logo']['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
            flash('danger', 'No se pudo subir el logo. Intenta nuevamente.');
            $this->redirigir('/app/configuracion');
        }

        $nombre = (string) ($_FILES['logo']['name'] ?? '');
        $tmp = (string) ($_FILES['logo']['tmp_name'] ?? '');
        $tamano = (int) ($_FILES['logo']['size'] ?? 0);

        if ($tamano > 2 * 1024 * 1024) {
            flash('danger', 'El logo supera el tamaño permitido (2MB).');
            $this->redirigir('/app/configuracion');
        }

        $extension = mb_strtolower(pathinfo($nombre, PATHINFO_EXTENSION));
        $permitidas = ['png', 'jpg', 'jpeg', 'webp', 'svg'];
        if (!in_array($extension, $permitidas, true)) {
            flash('danger', 'Formato de logo no permitido. Usa PNG, JPG, WEBP o SVG.');
            $this->redirigir('/app/configuracion');
        }

        $directorio = dirname(__DIR__, 3) . '/public/uploads/logos';
        if (!is_dir($directorio)) {
            mkdir($directorio, 0775, true);
        }

        $nombreFinal = 'logo_empresa_' . empresa_actual_id() . '_' . date('YmdHis') . '.' . $extension;
        $rutaFinal = $directorio . '/' . $nombreFinal;
        if (!move_uploaded_file($tmp, $rutaFinal)) {
            flash('danger', 'No se pudo guardar el archivo del logo.');
            $this->redirigir('/app/configuracion');
        }

        return '/uploads/logos/' . $nombreFinal;
    }
}
