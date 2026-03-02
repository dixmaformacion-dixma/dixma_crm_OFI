<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include "funciones/conexionBD.php";
include "funciones/funcionesAlumnos.php";
include "funciones/funcionesEmpresa.php";
include "funciones/funcionesAlumnosCursos.php";
include "funciones/funcionesContenidos.php";

session_start();
if (empty($_SESSION)) {
    header("Location: index.php");
    exit;
}

date_default_timezone_set("Europe/Madrid");
setlocale(LC_ALL, 'ES_es');

if (!isset($_GET['id'])) {
    die("Falta el parámetro 'id' (StudentCursoID).");
}

$StudentCursoID = intval($_GET['id']);
if ($StudentCursoID <= 0) {
    die("ID no válido.");
}

$conexionPDO = realizarConexion();

/* -- 1) Datos del curso de referencia -- */
$stmt = $conexionPDO->prepare(
    "SELECT N_Accion, N_Grupo, idEmpresa, Denominacion, Fecha_Inicio, Fecha_Fin, N_Horas, Modalidad
     FROM alumnocursos WHERE StudentCursoID = ?"
);
$stmt->bindValue(1, $StudentCursoID, PDO::PARAM_INT);
$stmt->execute();
$cursoRef = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$cursoRef) {
    die("Curso no encontrado.");
}

/* -- 2) Todos los trabajadores del mismo grupo -- */
$stmt2 = $conexionPDO->prepare(
    "SELECT alumnos.nombre, alumnos.apellidos, alumnos.nif
     FROM alumnocursos
     INNER JOIN alumnos ON alumnocursos.idAlumno = alumnos.idAlumno
     WHERE alumnocursos.N_Accion = ? AND alumnocursos.N_Grupo = ? AND alumnocursos.idEmpresa = ?
       AND alumnocursos.status_curso != 'baja'
     ORDER BY alumnos.apellidos ASC, alumnos.nombre ASC"
);
$stmt2->bindValue(1, $cursoRef['N_Accion'],  PDO::PARAM_STR);
$stmt2->bindValue(2, $cursoRef['N_Grupo'],   PDO::PARAM_STR);
$stmt2->bindValue(3, $cursoRef['idEmpresa'], PDO::PARAM_INT);
$stmt2->execute();
$trabajadores = $stmt2->fetchAll(PDO::FETCH_ASSOC);

/* -- 3) Datos de la empresa -- */
$stmt3 = $conexionPDO->prepare(
    "SELECT nombre, cif, calle, cp, poblacion, provincia FROM empresas WHERE idempresa = ?"
);
$stmt3->bindValue(1, $cursoRef['idEmpresa'], PDO::PARAM_INT);
$stmt3->execute();
$empresa = $stmt3->fetch(PDO::FETCH_ASSOC);
if (!$empresa) {
    $empresa = ['nombre' => '', 'cif' => '', 'calle' => '', 'cp' => '', 'poblacion' => '', 'provincia' => ''];
}

unset($conexionPDO);

/* -- 4) Contenidos -- */
$contenido = cargarContenidoAccion($cursoRef['N_Accion'], date('Y', strtotime($cursoRef['Fecha_Inicio'])));
$contenidoTexto = isset($contenido["Contenido"]) && !empty($contenido["Contenido"]) ? $contenido["Contenido"] : '';

/* -- Fechas y cálculos -- */
$fecha_inicio         = date('Y-m-d', strtotime($cursoRef['Fecha_Inicio']));
$fecha_fin            = date('Y-m-d', strtotime($cursoRef['Fecha_Fin']));
$same_date            = ($fecha_inicio === $fecha_fin);
$fecha_inicio_display = formattedDate($fecha_inicio);
$fecha_fin_display    = formattedDate($fecha_fin);
$fecha_expedicion     = date('Y-m-d', strtotime($fecha_fin . ' +2 days'));
$day_of_week = date('N', strtotime($fecha_expedicion));
if ($day_of_week == 6) $fecha_expedicion = date('Y-m-d', strtotime($fecha_expedicion . ' +2 days'));
elseif ($day_of_week == 7) $fecha_expedicion = date('Y-m-d', strtotime($fecha_expedicion . ' +1 day'));
$fecha_expedicion_display = formattedDate($fecha_expedicion);

