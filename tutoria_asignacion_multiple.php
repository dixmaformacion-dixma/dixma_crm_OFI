<?php
include "funciones/conexionBD.php";
include "funciones/funcionesAlumnos.php";
include "funciones/funcionesEmpresa.php";
include "funciones/funcionesAlumnosCursos.php";
include "funciones/funcionesCursos.php";
include "funciones/funcionesContenidos.php";

session_start();

if (empty($_SESSION)) {
    header("Location: index.php");
}

date_default_timezone_set("Europe/Madrid");
setlocale(LC_ALL, "spanish");

$mensaje = "";
$empresa = null;
$alumnos = [];
$idEmpresa = null;
$alumnos_seleccionados_ids = [];

// Lógica para manejar el POST (asignación de cursos)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['asignar_curso'])) {
    $idEmpresa = $_POST['idEmpresa'];
    $alumnosSeleccionados = isset($_POST['alumnos']) ? $_POST['alumnos'] : [];

    if (empty($alumnosSeleccionados)) {
        $mensaje = "<div class='alert alert-danger'>Error: Debes seleccionar al menos un alumno.</div>";
    } else {
        $datosCurso = [
            'Denominacion' => $_POST['Denominacion'] ?? null,
            'N_Accion' => $_POST['N_Accion'] ?? null,
            'N_Grupo' => $_POST['N_Grupo'] ?? null,
            'N_Horas' => isset($_POST['N_Horas']) ? str_replace(',', '.', $_POST['N_Horas']) : null,
            'Modalidad' => $_POST['Modalidad'] ?? null,
            'DOC_AF' => $_POST['DOC_AF'] ?? null,
            'Fecha_Inicio' => $_POST['Fecha_Inicio'] ?? null,
            'Fecha_Fin' => $_POST['Fecha_Fin'] ?? null,
            'tutor' => $_POST['tutor'] ?? null,
            'idCurso' => isset($_POST['selectFromCourseList']) ? $_POST['idCurso'] : null,
            'idEmpresa' => $idEmpresa,
            'Tipo_Venta' => $_POST['Tipo_Venta'],
            'seguimento0' => !empty($_POST['seguimento0']) ? $_POST['seguimento0'] : null,
            'seguimento1' => !empty($_POST['seguimento1']) ? $_POST['seguimento1'] : null,
            'seguimento2' => !empty($_POST['seguimento2']) ? $_POST['seguimento2'] : null,
            'seguimento3' => !empty($_POST['seguimento3']) ? $_POST['seguimento3'] : null,
            'seguimento4' => !empty($_POST['seguimento4']) ? $_POST['seguimento4'] : null,
            'seguimento5' => !empty($_POST['seguimento5']) ? $_POST['seguimento5'] : null,
        ];

        if (alumnoCursoAdjuntarMultiple($alumnosSeleccionados, $datosCurso)) {
            $mensaje = "<div class='alert alert-success'>Curso asignado correctamente a " . count($alumnosSeleccionados) . " alumnos.</div>";
        } else {
            $mensaje = "<div class='alert alert-danger'>Error: No se pudo completar la asignación de cursos. La operación ha sido revertida.</div>";
        }
    }
}

// Lógica para manejar la selección de alumnos (Paso 2)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['seleccionar_alumnos'])) {
    $alumnos_seleccionados_ids = $_POST['alumnos'] ?? [];
}

