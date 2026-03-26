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
// Conserva il valore originale di mostrar_solo_primero (se presente) prima di forzarlo a 0 per il rendering
$grupo_mostrar = 0;
if (!empty($cursos) && isset($cursos[0]['mostrar_solo_primero'])) {
    $grupo_mostrar = intval($cursos[0]['mostrar_solo_primero']);
}
unset($conexionPDO);

if (empty($cursos)) {
    echo "<p class='text-muted'>No se encontraron trabajadores para este grupo.</p>";
    exit;
}

// Disabilita il pulsante "ver todos" per evitare loop infiniti (manteniamo il flag originale in $grupo_mostrar)
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

// Gestione POST: aggiungi uno o più lavoratori al corso esistente
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['agregar_trabajadores'])) {
    $listaAlumnos = isset($_POST['alumnos']) ? array_map('intval', (array)$_POST['alumnos']) : [];

    if (!empty($listaAlumnos)) {
        // Deriva Fecha_Inicio, Fecha_Fin e seguimientos dal primo lavoratore già presente nel corso, se esiste
        if (!empty($cursos) && isset($cursos[0])) {
            $ref = $cursos[0];
            $datosCurso = [
                'Denominacion'   => $corsoRef['Denominacion'],
                'N_Accion'       => $corsoRef['N_Accion'],
                'N_Grupo'        => $corsoRef['N_Grupo'],
                'N_Horas'        => $ref['N_Horas'] ?? '',
                'Modalidad'      => $ref['Modalidad'] ?? '',
                'DOC_AF'         => $ref['DOC_AF'] ?? '',
                'Fecha_Inicio'   => $ref['Fecha_Inicio'] ?? '',
                'Fecha_Fin'      => $ref['Fecha_Fin'] ?? '',
                'tutor'          => $ref['tutor'] ?? '',
                'idCurso'        => isset($ref['idCurso']) ? intval($ref['idCurso']) : 0,
                'idEmpresa'      => $corsoRef['idEmpresa'],
                'Tipo_Venta'     => $ref['Tipo_Venta'] ?? $Tipo_Venta_Display,
                'seguimento0'    => $ref['seguimento0'] ?? '',
                'seguimento1'    => $ref['seguimento1'] ?? '',
                'seguimento2'    => $ref['seguimento2'] ?? '',
                'seguimento3'    => $ref['seguimento3'] ?? '',
                'seguimento4'    => $ref['seguimento4'] ?? '',
                'seguimento5'    => $ref['seguimento5'] ?? '',
                'Firma_Docente'  => $ref['firma_docente'] ?? null,
                'mostrar_solo_primero' => isset($grupo_mostrar) ? intval($grupo_mostrar) : 0,
            ];
        } else {
            // Fallback: valori vuoti
            $datosCurso = [
                'Denominacion'   => $corsoRef['Denominacion'],
                'N_Accion'       => $corsoRef['N_Accion'],
                'N_Grupo'        => $corsoRef['N_Grupo'],
                'N_Horas'        => '',
                'Modalidad'      => '',
                'DOC_AF'         => '',
                'Fecha_Inicio'   => '',
                'Fecha_Fin'      => '',
                'tutor'          => '',
                'idCurso'        => 0,
                'idEmpresa'      => $corsoRef['idEmpresa'],
                'Tipo_Venta'     => $Tipo_Venta_Display,
                'seguimento0'    => '',
                'seguimento1'    => '',
                'seguimento2'    => '',
                'seguimento3'    => '',
                'seguimento4'    => '',
                'seguimento5'    => '',
                'Firma_Docente'  => null,
                'mostrar_solo_primero' => 0,
            ];
        }

        if (alumnoCursoAdjuntarMultiple($listaAlumnos, $datosCurso)) {
            header('Location: ' . $page_from . '&msg=agregado');
            exit;
        } else {
            $errorAgregar = true;
        }
    } else {
        $errorAgregar = true;
    }
}

