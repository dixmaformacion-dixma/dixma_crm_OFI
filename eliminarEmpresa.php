<?php

include "funciones/conexionBD.php";
include "funciones/funcionesEmpresa.php";
include "funciones/funcionesUsuarios.php";

    session_start();

    if(empty($_SESSION)){

        header("Location: index.php");

    }

    date_default_timezone_set("Europe/Madrid");
    setlocale(LC_ALL, "spanish");

    if(isset($_GET['eliminar']) && $_SERVER['REQUEST_METHOD'] == 'GET'){

        if($_GET['eliminar'] == "si"){

            eliminarEmpresa($_GET['idEmpresa']);

        } 

        header('Refresh: 1; URL=empresas.php');

    }

?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="es" xml:lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Call Center</title>
    <link href="css/bootstrap.min.css" rel="stylesheet"></link>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/jquery.min.js"></script>
    <link rel="icon" href="images/favicon.ico">

</head>
<body style="background-color: #f3f6f4;">

    <!-- Menu cabecera -->

    <?php 
        $menuaction = 'administracion';
        require_once './template-parts/header/menu_top.php' 
    ?>

    <!-- Menu lateral y formulario -->
    <div class="container-fluid">

        <div class="row">

            <?php require_once("template-parts/leftmenu/administracion.template.php"); ?>

            <div class="col-md-10 col-12" id="formBusqueda">

                    <div class="container-fluid">

                        <div class="row">

                            <div class="col-12  text-center">

                            <?php  

                            if($empresa = buscarEmpresasPorID($_GET['idEmpresa'])){

                            echo "<div class='mt-3 alert alert-danger'>Seguro que desea eliminar la empresa: <b>" . $empresa['nombre'] . "</b> con ID: <b>" . $empresa['idempresa'] . "</b></div>";

                                echo "<form method='GET'>";

                                    echo "<input name='idEmpresa' value='" . $_GET['idEmpresa'] . "' hidden='true'></input>";

                                    echo "<div class='form-group'>";

                                        echo "<button name='eliminar' value='si' class='btn btn-danger col-12 col-md-4 mb-md-5 mt-md-5'>Si</button>";
                                        echo "<button name='eliminar' class='btn btn-primary col-12 col-md-4 ms-5 mb-md-5 mt-md-5'>No</button>";

                                    echo "</div>";

                                echo "</form>";

                            echo "</div>";

                            } else {

                                echo "<div class='alert alert-danger mt-5'>Empresa eliminada con exito</div>";

                            }

                            ?>

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