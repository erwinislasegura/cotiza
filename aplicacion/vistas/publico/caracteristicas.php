<section class="py-5 bg-white border-bottom">
    <div class="container">
        <div class="row g-4 align-items-center">
            <div class="col-lg-8">
                <span class="badge bg-info-subtle text-info-emphasis mb-2">Página de características</span>
                <h1 class="display-6 fw-bold mb-3">Características del sistema de cotizaciones</h1>
                <p class="lead text-secondary mb-4">Conoce todas las herramientas que te permiten organizar tu proceso comercial, reducir errores y trabajar de forma más eficiente en tu empresa.</p>
                <div class="d-flex flex-wrap gap-2">
                    <a href="<?= e(url('/planes')) ?>" class="btn btn-primary btn-sm">Ver planes</a>
                    <a href="<?= e(url('/contacto')) ?>" class="btn btn-outline-secondary btn-sm">Contactar</a>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card card-soft h-100">
                    <div class="card-body">
                        <h2 class="h6">¿Qué incluye esta solución?</h2>
                        <ul class="small mb-0">
                            <li>Gestión de clientes, contactos y catálogo.</li>
                            <li>Control de cotizaciones con seguimiento comercial.</li>
                            <li>Módulos para vendedores, inventario y reportes.</li>
                            <li>Flujo de trabajo pensado para empresas reales.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <h2 class="h4 mb-3">¿Qué es y para qué sirve un sistema de cotizaciones?</h2>
        <p class="text-secondary mb-0">Un <strong>sistema de cotizaciones</strong> es una herramienta que centraliza la creación de propuestas comerciales, clientes y productos en una sola plataforma. Un <strong>software de cotizaciones</strong> te ayuda a responder más rápido, reducir errores manuales y mantener control del proceso de ventas. Por eso se ha vuelto clave para gestionar <strong>cotizaciones para empresas</strong> que necesitan orden, seguimiento y crecimiento comercial sostenido.</p>
    </div>
</section>


