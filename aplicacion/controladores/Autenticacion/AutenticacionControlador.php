<?php

namespace Aplicacion\Controladores\Autenticacion;

use Aplicacion\Nucleo\Controlador;
use Aplicacion\Modelos\Usuario;
use Aplicacion\Modelos\Empresa;
use Aplicacion\Modelos\Plan;
use Aplicacion\Modelos\Suscripcion;

class AutenticacionControlador extends Controlador
{
    public function mostrarLogin(): void
    {
        $this->vista('autenticacion/login', [], 'publico');
    }

    public function iniciarSesion(): void
    {
        validar_csrf();
        $correo = filter_var($_POST['correo'] ?? '', FILTER_VALIDATE_EMAIL);
        $password = $_POST['password'] ?? '';

        if (!$correo || $password === '') {
            flash('danger', 'Completa correo y contraseña correctamente.');
            $this->redirigir('/iniciar-sesion');
        }

        $usuario = (new Usuario())->buscarPorCorreo($correo);
        if (!$usuario || !password_verify($password, $usuario['password'])) {
            flash('danger', 'Credenciales inválidas.');
            $this->redirigir('/iniciar-sesion');
        }

        session_regenerate_id(true);
        $_SESSION['usuario'] = [
            'id' => (int) $usuario['id'],
            'empresa_id' => $usuario['empresa_id'] ? (int) $usuario['empresa_id'] : null,
            'nombre' => $usuario['nombre'],
            'correo' => $usuario['correo'],
            'rol_codigo' => $usuario['rol_codigo'],
        ];

        if ($usuario['rol_codigo'] === 'superadministrador') {
            $this->redirigir('/admin/panel');
        }

        $this->redirigir('/app/panel');
    }

    public function cerrarSesion(): void
    {
        $_SESSION = [];
        session_destroy();
        $this->redirigir('/iniciar-sesion');
    }

    public function mostrarRegistro(): void
    {
        $planes = (new Plan())->listar(true);
        $this->vista('autenticacion/registro', ['planes' => $planes], 'publico');
    }

    public function registrarEmpresa(): void
    {
        validar_csrf();
        $planId = (int) ($_POST['plan_id'] ?? 0);
        $empresaModel = new Empresa();
        $usuarioModel = new Usuario();
        $suscripcionModel = new Suscripcion();

        $empresaId = $empresaModel->crear([
            'razon_social' => trim($_POST['razon_social'] ?? ''),
            'nombre_comercial' => trim($_POST['nombre_comercial'] ?? ''),
            'identificador_fiscal' => trim($_POST['identificador_fiscal'] ?? ''),
            'correo' => trim($_POST['correo_empresa'] ?? ''),
            'telefono' => trim($_POST['telefono'] ?? ''),
            'direccion' => trim($_POST['direccion'] ?? ''),
            'ciudad' => trim($_POST['ciudad'] ?? ''),
            'pais' => trim($_POST['pais'] ?? ''),
            'estado' => 'activa',
            'fecha_activacion' => date('Y-m-d'),
            'plan_id' => $planId,
        ]);

        $usuarioModel->crear([
            'empresa_id' => $empresaId,
            'rol_id' => 2,
            'nombre' => trim($_POST['nombre_admin'] ?? ''),
            'correo' => trim($_POST['correo_admin'] ?? ''),
            'password' => password_hash($_POST['password'] ?? '', PASSWORD_DEFAULT),
            'estado' => 'activo',
        ]);

        $suscripcionModel->crear([
            'empresa_id' => $empresaId,
            'plan_id' => $planId,
            'estado' => 'activa',
            'fecha_inicio' => date('Y-m-d'),
            'fecha_vencimiento' => date('Y-m-d', strtotime('+30 days')),
            'observaciones' => 'Alta inicial desde registro',
            'renovacion_automatica' => 0,
        ]);

        flash('success', 'Cuenta creada con éxito. Ahora puedes iniciar sesión.');
        $this->redirigir('/iniciar-sesion');
    }

    public function recuperarContrasena(): void
    {
        $this->vista('autenticacion/recuperar', [], 'publico');
    }

    public function restablecerContrasena(): void
    {
        $this->vista('autenticacion/restablecer', [], 'publico');
    }
}
