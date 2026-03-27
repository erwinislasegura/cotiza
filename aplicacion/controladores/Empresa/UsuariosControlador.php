<?php

namespace Aplicacion\Controladores\Empresa;

use Aplicacion\Nucleo\Controlador;
use Aplicacion\Modelos\Usuario;

class UsuariosControlador extends Controlador
{
    public function index(): void
    {
        $modelo = new Usuario();
        $usuarios = $modelo->listarPorEmpresa(empresa_actual_id());
        $roles = $modelo->listarRolesEmpresa();
        $this->vista('empresa/usuarios/index', compact('usuarios', 'roles'), 'empresa');
    }

    public function guardar(): void
    {
        validar_csrf();
        $modelo = new Usuario();
        $modelo->crear([
            'empresa_id' => empresa_actual_id(),
            'rol_id' => (int) ($_POST['rol_id'] ?? 0),
            'nombre' => trim($_POST['nombre'] ?? ''),
            'correo' => trim($_POST['correo'] ?? ''),
            'password' => password_hash($_POST['password'] ?? '123456', PASSWORD_BCRYPT),
            'estado' => $_POST['estado'] ?? 'activo',
        ]);
        flash('success', 'Usuario creado correctamente.');
        $this->redirigir('/app/usuarios');
    }

    public function ver(int $id): void
    {
        $usuario = (new Usuario())->obtenerPorIdEmpresa(empresa_actual_id(), $id);
        if (!$usuario) {
            flash('danger', 'Usuario no encontrado.');
            $this->redirigir('/app/usuarios');
        }
        $this->vista('empresa/usuarios/ver', compact('usuario'), 'empresa');
    }

    public function editar(int $id): void
    {
        $modelo = new Usuario();
        $usuario = $modelo->obtenerPorIdEmpresa(empresa_actual_id(), $id);
        if (!$usuario) {
            flash('danger', 'Usuario no encontrado.');
            $this->redirigir('/app/usuarios');
        }
        $roles = $modelo->listarRolesEmpresa();
        $this->vista('empresa/usuarios/editar', compact('usuario', 'roles'), 'empresa');
    }

    public function actualizar(int $id): void
    {
        validar_csrf();
        (new Usuario())->actualizarEmpresa(empresa_actual_id(), $id, [
            'nombre' => trim($_POST['nombre'] ?? ''),
            'correo' => trim($_POST['correo'] ?? ''),
            'rol_id' => (int) ($_POST['rol_id'] ?? 0),
            'estado' => $_POST['estado'] ?? 'activo',
        ]);
        flash('success', 'Usuario actualizado correctamente.');
        $this->redirigir('/app/usuarios');
    }
}
