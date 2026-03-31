<section class="hero py-5 bg-white border-bottom">
    <div class="container">
        <div class="row align-items-center g-4">
            <div class="col-lg-7">
                <span class="badge bg-info-subtle text-info-emphasis mb-2">Sistema de gestión comercial con POS + inventario</span>
                <h1 class="display-6 fw-bold">No es solo para cotizar: es para vender más, trabajar con orden y decidir con datos reales</h1>
                <p class="lead text-secondary">Centraliza cotizaciones, ventas e inventario en un solo sistema para evitar errores, mejorar el control diario y crecer con una operación profesional.</p>
                <div class="d-flex flex-wrap gap-2">
                    <a href="#planes" class="btn btn-primary btn-sm">Ver planes</a>
                    <a href="<?= e(url('/registro')) ?>" class="btn btn-outline-primary btn-sm">Comenzar ahora</a>
                    <a href="<?= e(url('/contacto')) ?>" class="btn btn-outline-secondary btn-sm">Hablar con ventas</a>
                </div>
                <p class="small text-secondary mt-3 mb-0">Cuando cotizaciones, POS e inventario están conectados, tu negocio gana velocidad, control y claridad para crecer sin improvisar.</p>
            </div>
            <div class="col-lg-5">
                <div class="card card-soft h-100">
                    <div class="card-body">
                        <h2 class="h6 mb-3">Impacto operativo en el día a día</h2>
                        <ul class="small mb-0 ps-3 d-grid gap-2">
                            <li>Sin control de stock pierdes ventas y credibilidad frente al cliente.</li>
                            <li>Sin sistema aumentan errores en precios, productos y procesos.</li>
                            <li>Sin seguimiento comercial se enfrían oportunidades y se pierden cierres.</li>
                            <li>Sin datos no puedes detectar qué vender más, ni dónde ajustar.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="planes" class="py-5 bg-white border-bottom">
    <div class="container">
        <div class="text-center mb-4">
            <h2 class="h3 mb-2">Planes diseñados por nivel de control y gestión</h2>
            <p class="text-secondary mb-0">Todos incluyen cotizaciones, inventario y punto de venta. La diferencia está en cuánto control tienes para crecer.</p>
        </div>
        <div class="row g-3 align-items-stretch">
            <div class="col-12 col-lg-4">
                <div class="card h-100 border-2">
                    <div class="card-body d-flex flex-column">
                        <h3 class="h5">Básico</h3>
                        <p class="text-secondary small">Comienza a ordenar tu negocio.</p>
                        <div class="h3 mb-0">$15.000 <small class="fs-6">/ mensual</small></div>
                        <p class="small text-secondary">10% descuento anual</p>
                        <ul class="small ps-3 d-grid gap-1">
                            <li>Gestión de clientes, productos y servicios</li>
                            <li>Cotizaciones + PDF + envío</li>
                            <li>Punto de venta (ventas simples)</li>
                            <li>Control básico de inventario</li>
                            <li>Registro de ventas</li>
                        </ul>
                        <p class="small mb-2"><strong>Limitado para crecer:</strong> sin seguimiento comercial, sin reportes avanzados, sin análisis de ventas, sin automatizaciones y sin control de equipo.</p>
                        <div class="d-grid gap-2 mt-auto">
                            <a href="<?= e(url('/registro')) ?>" class="btn btn-outline-primary btn-sm">Comenzar ahora</a>
                            <a href="<?= e(url('/contratar/plan-inicial')) ?>" class="btn btn-primary btn-sm">Contratar</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-4">
                <div class="card h-100 border-primary border-3 shadow">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex flex-wrap gap-2 mb-2">
                            <span class="badge text-bg-primary">RECOMENDADO</span>
                            <span class="badge text-bg-success">MÁS ELEGIDO</span>
                        </div>
                        <h3 class="h5">Profesional</h3>
                        <p class="text-secondary small">Aquí es donde tu negocio empieza a funcionar de verdad.</p>
                        <div class="h3 mb-0">$26.000 <small class="fs-6">/ mensual</small></div>
                        <p class="small text-secondary">10% descuento anual</p>
                        <ul class="small ps-3 d-grid gap-1">
                            <li>Todo lo del plan Básico</li>
                            <li>Inventario completo y control de stock real</li>
                            <li>Alertas de stock, recepciones y ajustes</li>
                            <li>Seguimiento comercial y gestión de vendedores</li>
                            <li>Reportes de ventas e historial completo</li>
                            <li>Mayor control del negocio para crecer con orden</li>
                        </ul>
                        <p class="small mb-2"><strong>Mejor relación valor/control:</strong> por una diferencia menor frente al básico, obtienes el nivel de gestión que evita pérdidas por desorden.</p>
                        <div class="d-grid gap-2 mt-auto">
                            <a href="<?= e(url('/registro')) ?>" class="btn btn-primary btn-sm">Comenzar ahora</a>
                            <a href="<?= e(url('/contratar/plan-profesional')) ?>" class="btn btn-dark btn-sm">Contratar</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-4">
                <div class="card h-100 border-2">
                    <div class="card-body d-flex flex-column">
                        <h3 class="h5">Empresa / Premium</h3>
                        <p class="text-secondary small">Control total para empresas que necesitan crecer sin perder el control.</p>
                        <div class="h3 mb-0">$55.000 <small class="fs-6">/ mensual</small></div>
                        <p class="small text-secondary">15% descuento anual</p>
                        <ul class="small ps-3 d-grid gap-1">
                            <li>Todo lo del plan Profesional</li>
                            <li>POS completo con control de caja</li>
                            <li>Apertura y cierre de caja</li>
                            <li>Inventario avanzado y órdenes de compra</li>
                            <li>Gestión comercial completa y reportes avanzados</li>
                            <li>Control multiusuario, configuración avanzada y automatizaciones</li>
                        </ul>
                        <div class="d-grid gap-2 mt-auto">
                            <a href="<?= e(url('/registro')) ?>" class="btn btn-outline-primary btn-sm">Comenzar ahora</a>
                            <a href="<?= e(url('/contratar/plan-corporativo')) ?>" class="btn btn-primary btn-sm">Contratar</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <p class="small text-secondary mt-3 mb-0 text-center">Si buscas crecer, controlar stock y tomar decisiones con datos, el plan Profesional suele ser la decisión más rentable.</p>
    </div>
