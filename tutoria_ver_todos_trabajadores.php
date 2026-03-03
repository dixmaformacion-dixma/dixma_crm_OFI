<?php
include "funciones/conexionBD.php";
include "funciones/funcionesAlumnos.php";
include "funciones/funcionesEmpresa.php";
include "funciones/funcionesAlumnosCursos.php";
include "funciones/funcionesCursos.php";
include "funciones/funcionesContenidos.php";

include "tutoria_editar_AlumnoCurso_function.php";
include "tutoria_insertar_commentario_function.php";
include "tutoria_editar_seguimentos_function.php";

session_start();
if (empty($_SESSION)) {
    http_response_code(403);
    echo "<p class='text-danger'>No autorizado.</p>";
    exit;
}

$StudentCursoID = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($StudentCursoID <= 0) {
    echo "<p class='text-danger'>ID no valido.</p>";
    exit;
}

$conexionPDO = realizarConexion();

$stmt = $conexionPDO->prepare(
    "SELECT N_Accion, N_Grupo, idEmpresa, Denominacion FROM alumnocursos WHERE StudentCursoID = ?"
);
$stmt->bindValue(1, $StudentCursoID, PDO::PARAM_INT);
$stmt->execute();
$corsoRef = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$corsoRef) {
    echo "<p class='text-danger'>Curso no encontrado.</p>";
    exit;
}

$stmt2 = $conexionPDO->prepare(
    "SELECT alumnocursos.*, alumnos.nombre, alumnos.apellidos, alumnos.nif,
            alumnos.email, alumnos.telefono, alumnos.horarioLaboral
     FROM alumnocursos
     INNER JOIN alumnos ON alumnocursos.idAlumno = alumnos.idAlumno
     WHERE alumnocursos.N_Accion = ? AND alumnocursos.N_Grupo = ? AND alumnocursos.idEmpresa = ?
     ORDER BY alumnocursos.StudentCursoID ASC"
);
$stmt2->bindValue(1, $corsoRef['N_Accion'],  PDO::PARAM_STR);
$stmt2->bindValue(2, $corsoRef['N_Grupo'],   PDO::PARAM_STR);
$stmt2->bindValue(3, $corsoRef['idEmpresa'], PDO::PARAM_INT);
$stmt2->execute();
$cursos = $stmt2->fetchAll(PDO::FETCH_ASSOC);
unset($conexionPDO);

if (empty($cursos)) {
    echo "<p class='text-muted'>No se encontraron trabajadores para este grupo.</p>";
    exit;
}

// Disabilita il pulsante "ver todos" per evitare loop infiniti
$cursos = array_map(function($c){ $c['mostrar_solo_primero'] = 0; return $c; }, $cursos);

$date               = date("Y-m-d");
$year               = (isset($_GET['year']) && $_GET['year'] !== '') ? intval($_GET['year']) : intval(date("Y"));
$Tipo_Venta_Display = (isset($_GET['Tipo_Venta_Display']) && $_GET['Tipo_Venta_Display'] !== '') ? $_GET['Tipo_Venta_Display'] : 'Bonificado';

$page_from = "tutoria_ver_todos_trabajadores.php?" . http_build_query([
    'id'                 => $StudentCursoID,
    'year'               => $year,
    'Tipo_Venta_Display' => $Tipo_Venta_Display,
]);

$empresasConPendientes = obtenerEmpresasConCursosPendientes();

