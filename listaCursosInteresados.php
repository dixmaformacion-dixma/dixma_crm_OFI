<?php

include "funciones/conexionBD.php";
include "funciones/funcionesCursos.php";

    session_start();

    if(empty($_SESSION)){

        header("Location: index.php");

    }

    date_default_timezone_set("Europe/Madrid");
    setlocale(LC_ALL, "spanish");
    clearstatcache();

    if($cursos = prueba($_GET['curso'])){}

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
    <script src="js/botonConsultar.js"></script>
    <script src="js/arrayCursos.js"></script>
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

                <?php require_once './template-parts/leftmenu/callcenter.template.php'; ?>

                <div class="col-md-10 col-12" id="formCursos">

                    <form method="GET">

                    </form>

                </div>

                <?php

                    if(!empty($cursos)){

                        echo "<script>";
                        echo "$('#formCursos').remove();";
                        echo "</script>";

                        echo "<div class='col-md-10 col-12 mt-2 table-responsive'>";

                        echo " <h2 class='text-center mt-2 pt-2 pb-3 mb-md-3 mb-3 border border-5 rounded' style='background-color: #b0d588; letter-spacing: 7px;'>CURSOS INTERESADOS</h2>";

                        echo "<table class='table table-striped table-bordered table-sm text-center mt-2 align-middle'>";
                        echo "<tr style='background-color: #8fd247;'>";
                        echo "<th> ID </th>";
                        echo "<th> Nombre empresa </th>";
                        echo "<th> Curso </th>";
                        echo "<th> Tipo de curso </th>";
                        echo "<th> Sector </th>";
                        echo "<th>Alumnos / Duración</th>";
                        echo "<th colspan=2></th>";
                        echo "</tr>";

                        for($i=0; $i < count($cursos); $i++){
                        
                        echo "<tr>";

                        echo "<td>" . $cursos[$i]['idempresa'] . "</td>";
                        echo "<td>" . $cursos[$i]['nombre'] . "</td>";
                        echo "<td>" . $cursos[$i]['Curso'] . "</td>";
                        echo "<td>" . $cursos[$i]['tipodeCurso'] . "</td>";
                        echo "<td>" . $cursos[$i]['tipodeCurso'] . "</td>";
                        echo "<td>" . $cursos[$i]['horasCurso'] . "</td>";
                        $redirectTo = '/listaCursosInteresados.php?curso='.$_GET['curso'];
                        echo "<td> 
                            <form action='consultarEmpresa.php'>
                                <input type='hidden' name='idEmpresa' value='{$cursos[$i]['idempresa']}'>
                                <input type='hidden' name='idLlamada' value='pendiente'>
                                <input type='hidden' name='tipo' value=''>
                                <input type='hidden' name='redirect' value='{$redirectTo}'>
                                <button type='submit' class='btn' style='background-color: #1e989e;'>Consultar <img src='images/iconos/info-circle.svg' class='ml-5'> </button>
                            </form>
                        </td>";
                        echo "<td> <button type='button' class='btn btn-danger' onclick='desinteresado(" . $cursos[$i]['idempresa'] . ", " . $cursos[$i]['Codigo'] . ")'>Eliminar <img src='images/iconos/x-circle.svg' class='ml-5'> </button> </td>";

                        echo "</tr>";
                        

                        }

                        echo "</table>";
                        echo "</div>";

                    } else {

                        echo "<script>";
                        echo "$('#formCursos').remove();";
                        echo "</script>";

                        echo "<div class='col-md-10 col-12 mt-2'>";

                        echo "<div class='alert alert-danger text-center mt-5'>No se encuentra ningun curso</div>";

                        echo "</div>";

                    }
                        
                ?>


        </div>

    </div>

    <div class="container-fluid">

        <div class="row">

            <footer class="col-12 border-top border-secondary" style="background-color:#e4e4e4;">

                <p class="text-center mt-md-4" style='color: #8fd247;'> <b> © Dixma Formación 2022. | Ctra. Madrid 152, Vigo 36318 | info@dixmaformacion.com | Tlf: +34 604 067 035 </b> </p>

            </footer>
        
        </div>

    </div>

</body>
</html>