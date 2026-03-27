<?php

namespace Aplicacion\Controladores\Empresa;

use Aplicacion\Nucleo\Controlador;
use Aplicacion\Modelos\Cotizacion;
use Aplicacion\Modelos\Cliente;
use Aplicacion\Modelos\Producto;
use Aplicacion\Servicios\ServicioPlan;

class CotizacionesControlador extends Controlador
{
    public function index(): void
    {
        $buscar = trim($_GET['q'] ?? '');
        $cotizaciones = (new Cotizacion())->listar(empresa_actual_id());
        if ($buscar !== '') {
            $cotizaciones = array_values(array_filter($cotizaciones, static function (array $cotizacion) use ($buscar): bool {
                return str_contains(strtolower($cotizacion['numero']), strtolower($buscar))
                    || str_contains(strtolower($cotizacion['cliente']), strtolower($buscar));
            }));
        }
        $this->vista('empresa/cotizaciones/index', compact('cotizaciones', 'buscar'), 'empresa');
    }

    public function crear(): void
    {
        $empresaId = empresa_actual_id();
        $clientes = (new Cliente())->listar($empresaId);
        $productos = (new Producto())->listar($empresaId);
        $siguienteNumero = (new Cotizacion())->siguienteNumero($empresaId);
        $this->vista('empresa/cotizaciones/formulario', compact('clientes', 'productos', 'siguienteNumero'), 'empresa');
    }

    public function guardar(): void
    {
        validar_csrf();
        $empresaId = empresa_actual_id();
        $usuario = usuario_actual();
        $modelo = new Cotizacion();

        (new ServicioPlan())->validarLimite($empresaId, 'maximo_cotizaciones_mes', $modelo->contarMes($empresaId), 'Llegaste al límite mensual de cotizaciones de tu plan.');

        $cantidad = (float) $_POST['cantidad'];
        $precio = (float) $_POST['precio_unitario'];
        $impuestoPorcentaje = (float) $_POST['impuesto_item'];
        $subtotal = $cantidad * $precio;
        $descuento = (float) ($_POST['descuento'] ?? 0);
        $subtotal = max(0, $subtotal - $descuento);
        $impuesto = $subtotal * ($impuestoPorcentaje / 100);
        $total = $subtotal + $impuesto;

        $numero = $modelo->siguienteNumero($empresaId);
        $consecutivo = (int) preg_replace('/^.*-/', '', $numero);

        $modelo->crearConItems([
            'empresa_id' => $empresaId,
            'cliente_id' => (int) $_POST['cliente_id'],
            'usuario_id' => (int) $usuario['id'],
            'numero' => $numero,
            'consecutivo' => $consecutivo,
            'estado' => $_POST['estado'] ?? 'borrador',
            'subtotal' => $subtotal,
            'descuento' => $descuento,
            'impuesto' => $impuesto,
            'total' => $total,
            'observaciones' => trim($_POST['observaciones'] ?? ''),
            'terminos_condiciones' => trim($_POST['terminos_condiciones'] ?? ''),
            'fecha_emision' => $_POST['fecha_emision'] ?? date('Y-m-d'),
            'fecha_vencimiento' => $_POST['fecha_vencimiento'] ?? date('Y-m-d', strtotime('+15 days')),
        ], [[
            'producto_id' => (int) $_POST['producto_id'],
            'descripcion' => trim($_POST['descripcion_item'] ?? ''),
            'cantidad' => $cantidad,
            'precio_unitario' => $precio,
            'porcentaje_impuesto' => $impuestoPorcentaje,
            'subtotal' => $subtotal,
            'total' => $total,
        ]]);

        flash('success', 'Cotización creada y numerada correctamente.');
        $this->redirigir('/app/cotizaciones');
    }
}