$statusColor = [
    "en curso"   => "",
    "finalizado" => "background-color: lightblue;",
    "descargado" => "background-color: lightblue;",
    "cerrado"    => "background-color: lightblue;",
    "baja"       => "background-color: #c30d0d; color:white;",
    "problem"    => "background-color: Gold;"
];
$statusDateColor = [
    "en curso"   => "",
    "finalizado" => "",
    "descargado" => "background-color: #c30d0d; color:white;",
    "cerrado"    => "background-color: #00693E; color:white;",
    "baja"       => "background-color: #c30d0d; color:white;",
    "problem"    => "background-color: Gold;"
];
$statusDiplomaColor = [
    "Copia recibida" => "background-color: #28D700;",
    "Entregado"      => "background-color: #28D700;",
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trabajadores del curso</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/jquery.min.js"></script>
    <script src="js/tutoria.js"></script>
    <script src="js/alumnocurso.js"></script>
    <link rel="icon" href="images/favicon.ico">
    <style>
        body { background-color:#f3f6f4; margin:0; padding:0; }
        .page-header {
            position: sticky; top: 0; z-index: 100;
            display: flex; align-items: center; justify-content: space-between;
            background-color: #88c743; padding: 12px 20px;
            border-bottom: 2px solid #6aaa30;
        }
        .page-header h5 { margin: 0; font-weight: bold; font-size: 1.1rem; }
        .page-body { padding: 20px; }
        .btn-close-window {
            background: none; border: none; font-size: 1.6rem;
            line-height: 1; cursor: pointer; color: #000; opacity: .6;
        }
        .btn-close-window:hover { opacity: 1; }
        .courseWrapper .container:nth-of-type(even) { background-color: #e7e9e8; }
    </style>
</head>
<body>
    <div class="page-header">
        <h5>Todos los trabajadores del curso</h5>
        <button class="btn-close-window" onclick="window.close()" title="Cerrar">&times;</button>
    </div>
    <div class="page-body">

        <div class="mb-2 px-2 d-flex align-items-center gap-3">
            <span class="badge text-dark fw-bold fs-6" style="background-color:#b0d588;">
                <?php echo htmlspecialchars($corsoRef['Denominacion']); ?>
                &nbsp;&mdash;&nbsp; Accion: <?php echo htmlspecialchars($corsoRef['N_Accion']); ?>
                / Grupo: <?php echo htmlspecialchars($corsoRef['N_Grupo']); ?>
                &nbsp;&mdash;&nbsp; <?php echo count($cursos); ?> trabajadores
            </span>
            <button type="button" class="btn btn-sm btn-warning fw-bold" id="btn-certificado">
               🖨️ Imprimir certificado
            </button>
        </div>

        <div class="col-md-12 col-12 container border border-2" style="background-color:#88c743">
            <div class='row p-0'>
                <div style="width:5%"><input type="checkbox" class="selectable" value="all"> <b>#</b></div>
                <div class='col-md-2 border-right'><b>Nombre</b></div>
                <div style="width:9%"><b>Fecha_Inicio</b></div>
                <div style="width:9%"><b>Fecha_Fin</b></div>
                <div class='col-md-2 border-right'><b>Denominacion</b></div>
                <div style="width:3%"><b>A/G</b></div>
                <div style="width:3%"><b>RM</b></div>
                <div style="width:3%"><b>CC</b></div>
                <div class='col-md-1 border-right'><b>Empresa</b></div>
                <div class='col-md-1 border-right'><b>Diploma</b></div>
                <div class="col-md-1"></div>
            </div>
        </div>

        <div class="courseWrapper">
        <?php
        $numr = 1;
        foreach ($cursos as $curso) {
            require("template-parts/components/curso.(cursolist.listadoCursos).php");
            $numr++;
        }
        ?>
        </div>

    </div><!-- /.page-body -->

<script>
    $(document).on('click', '.selectable', function () {
        if ($(this).val() == 'all') {
            $('.selectable').prop('checked', $(this).prop('checked'));
        }
    });
    document.getElementById('btn-certificado').addEventListener('click', function () {
        var checked = document.querySelectorAll('.selectable:checked');
        var baseUrl = 'tutoria_certificadoPDF.php?id=<?php echo $StudentCursoID; ?>';
        var ids = Array.from(checked)
            .map(function (c) { return c.value; })
            .filter(function (v) { return v !== 'all' && !isNaN(parseInt(v)); });
        if (ids.length > 0) {
            baseUrl += '&' + ids.map(function (v) { return 'ids[]=' + encodeURIComponent(v); }).join('&');
        }
        window.open(baseUrl, '_blank');
    });
</script>
</body>
</html>
