<?php

namespace Aplicacion\Middlewares;

class EmpresaMiddleware
{
    public function manejar(): void
    {
        if (!tiene_rol([
            'administrador_empresa',
            'vendedor',
            'administrativo',
            'contabilidad',
            'supervisor_comercial',
            'operaciones',
            'usuario_empresa',
        ])) {
            http_response_code(403);
            exit('Acceso restringido para usuarios de empresa.');
        }

        $rutaActual = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
        $base = base_path_url();
        if ($base !== '' && str_starts_with($rutaActual, $base . '/')) {
            $rutaActual = substr($rutaActual, strlen($base));
        }

        $mapaModuloPorRuta = [
            '/app/clientes' => 'modulo_clientes',
            '/app/productos' => 'modulo_productos',
            '/app/cotizaciones' => 'modulo_cotizaciones',
            '/app/punto-venta' => 'modulo_pos',
            '/app/inventario/recepciones' => 'modulo_recepciones',
            '/app/inventario/ajustes' => 'modulo_ajustes',
            '/app/inventario/movimientos' => 'modulo_movimientos',
            '/app/inventario/proveedores' => 'modulo_inventario',
            '/app/inventario/ordenes-compra' => 'modulo_ordenes_compra',
            '/app/contactos' => 'modulo_contactos',
            '/app/vendedores' => 'modulo_vendedores',
            '/app/categorias' => 'modulo_categorias',
            '/app/listas-precios' => 'modulo_listas_precios',
            '/app/seguimiento' => 'modulo_seguimiento',
            '/app/aprobaciones' => 'modulo_aprobaciones',
            '/app/documentos' => 'modulo_documentos',
            '/app/configuracion/envio-oc-html' => 'modulo_documentos',
            '/app/configuracion/correos-stock' => 'modulo_correos_stock',
            '/app/configuracion' => 'modulo_configuracion',
            '/app/usuarios' => 'modulo_usuarios',
            '/app/notificaciones' => 'modulo_notificaciones',
            '/app/historial' => 'modulo_historial',
        ];

        foreach ($mapaModuloPorRuta as $prefijo => $modulo) {
            if (($rutaActual === $prefijo || str_starts_with($rutaActual, $prefijo . '/'))
                && !plan_tiene_funcionalidad_empresa_actual($modulo)) {
                http_response_code(403);
                exit('Tu plan actual no incluye este módulo.');
            }
        }
    }
}
