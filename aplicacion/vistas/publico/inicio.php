<section class="hero py-5 bg-white border-bottom">
    <div class="container">
        <div class="row align-items-center g-4">
            <div class="col-lg-7">
                <span class="badge bg-info-subtle text-info-emphasis mb-2">Software de cotizaciones para empresas</span>
                <h1 class="display-6 fw-bold">El sistema de cotizaciones que te ayuda a vender más, con orden y sin depender de Excel</h1>
                <p class="lead text-secondary">Cotiza en minutos, controla tu proceso comercial y da seguimiento real a cada oportunidad desde una sola plataforma en la nube.</p>
                <div class="d-flex flex-wrap gap-2">
                    <a href="<?= e(url('/planes')) ?>" class="btn btn-primary btn-sm">Ver planes</a>
                    <a href="<?= e(url('/registro')) ?>" class="btn btn-outline-primary btn-sm">Crear cuenta</a>
                    <a href="<?= e(url('/contacto')) ?>" class="btn btn-outline-secondary btn-sm">Solicitar una demo</a>
                </div>
                <p class="small text-secondary mt-3 mb-0">Ideal para equipos comerciales, pymes y empresas que buscan cotizaciones profesionales, control de ventas y mejor conversión.</p>
            </div>
            <div class="col-lg-5">
                <div class="card card-soft">
                    <div class="card-body">
                        <h2 class="h6">Resultados que valoran nuestros clientes</h2>
                        <ul class="small mb-0">
                            <li>Reducción de tiempos al crear cotizaciones</li>
                            <li>Menos errores en precios, impuestos y totales</li>
                            <li>Seguimiento claro por estado: enviada, aceptada o rechazada</li>
                            <li>Proceso comercial más ordenado para toda la empresa</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <h2 class="h4 mb-3">Una herramienta comercial hecha para trabajar mejor</h2>
        <p class="text-secondary mb-4">Este programa para hacer cotizaciones combina velocidad, control y profesionalismo. Te permite centralizar clientes, productos y vendedores para que tu empresa tenga un sistema de ventas más predecible y fácil de administrar.</p>
        <div class="row g-3 small">
            <div class="col-md-4"><div class="card h-100"><div class="card-body"><strong>Más productividad</strong><p class="mb-0">Crea cotizaciones en pocos pasos y reutiliza información de clientes y productos sin repetir tareas.</p></div></div></div>
            <div class="col-md-4"><div class="card h-100"><div class="card-body"><strong>Control de cotizaciones</strong><p class="mb-0">Visualiza vencimientos, estados y oportunidades activas para tomar decisiones comerciales con datos.</p></div></div></div>
            <div class="col-md-4"><div class="card h-100"><div class="card-body"><strong>Imagen profesional</strong><p class="mb-0">Entrega propuestas claras y ordenadas que aumentan la confianza del cliente y mejoran tus cierres.</p></div></div></div>
        </div>
    </div>
</section>

<section class="py-5 bg-white border-top border-bottom">
    <div class="container">
        <h2 class="h4 mb-3">¿Cómo funciona el sistema de cotizaciones?</h2>
        <div class="row g-3 small">
            <div class="col-md-3"><div class="card h-100"><div class="card-body"><strong>1. Configura tu empresa</strong><p class="mb-0">Carga tus datos, define usuarios y deja tu cuenta lista en poco tiempo.</p></div></div></div>
            <div class="col-md-3"><div class="card h-100"><div class="card-body"><strong>2. Registra catálogo y clientes</strong><p class="mb-0">Agrega productos, servicios y cartera comercial para cotizar con rapidez.</p></div></div></div>
            <div class="col-md-3"><div class="card h-100"><div class="card-body"><strong>3. Genera y envía cotizaciones</strong><p class="mb-0">Calcula totales automáticamente y comparte propuestas por correo o PDF.</p></div></div></div>
            <div class="col-md-3"><div class="card h-100"><div class="card-body"><strong>4. Da seguimiento y cierra ventas</strong><p class="mb-0">Controla qué cotizaciones avanzan y enfoca al equipo en oportunidades reales.</p></div></div></div>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <h2 class="h4 mb-3">Sección técnica simple</h2>
        <div class="row g-3 small">
            <div class="col-md-6"><div class="card h-100"><div class="card-body"><strong>Acceso en la nube</strong><p class="mb-0">Funciona desde navegador, sin instalaciones complejas. Puedes usar el sistema desde oficina, casa o visita comercial.</p></div></div></div>
            <div class="col-md-6"><div class="card h-100"><div class="card-body"><strong>Arquitectura multiempresa</strong><p class="mb-0">Cada empresa gestiona su información de forma organizada, con usuarios y permisos para un trabajo seguro y estructurado.</p></div></div></div>
            <div class="col-md-6"><div class="card h-100"><div class="card-body"><strong>Automatización comercial</strong><p class="mb-0">El software de cotizaciones calcula subtotales, impuestos y totales para reducir errores y acelerar respuestas al cliente.</p></div></div></div>
            <div class="col-md-6"><div class="card h-100"><div class="card-body"><strong>Escalable para crecimiento</strong><p class="mb-0">Puedes empezar con operación pequeña y ampliar el uso conforme tu empresa crece en clientes, productos y equipo de ventas.</p></div></div></div>
        </div>
    </div>
