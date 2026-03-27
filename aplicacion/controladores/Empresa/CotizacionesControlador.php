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

        $productoIds = $_POST['producto_id'] ?? [];
        $descripciones = $_POST['descripcion_item'] ?? [];
        $cantidades = $_POST['cantidad'] ?? [];
        $precios = $_POST['precio_unitario'] ?? [];
        $impuestos = $_POST['impuesto_item'] ?? [];
        $descuentoTiposLinea = $_POST['descuento_tipo_item'] ?? [];
        $descuentoValoresLinea = $_POST['descuento_item'] ?? [];

        $items = [];
        $subtotal = 0.0;
        $impuesto = 0.0;
        $conteoLineas = max(
            count((array) $productoIds),
            count((array) $descripciones),
            count((array) $cantidades),
            count((array) $precios)
        );

        for ($i = 0; $i < $conteoLineas; $i++) {
            $productoId = (int) ($productoIds[$i] ?? 0) ?: null;
            $descripcion = trim((string) ($descripciones[$i] ?? ''));
            $cantidad = (float) ($cantidades[$i] ?? 0);
            $precio = (float) ($precios[$i] ?? 0);
            $impuestoPorcentaje = max(0, (float) ($impuestos[$i] ?? 0));
            $descuentoTipo = ($descuentoTiposLinea[$i] ?? 'valor') === 'porcentaje' ? 'porcentaje' : 'valor';
            $descuentoValor = max(0, (float) ($descuentoValoresLinea[$i] ?? 0));

            if ($cantidad <= 0 || $precio < 0) {
                continue;
            }

            $baseLinea = $cantidad * $precio;
            $descuentoMonto = $descuentoTipo === 'porcentaje'
                ? $baseLinea * (min($descuentoValor, 100) / 100)
                : min($descuentoValor, $baseLinea);

            $subtotalLinea = max(0, $baseLinea - $descuentoMonto);
            $impuestoLinea = $subtotalLinea * ($impuestoPorcentaje / 100);
            $totalLinea = $subtotalLinea + $impuestoLinea;

            $subtotal += $subtotalLinea;
            $impuesto += $impuestoLinea;
            $items[] = [
                'producto_id' => $productoId,
                'descripcion' => $descripcion !== '' ? $descripcion : 'Ítem ' . ($i + 1),
                'cantidad' => $cantidad,
                'precio_unitario' => $precio,
                'descuento_tipo' => $descuentoTipo,
                'descuento_valor' => $descuentoValor,
                'descuento_monto' => $descuentoMonto,
                'porcentaje_impuesto' => $impuestoPorcentaje,
                'subtotal' => $subtotalLinea,
                'total' => $totalLinea,
            ];
        }

        if ($items === []) {
            flash('danger', 'Debes agregar al menos un servicio o producto con cantidad válida.');
            $this->redirigir('/app/cotizaciones/crear');
        }

        $descuentoTipo = ($_POST['descuento_tipo_total'] ?? 'valor') === 'porcentaje' ? 'porcentaje' : 'valor';
        $descuentoValor = max(0, (float) ($_POST['descuento_total'] ?? 0));
        $descuento = $descuentoTipo === 'porcentaje'
            ? ($subtotal + $impuesto) * (min($descuentoValor, 100) / 100)
            : min($descuentoValor, $subtotal + $impuesto);
        $total = max(0, ($subtotal + $impuesto) - $descuento);

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
            'descuento_tipo' => $descuentoTipo,
            'descuento_valor' => $descuentoValor,
            'descuento' => $descuento,
            'impuesto' => $impuesto,
            'total' => $total,
            'observaciones' => trim($_POST['observaciones'] ?? ''),
            'terminos_condiciones' => trim($_POST['terminos_condiciones'] ?? ''),
            'fecha_emision' => $_POST['fecha_emision'] ?? date('Y-m-d'),
            'fecha_vencimiento' => $_POST['fecha_vencimiento'] ?? date('Y-m-d', strtotime('+15 days')),
        ], $items);

        flash('success', 'Cotización creada y numerada correctamente.');
        $this->redirigir('/app/cotizaciones');
    }

    public function ver(int $id): void
    {
        $cotizacion = (new Cotizacion())->obtenerPorId(empresa_actual_id(), $id);
        if (!$cotizacion) {
            flash('danger', 'Cotización no encontrada.');
            $this->redirigir('/app/cotizaciones');
        }
        $this->vista('empresa/cotizaciones/ver', compact('cotizacion'), 'empresa');
    }

    public function editar(int $id): void
    {
        $empresaId = empresa_actual_id();
        $cotizacion = (new Cotizacion())->obtenerPorId($empresaId, $id);
        if (!$cotizacion) {
            flash('danger', 'Cotización no encontrada.');
            $this->redirigir('/app/cotizaciones');
        }
        $clientes = (new Cliente())->listar($empresaId);
        $productos = (new Producto())->listar($empresaId);
        $this->vista('empresa/cotizaciones/editar', compact('cotizacion', 'clientes', 'productos'), 'empresa');
    }

    public function actualizar(int $id): void
    {
        validar_csrf();

        $productoIds = $_POST['producto_id'] ?? [];
        $descripciones = $_POST['descripcion_item'] ?? [];
        $cantidades = $_POST['cantidad'] ?? [];
        $precios = $_POST['precio_unitario'] ?? [];
        $impuestos = $_POST['impuesto_item'] ?? [];
        $descuentoTiposLinea = $_POST['descuento_tipo_item'] ?? [];
        $descuentoValoresLinea = $_POST['descuento_item'] ?? [];

        $items = [];
        $subtotal = 0.0;
        $impuesto = 0.0;
        $conteoLineas = max(
            count((array) $productoIds),
            count((array) $descripciones),
            count((array) $cantidades),
            count((array) $precios)
        );

        for ($i = 0; $i < $conteoLineas; $i++) {
            $productoId = (int) ($productoIds[$i] ?? 0) ?: null;
            $descripcion = trim((string) ($descripciones[$i] ?? ''));
            $cantidad = (float) ($cantidades[$i] ?? 0);
            $precio = (float) ($precios[$i] ?? 0);
            $impuestoPorcentaje = max(0, (float) ($impuestos[$i] ?? 0));
            $descuentoTipo = ($descuentoTiposLinea[$i] ?? 'valor') === 'porcentaje' ? 'porcentaje' : 'valor';
            $descuentoValor = max(0, (float) ($descuentoValoresLinea[$i] ?? 0));

            if ($cantidad <= 0 || $precio < 0) {
                continue;
            }

            $baseLinea = $cantidad * $precio;
            $descuentoMonto = $descuentoTipo === 'porcentaje'
                ? $baseLinea * (min($descuentoValor, 100) / 100)
                : min($descuentoValor, $baseLinea);
            $subtotalLinea = max(0, $baseLinea - $descuentoMonto);
            $impuestoLinea = $subtotalLinea * ($impuestoPorcentaje / 100);
            $totalLinea = $subtotalLinea + $impuestoLinea;

            $subtotal += $subtotalLinea;
            $impuesto += $impuestoLinea;
            $items[] = [
                'producto_id' => $productoId,
                'descripcion' => $descripcion !== '' ? $descripcion : 'Ítem ' . ($i + 1),
                'cantidad' => $cantidad,
                'precio_unitario' => $precio,
                'descuento_tipo' => $descuentoTipo,
                'descuento_valor' => $descuentoValor,
                'descuento_monto' => $descuentoMonto,
                'porcentaje_impuesto' => $impuestoPorcentaje,
                'subtotal' => $subtotalLinea,
                'total' => $totalLinea,
            ];
        }

        if ($items === []) {
            flash('danger', 'Debes mantener al menos un servicio o producto con cantidad válida.');
            $this->redirigir('/app/cotizaciones/editar/' . $id);
        }

        $descuentoTipo = ($_POST['descuento_tipo_total'] ?? 'valor') === 'porcentaje' ? 'porcentaje' : 'valor';
        $descuentoValor = max(0, (float) ($_POST['descuento_total'] ?? 0));
        $descuento = $descuentoTipo === 'porcentaje'
            ? ($subtotal + $impuesto) * (min($descuentoValor, 100) / 100)
            : min($descuentoValor, $subtotal + $impuesto);
        $total = max(0, ($subtotal + $impuesto) - $descuento);

        (new Cotizacion())->actualizarConItems(empresa_actual_id(), $id, [
            'cliente_id' => (int) $_POST['cliente_id'],
            'estado' => $_POST['estado'] ?? 'borrador',
            'subtotal' => $subtotal,
            'descuento_tipo' => $descuentoTipo,
            'descuento_valor' => $descuentoValor,
            'descuento' => $descuento,
            'impuesto' => $impuesto,
            'total' => $total,
            'observaciones' => trim($_POST['observaciones'] ?? ''),
            'terminos_condiciones' => trim($_POST['terminos_condiciones'] ?? ''),
            'fecha_emision' => $_POST['fecha_emision'] ?? date('Y-m-d'),
            'fecha_vencimiento' => $_POST['fecha_vencimiento'] ?? date('Y-m-d'),
        ], $items);
        flash('success', 'Cotización actualizada correctamente.');
        $this->redirigir('/app/cotizaciones');
    }

    public function eliminar(int $id): void
    {
        validar_csrf();
        (new Cotizacion())->eliminar(empresa_actual_id(), $id);
        flash('success', 'Cotización eliminada correctamente.');
        $this->redirigir('/app/cotizaciones');
    }
}