// Lógica para manejar el GET (búsqueda de empresa)
if (isset($_GET['idEmpresa'])) {
    $idEmpresa = $_GET['idEmpresa'];
    $empresa = cargarEmpresa($idEmpresa);
    if ($empresa) {
        $alumnos = todoAlumnoPorEmpresa($idEmpresa);
    }
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asignación Múltiple de Cursos</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/jquery.min.js"></script>
    <link rel="icon" href="images/favicon.ico">
</head>

<body style="background-color:#f3f6f4;">

    <?php require_once("template-parts/header/header.template.php"); ?>

    <div class="container-fluid">
        <div class="row">
            <?php require_once("template-parts/leftmenu/tutoria.template.php"); ?>

            <div class="col-md-10 col-12">
                <h2 class="text-center mt-2 pt-2 pb-3 mb-4 border border-5 rounded" style="background-color: #b0d588; letter-spacing: 7px;">ASIGNACIÓN MÚLTIPLE DE CURSOS</h2>

                <?php echo $mensaje; ?>

                <!-- 1. Buscador de Empresa -->
                <div class="card mb-4">
                    <div class="card-header fw-bold">Paso 1: Buscar Empresa</div>
                    <div class="card-body">
                        <form method="GET" action="tutoria_asignacion_multiple.php" class="row g-3 align-items-end">
                            <div class="col-md-6">
                                <label for="idEmpresa" class="form-label">Nombre de la Empresa</label>
                                <input class="form-control" list="empresas-list" id="empresa-input" placeholder="Escribe para buscar..." value="<?php echo htmlspecialchars($empresa['nombre'] ?? ''); ?>">
                                <datalist id="empresas-list"></datalist>
                                <input type="hidden" id="idEmpresa" name="idEmpresa" value="<?php echo htmlspecialchars($idEmpresa ?? ''); ?>">
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary">Buscar Alumnos</button>
                            </div>
                        </form>
                    </div>
                </div>

                <?php if ($empresa && empty($alumnos_seleccionados_ids) && !isset($_POST['asignar_curso'])) : ?>
                    <!-- Paso 2: Seleccionar Alumnos -->
                    <div class="card mb-4">
                        <div class="card-header fw-bold">Paso 2: Seleccionar Alumnos de <?php echo htmlspecialchars($empresa['nombre']); ?></div>
                        <form method="POST" action="tutoria_asignacion_multiple.php?idEmpresa=<?php echo $idEmpresa; ?>">
                            <div class="card-body">
                                <?php if (!empty($alumnos)) : ?>
                                    <div class="mb-3">
                                        <input type="checkbox" id="seleccionarTodos" class="form-check-input">
                                        <label for="seleccionarTodos" class="form-check-label">Seleccionar Todos</label>
                                    </div>
                                    <div style="max-height: 400px; overflow-y: auto; border: 1px solid #ddd; padding: 10px;">
                                        <?php foreach ($alumnos as $alumno) : ?>
                                            <div class="form-check">
                                                <input class="form-check-input alumno-checkbox" type="checkbox" name="alumnos[]" value="<?php echo $alumno['idAlumno']; ?>" id="alumno_<?php echo $alumno['idAlumno']; ?>">
                                                <label class="form-check-label" for="alumno_<?php echo $alumno['idAlumno']; ?>">
                                                    <?php echo htmlspecialchars($alumno['nombre'] . ' ' . $alumno['apellidos']); ?>
                                                </label>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else : ?>
                                    <p class="text-muted">No se encontraron alumnos para esta empresa.</p>
                                <?php endif; ?>
                            </div>
                            <div class="card-footer text-center">
                                <button type="submit" name="seleccionar_alumnos" class="btn btn-primary" <?php echo empty($alumnos) ? 'disabled' : ''; ?>>
                                    Continuar al Paso 3
                                </button>
                            </div>
                        </form>
                    </div>
                <?php endif; ?>

                <?php if (!empty($alumnos_seleccionados_ids)) : ?>
                    <!-- Paso 3: Rellenar Datos del Curso -->
                    <div class="card mb-4">
                        <div class="card-header fw-bold">Paso 3: Rellenar Datos del Curso</div>
                        <form method="POST" action="tutoria_asignacion_multiple.php?idEmpresa=<?php echo $idEmpresa; ?>">
                            <input type="hidden" name="idEmpresa" value="<?php echo $empresa['idempresa']; ?>">
                            <?php foreach ($alumnos_seleccionados_ids as $id_alumno) : ?>
                                <input type="hidden" name="alumnos[]" value="<?php echo htmlspecialchars($id_alumno); ?>">
                            <?php endforeach; ?>

                            <div class="card-body">
                                <p>Asignando curso a <strong><?php echo count($alumnos_seleccionados_ids); ?></strong> alumno(s) seleccionado(s).</p>
                                <div class="row g-3">
                                    <!-- Inicio del nuevo formulario personalizado -->
                                    <div id="form-multiple-container">
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label class="form-check-label">
                                                    <input type="checkbox" name="selectFromCourseList" class="form-check-input" onchange='changeCourseSelectionMode("form-multiple-container")'>
                                                    <b>Seleccionar de la lista de cursos</b>
                                                </label>
                                                <div class="d-flex mt-2">
                                                    <select class="form-select me-2" name="type" onchange='selectTypeOfCourses("form-multiple-container")' disabled>
                                                        <option disabled selected value>-- Tipo --</option>
                                                        <?php
                                                        $tipoCursosArray = cargarTipoCurso();
                                                        foreach ($tipoCursosArray as $tipo) {
                                                            echo '<option value="' . $tipo . '">' . $tipo . '</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                    <select class="form-select" name="idCurso" onchange='selectCourse("form-multiple-container")' disabled>
                                                        <option disabled selected value>-- Curso --</option>
                                                        <?php
                                                        $cursos = listadoCursos();
                                                        foreach ($cursos as $cursoItem) {
                                                            echo '<option style="display:none" class="courseOptions class' . $cursoItem['tipoCurso'] . '" value="' . $cursoItem['idCurso'] . '">' . htmlspecialchars($cursoItem['nombreCurso']) . '</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold">Denominación:</label>
                                                <input name="Denominacion" class="form-control form-control-sm text-uppercase" type="text" required>
                                            </div>
                                        </div>

                                        <div class="row mb-3 g-3">
                                            <div class="col-md-3"><label class="form-label fw-bold">Nº Acción:</label><input name="N_Accion" class="form-control form-control-sm" type="number" required></div>
                                            <div class="col-md-3"><label class="form-label fw-bold">Nº Grupo:</label><input name="N_Grupo" class="form-control form-control-sm" type="number" required></div>
                                            <div class="col-md-3"><label class="form-label fw-bold">Nº Horas:</label><input name="N_Horas" class="form-control form-control-sm" type="text"></div>
                                            <div class="col-md-3"><label class="form-label fw-bold">Tutor:</label><input name="tutor" class="form-control form-control-sm text-uppercase" type="text"></div>
                                        </div>

                                        <div class="row mb-3 g-3">
                                            <div class="col-md-6"><label class="form-label fw-bold">Fecha Inicio:</label><input onchange="changeSeguimentoDatesMultiple('form-multiple-container')" name="Fecha_Inicio" class="form-control form-control-sm Fecha_Inicio" type="date"></div>
                                            <div class="col-md-6"><label class="form-label fw-bold">Fecha Fin:</label><input onchange="changeSeguimentoDatesMultiple('form-multiple-container')" name="Fecha_Fin" class="form-control form-control-sm Fecha_Fin" type="date"></div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label class="fw-bold">Modalidad:</label>
                                                <div>
                                                    <input class="form-check-input" type="radio" name="Modalidad" value="Teleformación" checked> <label class="form-check-label">Teleformación</label>
                                                    <input class="form-check-input ms-2" type="radio" name="Modalidad" value="Presencial"> <label class="form-check-label">Presencial</label>
                                                    <input class="form-check-input ms-2" type="radio" name="Modalidad" value="Mixto"> <label class="form-check-label">Mixto</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="fw-bold">Tipo venta:</label>
                                                <div>
                                                    <input class="form-check-input" type="radio" name="Tipo_Venta" value="Bonificado" checked> <label class="form-check-label">Bonificado</label>
                                                    <input class="form-check-input ms-2" type="radio" name="Tipo_Venta" value="Privado"> <label class="form-check-label">Privado</label>
                                                </div>
                                            </div>
                                        </div>

                                        <hr>
                                        <h5 class="text-center text-muted">Fechas de Seguimiento (Automático)</h5>
                                        <div class="row g-2">
                                            <div class="col-md-4"><label class="form-label">1º TUTORÍA:</label><input name="seguimento0" class="form-control form-control-sm seguimento0" type="date"></div>
                                            <div class="col-md-4"><label class="form-label">SEGUIMIENTO 1:</label><input name="seguimento1" class="form-control form-control-sm seguimento1" type="date"></div>
                                            <div class="col-md-4"><label class="form-label">SEGUIMIENTO 2:</label><input name="seguimento2" class="form-control form-control-sm seguimento2" type="date"></div>
                                            <div class="col-md-4"><label class="form-label">SEGUIMIENTO 3:</label><input name="seguimento3" class="form-control form-control-sm seguimento3" type="date"></div>
                                            <div class="col-md-4"><label class="form-label">SEGUIMIENTO 4:</label><input name="seguimento4" class="form-control form-control-sm seguimento4" type="date"></div>
                                            <div class="col-md-4"><label class="form-label">SEGUIMIENTO 5:</label><input name="seguimento5" class="form-control form-control-sm seguimento5" type="date"></div>
                                        </div>
                                        <input name="DOC_AF" type="hidden" value="">
                                    </div>
                                    <!-- Fin del nuevo formulario -->
                                </div>
                            </div>
                            <div class="card-footer text-center">
                                <button type="submit" name="asignar_curso" class="btn btn-success btn-lg">
                                    Asignar Curso a Alumnos Seleccionados
                                </button>
                            </div>
                        </form>
                    </div>
                <?php elseif (isset($_GET['idEmpresa'])) : ?>
                    <div class="alert alert-warning">No se encontró ninguna empresa con el ID proporcionado.</div>
                <?php endif; ?>

            </div>
        </div>
    </div>

    <footer class="border-top border-secondary mt-5" style="background-color:#e4e4e4; height: 75px;">
        <p class="text-center mt-md-4" style='color: #8fd247;'> <b> © Dixma Formación 2022. | Ctra. Madrid 152, Vigo 36318 | info@dixmaformacion.com | Tlf: +34 604 067 035 </b> </p>
    </footer>
    <script>
        <?php
        // Este bloque es necesario para que las funciones JS tengan los datos de los cursos
        echo 'var CourseArray = {';
        foreach ($cursos as $cursoItem) {
            echo '' . $cursoItem['idCurso'] . ': {nombreCurso: "' . addslashes($cursoItem['nombreCurso']) . '", horasCurso: "' . $cursoItem['horasCurso'] . '"},';
        }
        echo '};';
        ?>
    </script>
    <script src="js/alumnocurso.js"></script>

    <script>
        // --- Script para el buscador de empresas ---
        $(document).ready(function() {
            var dataList = document.getElementById('empresas-list');
            var input = document.getElementById('empresa-input');
            var hiddenInput = document.getElementById('idEmpresa');

            $.ajax({
                url: 'funciones/ajax.php?action=getEmpresasList',
                success: function(data) {
                    var options = JSON.parse(data);
                    options.forEach(function(item) {
                        var option = document.createElement('option');
                        option.value = item.nombre;
                        option.setAttribute('data-id', item.idempresa);
                        dataList.appendChild(option);
                    });
                }
            });

            input.addEventListener('input', function(e) {
                var selectedOption = $('#empresas-list option[value="' + e.target.value + '"]');
                hiddenInput.value = selectedOption.data('id') || '';
            });
        });
        $(document).ready(function() {
            // Funcionalidad para el checkbox "Seleccionar Todos"
            $('#seleccionarTodos').on('click', function() {
                $('.alumno-checkbox').prop('checked', $(this).prop('checked'));
            });

            // Si se desmarca un alumno, desmarcar "Seleccionar Todos"
            $('.alumno-checkbox').on('click', function() {
                if (!$(this).prop('checked')) {
                    $('#seleccionarTodos').prop('checked', false);
                }
            });

        });
    </script>

</body>

</html>
