<?php

namespace Aplicacion\Controladores\Empresa;

use Aplicacion\Nucleo\Controlador;
use Aplicacion\Modelos\Producto;
use Aplicacion\Modelos\GestionComercial;
use Aplicacion\Servicios\ExcelExpoFormato;
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
        $categorias = (new GestionComercial())->listarTablaEmpresa('categorias_productos', empresa_actual_id(), '', 200);
        $this->vista('empresa/productos/formulario', ['producto' => null, 'categorias' => $categorias], 'empresa');
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
            'sku' => trim($_POST['sku'] ?? ''),
            'codigo_barras' => trim($_POST['codigo_barras'] ?? ''),
            'unidad' => trim($_POST['unidad'] ?? 'unidad'),
            'precio' => (float) ($_POST['precio'] ?? 0),
            'costo' => (float) ($_POST['costo'] ?? 0),
            'impuesto' => (float) ($_POST['impuesto'] ?? 0),
            'descuento_maximo' => (float) ($_POST['descuento_maximo'] ?? 0),
            'stock_minimo' => (float) ($_POST['stock_minimo'] ?? 0),
            'stock_aviso' => (float) ($_POST['stock_aviso'] ?? 0),
            'estado' => $_POST['estado'] ?? 'activo',
        ]);
        flash('success', 'Producto creado correctamente.');
        $this->redirigir($this->obtenerRutaRetorno('/app/productos'));
    }

    public function exportarExcel(): void
    {
        $buscar = trim($_GET['q'] ?? '');
        $productos = (new Producto())->listar(empresa_actual_id(), $buscar);

        $nombreArchivo = 'productos_' . date('Ymd_His') . '.xls';
        header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $nombreArchivo . '"');
        header('Pragma: no-cache');
        header('Expires: 0');

        echo "\xEF\xBB\xBF";
        echo '<html><head><meta charset="UTF-8"></head><body>';
        echo '<table border="1" cellspacing="0" cellpadding="4" style="' . ExcelExpoFormato::TABLA_ESTILO . '">';
        echo '<tr style="' . ExcelExpoFormato::ENCABEZADO_ESTILO . '">';
        echo '<th>N°</th>';
        echo '<th>ID</th>';
        echo '<th>Empresa ID</th>';
        echo '<th>Categoría ID</th>';
        echo '<th>Categoría</th>';
        echo '<th>Tipo</th>';
        echo '<th>Código</th>';
        echo '<th>SKU</th>';
        echo '<th>Código de barras</th>';
        echo '<th>Nombre</th>';
        echo '<th>Descripción</th>';
        echo '<th>Unidad</th>';
        echo '<th>Precio</th>';
        echo '<th>Costo</th>';
        echo '<th>Impuesto %</th>';
        echo '<th>Desc. máximo %</th>';
        echo '<th>Stock mínimo</th>';
        echo '<th>Stock aviso</th>';
        echo '<th>Estado</th>';
        echo '<th>Fecha creación</th>';
        echo '<th>Fecha actualización</th>';
        echo '</tr>';

        $indice = 1;
        foreach ($productos as $producto) {
            echo '<tr>';
            echo '<td>' . $indice . '</td>';
            echo '<td>' . $this->escapeExcelHtml($producto['id'] ?? '') . '</td>';
            echo '<td>' . $this->escapeExcelHtml($producto['empresa_id'] ?? '') . '</td>';
            echo '<td>' . $this->escapeExcelHtml($producto['categoria_id'] ?? '') . '</td>';
            echo '<td>' . $this->escapeExcelHtml($producto['categoria'] ?? '') . '</td>';
            echo '<td>' . $this->escapeExcelHtml(ucfirst((string) ($producto['tipo'] ?? 'producto'))) . '</td>';
            echo '<td style="' . ExcelExpoFormato::CELDA_TEXTO_EXCEL . '">' . $this->escapeExcelHtml($producto['codigo'] ?? '') . '</td>';
            echo '<td style="' . ExcelExpoFormato::CELDA_TEXTO_EXCEL . '">' . $this->escapeExcelHtml($producto['sku'] ?? '') . '</td>';
            echo '<td style="' . ExcelExpoFormato::CELDA_TEXTO_EXCEL . '">' . $this->escapeExcelHtml($producto['codigo_barras'] ?? '') . '</td>';
            echo '<td>' . $this->escapeExcelHtml($producto['nombre'] ?? '') . '</td>';
            echo '<td>' . $this->escapeExcelHtml($producto['descripcion'] ?? '') . '</td>';
            echo '<td>' . $this->escapeExcelHtml($producto['unidad'] ?? '') . '</td>';
            echo '<td>' . $this->escapeExcelHtml(number_format((float) ($producto['precio'] ?? 0), 2)) . '</td>';
            echo '<td>' . $this->escapeExcelHtml(number_format((float) ($producto['costo'] ?? 0), 2)) . '</td>';
            echo '<td>' . $this->escapeExcelHtml(number_format((float) ($producto['impuesto'] ?? 0), 2)) . '</td>';
            echo '<td>' . $this->escapeExcelHtml(number_format((float) ($producto['descuento_maximo'] ?? 0), 2)) . '</td>';
            echo '<td>' . $this->escapeExcelHtml(number_format((float) ($producto['stock_minimo'] ?? 0), 2)) . '</td>';
            echo '<td>' . $this->escapeExcelHtml(number_format((float) ($producto['stock_aviso'] ?? 0), 2)) . '</td>';
            echo '<td>' . $this->escapeExcelHtml(ucfirst((string) ($producto['estado'] ?? 'activo'))) . '</td>';
            echo '<td>' . $this->escapeExcelHtml($producto['fecha_creacion'] ?? '') . '</td>';
            echo '<td>' . $this->escapeExcelHtml($producto['fecha_actualizacion'] ?? '') . '</td>';
            echo '</tr>';
            $indice++;
        }

        echo '</table></body></html>';

        exit;
    }

    private function escapeExcelHtml(mixed $valor): string
    {
        $texto = trim(str_replace(["\r\n", "\r", "\n", "\t"], ' ', (string) $valor));

        if ($texto !== '' && preg_match('/^[=+\-@]/', $texto) === 1) {
            $texto = "'" . $texto;
        }

        return htmlspecialchars($texto, ENT_QUOTES, 'UTF-8');
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
            'sku' => trim($_POST['sku'] ?? ''),
            'codigo_barras' => trim($_POST['codigo_barras'] ?? ''),
            'unidad' => trim($_POST['unidad'] ?? 'unidad'),
            'precio' => (float) ($_POST['precio'] ?? 0),
            'costo' => (float) ($_POST['costo'] ?? 0),
            'impuesto' => (float) ($_POST['impuesto'] ?? 0),
            'descuento_maximo' => (float) ($_POST['descuento_maximo'] ?? 0),
            'stock_minimo' => (float) ($_POST['stock_minimo'] ?? 0),
            'stock_aviso' => (float) ($_POST['stock_aviso'] ?? 0),
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
