<?php

namespace Aplicacion\Controladores\Empresa;

use Aplicacion\Nucleo\Controlador;
use Aplicacion\Modelos\PuntoVenta;
use Aplicacion\Modelos\GestionComercial;
use Aplicacion\Servicios\ServicioAlertaStock;

class PuntoVentaControlador extends Controlador
{
    private function validarPermiso(string $permiso): void
    {
        $usuario = usuario_actual();
        if (!$usuario) {
            http_response_code(403);
            exit('No autorizado');
        }

        $roles = [
            'administrador_empresa' => ['ver_pos', 'abrir_caja_pos', 'cerrar_caja_pos', 'registrar_venta_pos', 'aplicar_descuento_pos', 'editar_precio_pos', 'ver_historial_pos', 'administrar_cajas_pos', 'configurar_pos'],
            'usuario_empresa' => ['ver_pos', 'abrir_caja_pos', 'registrar_venta_pos', 'ver_historial_pos'],
        ];

        if (($usuario['rol_codigo'] ?? '') === 'superadministrador') {
            return;
        }

        $permitidos = $roles[$usuario['rol_codigo'] ?? ''] ?? [];
        if (!in_array($permiso, $permitidos, true)) {
            http_response_code(403);
            exit('No tienes permisos para esta acción del POS.');
        }
    }

    public function index(): void
    {
        $this->validarPermiso('ver_pos');
        $empresaId = (int) empresa_actual_id();
        $usuario = usuario_actual();
        $pos = new PuntoVenta();

        $apertura = $pos->obtenerAperturaActiva($empresaId, (int) $usuario['id']);

        $buscar = trim($_GET['q'] ?? '');
        $categoriaId = (int) ($_GET['categoria_id'] ?? 0) ?: null;

        $productos = $pos->listarProductosPos($empresaId, $buscar, $categoriaId);
        $clientes = $pos->listarClientesPos($empresaId);
        $categorias = (new GestionComercial())->listarTablaEmpresa('categorias_productos', $empresaId, '', 300);
        $configuracion = $pos->obtenerConfiguracion($empresaId);
        $cajas = $pos->listarCajas($empresaId);
        $resumenCierre = $apertura ? $pos->resumenCierre($empresaId, (int) $apertura['id']) : null;

        $this->vista('empresa/pos/index', compact('apertura', 'productos', 'clientes', 'categorias', 'buscar', 'categoriaId', 'configuracion', 'cajas', 'resumenCierre'), 'empresa');
    }

    public function aperturaCaja(): void
    {
        $this->validarPermiso('abrir_caja_pos');
        $empresaId = (int) empresa_actual_id();
        $pos = new PuntoVenta();
        $cajas = $pos->listarCajas($empresaId);

        $apertura = $pos->obtenerAperturaActiva($empresaId, (int) (usuario_actual()['id'] ?? 0));
        $this->vista('empresa/pos/apertura_caja', compact('cajas', 'apertura'), 'empresa');
    }

    public function guardarAperturaCaja(): void
    {
        $this->validarPermiso('abrir_caja_pos');
        validar_csrf();
        $empresaId = (int) empresa_actual_id();
        $usuarioId = (int) (usuario_actual()['id'] ?? 0);
        $pos = new PuntoVenta();

        if ($pos->obtenerAperturaActiva($empresaId, $usuarioId)) {
            flash('warning', 'Ya tienes una caja abierta para operar.');
            $this->redirigir('/app/punto-venta');
        }

        $cajaId = (int) ($_POST['caja_id'] ?? 0);
        if ($cajaId <= 0) {
            flash('danger', 'Selecciona una caja válida.');
            $this->redirigir('/app/punto-venta/apertura-caja');
        }

        $pos->abrirCaja([
            'empresa_id' => $empresaId,
            'caja_id' => $cajaId,
            'usuario_id' => $usuarioId,
            'monto_inicial' => (float) ($_POST['monto_inicial'] ?? 0),
            'observacion' => trim($_POST['observacion'] ?? ''),
        ]);

        flash('success', 'Caja abierta correctamente.');
        $this->redirigir('/app/punto-venta');
    }

