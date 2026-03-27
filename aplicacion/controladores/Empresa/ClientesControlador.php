<?php

namespace Aplicacion\Controladores\Empresa;

use Aplicacion\Nucleo\Controlador;
use Aplicacion\Modelos\Cliente;
use Aplicacion\Servicios\ServicioPlan;

class ClientesControlador extends Controlador
{
    public function index(): void
    {
        $buscar = trim($_GET['q'] ?? '');
        $clientes = (new Cliente())->listar(empresa_actual_id(), $buscar);
        $this->vista('empresa/clientes/index', compact('clientes', 'buscar'), 'empresa');
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
            'correo' => trim($_POST['correo'] ?? ''),
            'telefono' => trim($_POST['telefono'] ?? ''),
            'direccion' => trim($_POST['direccion'] ?? ''),
            'notas' => trim($_POST['notas'] ?? ''),
            'estado' => 'activo',
        ]);

        flash('success', 'Cliente creado correctamente.');
        $this->redirigir('/app/clientes');
    }
}