</section>

<section class="py-5 border-bottom">
    <div class="container">
        <div class="row g-4">
            <div class="col-12 col-lg-6">
                <h2 class="h4 mb-2">Impacto operativo: sin sistema vs con sistema</h2>
                <p class="small text-secondary mb-3">Referencia realista sobre tiempos, errores y cierres cuando se integra cotización + POS + inventario.</p>
                <div class="card h-100">
                    <div class="card-body">
                        <canvas id="graficoComparativoLanding" height="230"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6">
                <h2 class="h4 mb-2">Evolución de control comercial e inventario</h2>
                <p class="small text-secondary mb-3">Al operar con datos en tiempo real, el negocio sostiene crecimiento con menos errores y mejor respuesta.</p>
                <div class="card h-100">
                    <div class="card-body">
                        <canvas id="graficoEvolucionLanding" height="230"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
(() => {
    if (typeof Chart === 'undefined') return;

    const mobile = window.innerWidth < 768;

    const comparativo = document.getElementById('graficoComparativoLanding');
    if (comparativo) {
        new Chart(comparativo, {
            type: 'bar',
            data: {
                labels: ['Tiempo por venta (min)', 'Errores operativos (%)', 'Cierres mensuales'],
                datasets: [
                    { label: 'Sin sistema', data: [28, 19, 15], backgroundColor: '#9aa9bc', borderRadius: 8 },
                    { label: 'Con sistema', data: [11, 5, 31], backgroundColor: '#0d6efd', borderRadius: 8 }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: mobile ? 'top' : 'bottom',
                        labels: { boxWidth: 10, font: { size: mobile ? 10 : 12 } }
                    }
                },
                scales: {
                    y: { beginAtZero: true, ticks: { font: { size: mobile ? 10 : 12 } } },
                    x: { ticks: { font: { size: mobile ? 10 : 12 } } }
                }
            }
        });
    }

    const evolucion = document.getElementById('graficoEvolucionLanding');
    if (evolucion) {
        new Chart(evolucion, {
            type: 'line',
            data: {
                labels: ['Mes 1', 'Mes 2', 'Mes 3', 'Mes 4', 'Mes 5', 'Mes 6'],
                datasets: [{
                    label: 'Mejora acumulada de control y eficiencia (%)',
                    data: [6, 12, 19, 27, 34, 41],
                    fill: true,
                    tension: 0.35,
                    borderColor: '#198754',
                    backgroundColor: 'rgba(25,135,84,.15)',
                    pointRadius: mobile ? 2 : 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: mobile ? 'top' : 'bottom',
                        labels: { boxWidth: 10, font: { size: mobile ? 10 : 12 } }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { callback: (v) => v + '%', font: { size: mobile ? 10 : 12 } }
                    },
                    x: { ticks: { font: { size: mobile ? 10 : 12 } } }
                }
            }
        });
    }
})();
</script>

