<?php

namespace Aplicacion\Controladores\Empresa;

use Aplicacion\Nucleo\Controlador;
use Aplicacion\Modelos\Cotizacion;
use Aplicacion\Modelos\Empresa;
use Aplicacion\Modelos\Cliente;
use Aplicacion\Modelos\GestionComercial;

class DocumentosControlador extends Controlador
{
    public function index(): void
    {
        $empresaId = empresa_actual_id();
        $cotizaciones = (new Cotizacion())->listar($empresaId);
        $cotizacionId = (int) ($_REQUEST['cotizacion_id'] ?? 0);
        $plantillaHtml = trim((string) ($_POST['plantilla_html'] ?? ''));
        $asuntoCorreo = trim((string) ($_POST['asunto_correo'] ?? ''));
        $accion = trim((string) ($_POST['accion'] ?? 'preview'));
        $modeloComercial = new GestionComercial();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            validar_csrf();
        }

        $empresa = (new Empresa())->buscar($empresaId) ?: [];
        $cotizacion = null;
        $cliente = null;
        if ($cotizacionId > 0) {
            $cotizacion = (new Cotizacion())->obtenerPorId($empresaId, $cotizacionId);
            if ($cotizacion) {
                $cliente = (new Cliente())->obtenerPorId($empresaId, (int) ($cotizacion['cliente_id'] ?? 0));
            }
        }

