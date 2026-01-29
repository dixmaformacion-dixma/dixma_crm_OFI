<?php

    include "funciones/conexionBD.php";
    include "funciones/funcionesAlumnos.php";
    include "funciones/funcionesAlumnosCursos.php";
    include "funciones/funcionesCursos.php";
    include "funciones/funcionesEmpresa.php";

    session_start();

    if(empty($_SESSION)){

        header("Location: index.php");

    }

    date_default_timezone_set("Europe/Madrid");
    setlocale(LC_ALL, "spanish");

    include "tutoria_adjuntar_function.php";

    if($_SERVER['REQUEST_METHOD'] === 'GET' and isset($_GET['valor'])){
        if($alumnos = buscarAlumnos($_GET['valor'])){

        } else {
            echo "<div class='alert alert-danger' role='alert'>No se encuentra ningun alumno</div>";
        }
    }else{
        //no longer showing all the students
        //$alumnos = todoAlumno()
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
    <script src="js/alumnocurso.js"></script>
    <link rel="icon" href="images/favicon.ico">
</head>
<body style="background-color:#f3f6f4;">

    <?php 
        $menuaction = 'tutoria';
        require_once './template-parts/header/menu_top.php' 
    ?>

    <!-- Menu lateral y formulario -->

    <div class="container-fluid">

        <div class="row">
            <?php require_once("template-parts/leftmenu/tutoria.template.php"); ?>
            <div class="col-md-10 col-12" id="formBusqueda">
            <h2 class="text-center mt-2 pt-2 pb-3 mb-md-5 mb-3 border border-5 rounded" style="background-color: #b0d588; letter-spacing: 7px;">INSERTAR CURSO</h2>
            <div class="mx-auto row pb-5 col-md-7 col-12">
                <form method="GET">
                    <b>Busque por apellido, NIF, teléfono o correo electrónico</b>
                    <input name="valor" class="form-control" type="text"></input>
                    <input class="form-control btn btn-primary" style="background-color:#1e989e" type="submit" value="Buscar"></input>
                    RESULTADOS PARA LA BUSQUEDA:
                    <?php 
                        if($_SERVER['REQUEST_METHOD'] === 'GET' and isset($_GET['valor'])){
                            echo $_GET['valor'];
                        }else{
                            echo "todo";
                        }
                    ?>
                </form>
            </div>
            <?php
                if(!empty($alumnos)){
                    foreach($alumnos as $alumno){
                        require("template-parts/components/alumno.insertarCurso.php");
                    }
                }
            ?>
            </div>

        </div>

    </div>

    <footer class="border-top border-secondary" style="background-color:#e4e4e4; height: 75px;">

            <p class="text-center mt-md-4" style='color: #8fd247;'> <b> © Dixma Formación 2022. | Ctra. Madrid 152, Vigo 36318 | info@dixmaformacion.com | Tlf: +34 604 067 035 </b> </p>

    </footer>

</body>
</html>