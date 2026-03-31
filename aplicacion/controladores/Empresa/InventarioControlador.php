<?php

namespace Aplicacion\Controladores\Empresa;

use Aplicacion\Nucleo\Controlador;
use Aplicacion\Modelos\Inventario;
use Aplicacion\Servicios\ServicioAlertaStock;
use Throwable;

class InventarioControlador extends Controlador
{
    private const MOTIVOS_AJUSTE = [
        'correccion_inventario' => 'Corrección de inventario',
        'merma' => 'Merma',
        'perdida' => 'Pérdida',
        'danio' => 'Daño',
        'ajuste_manual' => 'Ajuste manual',
        'diferencia_conteo' => 'Diferencia en conteo',
        'devolucion' => 'Devolución',
        'regularizacion' => 'Regularización',
    ];

    private function validarPermiso(string $permiso): void
    {
        $usuario = usuario_actual();
        if (!$usuario) {
            http_response_code(403);
            exit('No autorizado');
        }

        if (($usuario['rol_codigo'] ?? '') === 'superadministrador') {
            return;
        }

        $roles = [
            'administrador_empresa' => ['inventario_ver_recepciones', 'inventario_crear_recepciones', 'inventario_ver_ajustes', 'inventario_crear_ajustes', 'inventario_ver_movimientos', 'inventario_configurar_alertas'],
            'usuario_empresa' => ['inventario_ver_recepciones', 'inventario_ver_ajustes', 'inventario_ver_movimientos'],
        ];

        if (!in_array($permiso, $roles[$usuario['rol_codigo'] ?? ''] ?? [], true)) {
            http_response_code(403);
            exit('No tienes permisos para esta sección de inventario.');
        }
    }

    public function recepciones(): void
    {
        $this->validarPermiso('inventario_ver_recepciones');
        $empresaId = (int) empresa_actual_id();
        $inventario = new Inventario();

        $recepciones = $inventario->listarRecepciones($empresaId);
        $proveedores = $inventario->listarProveedores($empresaId);
        $productos = $inventario->listarProductos($empresaId);

        $this->vista('empresa/inventario/recepciones', compact('recepciones', 'proveedores', 'productos'), 'empresa');
    }

    public function guardarRecepcion(): void
    {
        $this->validarPermiso('inventario_crear_recepciones');
        validar_csrf();
        $empresaId = (int) empresa_actual_id();
        $usuario = usuario_actual();
        $inventario = new Inventario();

        $proveedorId = (int) ($_POST['proveedor_id'] ?? 0);
        $nuevoProveedor = trim((string) ($_POST['proveedor_nuevo'] ?? ''));
        if ($proveedorId <= 0 && $nuevoProveedor !== '') {
            $proveedorId = $inventario->crearProveedor($empresaId, [
                'nombre' => $nuevoProveedor,
                'identificador_fiscal' => trim((string) ($_POST['proveedor_identificador_fiscal'] ?? '')),
                'contacto' => trim((string) ($_POST['proveedor_contacto'] ?? '')),
                'correo' => trim((string) ($_POST['proveedor_correo'] ?? '')),
                'telefono' => trim((string) ($_POST['proveedor_telefono'] ?? '')),
                'direccion' => trim((string) ($_POST['proveedor_direccion'] ?? '')),
                'ciudad' => trim((string) ($_POST['proveedor_ciudad'] ?? '')),
                'observacion' => trim((string) ($_POST['proveedor_observacion'] ?? '')),
                'estado' => 'activo',
            ]);
        }

        $tiposPermitidos = ['guia_despacho', 'factura'];
        $tipoDocumento = in_array($_POST['tipo_documento'] ?? '', $tiposPermitidos, true) ? $_POST['tipo_documento'] : 'guia_despacho';

        $productoIds = $_POST['producto_id'] ?? [];
        $cantidades = $_POST['cantidad'] ?? [];
        $costos = $_POST['costo_unitario'] ?? [];
        $detalles = [];
        foreach ((array) $productoIds as $idx => $productoId) {
            $pid = (int) $productoId;
            $cantidad = (float) ($cantidades[$idx] ?? 0);
            if ($pid <= 0 || $cantidad <= 0) {
                continue;
            }
            $costo = (float) ($costos[$idx] ?? 0);
            $detalles[] = [
                'producto_id' => $pid,
                'cantidad' => $cantidad,
                'costo_unitario' => $costo,
                'subtotal' => $costo > 0 ? ($cantidad * $costo) : 0,
            ];
        }

        if ($detalles === []) {
            flash('danger', 'Agrega al menos un producto con cantidad válida para registrar la recepción.');
            $this->redirigir('/app/inventario/recepciones');
        }

        try {
            $inventario->crearRecepcion([
                'empresa_id' => $empresaId,
                'proveedor_id' => $proveedorId > 0 ? $proveedorId : null,
                'tipo_documento' => $tipoDocumento,
                'numero_documento' => trim((string) ($_POST['numero_documento'] ?? '')),
                'fecha_documento' => trim((string) ($_POST['fecha_documento'] ?? date('Y-m-d'))),
                'referencia_interna' => trim((string) ($_POST['referencia_interna'] ?? '')),
                'observacion' => trim((string) ($_POST['observacion'] ?? '')),
                'usuario_id' => (int) ($usuario['id'] ?? 0),
            ], $detalles);
            flash('success', 'Recepción de inventario guardada y stock actualizado correctamente.');
        } catch (Throwable $e) {
            flash('danger', 'No fue posible guardar la recepción: ' . $e->getMessage());
        }

        $this->redirigir('/app/inventario/recepciones');
    }

