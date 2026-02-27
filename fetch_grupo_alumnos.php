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
    echo "<p class='text-danger'>Corso non trovato.</p>";
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
    echo "<p class='text-muted'>Nessun lavoratore trovato per questo gruppo.</p>";
    exit;
}

// Nel modal non serve il bottone "ver todos grupo": lo nascondiamo azzerando il flag
$cursos = array_map(function($c){ $c['mostrar_solo_primero'] = 0; return $c; }, $cursos);

$date               = date("Y-m-d");
$year               = (isset($_GET['year']) && $_GET['year'] !== '') ? intval($_GET['year']) : intval(date("Y"));
$Tipo_Venta_Display = (isset($_GET['Tipo_Venta_Display']) && $_GET['Tipo_Venta_Display'] !== '') ? $_GET['Tipo_Venta_Display'] : 'Bonificado';
// openGrupo = ID del record rappresentativo del gruppo (per riaprire il modal dopo Guardar)
$page_from = "tutoria_listadoCursos.php?" . http_build_query([
    'year'               => $year,
    'Tipo_Venta_Display' => $Tipo_Venta_Display,
    'openGrupo'          => $StudentCursoID
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

<div class="mb-2 px-2">
    <span class="badge text-dark fw-bold fs-6" style="background-color:#b0d588;">
        <?php echo htmlspecialchars($corsoRef['Denominacion']); ?>
        &nbsp;&mdash;&nbsp; Accion: <?php echo htmlspecialchars($corsoRef['N_Accion']); ?>
        / Grupo: <?php echo htmlspecialchars($corsoRef['N_Grupo']); ?>
        &nbsp;&mdash;&nbsp; <?php echo count($cursos); ?> trabajadores
    </span>
</div>

<div class="col-md-12 col-12 container border border-2" style="background-color:#88c743">
    <div class='row p-0'>
        <div style="width:5%"><b>#</b></div>
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

<style>
.courseWrapper .container:nth-of-type(even){ background-color: #e7e9e8; }
</style>