// (Rimosso: gestione inserimento nuovo lavoratore qui — la pagina mantiene solo selezione/associazione)
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
    <script src="js/cursoScrollRestore.js"></script>
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
<?php
// Prepara lista di lavoratori disponibili (stessa azienda, non già nel gruppo)
$q = isset($_GET['q']) ? trim($_GET['q']) : '';
$openAddWorkersModal = !empty($_GET['open_add_workers']);
$conexionDisponibles = realizarConexion();
if ($q !== '') {
    $sqlDispon = "SELECT idAlumno, nombre, apellidos, nif FROM alumnos WHERE idEmpresa = ? AND idAlumno NOT IN (SELECT idAlumno FROM alumnocursos WHERE N_Accion = ? AND N_Grupo = ? AND idEmpresa = ?) AND (apellidos LIKE ? OR nombre LIKE ? OR nif LIKE ?) ORDER BY apellidos ASC";
    $stmtDispon = $conexionDisponibles->prepare($sqlDispon);
    $like = '%' . $q . '%';
    $stmtDispon->bindValue(1, $corsoRef['idEmpresa'], PDO::PARAM_INT);
    $stmtDispon->bindValue(2, $corsoRef['N_Accion'], PDO::PARAM_STR);
    $stmtDispon->bindValue(3, $corsoRef['N_Grupo'], PDO::PARAM_STR);
    $stmtDispon->bindValue(4, $corsoRef['idEmpresa'], PDO::PARAM_INT);
    $stmtDispon->bindValue(5, $like, PDO::PARAM_STR);
    $stmtDispon->bindValue(6, $like, PDO::PARAM_STR);
    $stmtDispon->bindValue(7, $like, PDO::PARAM_STR);
    $stmtDispon->execute();
} else {
    $sqlDispon = "SELECT idAlumno, nombre, apellidos, nif FROM alumnos WHERE idEmpresa = ? AND idAlumno NOT IN (SELECT idAlumno FROM alumnocursos WHERE N_Accion = ? AND N_Grupo = ? AND idEmpresa = ?) ORDER BY apellidos ASC";
    $stmtDispon = $conexionDisponibles->prepare($sqlDispon);
    $stmtDispon->bindValue(1, $corsoRef['idEmpresa'], PDO::PARAM_INT);
    $stmtDispon->bindValue(2, $corsoRef['N_Accion'], PDO::PARAM_STR);
    $stmtDispon->bindValue(3, $corsoRef['N_Grupo'], PDO::PARAM_STR);
    $stmtDispon->bindValue(4, $corsoRef['idEmpresa'], PDO::PARAM_INT);
    $stmtDispon->execute();
}
$alumnosDisponibles = $stmtDispon->fetchAll(PDO::FETCH_ASSOC);
unset($conexionDisponibles);
?>

<?php if (isset($_GET['msg']) && $_GET['msg'] === 'agregado'): ?>
    <div class="alert alert-success">Trabajadores añadidos correctamente.</div>
<?php endif; ?>
<?php if (!empty($errorAgregar)): ?>
    <div class="alert alert-danger">Error al añadir los trabajadores. Comprueba la selección y vuelve a intentarlo.</div>
<?php endif; ?>