    public function guardarVenta(): void
    {
        $this->validarPermiso('registrar_venta_pos');
        validar_csrf();
        $empresaId = (int) empresa_actual_id();
        $usuarioId = (int) (usuario_actual()['id'] ?? 0);
        $pos = new PuntoVenta();

        $apertura = $pos->obtenerAperturaActiva($empresaId, $usuarioId);
        if (!$apertura) {
            flash('danger', 'No hay caja abierta para registrar ventas.');
            $this->redirigir('/app/punto-venta/apertura-caja');
        }

        $items = json_decode((string) ($_POST['items_json'] ?? '[]'), true);
        $pagos = json_decode((string) ($_POST['pagos_json'] ?? '[]'), true);

        if (!is_array($items) || $items === []) {
            flash('danger', 'La venta debe tener al menos un producto.');
            $this->redirigir('/app/punto-venta');
        }

        if (!is_array($pagos) || $pagos === []) {
            flash('danger', 'Registra al menos un pago para finalizar la venta.');
            $this->redirigir('/app/punto-venta');
        }

        $tipoVenta = $_POST['tipo_venta'] ?? 'rapida';
        $clienteId = (int) ($_POST['cliente_id'] ?? 0);
        if ($tipoVenta === 'rapida' || $clienteId <= 0) {
            $clienteId = $pos->asegurarClienteRapido($empresaId);
        }

        $configuracion = $pos->obtenerConfiguracion($empresaId);

        try {
            $ventaId = $pos->registrarVenta([
                'empresa_id' => $empresaId,
                'caja_id' => (int) $apertura['caja_id'],
                'apertura_caja_id' => (int) $apertura['id'],
                'cliente_id' => $clienteId,
                'usuario_id' => $usuarioId,
                'tipo_venta' => $tipoVenta,
                'subtotal' => (float) ($_POST['subtotal'] ?? 0),
                'descuento' => (float) ($_POST['descuento'] ?? 0),
                'impuesto' => (float) ($_POST['impuesto'] ?? 0),
                'total' => (float) ($_POST['total'] ?? 0),
                'numero_venta' => $pos->siguienteNumeroVenta($empresaId),
                'observaciones' => trim($_POST['observaciones'] ?? ''),
                'monto_recibido' => (float) ($_POST['monto_recibido'] ?? 0),
                'vuelto' => (float) ($_POST['vuelto'] ?? 0),
            ], $items, $pagos, (bool) ($configuracion['permitir_venta_sin_stock'] ?? false));

            $alertas = new ServicioAlertaStock();
            foreach ($pos->obtenerTransicionesStock() as $transicion) {
                $alertas->evaluarYNotificar($empresaId, (int) $transicion['producto_id'], (float) $transicion['stock_anterior'], (float) $transicion['stock_actual'], (string) (usuario_actual()['nombre'] ?? ''));
            }

            flash('success', 'Venta registrada correctamente.');
            $this->redirigir('/app/punto-venta/ventas/imprimir/' . $ventaId);
        } catch (\Throwable $e) {
            flash('danger', 'No fue posible registrar la venta: ' . $e->getMessage());
            $this->redirigir('/app/punto-venta');
        }
    }

    public function ventas(): void
    {
        $this->validarPermiso('ver_historial_pos');
        $pos = new PuntoVenta();
        $empresaId = (int) empresa_actual_id();
        $ventas = $pos->listarVentas($empresaId);
        $configuracion = $pos->obtenerConfiguracion($empresaId);
        $this->vista('empresa/pos/ventas', compact('ventas', 'configuracion'), 'empresa');
    }

    public function verVenta(int $id): void
    {
        $this->validarPermiso('ver_historial_pos');
        $pos = new PuntoVenta();
        $empresaId = (int) empresa_actual_id();
        $venta = $pos->obtenerVenta($empresaId, $id);
        if (!$venta) {
            http_response_code(404);
            exit('Venta no encontrada.');
        }
        $configuracion = $pos->obtenerConfiguracion($empresaId);
        $this->vista('empresa/pos/ver_venta', compact('venta', 'configuracion'), 'empresa');
    }

    public function imprimirVenta(int $id): void
    {
        $this->validarPermiso('ver_historial_pos');
        $pos = new PuntoVenta();
        $empresaId = (int) empresa_actual_id();
        $venta = $pos->obtenerVenta($empresaId, $id);
        if (!$venta) {
            http_response_code(404);
            exit('Venta no encontrada.');
        }
        $configuracion = $pos->obtenerConfiguracion($empresaId);
        $this->vista('empresa/pos/imprimir_venta', compact('venta', 'configuracion'), 'impresion');
    }

    public function cierreCaja(): void
    {
        $this->validarPermiso('cerrar_caja_pos');
        $empresaId = (int) empresa_actual_id();
        $usuarioId = (int) (usuario_actual()['id'] ?? 0);
        $pos = new PuntoVenta();
        $apertura = $pos->obtenerAperturaActiva($empresaId, $usuarioId);
        $resumen = null;
        if ($apertura) {
            $resumen = $pos->resumenCierre($empresaId, (int) $apertura['id']);
        }
        $historialCierres = $pos->listarHistorialCierres($empresaId);
        $configuracion = $pos->obtenerConfiguracion($empresaId);

        $this->vista('empresa/pos/cierre_caja', compact('apertura', 'resumen', 'historialCierres', 'configuracion'), 'empresa');
    }