</section>

<section class="py-5 border-top">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="h4 mb-0">Planes y precios</h2>
            <a href="<?= e(url('/planes')) ?>" class="btn btn-outline-primary btn-sm">Comparar planes</a>
        </div>
        <p class="text-secondary small">Elige el plan que mejor se adapte a tu etapa comercial. Puedes iniciar rápido y escalar cuando tu operación lo necesite.</p>
        <div class="row g-3 mt-1">
            <?php foreach ($planes as $plan): ?>
                <div class="col-md-4">
                    <div class="card h-100 border-2" style="border-color: <?= e($plan['color_visual']) ?> !important;">
                        <div class="card-body">
                            <h3 class="h5"><?= e($plan['nombre']) ?></h3>
                            <p class="small text-secondary"><?= e($plan['resumen_comercial']) ?></p>
                            <div class="h4">$<?= number_format((float)$plan['precio_mensual'], 2) ?><small class="fs-6">/mes</small></div>
                            <a class="btn btn-primary btn-sm w-100" href="<?= e(url('/contratar/' . $plan['slug'])) ?>">Contratar plan</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php
$faqs = [
    ['q' => '¿Qué es un sistema de cotizaciones?', 'a' => 'Un sistema de cotizaciones es un software que permite crear, organizar y enviar propuestas comerciales de forma ordenada. Ayuda a tu empresa a trabajar con información centralizada, reducir errores y responder más rápido a cada oportunidad de ventas.'],
    ['q' => '¿Para qué sirve un sistema de cotizaciones?', 'a' => 'Sirve para estandarizar cómo tu empresa prepara cotizaciones, controlar precios y mantener seguimiento de cada propuesta. Con un proceso claro, el equipo comercial puede atender más clientes y cerrar ventas con mayor confianza.'],
    ['q' => '¿Cómo mejorar el proceso de cotización en una empresa?', 'a' => 'La forma más efectiva es digitalizarlo con un software de cotizaciones que centralice clientes, productos y estados. Así eliminas tareas manuales, aceleras respuestas y mejoras la calidad de tus cotizaciones para empresas.'],
    ['q' => '¿Por qué usar un sistema en lugar de Excel?', 'a' => 'Excel ayuda al inicio, pero se vuelve limitado cuando crecen los clientes y el volumen comercial. Un sistema de cotizaciones brinda control por estado, automatiza cálculos y evita desorden entre versiones o archivos sueltos.'],
    ['q' => '¿Este sistema sirve para mi tipo de negocio?', 'a' => 'Sí, está pensado para empresas que venden productos, servicios o ambos y necesitan cotizaciones profesionales. Si tu operación comercial requiere orden, seguimiento y rapidez, este sistema se adapta bien.'],
    ['q' => '¿Puedo usar el sistema desde cualquier lugar?', 'a' => 'Sí. Al ser una plataforma en la nube, puedes acceder desde cualquier lugar con internet y navegador. Esto facilita trabajar en oficina, remoto o en terreno sin perder continuidad en las cotizaciones.'],
    ['q' => '¿Necesito instalar algo en mi computador?', 'a' => 'No necesitas instalaciones complejas ni mantenimiento local. Solo ingresas al sistema desde tu navegador y empiezas a trabajar, lo que simplifica la adopción para tu empresa y tu equipo comercial.'],
    ['q' => '¿El sistema funciona en celular o tablet?', 'a' => 'Sí, puedes revisar y gestionar cotizaciones desde dispositivos móviles compatibles con navegador. Es útil para vendedores que necesitan consultar datos o responder clientes mientras están fuera de la oficina.'],
    ['q' => '¿Qué tan difícil es usar el sistema?', 'a' => 'El uso es simple y está orientado al trabajo diario de ventas. La interfaz busca que cualquier persona del equipo pueda crear cotizaciones, buscar clientes y dar seguimiento sin curva técnica compleja.'],
    ['q' => '¿Cuánto tiempo toma comenzar a usarlo?', 'a' => 'Normalmente puedes empezar en poco tiempo, una vez cargues datos básicos como clientes y productos. Desde ahí, tu empresa ya puede generar cotizaciones profesionales y operar con mayor orden comercial.'],
    ['q' => '¿Puedo registrar mis propios productos y servicios?', 'a' => 'Sí. El sistema permite crear y mantener tu propio catálogo para cotizar con datos actualizados. Esto mejora la consistencia de precios y evita errores al momento de preparar propuestas para clientes.'],
    ['q' => '¿Puedo agregar clientes fácilmente?', 'a' => 'Sí, puedes incorporar clientes de forma rápida para mantener tu cartera organizada. Al centralizar esta información, cada cotización sale con datos correctos y el proceso de ventas se vuelve más fluido.'],
    ['q' => '¿El sistema calcula automáticamente impuestos y totales?', 'a' => 'Sí, el software de cotizaciones automatiza cálculos de subtotales, impuestos y total final. Esto reduce errores manuales, acelera la preparación de propuestas y mejora la confiabilidad frente al cliente.'],
    ['q' => '¿Puedo generar cotizaciones en PDF?', 'a' => 'Sí, puedes generar cotizaciones en PDF para compartir documentos claros y profesionales. Este formato facilita enviar propuestas, presentarlas formalmente y conservar respaldo comercial en tu empresa.'],
    ['q' => '¿Puedo enviar cotizaciones por correo?', 'a' => 'Sí, el sistema facilita el envío de cotizaciones por correo para acelerar la atención comercial. De esta manera reduces tiempos de respuesta y mantienes trazabilidad en la comunicación con tus clientes.'],
    ['q' => '¿El sistema permite hacer seguimiento a las cotizaciones?', 'a' => 'Sí, puedes controlar el estado de cada cotización y su avance dentro del proceso de ventas. Esto ayuda a priorizar oportunidades activas y tomar decisiones comerciales más oportunas.'],
    ['q' => '¿Puedo saber qué cotizaciones fueron aceptadas o rechazadas?', 'a' => 'Sí, el control de estados te permite identificar cotizaciones aceptadas, rechazadas o pendientes. Con esa visibilidad, tu empresa puede medir resultados y ajustar su estrategia comercial con datos reales.'],
    ['q' => '¿Puedo trabajar con varios vendedores?', 'a' => 'Sí, el sistema está preparado para equipos comerciales con varios usuarios. Cada vendedor puede gestionar su trabajo y la empresa mantiene una visión general del desempeño y las oportunidades.'],
    ['q' => '¿El sistema permite controlar inventario?', 'a' => 'Puedes apoyar el control comercial relacionado al catálogo y disponibilidad según la configuración del plan. Esto ayuda a cotizar con mayor precisión y reducir promesas de venta fuera de capacidad real.'],
    ['q' => '¿Se pueden gestionar órdenes de compra?', 'a' => 'Sí, puedes incorporar la gestión de órdenes dentro del flujo comercial según los módulos habilitados. Esto mejora la continuidad entre cotización, aprobación y ejecución en tu empresa.'],
    ['q' => '¿Mis datos están seguros?', 'a' => 'Sí, la información comercial se gestiona con estructura por empresa y controles de acceso por usuario. Esto entrega mayor orden y seguridad que trabajar con archivos dispersos o compartidos sin control.'],
    ['q' => '¿Qué pasa si pierdo mi información actual?', 'a' => 'Antes de migrar, se puede planificar una carga ordenada para proteger tus datos y evitar pérdidas. El objetivo del sistema es justamente centralizar la información para que no dependa de archivos aislados.'],
    ['q' => '¿Puedo migrar desde Excel u otro sistema?', 'a' => 'Sí, muchas empresas comienzan importando datos desde Excel u otras fuentes. Esta transición permite pasar de un proceso manual a un sistema de cotizaciones más estable, trazable y profesional.'],
    ['q' => '¿El sistema se actualiza con el tiempo?', 'a' => 'Sí, el software evoluciona para mantener mejoras funcionales y operativas. Esto permite que tu empresa cuente con una herramienta vigente, adaptada a necesidades comerciales cambiantes.'],
    ['q' => '¿Puedo cambiar de plan más adelante?', 'a' => 'Sí, puedes ajustar tu plan según el momento de tu empresa y el crecimiento del equipo. Así pagas por lo que realmente necesitas y mantienes flexibilidad operativa.'],
    ['q' => '¿Hay algún tipo de contrato o permanencia?', 'a' => 'La estructura comercial está pensada para dar claridad en planes y condiciones. Puedes evaluar la opción más conveniente para tu negocio sin compromisos innecesarios de largo plazo.'],
    ['q' => '¿Qué tipo de soporte incluye el sistema?', 'a' => 'Incluye acompañamiento para resolver dudas de uso y facilitar la adopción del sistema. Un buen soporte acelera resultados, evita bloqueos operativos y mejora la experiencia de tu equipo.'],
    ['q' => '¿El sistema sirve para empresas pequeñas?', 'a' => 'Sí, es ideal para pymes que buscan ordenar cotizaciones y profesionalizar ventas sin procesos complejos. Permite empezar con una operación simple y crecer sobre una base organizada.'],
    ['q' => '¿Puede escalar si mi empresa crece?', 'a' => 'Sí, el sistema está diseñado para acompañar el crecimiento en clientes, productos y usuarios. Esto evita cambiar de herramienta constantemente y protege la continuidad del proceso comercial.'],
    ['q' => '¿Cómo ayuda este sistema a vender más?', 'a' => 'Ayuda a vender más porque acelera respuestas, mejora la presentación de propuestas y permite seguimiento oportuno. Con mejor control de cotizaciones, aumentan las oportunidades de cierre.'],
    ['q' => '¿Cómo ayuda este sistema a ordenar mi empresa?', 'a' => 'Centraliza la información comercial en un solo lugar para evitar desorden entre planillas y correos. Tu empresa gana visibilidad, estandariza su proceso y reduce errores operativos.'],
    ['q' => '¿Vale la pena implementar un sistema de cotizaciones?', 'a' => 'Sí, especialmente cuando buscas crecer con un proceso comercial más profesional y medible. La inversión se refleja en ahorro de tiempo, mejor control y mayor capacidad de respuesta al cliente.'],
    ['q' => '¿Qué beneficios tiene digitalizar el proceso comercial?', 'a' => 'Digitalizar cotizaciones y seguimiento mejora la velocidad, la trazabilidad y la calidad del servicio. También permite tomar decisiones con datos y construir un sistema de ventas más eficiente.'],
    ['q' => '¿Este sistema reemplaza Excel completamente?', 'a' => 'En la mayoría de escenarios comerciales, sí, porque concentra las tareas clave de cotización en una sola plataforma. Así reduces dependencia de archivos manuales y ganas control operativo en la empresa.'],
    ['q' => '¿Qué diferencia este sistema de otros?', 'a' => 'Se enfoca en uso práctico, control de cotizaciones y orden comercial para empresas reales, no solo en funciones aisladas. Combina rapidez, seguimiento y claridad para apoyar ventas de forma consistente.'],
];
?>