<section class="py-5 border-bottom">
    <div class="container">
        <div class="row g-4">
            <div class="col-12 col-lg-6">
                <h2 class="h4 mb-3">Qué pasa cuando NO tienes un sistema</h2>
                <div class="card h-100 border-danger-subtle">
                    <div class="card-body">
                        <ul class="mb-0 small ps-3 d-grid gap-2">
                            <li>Pierdes ventas por no saber qué tienes disponible en stock.</li>
                            <li>No sabes cuánto ganas realmente por producto, vendedor o período.</li>
                            <li>Vendes sin inventario actualizado y luego debes resolver reclamos.</li>
                            <li>Trabajas desordenado con múltiples planillas, chats y notas sueltas.</li>
                            <li>Dependes de Excel para tareas críticas que requieren control en tiempo real.</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6">
                <h2 class="h4 mb-3">Qué cambia cuando usas este sistema</h2>
                <div class="card h-100 border-success-subtle">
                    <div class="card-body">
                        <ul class="mb-0 small ps-3 d-grid gap-2">
                            <li>Ves ventas en tiempo real y tomas decisiones con información confiable.</li>
                            <li>Reduces errores humanos al automatizar cálculo, registro y seguimiento.</li>
                            <li>Controlas inventario y evitas vender productos sin disponibilidad.</li>
                            <li>Ordenas el trabajo comercial para responder más rápido y cerrar mejor.</li>
                            <li>Mejoras la experiencia del cliente con procesos claros y profesionales.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5 bg-white border-bottom">
    <div class="container">
        <h2 class="h4 mb-3">Beneficios reales para el negocio</h2>
        <p class="text-secondary mb-4">Este sistema está diseñado para operar mejor cada día: vender más, evitar errores y mantener control comercial e inventario sin perder tiempo.</p>
        <div class="row g-3 small">
            <div class="col-12 col-md-6 col-lg-4"><div class="card h-100"><div class="card-body"><strong>Control en tiempo real</strong><p class="mb-0">Consulta ventas, stock y movimiento comercial sin esperar cierres manuales.</p></div></div></div>
            <div class="col-12 col-md-6 col-lg-4"><div class="card h-100"><div class="card-body"><strong>Menos errores</strong><p class="mb-0">Estandariza procesos para evitar fallas de carga, cálculos y duplicidades.</p></div></div></div>
            <div class="col-12 col-md-6 col-lg-4"><div class="card h-100"><div class="card-body"><strong>Más velocidad de venta</strong><p class="mb-0">Cotiza, cobra y registra más rápido para atender más oportunidades.</p></div></div></div>
            <div class="col-12 col-md-6 col-lg-4"><div class="card h-100"><div class="card-body"><strong>Mejor imagen frente al cliente</strong><p class="mb-0">Proyecta una operación profesional con documentos y respuestas consistentes.</p></div></div></div>
            <div class="col-12 col-md-6 col-lg-4"><div class="card h-100"><div class="card-body"><strong>Mejor organización</strong><p class="mb-0">Cada área trabaja con la misma información y un flujo comercial claro.</p></div></div></div>
            <div class="col-12 col-md-6 col-lg-4"><div class="card h-100"><div class="card-body"><strong>Más ventas con control</strong><p class="mb-0">Al combinar seguimiento + stock + reportes, mejoras conversión y rentabilidad.</p></div></div></div>
        </div>
    </div>
</section>

