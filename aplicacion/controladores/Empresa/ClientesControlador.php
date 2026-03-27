<?php

namespace Aplicacion\Controladores\Empresa;

use Aplicacion\Nucleo\Controlador;
use Aplicacion\Modelos\Cliente;
use Aplicacion\Modelos\GestionComercial;
use Aplicacion\Servicios\ServicioPlan;

class ClientesControlador extends Controlador
{
    public function index(): void
    {
        $buscar = trim($_GET['q'] ?? '');
        $clientes = (new Cliente())->listar(empresa_actual_id(), $buscar);
        $vendedores = (new GestionComercial())->listarTablaEmpresa('vendedores', empresa_actual_id(), '', 200);
        $this->vista('empresa/clientes/index', compact('clientes', 'buscar', 'vendedores'), 'empresa');
    }

    public function crear(): void
    {
        $this->vista('empresa/clientes/formulario', ['cliente' => null], 'empresa');
    }

    public function guardar(): void
    {
        validar_csrf();
        $empresaId = empresa_actual_id();
        $modelo = new Cliente();
        (new ServicioPlan())->validarLimite($empresaId, 'maximo_clientes', $modelo->contar($empresaId), 'Has alcanzado el máximo de clientes permitido por tu plan.');

        $modelo->crear([
            'empresa_id' => $empresaId,
            'nombre' => trim($_POST['nombre'] ?? ''),
            'razon_social' => trim($_POST['razon_social'] ?? $_POST['nombre'] ?? ''),
            'nombre_comercial' => trim($_POST['nombre_comercial'] ?? $_POST['nombre'] ?? ''),
            'identificador_fiscal' => trim($_POST['identificador_fiscal'] ?? ''),
            'giro' => trim($_POST['giro'] ?? ''),
            'correo' => trim($_POST['correo'] ?? ''),
            'telefono' => trim($_POST['telefono'] ?? ''),
            'direccion' => trim($_POST['direccion'] ?? ''),
            'ciudad' => trim($_POST['ciudad'] ?? ''),
            'vendedor_id' => (int) ($_POST['vendedor_id'] ?? 0) ?: null,
            'notas' => trim($_POST['notas'] ?? ''),
            'estado' => $_POST['estado'] ?? 'activo',
        ]);

        flash('success', 'Cliente creado correctamente.');
        $this->redirigir($this->obtenerRutaRetorno('/app/clientes'));
    }

    private function obtenerRutaRetorno(string $rutaPredeterminada): string
    {
        $ruta = trim($_POST['redirect_to'] ?? '');
        if ($ruta !== '' && strpos($ruta, '/app/') === 0) {
            return $ruta;
        }

        return $rutaPredeterminada;
    }

    public function ver(int $id): void
    {
        $cliente = (new Cliente())->obtenerPorId(empresa_actual_id(), $id);
        if (!$cliente) {
            flash('danger', 'Cliente no encontrado.');
            $this->redirigir('/app/clientes');
        }
        $this->vista('empresa/clientes/ver', compact('cliente'), 'empresa');
    }

    public function editar(int $id): void
    {
        $empresaId = empresa_actual_id();
        $cliente = (new Cliente())->obtenerPorId($empresaId, $id);
        if (!$cliente) {
            flash('danger', 'Cliente no encontrado.');
            $this->redirigir('/app/clientes');
        }
        $vendedores = (new GestionComercial())->listarTablaEmpresa('vendedores', $empresaId, '', 200);
        $this->vista('empresa/clientes/editar', compact('cliente', 'vendedores'), 'empresa');
    }

    public function actualizar(int $id): void
    {
        validar_csrf();
        (new Cliente())->actualizar(empresa_actual_id(), $id, [
            'razon_social' => trim($_POST['razon_social'] ?? ''),
            'nombre_comercial' => trim($_POST['nombre_comercial'] ?? ''),
            'identificador_fiscal' => trim($_POST['identificador_fiscal'] ?? ''),
            'giro' => trim($_POST['giro'] ?? ''),
            'correo' => trim($_POST['correo'] ?? ''),
            'telefono' => trim($_POST['telefono'] ?? ''),
            'direccion' => trim($_POST['direccion'] ?? ''),
            'ciudad' => trim($_POST['ciudad'] ?? ''),
            'vendedor_id' => (int) ($_POST['vendedor_id'] ?? 0) ?: null,
            'notas' => trim($_POST['notas'] ?? ''),
            'estado' => $_POST['estado'] ?? 'activo',
        ]);
        flash('success', 'Cliente actualizado correctamente.');
        $this->redirigir('/app/clientes');
    }

    public function eliminar(int $id): void
    {
        validar_csrf();
        (new Cliente())->eliminar(empresa_actual_id(), $id);
        flash('success', 'Cliente eliminado correctamente.');
        $this->redirigir('/app/clientes');
    }
}
