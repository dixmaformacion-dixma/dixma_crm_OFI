<?php

include "funciones/conexionBD.php";
include "funciones/funcionesAlumnosCursos.php";
include "funciones/funcionesEmpresa.php";
include "funciones/funcionesCursos.php";

session_start();

if (empty($_SESSION)) {

    header("Location: index.php");
}

date_default_timezone_set("Europe/Madrid");
setlocale(LC_ALL, "spanish");

include "tutoria_editar_AlumnoCurso_function.php";
include "tutoria_insertar_commentario_function.php";
include "tutoria_editar_seguimentos_function.php";

if (isset($_GET['eliminarCurso']) && is_numeric($_GET['eliminarCurso'])) {
    if (eliminarAlumnoCurso($_GET['eliminarCurso'])) {
        echo "<div class='alert alert-success'>Inscripción de curso eliminada correctamente.</div>";
    } else {
        echo "<div class='alert alert-danger'>Error al eliminar la inscripción del curso.</div>";
    }
}


?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="es" xml:lang="es" lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tutoria</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    </link>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/jquery.min.js"></script>
    <script src="js/tutoria.js"></script>
    <script src="js/alumnocurso.js"></script>
    <link rel="icon" href="images/favicon.ico">
</head>

<body style="background-color:#f3f6f4;">
    <style>
        #printDiplomaButton {
            display: none;
        }
    </style>

    <?php require_once("template-parts/header/header.template.php"); ?>

    <!-- Menu lateral y formulario -->

    <div class="container-fluid">

        <div class="row">

            <?php require_once("template-parts/leftmenu/tutoria.template.php"); ?>

            <div class="col-md-10 col-12" id="formBusqueda">

                <h2 class="text-center mt-2 pt-2 pb-3 mb-md-5 mb-3 border border-5 rounded" style="background-color: #b0d588; letter-spacing: 7px;">CURSOS PENDIENTES</h2>

                <div class="container-fluid">
                    <div class="mb-5 courseWrapper">
                        <?php
                        if ($cursos = cargarAlumnoCursosPendientes()) {
                            require("template-parts/components/cursolist.cursosPendientes.php");
                        }
                        ?>
                    </div>
                    <style>
                        .courseWrapper .container:nth-of-type(even) {
                            background-color: #e7e9e8;
                        }
                    </style>
                </div>

            </div>

        </div>

    </div>

    <footer class="border-top border-secondary" style="background-color:#e4e4e4; height: 75px;">

        <p class="text-center mt-md-4" style='color: #8fd247;'> <b> © Dixma Formación 2022. | Ctra. Madrid 152, Vigo 36318 | info@dixmaformacion.com | Tlf: +34 604 067 035 </b> </p>

    </footer>
</body>

</html>