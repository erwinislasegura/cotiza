<?php

namespace Aplicacion\Controladores\Admin;

use Aplicacion\Nucleo\Controlador;
use Aplicacion\Modelos\Configuracion;
use Throwable;

class ConfiguracionGeneralControlador extends Controlador
{
    private const CLAVES_CONFIG = [
        'nombre_plataforma',
        'correo_soporte',
        'moneda_defecto',
        'zona_horaria',
        'estado_plataforma',
    ];

    public function index(): void
    {
        $config = $this->obtenerConfiguracionVista();
        $this->vista('admin/configuracion/index', compact('config'), 'admin');
    }

    public function guardar(): void
    {
        validar_csrf();

        $moneda = mb_strtoupper(trim((string) ($_POST['moneda_defecto'] ?? 'CLP')));
        if (!in_array($moneda, ['CLP', 'USD', 'EUR'], true)) {
            $moneda = 'CLP';
        }

        $estado = trim((string) ($_POST['estado_plataforma'] ?? 'activo'));
        if (!in_array($estado, ['activo', 'mantenimiento'], true)) {
            $estado = 'activo';
        }

        $correoSoporte = trim((string) ($_POST['correo_soporte'] ?? ''));
        if ($correoSoporte !== '' && !filter_var($correoSoporte, FILTER_VALIDATE_EMAIL)) {
            flash('danger', 'El correo de soporte no es válido.');
            $this->redirigir('/admin/configuracion');
        }

        $data = [
            'nombre_plataforma' => trim((string) ($_POST['nombre_plataforma'] ?? 'CotizaPro')),
            'correo_soporte' => $correoSoporte,
            'moneda_defecto' => $moneda,
            'zona_horaria' => trim((string) ($_POST['zona_horaria'] ?? 'America/Santiago')),
            'estado_plataforma' => $estado,
        ];

        try {
            (new Configuracion())->guardarMultiples($data);
            flash('success', 'Configuración general guardada correctamente.');
        } catch (Throwable $e) {
            flash('danger', 'No se pudo guardar la configuración general.');
        }

        $this->redirigir('/admin/configuracion');
    }

    private function obtenerConfiguracionVista(): array
    {
        $configDb = (new Configuracion())->obtenerMapa(self::CLAVES_CONFIG);
        return [
            'nombre_plataforma' => (string) ($configDb['nombre_plataforma'] ?? 'CotizaPro'),
            'correo_soporte' => (string) ($configDb['correo_soporte'] ?? ''),
            'moneda_defecto' => (string) ($configDb['moneda_defecto'] ?? 'CLP'),
            'zona_horaria' => (string) ($configDb['zona_horaria'] ?? 'America/Santiago'),
            'estado_plataforma' => (string) ($configDb['estado_plataforma'] ?? 'activo'),
        ];
    }
}