/* -- Dirección empresa -- */
$partes = array_filter([
    trim($empresa['calle']),
    trim($empresa['cp']) . ' ' . trim($empresa['poblacion']),
    trim($empresa['provincia']),
]);
$direccionEmpresa = implode(', ', $partes);
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo htmlspecialchars('Certificado_'.$cursoRef['N_Accion'].'_'.$cursoRef['N_Grupo'].'_'.preg_replace('/[^A-Za-z0-9_]/','_', $empresa['nombre'])); ?></title>
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <script src="js/jquery.min.js"></script>
  <link rel="icon" href="images/favicon.ico">

  <style>
    * { box-sizing: border-box; }
    body { margin: 0; padding: 0; background: #ccc; }

    /* ---- Barra herramientas (oculta al imprimir) ---- */
    #toolbar {
      background: #88c743;
      border-bottom: 2px solid #6aaa30;
      padding: 8px 20px;
      display: flex;
      align-items: center;
      gap: 10px;
      position: sticky;
      top: 0;
      z-index: 300;
    }
    #toolbar h6 { margin: 0; font-weight: bold; color: #fff; font-size: 0.9rem; }

    /* ---- Página A4 portrait ---- */
    .pagina {
      width: 21cm;
      min-height: 29.7cm;
      margin: 1cm auto;
      background: #fff;
      padding: 1.5cm 1.8cm 1.8cm 1.8cm;
      font-family: Arial, sans-serif;
      font-size: 9.5pt;
      line-height: 1.5;
      color: #222;
      position: relative;
      -webkit-print-color-adjust: exact;
      print-color-adjust: exact;
    }

    /* Logo */
    .logo-area {
      text-align: right;
      margin-bottom: 0.5cm;
    }
    .logo-area img { height: 1.6cm; }

    /* Texto fijo cabecera */
    .cabecera { margin-bottom: 0.5cm; }

    /* CERTIFICA QUE */
    .certifica-title {
      font-weight: bold;
      margin-bottom: 0.3cm;
    }

    /* Lista de trabajadores */
    .lista-trabajadores {
      list-style: none;
      padding: 0;
      margin: 0 0 0.5cm 0.5cm;
    }
    .lista-trabajadores li { margin-bottom: 0.1cm; }
    .lista-trabajadores li::before {
      content: "o\00a0\00a0";
      font-size: 8pt;
    }

    /* Párrafo empresa/curso */
    .parrafo-empresa {
      margin-bottom: 0.5cm;
      text-align: justify;
    }

    /* Sección contenidos */
    .contenidos-title {
      font-weight: bold;
      margin-bottom: 0.2cm;
    }
    #contenidos-area {
      margin-bottom: 0.6cm;
      font-size: 9pt;
    }

    /* Pie */
    .footer-text { margin-bottom: 0.4cm; }
    .footer-firma {
      display: flex;
      justify-content: space-between;
      align-items: flex-end;
      margin-top: 0.3cm;
    }
    .sello img { height: 3cm; }
    .pagina-numero { font-size: 8pt; color: #888; font-style: italic; }

    /* ---- Campos editables ---- */
    [contenteditable="true"] {
      display: inline;
      outline: none;
      border-bottom: 1px dashed #aaa;
      min-width: 1cm;
      background: transparent;
    }
    [contenteditable="true"]:focus {
      background: rgba(255,255,180,0.7);
      border-radius: 2px;
    }
    [contenteditable="true"].block-field {
      display: block;
      border-bottom: none;
    }

    /* ---- li editables ---- */
    li.trabajador-item [contenteditable="true"] { width: 100%; }

  </style>

  <style media="print">
    #toolbar { display: none !important; }
    body { background: white; }
    .pagina { margin: 0 !important; box-shadow: none !important; }
    [contenteditable="true"] { border-bottom: none !important; background: transparent !important; }
    @page { size: A4 portrait; margin: 0; }
  </style>
</head>
<body>

<!-- BARRA HERRAMIENTAS -->
<div id="toolbar">
  <h6>Certificado &mdash; <?php echo htmlspecialchars($cursoRef['N_Accion']); ?> / <?php echo htmlspecialchars($cursoRef['N_Grupo']); ?></h6>
  <button class="btn btn-dark btn-sm ms-auto" onclick="window.print()">
    🖨️ Imprimir / Guardar PDF
  </button>
  <button class="btn btn-secondary btn-sm" onclick="window.close()">Cerrar</button>
</div>

<!-- PÁGINA -->
<div class="pagina">

  <!-- Logo Dixma -->
  <div class="logo-area">
    <img src="images/logoWord.jpg" alt="DIXMA">
  </div>

  <!-- Cabecera fija -->
  <div class="cabecera">
    Dña. María José Domínguez Míguez con DNI 36144732-W como representante legal de DIXMA
    con CIF: E27876325 y con domicilio en Ctra. de Madrid, 152 - 36318 - Vigo.
  </div>

  <!-- CERTIFICA QUE -->
  <div class="certifica-title">CERTIFICA QUE:</div>

  <!-- Lista editable de trabajadores -->
  <ul class="lista-trabajadores" id="lista-trabajadores">
    <?php foreach ($trabajadores as $t): ?>
    <li class="trabajador-item">
      <span contenteditable="true"><?php echo htmlspecialchars(mb_strtoupper($t['nombre'].' '.$t['apellidos'], 'UTF-8')); ?></span>
      &nbsp;con DNI:&nbsp;<span contenteditable="true"><?php echo htmlspecialchars($t['nif']); ?></span>
    </li>
    <?php endforeach; ?>
  </ul>

  <!-- Párrafo empresa / curso -->
  <div class="parrafo-empresa">
    Trabajadores de la empresa&nbsp;<strong contenteditable="true"><?php echo htmlspecialchars($empresa['nombre']); ?></strong>&nbsp;con CIF:&nbsp;<strong contenteditable="true"><?php echo htmlspecialchars($empresa['cif']); ?></strong>&nbsp;y domicilio social en&nbsp;<span contenteditable="true"><?php echo htmlspecialchars($direccionEmpresa); ?></span>,&nbsp;han realizado la formación de&nbsp;<strong contenteditable="true"><?php echo htmlspecialchars($cursoRef['Denominacion']); ?></strong>&nbsp;<?php if ($same_date): ?>el&nbsp;<span contenteditable="true"><?php echo htmlspecialchars($fecha_inicio_display); ?></span><?php else: ?>entre el&nbsp;<span contenteditable="true"><?php echo htmlspecialchars($fecha_inicio_display); ?></span>&nbsp;y el&nbsp;<span contenteditable="true"><?php echo htmlspecialchars($fecha_fin_display); ?></span><?php endif; ?>&nbsp;con una duración de&nbsp;<span contenteditable="true"><?php echo htmlspecialchars($cursoRef['N_Horas']); ?></span>&nbsp;hora<?php echo ($cursoRef['N_Horas'] != 1 ? 's' : ''); ?> en modalidad&nbsp;<span contenteditable="true"><?php echo htmlspecialchars($cursoRef['Modalidad'] ?? 'presencial'); ?></span>.
  </div>

  <!-- Contenidos -->
  <div class="contenidos-title">CONTENIDOS IMPARTIDOS EN LA FORMACIÓN:</div>
  <div id="contenidos-area" contenteditable="true" class="block-field">
    <?php echo !empty($contenidoTexto) ? $contenidoTexto : '<p><em>(Sin contenidos registrados)</em></p>'; ?>
  </div>

  <!-- Pie -->
  <div class="footer-text">
    Y para que conste a los efectos oportunos, se expide el presente certificado en<br>
    <span contenteditable="true">Vigo</span>, a&nbsp;<span contenteditable="true"><?php echo htmlspecialchars($fecha_expedicion_display); ?></span>
  </div>

  <div class="footer-firma">
    <div class="sello">
      <img src="images/selloDixma.png" alt="Sello DIXMA">
    </div>
    <div class="pagina-numero">P á g i n a &nbsp; 1 | 1</div>
  </div>

</div><!-- /.pagina -->

<script>
// Ajusta el tamaño de fuente de la sección de contenidos
(function(){
  var btn = document.createElement('div');
  btn.id = 'font-controls';
  btn.style.cssText = 'background:#88c743;padding:4px 20px;text-align:center;font-size:0.8rem;';
  btn.innerHTML = 'Tamaño fuente contenidos: '
    + '<button onclick="cambiarFuente(0.5)" class="btn btn-sm btn-success ms-2">(+)</button>'
    + '<button onclick="cambiarFuente(0)" class="btn btn-sm btn-primary ms-1">Reset</button>'
    + '<button onclick="cambiarFuente(-0.5)" class="btn btn-sm btn-danger ms-1">(-)</button>';
  document.querySelector('#toolbar').appendChild(btn);

  var base = 9;
  window.cambiarFuente = function(d){
    if(d === 0){ base = 9; }
    else { base += d; }
    document.getElementById('contenidos-area').style.fontSize = base + 'pt';
  };
})();
</script>

</body>
</html>
