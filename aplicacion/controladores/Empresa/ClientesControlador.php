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
        $this->redirigir('/app/clientes');
    }
}