<div class="modal fade" id="addWorkersModal" tabindex="-1" aria-labelledby="addWorkersModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="addWorkersModalLabel">Añadir trabajadores al grupo</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <form method="get" class="row g-2 align-items-center mb-3">
                    <input type="hidden" name="id" value="<?php echo intval($StudentCursoID); ?>">
                    <div class="col-12 col-md-8">
                        <input type="text" name="q" value="<?php echo htmlspecialchars($q); ?>" placeholder="Apellidos, nombre o NIF" class="form-control">
                    </div>
                    <div class="col-12 col-md-4 d-flex gap-2">
                        <button type="submit" class="btn btn-secondary flex-fill">Buscar</button>
                        <a href="<?php echo htmlspecialchars($page_from . '&open_add_workers=1'); ?>" class="btn btn-outline-secondary flex-fill">Mostrar todos</a>
                    </div>
                </form>

                <form method="post">
                    <input type="hidden" name="_referer" value="<?php echo htmlspecialchars($page_from . ( $q !== '' ? '&q=' . urlencode($q) : '' )); ?>">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <label class="form-label mb-0 fw-bold">Selecciona trabajadores de la misma empresa</label>
                        <label class="mb-0"><input type="checkbox" id="select_all"> Seleccionar todos</label>
                    </div>
                    <div style="max-height:320px; overflow:auto; border:1px solid #ddd; padding:8px; border-radius:6px; background:#fff;">
                        <?php if (empty($alumnosDisponibles)): ?>
                            <div class="text-muted">No hay trabajadores disponibles con estos criterios.</div>
                        <?php else: ?>
                            <?php foreach ($alumnosDisponibles as $al): ?>
                                <div style="padding:4px 0; border-bottom:1px solid #f0f0f0;">
                                    <label class="w-100">
                                        <input type="checkbox" name="alumnos[]" class="alumno_checkbox" value="<?php echo intval($al['idAlumno']); ?>">
                                        <?php echo htmlspecialchars($al['apellidos'] . ' ' . $al['nombre'] . ' (' . $al['nif'] . ')'); ?>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-3">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" name="agregar_trabajadores" class="btn btn-primary">Añadir seleccionados</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('select_all')?.addEventListener('change', function(e){
    var checked = e.target.checked;
    document.querySelectorAll('.alumno_checkbox').forEach(function(cb){ cb.checked = checked; });
});
</script>

<script>
// Mantiene aperta la finestra quando la pagina ricarica dopo una búsqueda, un error o "Mostrar todos"
<?php if (!empty($q) || !empty($errorAgregar) || $openAddWorkersModal): ?>
document.addEventListener('DOMContentLoaded', function () {
    var modalEl = document.getElementById('addWorkersModal');
    if (modalEl && window.bootstrap) {
        var modal = bootstrap.Modal.getOrCreateInstance(modalEl);
        modal.show();
    }
});
<?php endif; ?>
</script>
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
            <a href="tutoria_controlAsistencia.php?N_Accion=<?php echo urlencode($corsoRef['N_Accion']); ?>&N_Grupo=<?php echo urlencode($corsoRef['N_Grupo']); ?>" id="controlAsistenciaBtn" class="btn btn-sm btn-success fw-bold">
                Control de Asistencia
            </a>
            <a href="tutoria_diplomaPDF_all.php" id="printAll" target="_blank" class="btn btn-sm btn-danger fw-bold">
                Imprimir Diplomas Seleccionados
            </a>
            <button type="button" class="btn btn-sm btn-warning fw-bold" id="btn-certificado">
               🖨️ Imprimir certificado
            </button>
            <button type="button" class="btn btn-sm btn-primary fw-bold" data-bs-toggle="modal" data-bs-target="#addWorkersModal">
                Añadir nuevos trabajadores
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

        let href = $('#printAll').attr('href').split('?')[0];
        let selectables = $('.selectable:checked').toArray()
            .map(function (item) { return $(item).val(); })
            .filter(function (value) { return !isNaN(parseInt(value)); });

        href += '?ids=' + selectables.join(',');
        $('#printAll').attr('href', href);
    });

    $('#controlAsistenciaBtn').on('click', function (e) {
        e.preventDefault();

        let selectedIds = $('.selectable:checked').toArray()
            .map(function (item) { return $(item).val(); })
            .filter(function (value) { return value !== 'all' && !isNaN(parseInt(value)); });

        if (selectedIds.length === 0) {
            alert('Por favor, selecciona al menos un alumno para generar el control de asistencia.');
            return;
        }

        let baseUrl = $(this).attr('href');
        window.location.href = baseUrl + '&ids=' + selectedIds.join(',');
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
