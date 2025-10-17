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

    <nav class="navbar navbar-expand-lg justify-content-center border-bottom border-secondary" style="background-color:#e4e4e4;">

        <div class="container-fluid">

            <a class="navbar-brand" href="inicio.php"><img src="images/logo.gif" id="logo" class="img-fluid" style="width: 200px; heigth: 50px"></a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-center" id="navbarSupportedContent">

                <div class="navbar-nav nav-pills">

                    <a class="nav-link active text-bg-secondary" href="inicio.php" aria-current="page"><b> Call Center </b></a>

                <?php

                    if($_SESSION['rol'] == "admin"){

                    echo "<a class='nav-link' href='administracion.php'><b> Administracion </b></a>";

                    }

                ?>

                    <a class="nav-link" href="comercial.php"><b> Comercial </b></a>

                <?php

                    if($_SESSION['rol'] == "admin" || $_SESSION['codigoUsuario'][0] == "3"){

                    echo "<a class='nav-link' href='tutoria.php'><b> Tutoria </b></a>";

                    }

                ?>

                    <a class="nav-link disabled me-5" href=""><b> Estadisticas </b></a>
                    
                    <div class="dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <b> <?php echo $_SESSION['usuario'] ?> </b>
                        </a>

                        <div class="dropdown-menu" style="background-color: #e4e4e4">
                            <a class="dropdown-item " href="perfilUsuario.php"><b> Perfil </b></a>
                            <hr class="dropdown-divider">
                            <a class="dropdown-item " href="funciones/cerrarSesion.php"><b> Cerrar sesion </b></a>
                        </div>
                    </div>

                </div>

            </div>

        </div>

    </nav>

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
                        echo "<td> <button type='button' class='btn' style='background-color: #1e989e;' onclick='enviarConsulta(" . $cursos[$i]['idempresa'] . ")'>Consultar <img src='images/iconos/info-circle.svg' class='ml-5'> </button> </td>";
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