    public function guardarCierreCaja(): void
    {
        $this->validarPermiso('cerrar_caja_pos');
        validar_csrf();
        $empresaId = (int) empresa_actual_id();
        $usuarioId = (int) (usuario_actual()['id'] ?? 0);
        $pos = new PuntoVenta();
        $apertura = $pos->obtenerAperturaActiva($empresaId, $usuarioId);

        if (!$apertura) {
            flash('warning', 'No hay caja abierta para cerrar.');
            $this->redirigir('/app/punto-venta/cierre-caja');
        }

        $resumen = $pos->resumenCierre($empresaId, (int) $apertura['id']);
        $montoEsperado = (float) $apertura['monto_inicial'] + (float) ($resumen['total_ventas'] ?? 0);
        $montoContado = (float) ($_POST['monto_contado'] ?? 0);
        $diferencia = $montoContado - $montoEsperado;

        $pos->cerrarCaja([
            'empresa_id' => $empresaId,
            'apertura_caja_id' => (int) $apertura['id'],
            'usuario_id' => $usuarioId,
            'monto_esperado' => $montoEsperado,
            'monto_contado' => $montoContado,
            'diferencia' => $diferencia,
            'observacion' => trim($_POST['observacion'] ?? ''),
            'monto_efectivo' => (float) ($resumen['efectivo'] ?? 0),
            'monto_transferencia' => (float) ($resumen['transferencia'] ?? 0),
            'monto_tarjeta' => (float) ($resumen['tarjeta'] ?? 0),
            'monto_inicial' => (float) $apertura['monto_inicial'],
        ]);

        flash('success', 'Caja cerrada correctamente. Diferencia: ' . number_format($diferencia, 2));
        $this->redirigir('/app/punto-venta/cierre-caja');
    }

    public function cajas(): void
    {
        $this->validarPermiso('administrar_cajas_pos');
        $cajas = (new PuntoVenta())->listarCajas((int) empresa_actual_id());
        $this->vista('empresa/pos/cajas', compact('cajas'), 'empresa');
    }

    public function guardarCaja(): void
    {
        $this->validarPermiso('administrar_cajas_pos');
        validar_csrf();
        (new PuntoVenta())->crearCaja([
            'empresa_id' => (int) empresa_actual_id(),
            'nombre' => trim($_POST['nombre'] ?? ''),
            'codigo' => trim($_POST['codigo'] ?? ''),
            'estado' => $_POST['estado'] ?? 'activa',
        ]);
        flash('success', 'Caja creada correctamente.');
        $this->redirigir('/app/punto-venta/cajas');
    }

    public function movimientosCaja(): void
    {
        $this->validarPermiso('ver_historial_pos');
        $pos = new PuntoVenta();
        $empresaId = (int) empresa_actual_id();
        $movimientos = $pos->listarMovimientosCaja($empresaId);
        $configuracion = $pos->obtenerConfiguracion($empresaId);
        $this->vista('empresa/pos/movimientos', compact('movimientos', 'configuracion'), 'empresa');
    }

    public function configuracion(): void
    {
        $this->validarPermiso('configurar_pos');
        $configuracion = (new PuntoVenta())->obtenerConfiguracion((int) empresa_actual_id());
        $this->vista('empresa/pos/configuracion', compact('configuracion'), 'empresa');
    }

    public function guardarConfiguracion(): void
    {
        $this->validarPermiso('configurar_pos');
        validar_csrf();
        (new PuntoVenta())->guardarConfiguracion((int) empresa_actual_id(), [
            'permitir_venta_sin_stock' => isset($_POST['permitir_venta_sin_stock']) ? 1 : 0,
            'impuesto_por_defecto' => (float) ($_POST['impuesto_por_defecto'] ?? 0),
            'usar_decimales' => isset($_POST['usar_decimales']) ? 1 : 0,
            'cantidad_decimales' => max(0, min(6, (int) ($_POST['cantidad_decimales'] ?? 2))),
            'moneda' => in_array((string) ($_POST['moneda'] ?? 'CLP'), ['CLP', 'USD', 'EU'], true) ? (string) $_POST['moneda'] : 'CLP',
        ]);

        flash('success', 'Configuración POS actualizada.');
        $this->redirigir('/app/punto-venta/configuracion');
    }
}