    public function verRecepcion(int $id): void
    {
        $this->validarPermiso('inventario_ver_recepciones');
        $recepcion = (new Inventario())->obtenerRecepcion((int) empresa_actual_id(), $id);
        if (!$recepcion) {
            http_response_code(404);
            exit('Recepción no encontrada.');
        }

        $this->vista('empresa/inventario/recepcion_ver', compact('recepcion'), 'empresa');
    }

    public function ajustes(): void
    {
        $this->validarPermiso('inventario_ver_ajustes');
        $empresaId = (int) empresa_actual_id();
        $inventario = new Inventario();
        $filtros = [
            'producto_id' => (int) ($_GET['producto_id'] ?? 0),
            'tipo_ajuste' => trim((string) ($_GET['tipo_ajuste'] ?? '')),
            'fecha_desde' => trim((string) ($_GET['fecha_desde'] ?? '')),
            'fecha_hasta' => trim((string) ($_GET['fecha_hasta'] ?? '')),
        ];

        $productos = $inventario->listarProductos($empresaId);
        $ajustes = $inventario->listarAjustes($empresaId, $filtros);
        $motivos = self::MOTIVOS_AJUSTE;

        $this->vista('empresa/inventario/ajustes', compact('ajustes', 'productos', 'motivos', 'filtros'), 'empresa');
    }

