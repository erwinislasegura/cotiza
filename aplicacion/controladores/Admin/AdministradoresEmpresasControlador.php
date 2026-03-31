<?php

namespace Aplicacion\Controladores\Admin;

use Aplicacion\Modelos\Empresa;
use Aplicacion\Modelos\LogAdministracion;
use Aplicacion\Modelos\Usuario;
use Aplicacion\Nucleo\Controlador;

class AdministradoresEmpresasControlador extends Controlador
{
    public function index(): void
    {
        $filtros = [
            'busqueda' => trim($_GET['q'] ?? ''),
            'empresa_id' => $_GET['empresa_id'] ?? '',
        ];
        $administradores = (new Usuario())->listarAdministradoresEmpresa($filtros);
        $empresas = (new Empresa())->listar();
        $this->vista('admin/administradores/index', compact('administradores', 'filtros', 'empresas'), 'admin');
    }

    public function actualizar(int $id): void
    {
        validar_csrf();
        $correo = trim($_POST['correo'] ?? '');
        $nombre = trim($_POST['nombre'] ?? '');
        (new Usuario())->actualizarCredencialesAdmin($id, $correo, $nombre);
        (new LogAdministracion())->registrar('admin_empresas', 'actualizar_credenciales', 'Actualización de datos de acceso', null, $id);
        flash('success', 'Datos del administrador actualizados.');
        $this->redirigir('/admin/administradores-empresa');
    }

    public function cambiarEstado(int $id): void
    {
        validar_csrf();
        $estado = $_POST['estado'] ?? 'activo';
        (new Usuario())->actualizarEstado($id, $estado);
        (new LogAdministracion())->registrar('admin_empresas', 'cambiar_estado', 'Estado a ' . $estado, null, $id);
        flash('success', 'Estado actualizado.');
        $this->redirigir('/admin/administradores-empresa');
    }

    public function resetearPassword(int $id): void
    {
        validar_csrf();
        $passwordManual = trim($_POST['password_nueva'] ?? '');
        $generarTemporal = isset($_POST['generar_temporal']);

        $passwordPlano = $passwordManual;
        if ($generarTemporal || $passwordPlano === '') {
            $passwordPlano = 'Tmp' . bin2hex(random_bytes(4)) . 'A!';
        }

        (new Usuario())->resetearPasswordAdminEmpresa($id, password_hash($passwordPlano, PASSWORD_DEFAULT));
        (new LogAdministracion())->registrar('admin_empresas', 'reset_password', 'Reseteo de contraseña administrativa', null, $id);
        flash('success', 'Contraseña actualizada correctamente. Clave temporal: ' . $passwordPlano);
        $this->redirigir('/admin/administradores-empresa');
    }
}