<section class="py-5 bg-white border-top border-bottom">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-6">
                <h2 class="h4 mb-2">Comparativo de desempeño comercial</h2>
                <p class="text-secondary small mb-3">La diferencia entre trabajar sin sistema y con una plataforma profesional se refleja en productividad y control.</p>
                <div class="card chart-card">
                    <div class="card-body">
                        <canvas id="graficoComparativoCaracteristicas" height="220"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <h2 class="h4 mb-2">Evolución de ganancias tras implementar el sistema</h2>
                <p class="text-secondary small mb-3">Visualización estilo dashboard para entender el impacto en ingresos al profesionalizar cotizaciones y seguimiento.</p>
                <div class="card chart-card">
                    <div class="card-body">
                        <canvas id="graficoGananciasCaracteristicas" height="220"></canvas>
                    </div>
                </div>
                <p class="small text-secondary mt-2 mb-0">Escenario de referencia: crecimiento acumulado de <strong>+48%</strong> en 12 meses.</p>
            </div>
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
(() => {
    if (typeof Chart === 'undefined') return;

    const comparativo = document.getElementById('graficoComparativoCaracteristicas');
    if (comparativo) {
        new Chart(comparativo, {
            type: 'bar',
            data: {
                labels: ['Cotizaciones/semana', 'Tiempo respuesta (h)', 'Seguimiento efectivo (%)'],
                datasets: [
                    { label: 'Sin sistema', data: [21, 30, 37], backgroundColor: '#97a6ba', borderRadius: 6 },
                    { label: 'Con CotizaPro', data: [58, 6, 88], backgroundColor: '#1f6feb', borderRadius: 6 }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom' } },
                scales: { y: { beginAtZero: true, grid: { color: '#edf1f7' } } }
            }
        });
    }

    const ganancias = document.getElementById('graficoGananciasCaracteristicas');
    if (ganancias) {
        new Chart(ganancias, {
            type: 'line',
            data: {
                labels: ['T1', 'T2', 'T3', 'T4'],
                datasets: [{
                    label: 'Crecimiento acumulado de ingresos (%)',
                    data: [9, 21, 34, 48],
                    fill: true,
                    tension: 0.35,
                    borderColor: '#0d6efd',
                    backgroundColor: 'rgba(13,110,253,.15)',
                    pointBackgroundColor: '#0d6efd',
                    pointRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom' } },
                scales: { y: { beginAtZero: true, ticks: { callback: (v) => v + '%' }, grid: { color: '#edf1f7' } } }
            }
        });
    }
})();
</script>

<section class="py-5 bg-white border-top border-bottom">
    <div class="container">
        <h2 class="h4 mb-4">Características funcionales del sistema</h2>
        <div class="row g-3 small">
            <div class="col-md-6 col-lg-4"><div class="card h-100"><div class="card-body"><h3 class="h6">Gestión de clientes</h3><p class="mb-0 text-secondary">Registra y organiza tu cartera en pocos pasos, con acceso rápido a la información comercial. Beneficio: menos tiempo buscando datos y más tiempo vendiendo.</p></div></div></div>
            <div class="col-md-6 col-lg-4"><div class="card h-100"><div class="card-body"><h3 class="h6">Gestión de contactos</h3><p class="mb-0 text-secondary">Asocia múltiples contactos por cliente para mejorar continuidad en llamadas, correos y seguimiento. Beneficio: mejor comunicación y menos pérdida de oportunidades.</p></div></div></div>
            <div class="col-md-6 col-lg-4"><div class="card h-100"><div class="card-body"><h3 class="h6">Productos y servicios</h3><p class="mb-0 text-secondary">Crea tu catálogo con precios y descripciones claras para cotizar sin improvisar. Beneficio: propuestas consistentes y mayor control comercial.</p></div></div></div>
            <div class="col-md-6 col-lg-4"><div class="card h-100"><div class="card-body"><h3 class="h6">Cotizaciones</h3><p class="mb-0 text-secondary">Genera cotizaciones rápido con cálculos automáticos y presentación profesional. Beneficio: respuestas más ágiles y mejor imagen frente al cliente.</p></div></div></div>
            <div class="col-md-6 col-lg-4"><div class="card h-100"><div class="card-body"><h3 class="h6">Seguimiento</h3><p class="mb-0 text-secondary">Controla estados y avance de cada oportunidad para priorizar acciones comerciales. Beneficio: mayor visibilidad de qué cotizaciones están por cerrar.</p></div></div></div>
            <div class="col-md-6 col-lg-4"><div class="card h-100"><div class="card-body"><h3 class="h6">Vendedores</h3><p class="mb-0 text-secondary">Asigna cuentas y monitorea gestión por usuario para coordinar mejor al equipo. Beneficio: control de desempeño y trabajo comercial más ordenado.</p></div></div></div>
            <div class="col-md-6 col-lg-4"><div class="card h-100"><div class="card-body"><h3 class="h6">Inventario</h3><p class="mb-0 text-secondary">Mantén control de stock para cotizar con información actualizada. Beneficio: menos errores por disponibilidad y mayor confianza del cliente.</p></div></div></div>
            <div class="col-md-6 col-lg-4"><div class="card h-100"><div class="card-body"><h3 class="h6">Órdenes de compra</h3><p class="mb-0 text-secondary">Gestiona el flujo comercial posterior a la cotización para dar continuidad al proceso. Beneficio: operación más clara entre ventas y ejecución.</p></div></div></div>
            <div class="col-md-6 col-lg-4"><div class="card h-100"><div class="card-body"><h3 class="h6">Reportes</h3><p class="mb-0 text-secondary">Consulta análisis básicos para evaluar avances y apoyar decisiones comerciales. Beneficio: mejor lectura de resultados y ajustes oportunos.</p></div></div></div>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <h2 class="h4 mb-3">Cómo se usa en la práctica</h2>
        <div class="row g-3 small">
            <div class="col-md-2"><div class="card h-100"><div class="card-body"><strong>1.</strong> Registras clientes</div></div></div>
            <div class="col-md-2"><div class="card h-100"><div class="card-body"><strong>2.</strong> Agregas productos</div></div></div>
            <div class="col-md-2"><div class="card h-100"><div class="card-body"><strong>3.</strong> Creas cotizaciones</div></div></div>
            <div class="col-md-3"><div class="card h-100"><div class="card-body"><strong>4.</strong> Haces seguimiento</div></div></div>
            <div class="col-md-3"><div class="card h-100"><div class="card-body"><strong>5.</strong> Cierras ventas</div></div></div>
        </div>
        <p class="text-secondary mt-3 mb-4">En la práctica, esto te permite trabajar con orden y sin depender de archivos sueltos, mejorando la continuidad del proceso comercial en toda tu empresa.</p>
        <div class="d-flex flex-wrap gap-2">
            <a href="<?= e(url('/planes')) ?>" class="btn btn-primary btn-sm">Ver planes</a>
            <a href="<?= e(url('/planes')) ?>" class="btn btn-outline-primary btn-sm">Contratar</a>
            <a href="<?= e(url('/contacto')) ?>" class="btn btn-outline-secondary btn-sm">Contactar</a>
        </div>
    </div>
</section>

<section class="py-5 bg-white border-top border-bottom">
    <div class="container">
        <h2 class="h4 mb-4">Beneficios clave para tu empresa</h2>
        <div class="row g-3 small">
            <div class="col-md-6"><div class="card h-100"><div class="card-body"><strong>Menos errores</strong><p class="mb-0 text-secondary">La automatización reduce fallas en cálculos y datos de cotización.</p></div></div></div>
            <div class="col-md-6"><div class="card h-100"><div class="card-body"><strong>Más rapidez</strong><p class="mb-0 text-secondary">Respondes más rápido a clientes y aprovechas mejor cada oportunidad.</p></div></div></div>
            <div class="col-md-6"><div class="card h-100"><div class="card-body"><strong>Mejor imagen profesional</strong><p class="mb-0 text-secondary">Entregas propuestas claras y consistentes que transmiten confianza.</p></div></div></div>
            <div class="col-md-6"><div class="card h-100"><div class="card-body"><strong>Más control</strong><p class="mb-0 text-secondary">Visualizas estados y resultados para tomar decisiones comerciales con criterio.</p></div></div></div>
            <div class="col-md-6"><div class="card h-100"><div class="card-body"><strong>Mejor organización</strong><p class="mb-0 text-secondary">Centralizas la gestión comercial y eliminas desorden entre archivos y correos.</p></div></div></div>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <h2 class="h4 mb-3">Sección técnica simple</h2>
        <div class="row g-3 small">
            <div class="col-md-6"><div class="card h-100"><div class="card-body"><strong>Sistema en la nube</strong><p class="mb-0 text-secondary">Puedes acceder desde cualquier lugar con conexión a internet, sin necesidad de instalar programas.</p></div></div></div>
            <div class="col-md-6"><div class="card h-100"><div class="card-body"><strong>Acceso desde cualquier dispositivo</strong><p class="mb-0 text-secondary">Trabaja desde computador, tablet o celular según tu dinámica comercial diaria.</p></div></div></div>
            <div class="col-md-6"><div class="card h-100"><div class="card-body"><strong>Información centralizada y uso seguro</strong><p class="mb-0 text-secondary">Toda la operación comercial se concentra en un solo lugar para mantener orden y control de accesos.</p></div></div></div>
            <div class="col-md-6"><div class="card h-100"><div class="card-body"><strong>Actualizaciones continuas</strong><p class="mb-0 text-secondary">El sistema evoluciona para mantener una herramienta vigente, práctica y confiable para empresas.</p></div></div></div>
        </div>
    </div>
</section>

<section class="py-5 bg-white border-top border-bottom">
    <div class="container">
        <h2 class="h4 mb-3">¿Por qué este sistema y no otro?</h2>
        <p class="text-secondary mb-0">Porque es un sistema simple, práctico y enfocado en empresas reales que necesitan resultados en el día a día. Está diseñado para ordenar la operación comercial, facilitar el trabajo del equipo y escalar contigo a medida que crecen tus ventas.</p>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <h2 class="h4 mb-3">SEO y contexto comercial</h2>
        <p class="text-secondary mb-0">Si buscas un <strong>sistema de cotizaciones para empresas</strong>, este <strong>software para cotizar</strong> funciona como una <strong>herramienta de ventas</strong> para mejorar el <strong>control de cotizaciones</strong> y fortalecer la <strong>gestión comercial</strong> sin complejidad innecesaria.</p>
    </div>
</section>

<section class="py-5 border-top">
    <div class="container text-center">
        <h2 class="h4">Empieza a trabajar con orden y mejora tu proceso comercial desde hoy.</h2>
        <p class="text-secondary">Conoce los planes, elige la opción que mejor se adapta a tu empresa y da el siguiente paso para profesionalizar tus cotizaciones.</p>
        <div class="d-flex justify-content-center gap-2 flex-wrap">
            <a href="<?= e(url('/registro')) ?>" class="btn btn-primary btn-sm">Crear cuenta</a>
            <a href="<?= e(url('/planes')) ?>" class="btn btn-outline-primary btn-sm">Ver planes</a>
        </div>
    </div>
</section>

<div class="d-md-none mobile-buy-bar">
    <div class="d-flex gap-2">
        <a href="<?= e(url('/planes')) ?>" class="btn btn-primary btn-sm">Ver planes</a>
        <a href="<?= e(url('/contacto')) ?>" class="btn btn-outline-secondary btn-sm">Contactar</a>
    </div>
</div>
