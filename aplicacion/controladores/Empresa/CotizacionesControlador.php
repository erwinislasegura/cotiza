<?php

namespace Aplicacion\Controladores\Empresa;

use Aplicacion\Nucleo\Controlador;
use Aplicacion\Modelos\Cotizacion;
use Aplicacion\Modelos\Cliente;
use Aplicacion\Modelos\Producto;
use Aplicacion\Modelos\Empresa;
use Aplicacion\Modelos\GestionComercial;
use Aplicacion\Servicios\ServicioPlan;
use Aplicacion\Servicios\ServicioPreciosLista;

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
        $listasPrecios = (new GestionComercial())->listarListasPreciosActivas($empresaId);
        $siguienteNumero = (new Cotizacion())->siguienteNumero($empresaId);
        $this->vista('empresa/cotizaciones/formulario', compact('clientes', 'productos', 'siguienteNumero', 'listasPrecios'), 'empresa');
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
        $canalVenta = trim((string) ($_POST['canal_venta'] ?? ''));
        $clienteIdSeleccionado = (int) ($_POST['cliente_id'] ?? 0) ?: null;
        $listaPrecioId = (int) ($_POST['lista_precio_id'] ?? 0) ?: null;
        $servicioPrecios = new ServicioPreciosLista();

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
            [$precio, $descuentoTipo, $descuentoValor] = $this->aplicarPrecioListaLinea(
                $servicioPrecios,
                $empresaId,
                $productoId,
                $clienteIdSeleccionado,
                $canalVenta !== '' ? $canalVenta : null,
                $listaPrecioId,
                $precio,
                $descuentoTipo,
                $descuentoValor
            );

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

        $cotizacionId = $modelo->crearConItems([
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
        $this->redirigirSegunAccion($_POST['accion'] ?? 'guardar_salir', '/app/cotizaciones/editar/' . $cotizacionId, '/app/cotizaciones');
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

    public function imprimir(int $id): void
    {
        $empresaId = empresa_actual_id();
        $cotizacion = (new Cotizacion())->obtenerPorId($empresaId, $id);
        if (!$cotizacion) {
            flash('danger', 'Cotización no encontrada.');
            $this->redirigir('/app/cotizaciones');
        }

        $empresa = (new Empresa())->buscar($empresaId);
        $listaPrecioId = (int) ($_GET['lista_precio_id'] ?? 0) ?: null;
        $servicioPrecios = new ServicioPreciosLista();
        $listaAplicada = $servicioPrecios->resolverListaPrecio(
            $empresaId,
            (int) ($cotizacion['cliente_id'] ?? 0) ?: null,
            null,
            date('Y-m-d'),
            $listaPrecioId
        );
        $listasPrecios = (new GestionComercial())->listarListasPreciosActivas($empresaId);
        $this->vista('empresa/cotizaciones/imprimir', compact('cotizacion', 'empresa', 'listaAplicada', 'listasPrecios'), 'impresion');
    }

    public function descargarPdf(int $id): void
    {
        $empresaId = empresa_actual_id();
        $cotizacion = (new Cotizacion())->obtenerPorId($empresaId, $id);
        if (!$cotizacion) {
            flash('danger', 'Cotización no encontrada.');
            $this->redirigir('/app/cotizaciones');
        }

        $empresa = (new Empresa())->buscar($empresaId);
        $listaPrecioId = (int) ($_GET['lista_precio_id'] ?? 0) ?: null;
        $servicioPrecios = new ServicioPreciosLista();
        $listaAplicada = $servicioPrecios->resolverListaPrecio(
            $empresaId,
            (int) ($cotizacion['cliente_id'] ?? 0) ?: null,
            null,
            date('Y-m-d'),
            $listaPrecioId
        );
        $listasPrecios = (new GestionComercial())->listarListasPreciosActivas($empresaId);
        $autoDescargarPdf = true;
        $this->vista('empresa/cotizaciones/imprimir', compact('cotizacion', 'empresa', 'listaAplicada', 'listasPrecios', 'autoDescargarPdf'), 'impresion');
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
        $listasPrecios = (new GestionComercial())->listarListasPreciosActivas($empresaId);
        $listaPrecioSeleccionada = (new ServicioPreciosLista())->resolverListaPrecio($empresaId, (int) $cotizacion['cliente_id'], null, date('Y-m-d'));
        $this->vista('empresa/cotizaciones/editar', compact('cotizacion', 'clientes', 'productos', 'listasPrecios', 'listaPrecioSeleccionada'), 'empresa');
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
        $canalVenta = trim((string) ($_POST['canal_venta'] ?? ''));
        $clienteIdSeleccionado = (int) ($_POST['cliente_id'] ?? 0) ?: null;
        $listaPrecioId = (int) ($_POST['lista_precio_id'] ?? 0) ?: null;
        $servicioPrecios = new ServicioPreciosLista();

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
            [$precio, $descuentoTipo, $descuentoValor] = $this->aplicarPrecioListaLinea(
                $servicioPrecios,
                empresa_actual_id(),
                $productoId,
                $clienteIdSeleccionado,
                $canalVenta !== '' ? $canalVenta : null,
                $listaPrecioId,
                $precio,
                $descuentoTipo,
                $descuentoValor
            );

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
        $this->redirigirSegunAccion($_POST['accion'] ?? 'guardar_salir', '/app/cotizaciones/editar/' . $id, '/app/cotizaciones');
    }

    private function aplicarPrecioListaLinea(
        ServicioPreciosLista $servicioPrecios,
        int $empresaId,
        ?int $productoId,
        ?int $clienteId,
        ?string $canalVenta,
        ?int $listaPrecioId,
        float $precio,
        string $descuentoTipo,
        float $descuentoValor
    ): array {
        if ($productoId === null) {
            return [$precio, $descuentoTipo, $descuentoValor];
        }

        $precioCalculado = $servicioPrecios->calcularPrecioProducto($empresaId, $productoId, $clienteId, $canalVenta, date('Y-m-d'), $listaPrecioId);
        if (!$precioCalculado) {
            return [$precio, $descuentoTipo, $descuentoValor];
        }

        $precioIngresado = $precio > 0;

        $usaDescuentoLista = ($precioCalculado['ajuste_tipo'] ?? '') === 'descuento' && (float) ($precioCalculado['ajuste_porcentaje'] ?? 0) > 0;
        if ($usaDescuentoLista && !$precioIngresado) {
            $precio = (float) $precioCalculado['precio_base'];
        }
        if ($usaDescuentoLista && $descuentoValor <= 0) {
            $descuentoTipo = 'porcentaje';
            $descuentoValor = (float) $precioCalculado['ajuste_porcentaje'];
        }

        if (!$usaDescuentoLista && !$precioIngresado) {
            $precio = (float) $precioCalculado['precio_final'];
        }

        return [$precio, $descuentoTipo, $descuentoValor];
    }

    private function redirigirSegunAccion(string $accion, string $rutaMantener, string $rutaSalir): void
    {
        if ($accion === 'guardar') {
            $this->redirigir($rutaMantener);
        }
        $this->redirigir($rutaSalir);
    }

    private function generarPdfCotizacion(array $cotizacion, array $empresa): string
    {
        $clienteNombre = trim((string) (($cotizacion['cliente_razon_social'] ?? '') !== '' ? $cotizacion['cliente_razon_social'] : ($cotizacion['cliente'] ?? '')));
        $items = $cotizacion['items'] ?? [];
        $descuentoTexto = (($cotizacion['descuento_tipo'] ?? 'valor') === 'porcentaje')
            ? number_format((float) ($cotizacion['descuento_valor'] ?? 0), 2) . '%'
            : '$' . number_format((float) ($cotizacion['descuento'] ?? 0), 0, ',', '.');
        $neto = max(0, (float) ($cotizacion['subtotal'] ?? 0) - (float) ($cotizacion['descuento'] ?? 0));

        $c = [];
        $c[] = '0.95 0.96 0.98 rg 0 0 612 792 re f';
        $c[] = '1 1 1 rg 26 26 560 740 re f';
        $c[] = '0.12 0.31 0.47 RG 2 w 26 695 m 586 695 l S';

        $c[] = 'BT /F1 20 Tf 0.12 0.31 0.47 rg 40 742 Td (' . $this->pdfEsc($empresa['nombre_comercial'] ?? $empresa['razon_social'] ?? 'Comercial') . ') Tj ET';
        $c[] = 'BT /F1 11 Tf 0.12 0.31 0.47 rg 430 748 Td (COTIZACION) Tj ET';
        $c[] = 'BT /F1 9 Tf 0 0 0 rg 430 734 Td (N\\260: ' . $this->pdfEsc((string) ($cotizacion['numero'] ?? '')) . ') Tj ET';
        $c[] = 'BT /F1 9 Tf 0 0 0 rg 430 720 Td (Fecha: ' . $this->pdfEsc((string) ($cotizacion['fecha_emision'] ?? '')) . ') Tj ET';
        $c[] = 'BT /F1 9 Tf 0 0 0 rg 430 706 Td (Validez: ' . $this->pdfEsc((string) ($cotizacion['fecha_vencimiento'] ?? '')) . ') Tj ET';

        $c[] = 'BT /F1 9 Tf 0 0 0 rg 40 724 Td (RUT: ' . $this->pdfEsc((string) ($empresa['identificador_fiscal'] ?? '')) . ') Tj ET';
        $c[] = 'BT /F1 9 Tf 0 0 0 rg 40 710 Td (' . $this->pdfEsc(trim((string) (($empresa['direccion'] ?? '') . ', ' . ($empresa['ciudad'] ?? '')))) . ') Tj ET';
        $c[] = 'BT /F1 9 Tf 0 0 0 rg 40 696 Td (Telefono: ' . $this->pdfEsc((string) ($empresa['telefono'] ?? '')) . ') Tj ET';
        $c[] = 'BT /F1 9 Tf 0 0 0 rg 40 682 Td (Correo: ' . $this->pdfEsc((string) ($empresa['correo'] ?? '')) . ') Tj ET';

        $c[] = 'BT /F1 10 Tf 0.12 0.31 0.47 rg 40 668 Td (Datos del cliente) Tj ET';
        $c[] = 'BT /F1 9 Tf 0 0 0 rg 40 652 Td (Cliente: ' . $this->pdfEsc($clienteNombre) . ') Tj ET';
        $c[] = 'BT /F1 9 Tf 0 0 0 rg 300 652 Td (RUT: ' . $this->pdfEsc((string) ($cotizacion['cliente_identificador_fiscal'] ?? '')) . ') Tj ET';
        $c[] = 'BT /F1 9 Tf 0 0 0 rg 40 638 Td (Contacto: ' . $this->pdfEsc((string) ($cotizacion['cliente'] ?? '')) . ') Tj ET';
        $c[] = 'BT /F1 9 Tf 0 0 0 rg 300 638 Td (Correo: ' . $this->pdfEsc((string) ($cotizacion['cliente_correo'] ?? '')) . ') Tj ET';
        $c[] = 'BT /F1 9 Tf 0 0 0 rg 40 624 Td (Telefono: ' . $this->pdfEsc((string) ($cotizacion['cliente_telefono'] ?? '')) . ') Tj ET';
        $c[] = 'BT /F1 9 Tf 0 0 0 rg 300 624 Td (Direccion: ' . $this->pdfEsc(trim((string) (($cotizacion['cliente_direccion'] ?? '') . ', ' . ($cotizacion['cliente_ciudad'] ?? '')))) . ') Tj ET';

        $c[] = '0.12 0.31 0.47 rg 40 594 532 18 re f';
        $headers = [['Codigo', 44], ['Descripcion', 100], ['Cant.', 345], ['Unidad', 390], ['P. Unitario', 450], ['Total', 520]];
        foreach ($headers as [$txt, $x]) {
            $c[] = 'BT /F1 8 Tf 1 1 1 rg ' . $x . ' 600 Td (' . $this->pdfEsc($txt) . ') Tj ET';
        }

        $y = 578;
        foreach ($items as $item) {
            if ($y < 430) {
                break;
            }
            $c[] = '0.86 0.89 0.92 RG 0.5 w 40 ' . ($y - 2) . ' 532 20 re S';
            $c[] = 'BT /F1 8 Tf 0 0 0 rg 44 ' . ($y + 6) . ' Td (' . $this->pdfEsc((string) ($item['codigo'] ?? ('ITM-' . (string) ($item['id'] ?? '')))) . ') Tj ET';
            $c[] = 'BT /F1 8 Tf 0 0 0 rg 100 ' . ($y + 6) . ' Td (' . $this->pdfEsc((string) ($item['descripcion'] ?? '')) . ') Tj ET';
            $c[] = 'BT /F1 8 Tf 0 0 0 rg 348 ' . ($y + 6) . ' Td (' . $this->pdfEsc(number_format((float) ($item['cantidad'] ?? 0), 2)) . ') Tj ET';
            $c[] = 'BT /F1 8 Tf 0 0 0 rg 392 ' . ($y + 6) . ' Td (' . $this->pdfEsc((string) ($item['unidad'] ?? 'Unidad')) . ') Tj ET';
            $c[] = 'BT /F1 8 Tf 0 0 0 rg 452 ' . ($y + 6) . ' Td ($' . $this->pdfEsc(number_format((float) ($item['precio_unitario'] ?? 0), 0, ',', '.')) . ') Tj ET';
            $c[] = 'BT /F1 8 Tf 0 0 0 rg 522 ' . ($y + 6) . ' Td ($' . $this->pdfEsc(number_format((float) ($item['total'] ?? 0), 0, ',', '.')) . ') Tj ET';
            $y -= 20;
        }

        $totY = 360;
        $rows = [
            ['Subtotal', '$' . number_format((float) ($cotizacion['subtotal'] ?? 0), 0, ',', '.')],
            ['Descuento', '- ' . $descuentoTexto],
            ['Neto', '$' . number_format($neto, 0, ',', '.')],
            ['IVA (19%)', '$' . number_format((float) ($cotizacion['impuesto'] ?? 0), 0, ',', '.')],
        ];
        foreach ($rows as $i => [$label, $value]) {
            $yy = $totY - ($i * 20);
            $c[] = '0.86 0.89 0.92 RG 0.5 w 330 ' . $yy . ' 242 20 re S';
            $c[] = 'BT /F1 8 Tf 0 0 0 rg 338 ' . ($yy + 7) . ' Td (' . $this->pdfEsc($label) . ') Tj ET';
            $c[] = 'BT /F1 8 Tf 0 0 0 rg 500 ' . ($yy + 7) . ' Td (' . $this->pdfEsc($value) . ') Tj ET';
        }
        $c[] = '0.12 0.31 0.47 rg 330 280 242 22 re f';
        $c[] = 'BT /F1 9 Tf 1 1 1 rg 338 288 Td (Total) Tj ET';
        $c[] = 'BT /F1 9 Tf 1 1 1 rg 500 288 Td ($' . $this->pdfEsc(number_format((float) ($cotizacion['total'] ?? 0), 0, ',', '.')) . ') Tj ET';

        $c[] = 'BT /F1 10 Tf 0.12 0.31 0.47 rg 40 258 Td (Observaciones) Tj ET';
        $c[] = '0.97 0.98 0.99 rg 40 210 532 40 re f';
        $c[] = '0.12 0.31 0.47 RG 2 w 40 210 m 40 250 l S';
        $c[] = 'BT /F1 8 Tf 0 0 0 rg 50 236 Td (' . $this->pdfEsc((string) ($cotizacion['observaciones'] ?? '')) . ') Tj ET';

        $c[] = 'BT /F1 10 Tf 0.12 0.31 0.47 rg 40 190 Td (Terminos y condiciones) Tj ET';
        $ty = 176;
        foreach (preg_split('/\\r\\n|\\r|\\n/', trim((string) ($cotizacion['terminos_condiciones'] ?? ''))) as $term) {
            if (trim($term) === '' || $ty < 110) {
                continue;
            }
            $c[] = 'BT /F1 8 Tf 0 0 0 rg 46 ' . $ty . ' Td (- ' . $this->pdfEsc(trim($term)) . ') Tj ET';
            $ty -= 12;
        }

        $c[] = '0.3 0.35 0.4 RG 1 w 70 78 m 260 78 l S';
        $c[] = '0.3 0.35 0.4 RG 1 w 350 78 m 540 78 l S';
        $c[] = 'BT /F1 9 Tf 0 0 0 rg 130 66 Td (' . $this->pdfEsc((string) ($cotizacion['vendedor'] ?? '')) . ') Tj ET';
        $c[] = 'BT /F1 8 Tf 0 0 0 rg 130 54 Td (Ejecutivo Comercial) Tj ET';
        $c[] = 'BT /F1 9 Tf 0 0 0 rg 420 66 Td (' . $this->pdfEsc((string) ($cotizacion['cliente'] ?? '')) . ') Tj ET';
        $c[] = 'BT /F1 8 Tf 0 0 0 rg 410 54 Td (Aceptacion cliente) Tj ET';
        $c[] = 'BT /F1 7 Tf 0.4 0.45 0.5 rg 210 36 Td (Documento generado automaticamente por el sistema de cotizaciones.) Tj ET';

        return $this->crearPdfTexto($c);
    }

    private function crearPdfTexto(array $comandos): string
    {
        $contenido = implode("\n", $comandos);

        $objetos = [];
        $objetos[] = "1 0 obj << /Type /Catalog /Pages 2 0 R >> endobj";
        $objetos[] = "2 0 obj << /Type /Pages /Kids [3 0 R] /Count 1 >> endobj";
        $objetos[] = "3 0 obj << /Type /Page /Parent 2 0 R /MediaBox [0 0 612 792] /Resources << /Font << /F1 4 0 R >> >> /Contents 5 0 R >> endobj";
        $objetos[] = "4 0 obj << /Type /Font /Subtype /Type1 /BaseFont /Helvetica >> endobj";
        $objetos[] = "5 0 obj << /Length " . strlen($contenido) . " >> stream\n" . $contenido . "\nendstream endobj";

        $pdf = "%PDF-1.4\n";
        $offsets = [0];
        foreach ($objetos as $obj) {
            $offsets[] = strlen($pdf);
            $pdf .= $obj . "\n";
        }
        $xref = strlen($pdf);
        $pdf .= "xref\n0 " . (count($objetos) + 1) . "\n";
        $pdf .= "0000000000 65535 f \n";
        for ($i = 1; $i <= count($objetos); $i++) {
            $pdf .= sprintf("%010d 00000 n \n", $offsets[$i]);
        }
        $pdf .= "trailer << /Size " . (count($objetos) + 1) . " /Root 1 0 R >>\n";
        $pdf .= "startxref\n" . $xref . "\n%%EOF";

        return $pdf;
    }

    private function pdfEsc(string $texto): string
    {
        $t = iconv('UTF-8', 'Windows-1252//TRANSLIT//IGNORE', $texto) ?: $texto;
        $t = str_replace(['\\', '(', ')'], ['\\\\', '\\(', '\\)'], $t);
        return preg_replace('/[\\x00-\\x08\\x0B\\x0C\\x0E-\\x1F]/', '', $t) ?? '';
    }

    public function eliminar(int $id): void
    {
        validar_csrf();
        (new Cotizacion())->eliminar(empresa_actual_id(), $id);
        flash('success', 'Cotización eliminada correctamente.');
        $this->redirigir('/app/cotizaciones');
    }
}
