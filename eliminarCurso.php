<?php

include "funciones/conexionBD.php";
include "funciones/funcionesEmpresa.php";
include "funciones/funcionesCursos.php";

    session_start();

    if(empty($_SESSION)){

        header("Location: index.php");

    }

    date_default_timezone_set("Europe/Madrid");
    setlocale(LC_ALL, "spanish");


    if(isset($_GET['eliminar']) && $_SERVER['REQUEST_METHOD'] == 'GET'){

        $id = $_GET['eliminar'];
        $codigoCurso = $_GET['codigoCurso'];

        desinteresado($id, $codigoCurso);

        header('Refresh: 1; URL=cursosInteresados.php');

    } else {

        if($empresa = cargarEmpresa($_GET['idEmpresa'])){


        }

    }

?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="es" xml:lang="es" lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Call Center</title>
    <link href="css/bootstrap.min.css" rel="stylesheet"></link>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/jquery.min.js"></script>
    <script src="js/botonDesinteresado.js"></script>
    <link rel="icon" href="images/favicon.ico">

</head>
<body style="background-color:#f3f6f4;">

    <!-- Menu cabecera -->

    <?php 
        $menuaction = 'callcenter';
        require_once './template-parts/header/menu_top.php' 
    ?>

    <!-- Menu lateral y formulario -->

    <div class="container-fluid">

        <div class="row">

            <div class="col-md-2 col-12 align-items-start text-justify" style="background-color:#e4e4e4;">
                    <nav class="navbar-nav nav-pills flex-column mt-2 mb-2">
                        <a class="nav-link" href="buscarEmpresa.php"> <img class="ms-3" src="images/iconos/search.svg"> <b> Insertar / Buscar </b></a>
                        <a class="nav-link" href="pendientes.php"> <img class="ms-3" src="images/iconos/exclamation-triangle.svg"> <b> Pendientes </b></a>
                        <a class="nav-link" href="listado.php"> <img class="ms-3" src="images/iconos/list.svg"> <b> Listado </b></a>
                        <a class="nav-link" href="sectores.php"> <img class="ms-3" src="images/iconos/briefcase.svg"> <b> Sectores </b></a>
                        <a class="nav-link" href="control_llamadas.php"> <img class="ms-3" src="images/iconos/telephone.svg"> <b> Control de llamadas </b></a>
                        <a class="nav-link" href="citas.php"> <img class="ms-3" src="images/iconos/calendar-day.svg"> <b> Citas </b></a>
                        <a class="nav-link" href="listadoCitas.php"> <img class="ms-3" src="images/iconos/calendar-date.svg"> <b> Listado de Citas </b></a>
                        <a class="nav-link active text-bg-secondary" href="cursosInteresados.php"> <img class="ms-3" src="images/iconos/book.svg"> <b> Cursos interesados </b></a>

                    <?php 
                    
                        if($_SESSION['codigoUsuario'][0] == "2"){

                            echo "<hr class='border border-dark'>";
                            echo "<a class='nav-link' href='pedirCita.php'> <img class='ms-3' src='images/iconos/calendar-plus.svg'> <b> Pedir Cita </b></a>";
                            echo "<a class='nav-link' href='hacerSeguimiento.php'> <img class='ms-3' src='images/iconos/box-arrow-in-right.svg'> <b> Hacer seguimiento </b></a>";

                        }

                        if($_SESSION['codigoUsuario'][0] == "1"){

                            echo "<hr class='border border-dark'>";
                            echo "<a class='nav-link' href='Callcenter_crearCurso.php'> <img class='ms-3' src='images/iconos/book.svg'> <b> Crear curso </b></a>";

                        }

                    ?>

                    </nav> 
                </div>

         <div class="col-md-10 col-12 text-center" id="formCursos">

            <?php 

                if(!empty($empresa)){

                echo "<div class='alert alert-danger mt-5'>Seguro que desea eliminar el curso de la empresa: <b>" . $empresa['nombre'] . "</b> con ID: <b>" . $empresa['idempresa'] . "</b> </div>";

                echo "<form>";
                
                    echo "<input name='codigoCurso' value='" . $_GET['codigoCurso'] . "' hidden> </input>";
                    echo "<button name='eliminar' value='". $empresa['idempresa'] . "' class='btn btn-danger col-12 col-md-1' >Si</button>";
                    echo "<button class='btn col-12 col-md-1 mt-2 mb-2 ms-md-2' type='button' style='background-color: #1e989e;' onclick='noEliminar()'>No</button>";

                echo "</form>";


            } else {

                echo "<div class='alert alert-danger mt-5'>Curso eliminado con exito</div>";

            }
            
            ?>

        </div>

    </div>

    <footer class="border-top border-secondary" style="background-color:#e4e4e4; height: 75px;">

            <p class="text-center mt-md-4" style='color: #8fd247;'> <b> © Dixma Formación 2022. | Ctra. Madrid 152, Vigo 36318 | info@dixmaformacion.com | Tlf: +34 604 067 035 </b> </p>

    </footer>
    
</body>
</html>