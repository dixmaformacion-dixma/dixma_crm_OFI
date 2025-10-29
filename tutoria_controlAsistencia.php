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
            }
            .printable-area {
                border: none !important;
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
            <h2 class="text-center mb-4">Control de Asistencia</h2>

            <?php if (!$course_data || empty($students_list)) : ?>
                <div class="alert alert-warning text-center">
                    No se encontraron datos del curso o no se seleccionaron alumnos.
                </div>
            <?php else : ?>
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <strong>Detalles del Curso</strong>
                    </div>
                    <div class="card-body">
                        <p><strong>Nombre del Curso:</strong> <?= htmlspecialchars($course_data['Denominacion']) ?></p>
                        <p><strong>Nº de Acción:</strong> <?= htmlspecialchars($course_data['N_Accion']) ?></p>
                        <p><strong>Nº de Grupo:</strong> <?= htmlspecialchars($course_data['N_Grupo']) ?></p>
                        <p><strong>Fecha de Inicio:</strong> <?= formattedDate($course_data['Fecha_Inicio']) ?></p>
                        <p><strong>Fecha de Fin:</strong> <?= formattedDate($course_data['Fecha_Fin']) ?></p>
                        <p><strong>Formador:</strong> <?= htmlspecialchars($course_data['tutor']) ?></p>
                    </div>
                </div>

                <h4 class="mt-4">Alumnos Seleccionados</h4>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th>Apellido</th>
                                <th>Nombre</th>
                                <th>NIF</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($students_list as $student) : ?>
                                <tr>
                                    <td><?= htmlspecialchars($student['apellidos']) ?></td>
                                    <td><?= htmlspecialchars($student['nombre']) ?></td>
                                    <td><?= htmlspecialchars($student['NIF']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>