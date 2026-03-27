<h1 class="h4 mb-3">Configuración de empresa</h1>
<div class="card"><div class="card-header">Datos comerciales y formato de documentos</div><div class="card-body">
  <p class="small text-muted">Módulo base actualizado para operar como SaaS comercial. Esta pantalla centraliza razón social, moneda, impuestos, numeración y formato de documento.</p>
  <form class="row g-2" onsubmit="alert('Esta base queda lista para conectar persistencia en siguiente iteración.'); return false;">
    <div class="col-md-3"><label class="form-label">Razón social</label><input class="form-control"></div>
    <div class="col-md-3"><label class="form-label">Nombre comercial</label><input class="form-control"></div>
    <div class="col-md-2"><label class="form-label">Identificador fiscal</label><input class="form-control"></div>
    <div class="col-md-2"><label class="form-label">Moneda</label><input class="form-control" value="USD"></div>
    <div class="col-md-2"><label class="form-label">Impuesto %</label><input class="form-control" value="19"></div>
    <div class="col-md-3"><label class="form-label">Correo</label><input class="form-control"></div>
    <div class="col-md-2"><label class="form-label">Teléfono</label><input class="form-control"></div>
    <div class="col-md-3"><label class="form-label">Dirección</label><input class="form-control"></div>
    <div class="col-md-2"><label class="form-label">Ciudad</label><input class="form-control"></div>
    <div class="col-md-2"><label class="form-label">País</label><input class="form-control"></div>
    <div class="col-md-2"><label class="form-label">Prefijo cotización</label><input class="form-control" value="COT"></div>
    <div class="col-md-2"><label class="form-label">Formato</label><select class="form-select"><option>A4</option><option>Letter</option></select></div>
    <div class="col-12"><button class="btn btn-primary btn-sm">Guardar configuración</button></div>
  </form>
</div></div>