        $variables = $this->armarVariablesPlantilla($empresa, $cotizacion, $cliente);
        $plantillaGuardada = $modeloComercial->obtenerPlantillaCorreoCotizacion($empresaId);

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $accion === 'guardar') {
            if ($asuntoCorreo === '') {
                $asuntoCorreo = 'Cotización {{numero_cotizacion}} - {{empresa_nombre}}';
            }
            if ($plantillaHtml === '') {
                $plantillaHtml = $this->plantillaBaseCorreo();
            }
            $modeloComercial->guardarPlantillaCorreoCotizacion($empresaId, $asuntoCorreo, $plantillaHtml);
            flash('success', 'Plantilla de correo guardada. Se aplicará en los envíos desde cotizaciones.');
            $plantillaGuardada = $modeloComercial->obtenerPlantillaCorreoCotizacion($empresaId);
        }

        if ($plantillaHtml === '') {
            $plantillaHtml = trim((string) ($plantillaGuardada['observaciones_defecto'] ?? ''));
        }
        if ($plantillaHtml === '') {
            $plantillaHtml = $this->plantillaBaseCorreo();
        }

        if ($asuntoCorreo === '') {
            $asuntoCorreo = trim((string) ($plantillaGuardada['terminos_defecto'] ?? ''));
        }
        if ($asuntoCorreo === '') {
            $asuntoCorreo = 'Cotización {{numero_cotizacion}} - {{empresa_nombre}}';
        }

        $vistaPrevia = $this->renderizarPlantilla($plantillaHtml, $variables);
        $asuntoPrevia = $this->renderizarPlantilla($asuntoCorreo, $variables);

        $this->vista('empresa/documentos/index', compact(
            'cotizaciones',
            'cotizacionId',
            'plantillaHtml',
            'asuntoCorreo',
            'asuntoPrevia',
            'vistaPrevia',
            'variables',
            'cotizacion',
            'cliente',
            'empresa'
        ), 'empresa');
    }

    private function armarVariablesPlantilla(array $empresa, ?array $cotizacion, ?array $cliente): array
    {
        $empresaNombre = trim((string) ($empresa['nombre_comercial'] ?? $empresa['razon_social'] ?? 'Tu empresa'));
        $remitenteCorreo = trim((string) ($empresa['imap_remitente_correo'] ?? '')) !== ''
            ? trim((string) ($empresa['imap_remitente_correo'] ?? ''))
            : trim((string) ($empresa['correo'] ?? ''));
        $remitenteNombre = trim((string) ($empresa['imap_remitente_nombre'] ?? '')) !== ''
            ? trim((string) ($empresa['imap_remitente_nombre'] ?? ''))
            : $empresaNombre;

        $clienteNombre = trim((string) ($cliente['razon_social'] ?? ''));
        if ($clienteNombre === '') {
            $clienteNombre = trim((string) ($cliente['nombre_comercial'] ?? ''));
        }
        if ($clienteNombre === '') {
            $clienteNombre = trim((string) ($cliente['nombre'] ?? 'Cliente'));
        }

        $correoDestino = trim((string) ($cliente['correo'] ?? 'sin-correo@cliente.com'));
        if (!filter_var($correoDestino, FILTER_VALIDATE_EMAIL)) {
            $correoDestino = 'sin-correo@cliente.com';
        }

        $numero = (string) ($cotizacion['numero'] ?? 'COT-0000');
        $total = '$' . number_format((float) ($cotizacion['total'] ?? 0), 2, ',', '.');
        $fechaVencimiento = (string) ($cotizacion['fecha_vencimiento'] ?? date('Y-m-d'));
        $urlPublica = url('/cotizacion/publica/' . (string) ($cotizacion['token_publico'] ?? '{token}'));
        $urlPdf = url('/app/cotizaciones/pdf/' . (int) ($cotizacion['id'] ?? 0));

        return [
            '{{empresa_nombre}}' => $empresaNombre,
            '{{cliente_nombre}}' => $clienteNombre,
            '{{correo_destino}}' => $correoDestino,
            '{{numero_cotizacion}}' => $numero,
            '{{total_cotizacion}}' => $total,
            '{{fecha_vencimiento}}' => $fechaVencimiento,
            '{{url_publica}}' => $urlPublica,
            '{{url_pdf}}' => $urlPdf,
            '{{remitente_nombre}}' => $remitenteNombre,
            '{{remitente_correo}}' => $remitenteCorreo,
        ];
    }

    private function renderizarPlantilla(string $html, array $variables): string
    {
        $reemplazosSeguros = [];
        foreach ($variables as $clave => $valor) {
            $reemplazosSeguros[$clave] = htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
        }

        return strtr($html, $reemplazosSeguros);
    }

    private function plantillaBaseCorreo(): string
    {
        return <<<'HTML'
<div style="font-family:Arial,Helvetica,sans-serif;background:#f5f7fb;padding:24px;color:#111827;">
  <div style="max-width:680px;margin:0 auto;background:#ffffff;border:1px solid #e5e7eb;border-radius:12px;overflow:hidden;">
    <div style="background:#0f3d77;color:#ffffff;padding:20px 24px;">
      <h2 style="margin:0;font-size:20px;">{{empresa_nombre}}</h2>
      <p style="margin:6px 0 0;font-size:13px;opacity:.9;">Envío automático de cotización</p>
    </div>
    <div style="padding:24px;">
      <p style="margin:0 0 14px;">Hola <strong>{{cliente_nombre}}</strong>,</p>
      <p style="margin:0 0 14px;line-height:1.5;">Adjuntamos la cotización <strong>{{numero_cotizacion}}</strong> por un total de <strong>{{total_cotizacion}}</strong>, con vigencia hasta el <strong>{{fecha_vencimiento}}</strong>.</p>
      <p style="margin:0 0 20px;line-height:1.5;">Puedes revisarla en línea y registrar tu decisión desde el siguiente botón:</p>
      <p style="margin:0 0 18px;">
        <a href="{{url_publica}}" style="display:inline-block;background:#0f3d77;color:#ffffff;text-decoration:none;padding:12px 18px;border-radius:8px;font-weight:600;">Ver, aceptar o rechazar cotización</a>
      </p>
      <p style="margin:0 0 8px;font-size:13px;color:#4b5563;">También puedes descargar el PDF directamente:</p>
      <p style="margin:0 0 20px;font-size:13px;"><a href="{{url_pdf}}" style="color:#0f3d77;">{{url_pdf}}</a></p>
      <p style="margin:0;font-size:12px;color:#6b7280;">Este correo fue enviado a {{correo_destino}} por {{remitente_nombre}} ({{remitente_correo}}).</p>
    </div>
  </div>
</div>
HTML;
    }
}
