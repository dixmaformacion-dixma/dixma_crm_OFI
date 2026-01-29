<?php

include "funciones/conexionBD.php";
include "funciones/funcionesEmpresa.php";

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

            if($empresa = buscarEmpresas($_GET['valor'])){

            } else {

                echo "<div class='alert alert-danger' role='alert'>No se encuentra ninguna empresa, <a href='nuevaEmpresa.php'>AGREGAR NUEVA</a></div>";

            }

        }

    }

?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="es" xml:lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comercial</title>
    <link href="css/bootstrap.min.css" rel="stylesheet"></link>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/jquery.min.js"></script>
    <link rel="icon" href="images/favicon.ico">

</head>
<body style="background-color: #f3f6f4;">

    <!-- Menu cabecera -->

    <?php 
        $menuaction = 'comercial';
        require_once './template-parts/header/menu_top.php' 
    ?>

    <!-- Menu lateral y formulario -->
    <div class="container-fluid">

        <div class="row">

            <div class="col-md-2 col-12 align-items-start text-justify" style="background-color:#e4e4e4;">
                <nav class="navbar-nav nav-pills flex-column mt-2 mb-2">
                    <a class="nav-link" href="listadoVentasComercial.php"> <img class="ms-3" src="images/iconos/list.svg"> <b> Listado ventas </b></a>
                </nav> 
            </div>

            <div class="col-md-10 col-12" id="formBusqueda">

                    <div class="container-fluid">

                        <div class="row">

                            <div class="col-12  text-center">

                            <h1 class="mt-5 mb-5">Pantalla de comercial:</h1>
                            <img src="images/logo.gif" class="img-fluid mb-5 mx-auto my-auto" style="width:500px;">

                            </div>

                        </div>

                    </div>

            </div>

        </div>

    </div>

    <footer class="border-top border-secondary" style="background-color:#e4e4e4; height: 75px;">

            <p class="text-center mt-md-4" style='color: #8fd247;'> <b> © Dixma Formación 2022. | Ctra. Madrid 152, Vigo 36318 | info@dixmaformacion.com | Tlf: +34 604 067 035 </b> </p>

    </footer>
    
</body>
</html>