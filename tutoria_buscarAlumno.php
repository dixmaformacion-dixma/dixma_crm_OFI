<?php

    include "funciones/conexionBD.php";
    include "funciones/funcionesAlumnos.php";
    include "funciones/funcionesAlumnosCursos.php";
    include "funciones/funcionesEmpresa.php";
    include "funciones/funcionesCursos.php";

    session_start();

    if(empty($_SESSION)){

        header("Location: index.php");

    }

    date_default_timezone_set("Europe/Madrid");
    setlocale(LC_ALL, "spanish");

    if(isset($_GET['consultar']) && $_SERVER['REQUEST_METHOD'] == 'GET'){

        if(empty($_GET['valor'])){
            echo "<div class='alert alert-danger' role='alert'> El campo de busqueda no puede estar vacio </div>";
        } else {
            if($alumno = buscarAlumno($_GET['valor'])){

            } else {

                echo "<div class='alert alert-danger' role='alert'>No se encuentra ningun alumno</div>";

            }

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
    <link href="css/bootstrap.min.css" rel="stylesheet"></link>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/jquery.min.js"></script>
    <script src="js/tutoria.js"></script>
    <link rel="icon" href="images/favicon.ico">
</head>
<body style="background-color:#f3f6f4;">

    <?php require_once("template-parts/header/header.template.php"); ?>

    <!-- Menu lateral y formulario -->

    <div class="container-fluid">

        <div class="row">

            <?php require_once("template-parts/leftmenu/tutoria.template.php"); ?>

            <div class="col-md-10 col-12" id="formBusqueda">

                <form method="GET">

                    <h2 class="text-center mt-2 pt-2 pb-3 mb-md-5 mb-3 border border-5 rounded" style="background-color: #b0d588; letter-spacing: 7px;">BUSCADOR DE ALUMNOS</h2>

                    <div class="container-fluid">

                        <div class="row d-flex justify-content-center">

                            <div class="form-group col-md-4 col-12 text-center">

                                <!--<label class="form-label">Inserta <b>Numero / Nombre</b> de la empresa:</label>-->
                                <label class="form-label"><b>Apellido / NIF / Correo / Telefono</b> del alumno:</label>
                                <input type="text" class="form-control col-10 col-md-3" name="valor"></input>
                                <input type="submit" class="btn mb-3 mt-3 col-12 col-md-12" style="background-color: #1e989e" value="Buscar" name="consultar">

                            </div>

                        </div>

                    </div>

                </form>

            </div>

                <?php

                    if(!empty($alumno)){
                        echo "<script>";
                        echo "$('#formBusqueda').remove();";
                        echo "</script>";

                        echo "<div class='col-md-10 col-12'>";

                        echo "<h2 class='text-center mt-2 pt-2 pb-2 border border-5' style='background-color: #b0d588; letter-spacing: 7px;'>FICHA DE ALUMNO</h2>";

                        require("template-parts/components/alumno.buscarAlumno.php");

                    }

                    echo "</div>";
                    ?>

            </div>

        </div>

    </div>

    <footer class="border-top border-secondary" style="background-color:#e4e4e4; height: 75px;">

            <p class="text-center mt-md-4" style='color: #8fd247;'> <b> © Dixma Formación 2022. | Ctra. Madrid 152, Vigo 36318 | info@dixmaformacion.com | Tlf: +34 604 067 035 </b> </p>

    </footer>

</body>
</html>