<?php

namespace Aplicacion\Controladores\Empresa;

use Aplicacion\Nucleo\Controlador;
use Aplicacion\Modelos\Producto;
use Aplicacion\Modelos\GestionComercial;
use Aplicacion\Servicios\ServicioPlan;

class ProductosControlador extends Controlador
{
    public function index(): void
    {
        $buscar = trim($_GET['q'] ?? '');
        $productos = (new Producto())->listar(empresa_actual_id(), $buscar);
        $categorias = (new GestionComercial())->listarTablaEmpresa('categorias_productos', empresa_actual_id(), '', 200);
        $this->vista('empresa/productos/index', compact('productos', 'buscar', 'categorias'), 'empresa');
    }

    public function crear(): void
    {
        $this->vista('empresa/productos/formulario', ['producto' => null], 'empresa');
    }

    public function guardar(): void
    {
        validar_csrf();
        $empresaId = empresa_actual_id();
        $modelo = new Producto();
        (new ServicioPlan())->validarLimite($empresaId, 'maximo_productos', $modelo->contar($empresaId), 'Has alcanzado el máximo de productos permitido por tu plan.');

        $modelo->crear([
            'empresa_id' => $empresaId,
            'categoria_id' => (int) ($_POST['categoria_id'] ?? 0) ?: null,
            'tipo' => $_POST['tipo'] ?? 'producto',
            'codigo' => trim($_POST['codigo'] ?? ''),
            'nombre' => trim($_POST['nombre'] ?? ''),
            'descripcion' => trim($_POST['descripcion'] ?? ''),
            'unidad' => trim($_POST['unidad'] ?? 'unidad'),
            'precio' => (float) ($_POST['precio'] ?? 0),
            'costo' => (float) ($_POST['costo'] ?? 0),
            'impuesto' => (float) ($_POST['impuesto'] ?? 0),
            'descuento_maximo' => (float) ($_POST['descuento_maximo'] ?? 0),
            'estado' => $_POST['estado'] ?? 'activo',
        ]);
        flash('success', 'Producto creado correctamente.');
        $this->redirigir('/app/productos');
    }
}