<section class="py-5 bg-white border-top border-bottom">
    <div class="container">
        <h2 class="h4 mb-2">Preguntas frecuentes</h2>
        <p class="text-secondary small mb-4">Resolvemos las dudas más comunes sobre sistema de cotizaciones, software de ventas y control comercial para empresas.</p>
        <div class="accordion" id="faq-seo">
            <?php foreach ($faqs as $indice => $faq): ?>
                <?php
                    $faqId = 'faq-' . ($indice + 1);
                    $faqHeading = 'faq-heading-' . ($indice + 1);
                    $expandido = $indice === 0;
                ?>
                <div class="accordion-item">
                    <h3 class="accordion-header" id="<?= e($faqHeading) ?>">
                        <button class="accordion-button <?= $expandido ? '' : 'collapsed' ?>" type="button" data-bs-toggle="collapse" data-bs-target="#<?= e($faqId) ?>" aria-expanded="<?= $expandido ? 'true' : 'false' ?>" aria-controls="<?= e($faqId) ?>">
                            <?= e($faq['q']) ?>
                        </button>
                    </h3>
                    <div id="<?= e($faqId) ?>" class="accordion-collapse collapse <?= $expandido ? 'show' : '' ?>" aria-labelledby="<?= e($faqHeading) ?>" data-bs-parent="#faq-seo">
                        <div class="accordion-body small text-secondary">
                            <?= e($faq['a']) ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container text-center">
        <h2 class="h4">Profesionaliza tus cotizaciones y acelera tus ventas desde hoy</h2>
        <p class="text-secondary">Da el siguiente paso hacia un proceso comercial más claro, medible y escalable para tu empresa.</p>
        <div class="d-flex justify-content-center gap-2 flex-wrap">
            <a href="<?= e(url('/registro')) ?>" class="btn btn-primary btn-sm">Comenzar ahora</a>
            <a href="<?= e(url('/planes')) ?>" class="btn btn-outline-primary btn-sm">Ver planes</a>
            <a href="<?= e(url('/contacto')) ?>" class="btn btn-outline-secondary btn-sm">Hablar con ventas</a>
        </div>
    </div>
</section>
