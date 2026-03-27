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
        $this->redirigir($this->obtenerRutaRetorno('/app/productos'));
    }


    private function obtenerRutaRetorno(string $rutaPredeterminada): string
    {
        $ruta = trim($_POST['redirect_to'] ?? '');
        if ($ruta !== '' && str_starts_with($ruta, '/app/')) {
            return $ruta;
        }

        return $rutaPredeterminada;
    }

    public function ver(int $id): void
    {
        $producto = (new Producto())->obtenerPorId(empresa_actual_id(), $id);
        if (!$producto) {
            flash('danger', 'Producto no encontrado.');
            $this->redirigir('/app/productos');
        }
        $this->vista('empresa/productos/ver', compact('producto'), 'empresa');
    }

    public function editar(int $id): void
    {
        $empresaId = empresa_actual_id();
        $producto = (new Producto())->obtenerPorId($empresaId, $id);
        if (!$producto) {
            flash('danger', 'Producto no encontrado.');
            $this->redirigir('/app/productos');
        }
        $categorias = (new GestionComercial())->listarTablaEmpresa('categorias_productos', $empresaId, '', 200);
        $this->vista('empresa/productos/editar', compact('producto', 'categorias'), 'empresa');
    }

    public function actualizar(int $id): void
    {
        validar_csrf();
        (new Producto())->actualizar(empresa_actual_id(), $id, [
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
        flash('success', 'Producto actualizado correctamente.');
        $this->redirigir('/app/productos');
    }

    public function eliminar(int $id): void
    {
        validar_csrf();
        (new Producto())->eliminar(empresa_actual_id(), $id);
        flash('success', 'Producto eliminado correctamente.');
        $this->redirigir('/app/productos');
    }
}
