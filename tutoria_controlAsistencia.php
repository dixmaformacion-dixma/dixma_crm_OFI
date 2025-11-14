<?php
include "funciones/conexionBD.php";
include "funciones/funcionesAlumnosCursos.php";
include "funciones/funcionesCursos.php";

session_start();

if (empty($_SESSION)) {
    header("Location: index.php");
    exit();
}

date_default_timezone_set("Europe/Madrid");
setlocale(LC_ALL, "spanish");

$n_accion = $_GET['N_Accion'] ?? null;
$n_grupo = $_GET['N_Grupo'] ?? null;
$student_ids_str = $_GET['ids'] ?? '';

$course_data = null;
$students_list = [];

if ($n_accion && $n_grupo && !empty($student_ids_str)) {
    // Obtener la información del primer curso/alumno para los detalles generales
    $first_id = explode(',', $student_ids_str)[0];
    $first_course_info = cargarAlumnoCurso($first_id);
    if ($first_course_info) {
        $course_data = [
            'Denominacion' => $first_course_info['Denominacion'],
            'N_Accion' => $first_course_info['N_Accion'],
            'N_Grupo' => $first_course_info['N_Grupo'],
            'Fecha_Inicio' => $first_course_info['Fecha_Inicio'],
            'Fecha_Fin' => $first_course_info['Fecha_Fin'],
            'tutor' => $first_course_info['tutor']
        ];
    }

    // Obtener los detalles de los alumnos seleccionados
    $conexionPDO = realizarConexion();
    $sql = "SELECT apellidos, nombre, NIF FROM alumnos WHERE idAlumno IN (
                SELECT idAlumno FROM alumnocursos WHERE StudentCursoID IN ($student_ids_str)
            ) ORDER BY apellidos, nombre";

    $stmt = $conexionPDO->prepare($sql);
    $stmt->execute();
    $students_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="es" xml:lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Control de Asistencia para Imprimir</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/jquery.min.js"></script>
    <link rel="icon" href="images/favicon.ico">
    <style>
        @media print {
            .no-print {
                display: none !important;
            }

            body {
                background-color: #fff !important;
                width: 100%; /* Dejamos que el navegador controle el ancho */
                font-size: 12px;
                color: #003366;
            }

            .printable-area {
                border: none !important; /* Aseguramos que el área imprimible no tenga bordes */
            }

            .editable-input {
                border: none;
                border-bottom: 1px solid #003366;
                text-align: center;
                background-color: transparent;
                color: #003366;
            }
            .table{
                color: #003366;
            }

            @page {
                margin-top: 1.5cm;
            }

            thead {
                display: table-header-group; /* Repite el encabezado de la tabla en cada página */
            }

            tr {
                page-break-inside: avoid; /* Evita que las filas se corten entre páginas */
            }
        }
    </style>
</head>

<body style="background-color:#f3f6f4;">

    <div class="container mt-4">
        <div class="row no-print">
            <div class="col-12 text-center mb-4">
                <button onclick="window.print();" class="btn btn-primary">Imprimir</button>
                <button onclick="history.back();" class="btn btn-secondary">Volver</button>
            </div>
        </div>

        <div class="printable-area p-4 border rounded bg-white">
            <?php if (!$course_data || empty($students_list)) : ?>
                <div class="alert alert-warning text-center">
                    No se encontraron datos del curso o no se seleccionaron alumnos.
                </div>
            <?php else : ?>

                <div class="col-5">
                    <img src="images/logoWord.jpg" height="60" width="200">
                </div>
                <h2 class="text-center mb-4">Control de Asistencia</h2>
                <div class="mb-4 p-3" style="border: 1px solid #003366; color: #003366;">
                    <div>
                        <strong>DENOMINACIÓN DE LA ACCIÓN FORMATIVA:</strong> <?= htmlspecialchars($course_data['Denominacion']) ?>
                    </div>
                    <div class="mt-2">
                        <span class="me-4"><strong>Nº AF:</strong> <?= htmlspecialchars($course_data['N_Accion']) ?></span>
                        <span class="me-4 ms-4"><strong>FECHA DE INICIO:</strong> <?= formattedDate($course_data['Fecha_Inicio']) ?></span>
                        <span><strong>FECHA FIN:</strong> <?= formattedDate($course_data['Fecha_Fin']) ?></span>
                    </div>
                    <div class="mt-2">
                        <strong>FORMADOR/RESPONSABLE DE FORMACIÓN:</strong> <?= htmlspecialchars(mb_strtoupper($course_data['tutor'])) ?>
                    </div>
                    <div class="mt-2">
                        <strong>SESIÓN Nº:</strong> <input type="text" class="editable-input" style="width: 45px;">
                        <strong class="ms-2">FECHA:</strong> <input type="text" class="editable-input" style="width: 70px;">
                        <strong class="ms-2">MAÑANA/TARDE:</strong> <input type="text" class="editable-input" style="width: 70px;">
                        <strong class="ms-2">HORARIO:</strong> DE <input type="text" class="editable-input" style="width: 35px;"> A <input type="text" class="editable-input" style="width: 35px;">
                    </div>
                    <div class="mt-5">
                        <strong>Firmado:</strong>
                        <br>
                        <strong>(Formador/Resp. Formación)</strong>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th colspan="3" class="text-center">DATOS DE LOS ASISTENTES</th>
                                <th rowspan="2" class="text-center align-middle">FIRMAS</th>
                                <th rowspan="2" class="text-center align-middle">OBSERVACIONES</th>
                            </tr>
                            <tr>
                                <th>APELLIDOS</th>
                                <th>NOMBRE</th>
                                <th>NIF</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($students_list as $student) : ?>
                                <tr>
                                    <td><?= htmlspecialchars($student['apellidos']) ?></td>
                                    <td><?= htmlspecialchars($student['nombre']) ?></td>
                                    <td><?= htmlspecialchars($student['NIF']) ?></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 p-3" style="border: 1px solid #003366; color: #003366;">
                    <strong>OBSERVACIONES GENERALES:</strong>
                    <div style="height: 60px;">
                        <!-- Espacio para escribir observaciones -->
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

</body>

</html>