<section class="py-5 border-bottom">
    <div class="container">
        <div class="row g-4 align-items-center">
            <div class="col-12 col-lg-7">
                <h2 class="h4 mb-3">POS + inventario integrados: la base para operar con orden</h2>
                <p class="text-secondary">Aquí no gestionas ventas y stock por separado. Cada venta impacta inventario automáticamente para que siempre sepas qué tienes, qué falta y qué debes reponer.</p>
                <div class="row g-3 small mt-1">
                    <div class="col-12 col-sm-6"><div class="card h-100"><div class="card-body"><strong>Ventas conectadas al stock</strong><p class="mb-0">Cada transacción descuenta inventario y actualiza disponibilidad real.</p></div></div></div>
                    <div class="col-12 col-sm-6"><div class="card h-100"><div class="card-body"><strong>Evita quiebres y sobreventas</strong><p class="mb-0">No prometes productos sin existencia, protegiendo margen y confianza.</p></div></div></div>
                    <div class="col-12 col-sm-6"><div class="card h-100"><div class="card-body"><strong>Decisiones con datos</strong><p class="mb-0">Identifica rotación, productos críticos y oportunidades de mejora.</p></div></div></div>
                    <div class="col-12 col-sm-6"><div class="card h-100"><div class="card-body"><strong>Operación más eficiente</strong><p class="mb-0">Menos tareas manuales y más tiempo para vender y atender clientes.</p></div></div></div>
                </div>
            </div>
            <div class="col-12 col-lg-5">
                <div class="card bg-light border-0 h-100">
                    <div class="card-body">
                        <h3 class="h6">Mensaje central</h3>
                        <p class="small mb-0">Este sistema no es solo para cotizar. Es una herramienta de trabajo para vender más, evitar errores, controlar inventario, ordenar el negocio y tomar decisiones con datos reales.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5 border-bottom">
    <div class="container">
        <h2 class="h4 mb-3">Tabla comparativa de funcionalidades</h2>
        <p class="text-secondary small">Compara el avance por nivel para elegir según la madurez de tu operación.</p>
        <div class="table-responsive">
            <table class="table table-bordered align-middle small">
                <thead class="table-light">
                    <tr>
                        <th>Funcionalidad</th>
                        <th>Básico</th>
                        <th>Profesional</th>
                        <th>Empresa / Premium</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td>Cotizaciones</td><td>✔</td><td>✔</td><td>✔</td></tr>
                    <tr><td>Inventario</td><td>Básico</td><td>Completo + stock real</td><td>Avanzado + compras</td></tr>
                    <tr><td>Punto de venta</td><td>Ventas simples</td><td>Operación diaria</td><td>POS completo + caja</td></tr>
                    <tr><td>Seguimiento comercial</td><td>—</td><td>✔</td><td>✔ Avanzado</td></tr>
                    <tr><td>Gestión de vendedores</td><td>—</td><td>✔</td><td>✔ Multiusuario</td></tr>
                    <tr><td>Reportes de ventas</td><td>Básico</td><td>✔</td><td>✔ Avanzado</td></tr>
                    <tr><td>Alertas de stock</td><td>—</td><td>✔</td><td>✔</td></tr>
                    <tr><td>Ajustes y recepciones inventario</td><td>—</td><td>✔</td><td>✔</td></tr>
                    <tr><td>Automatizaciones</td><td>—</td><td>—</td><td>✔</td></tr>
                    <tr><td>Precio mensual</td><td><strong>$15.000</strong></td><td><strong>$26.000</strong></td><td><strong>$55.000</strong></td></tr>
                    <tr><td>Descuento anual</td><td>10%</td><td>10%</td><td>15%</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container text-center">
        <h2 class="h4">Convierte tu operación comercial en un sistema que realmente impulsa el negocio</h2>
        <p class="text-secondary">No se trata solo de cotizar: se trata de vender más, controlar mejor y crecer con orden.</p>
        <div class="d-flex justify-content-center gap-2 flex-wrap">
            <a href="<?= e(url('/registro')) ?>" class="btn btn-primary btn-sm">Comenzar ahora</a>
            <a href="<?= e(url('/planes')) ?>" class="btn btn-outline-primary btn-sm">Ver planes</a>
            <a href="<?= e(url('/contacto')) ?>" class="btn btn-outline-secondary btn-sm">Contratar</a>
        </div>
    </div>
</section>

<div class="d-md-none mobile-buy-bar">
    <div class="d-flex gap-2 w-100">
        <a href="#planes" class="btn btn-primary btn-sm flex-fill">Ver planes</a>
        <a href="<?= e(url('/registro')) ?>" class="btn btn-outline-secondary btn-sm flex-fill">Comenzar ahora</a>
    </div>
</div>
