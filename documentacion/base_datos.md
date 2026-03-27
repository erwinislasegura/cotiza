# Base de datos

El esquema usa una sola base compartida multiempresa y aplica `empresa_id` para aislar información de negocio.

## Tablas clave
- Seguridad: `roles`, `permisos`, `roles_permisos`, `usuarios`, `restablecimientos_contrasena`.
- SaaS comercial: `planes`, `funcionalidades`, `plan_funcionalidades`, `suscripciones`, `historial_suscripciones`, `pagos`, `logs_pagos`.
- Dominio cotizaciones: `clientes`, `contactos_cliente`, `categorias_productos`, `productos`, `cotizaciones`, `items_cotizacion`, `historial_estados_cotizacion`.
- Operación: `logs_correos`, `logs_actividad`, `configuraciones`, `configuraciones_empresa`.

## Reglas implementadas
- Numeración de cotización por empresa (`numero` único por `empresa_id`).
- Soft delete en tablas de negocio críticas.
- Relaciones y llaves foráneas para integridad.
- Índices por estado y por `empresa_id`.
