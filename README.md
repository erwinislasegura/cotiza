# CotizaPro SaaS

Plataforma SaaS multiempresa para gestión comercial de cotizaciones, diseñada con arquitectura MVC propia en PHP 8+, MySQL y Bootstrap 5. Incluye landing comercial, autenticación, panel de superadministrador, panel por empresa, gestión de planes, funcionalidades, suscripciones, clientes, productos, cotizaciones, pagos y reportes base.

## Requisitos
- PHP 8.1+
- MySQL 8+
- Apache/Nginx con reescritura de URL

## Instalación rápida
1. Copia `.env.example` a `.env` y ajusta credenciales.
2. Crea la base de datos e importa:
   - `base_datos/esquema/esquema.sql`
   - `base_datos/esquema/semillas.sql`
   - `base_datos/esquema/datos_demo.sql`
3. Sirve el proyecto apuntando a `/public`.

## Credenciales demo
- Superadministrador: `superadmin@cotizapro.com` / `Demo1234*`
- Administrador empresa demo: `admin@andina.com` / `Demo1234*`
- Usuario empresa demo: `usuario@andina.com` / `Demo1234*`

## Estructura
- `aplicacion/`: núcleo MVC, controladores, modelos, vistas, servicios, middlewares y ayudantes.
- `rutas/`: rutas públicas, autenticación, admin y empresa.
- `base_datos/esquema/`: esquema SQL completo, semillas y datos demo.
- `documentacion/`: guías de instalación, módulos, base de datos y despliegue.

## Módulos principales
- Landing pública comercial y contratación.
- Registro de empresa + suscripción inicial.
- Panel superadministrador (empresas, planes, funcionalidades, suscripciones, pagos, reportes).
- Panel empresa (clientes, productos, cotizaciones, usuarios).
- Multiempresa con aislamiento por `empresa_id`.
- Validación de límites por plan desde base de datos.

## Notas de desarrollo
- Arquitectura preparada para integrar SMTP real y pasarela de pago real.
- El servicio de correo y pagos está desacoplado en `aplicacion/servicios`.
- Para seguridad base: CSRF, sesiones seguras, `password_hash`, consultas preparadas PDO, escape HTML.

## Notas de producción
- Activar HTTPS y ajustar cookies seguras.
- Configurar cron para vencimientos de suscripciones.
- Reforzar logs y monitoreo (SIEM/APM).