    public function guardarAjuste(): void
    {
        $this->validarPermiso('inventario_crear_ajustes');
        validar_csrf();
        $empresaId = (int) empresa_actual_id();
        $usuario = usuario_actual();
        $inventario = new Inventario();

        $productoId = (int) ($_POST['producto_id'] ?? 0);
        $tipoAjuste = ($_POST['tipo_ajuste'] ?? 'entrada') === 'salida' ? 'salida' : 'entrada';
        $cantidad = max(0, (float) ($_POST['cantidad'] ?? 0));

        if ($productoId <= 0 || $cantidad <= 0) {
            flash('danger', 'Producto y cantidad son obligatorios para crear un ajuste.');
            $this->redirigir('/app/inventario/ajustes');
        }

        $productoActual = null;
        foreach ($inventario->listarProductos($empresaId) as $p) {
            if ((int) $p['id'] === $productoId) {
                $productoActual = $p;
                break;
            }
        }
        $stockAnterior = (float) ($productoActual['stock_actual'] ?? 0);
        $stockNuevo = $tipoAjuste === 'entrada' ? $stockAnterior + $cantidad : $stockAnterior - $cantidad;

        try {
            $ajusteId = $inventario->crearAjuste([
                'empresa_id' => $empresaId,
                'producto_id' => $productoId,
                'tipo_ajuste' => $tipoAjuste,
                'cantidad' => $cantidad,
                'motivo' => trim((string) ($_POST['motivo'] ?? 'ajuste_manual')),
                'observacion' => trim((string) ($_POST['observacion'] ?? '')),
                'usuario_id' => (int) ($usuario['id'] ?? 0),
            ], $inventario->obtenerAjustePermitirNegativo($empresaId));

            (new ServicioAlertaStock())->evaluarYNotificar($empresaId, $productoId, $stockAnterior, max(0, $stockNuevo), (string) ($usuario['nombre'] ?? ''));
            flash('success', 'Ajuste registrado correctamente.');
            $this->redirigir('/app/inventario/ajustes/ver/' . $ajusteId);
        } catch (Throwable $e) {
            flash('danger', 'No fue posible registrar el ajuste: ' . $e->getMessage());
            $this->redirigir('/app/inventario/ajustes');
        }
    }

    public function verAjuste(int $id): void
    {
        $this->validarPermiso('inventario_ver_ajustes');
        $ajuste = (new Inventario())->obtenerAjuste((int) empresa_actual_id(), $id);
        if (!$ajuste) {
            http_response_code(404);
            exit('Ajuste no encontrado.');
        }
        $this->vista('empresa/inventario/ajuste_ver', compact('ajuste'), 'empresa');
    }


    public function proveedores(): void
    {
        $this->validarPermiso('inventario_ver_recepciones');
        $empresaId = (int) empresa_actual_id();
        $proveedores = (new Inventario())->listarProveedores($empresaId);
        $this->vista('empresa/inventario/proveedores', compact('proveedores'), 'empresa');
    }

    public function guardarProveedor(): void
    {
        $this->validarPermiso('inventario_crear_recepciones');
        validar_csrf();
        $empresaId = (int) empresa_actual_id();
        $nombre = trim((string) ($_POST['nombre'] ?? ''));
        if ($nombre === '') {
            flash('danger', 'El nombre del proveedor es obligatorio.');
            $this->redirigir('/app/inventario/proveedores');
        }

        (new Inventario())->crearProveedor($empresaId, [
            'nombre' => $nombre,
            'identificador_fiscal' => trim((string) ($_POST['identificador_fiscal'] ?? '')),
            'contacto' => trim((string) ($_POST['contacto'] ?? '')),
            'correo' => trim((string) ($_POST['correo'] ?? '')),
            'telefono' => trim((string) ($_POST['telefono'] ?? '')),
            'direccion' => trim((string) ($_POST['direccion'] ?? '')),
            'ciudad' => trim((string) ($_POST['ciudad'] ?? '')),
            'observacion' => trim((string) ($_POST['observacion'] ?? '')),
            'estado' => ($_POST['estado'] ?? 'activo') === 'inactivo' ? 'inactivo' : 'activo',
        ]);

        flash('success', 'Proveedor creado correctamente.');
        $this->redirigir((string) ($_POST['redirect_to'] ?? '/app/inventario/proveedores'));
    }

    public function movimientos(): void
    {
        $this->validarPermiso('inventario_ver_movimientos');
        $empresaId = (int) empresa_actual_id();
        $productoId = (int) ($_GET['producto_id'] ?? 0) ?: null;
        $inventario = new Inventario();

        $movimientos = $inventario->listarMovimientos($empresaId, $productoId);
        $productos = $inventario->listarProductos($empresaId);

        $this->vista('empresa/inventario/movimientos', compact('movimientos', 'productos', 'productoId'), 'empresa');
    }
}
